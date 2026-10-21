<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Services\SpamDetectionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Tests\TestCase;

/**
 * Spam cases are real submissions that slipped past the filter in Oct 2026;
 * clean cases are the kinds of messages visitors and business owners send.
 */
class SpamDetectionTest extends TestCase
{
    use RefreshDatabase;

    private function score(array $data, array $meta = []): array
    {
        return app(SpamDetectionService::class)->analyze($data, $meta + [
            'token_state' => 'ok',
            'form_start_time' => time() - 90, // human pacing unless a test says otherwise
        ]);
    }

    public static function priceBotMessages(): array
    {
        return [
            'english' => ['Hi, I wanted to know your price.'],
            'italian' => ['Ciao, volevo sapere il tuo prezzo.'],
            'icelandic' => ['Hæ, ég vildi vita verð þitt.'],
            'indonesian' => ['Hai, saya ingin tahu harga Anda.'],
            'greek' => ['Γεια σου, ήθελα να μάθω την τιμή σας.'],
            'croatian' => ['Zdravo, htio sam znati vašu cijenu.'],
            'azerbaijani' => ['Salam, qiymətinizi bilmək istədim.'],
            'welsh' => ['Hi, roeddwn i eisiau gwybod eich pris.'],
        ];
    }

    /** @dataProvider priceBotMessages */
    public function test_price_inquiry_bot_is_flagged_in_every_language(string $message): void
    {
        // This bot posts without running the page's scripts, so it never has a token.
        $result = $this->score([
            'name' => 'RobertPoeld', 'first_name' => 'RobertPoeld',
            'email' => 'ninadvhegmannjfc33@gmail.com', 'phone' => '+44 7700 900123',
            'message' => $message,
        ], ['token_state' => 'missing', 'form_start_time' => null]);

        $this->assertTrue($result['is_spam'], json_encode($result));
    }

    public function test_price_bot_is_flagged_on_content_alone_when_language_is_detectable(): void
    {
        foreach (['Ciao, volevo sapere il tuo prezzo.', 'Hæ, ég vildi vita verð þitt.', 'Γεια σου, ήθελα να μάθω την τιμή σας.', 'Hi, I wanted to know your price.'] as $message) {
            $result = $this->score([
                'name' => 'RobertPoeld', 'email' => 'ninadvhegmannjfc33@gmail.com',
                'phone' => '+44 7700 900123', 'message' => $message,
            ]);
            $this->assertTrue($result['is_spam'], $message.' '.json_encode($result));
        }
    }

    public function test_repeat_sender_already_flagged_is_flagged_again(): void
    {
        ContactMessage::create([
            'name' => 'RobertPoeld', 'email' => 'repeat@gmail.com', 'message' => 'x',
            'status' => 'new', 'is_spam' => true, 'spam_score' => 80, 'ip_address' => '77.90.185.5',
        ]);

        $result = $this->score([
            'name' => 'Robert Smith', 'email' => 'other@gmail.com',
            'message' => 'Hi, roeddwn i eisiau gwybod eich pris.',
        ], ['ip' => '77.90.185.5']);

        $this->assertTrue($result['is_spam'], json_encode($result));
    }

    public function test_seo_and_overseas_pitches_are_flagged(): void
    {
        $seo = $this->score([
            'name' => 'SparkSEOPro Team', 'email' => 'sparkseopro@gmail.com',
            'message' => "Hello, We're SparkSEOPro. We do SEO for Shopify stores, only Shopify. The quickest wins come from fixing your product pages.",
        ]);
        $this->assertTrue($seo['is_spam'], json_encode($seo));

        $jewelry = $this->score([
            'name' => 'Florist', 'email' => 'finance-and-investments@ukr.net', 'phone' => '+380 44 123 4567 89',
            'message' => 'The modern jewelry industry is developing very dynamically. On the website Jewelry-Online you can find everything.',
        ]);
        $this->assertTrue($jewelry['is_spam'], json_encode($jewelry));
    }

    public static function realVisitorMessages(): array
    {
        return [
            'parking question' => ['Sarah Miller', 'sarah.miller@gmail.com', '937-555-0142', "Hi! We're driving up from Dayton on Saturday. Where's the best place to park near the Holland Theatre?"],
            'business owner' => ['Tom Reynolds', 'treynolds@outlook.com', '(937) 555-0187', 'I own a shop on Main Street and need to update our holiday hours on the business listing. Who should I contact?'],
            'family visit' => ['Maria Gonzalez', 'maria.gonzalez@yahoo.com', null, 'Do you have a list of December events? Our family is visiting for the Christmas parade.'],
            'short question' => ['Kim Lee', 'kimlee@icloud.com', null, 'Is the farmers market open this week?'],
            'sponsorship' => ['Dana Brooks', 'dana@brooksinsurance.com', '937-555-0110', "We'd love to sponsor Downtown Days next year. Who handles partnerships?"],
            'club meeting' => ['Linda Park', 'lpark@aol.com', null, 'Our garden club meets once per month and would love to book a downtown walking tour for about 15 people.'],
            'thank you' => ['Jen Walters', 'jwalters@gmail.com', null, 'Thank you for the great pumpkin walk this year, the kids loved it!'],
        ];
    }

    /** @dataProvider realVisitorMessages */
    public function test_real_visitors_are_not_flagged(string $name, string $email, ?string $phone, string $message): void
    {
        $parts = explode(' ', $name, 2);
        $result = $this->score([
            'name' => $name, 'first_name' => $parts[0], 'last_name' => $parts[1] ?? '',
            'email' => $email, 'phone' => $phone, 'message' => $message,
        ], ['ip' => '203.0.113.7']);

        $this->assertFalse($result['is_spam'], json_encode($result));
    }

    public function test_contact_form_flags_a_direct_post_without_a_token(): void
    {
        $this->post('/contact', [
            'name' => 'RobertPoeld', 'email' => 'ninadvhegmannjfc33@gmail.com',
            'phone' => '+44 7700 900123', 'subject' => 'General Inquiry',
            'message' => 'Hi, roeddwn i eisiau gwybod eich pris.',
        ]);

        $this->assertDatabaseHas('contact_messages', ['email' => 'ninadvhegmannjfc33@gmail.com', 'is_spam' => true]);
    }

    public function test_contact_form_flags_a_forged_token(): void
    {
        $this->post('/contact', [
            'name' => 'Pat Visitor', 'email' => 'forged@example.com',
            'message' => 'I would love to learn more about visiting downtown Bellefontaine.',
            'form_token' => base64_encode((string) (time() - 600)),
        ]);

        $message = ContactMessage::where('email', 'forged@example.com')->first();
        $this->assertContains('Form timing token invalid or tampered', $message->spam_reasons);
    }

    public function test_contact_form_accepts_a_real_token(): void
    {
        $this->post('/contact', [
            'name' => 'Pat Visitor', 'email' => 'real@example.com',
            'message' => 'I would love to learn more about visiting downtown Bellefontaine this fall.',
            'form_token' => Crypt::encryptString((string) (time() - 60)),
        ]);

        $this->assertDatabaseHas('contact_messages', ['email' => 'real@example.com', 'is_spam' => false, 'spam_score' => 0]);
    }
}
