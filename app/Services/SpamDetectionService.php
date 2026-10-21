<?php

namespace App\Services;

use App\Models\ContactMessage;

class SpamDetectionService
{
    protected array $reasons = [];
    protected int $score = 0;

    // Spam threshold - score at or above this is considered spam
    public const SPAM_THRESHOLD = 50;

    /**
     * Analyze form submission for spam indicators.
     *
     * @param array $data Form data
     * @param array $meta Meta data (honeypot, timing, etc.)
     * @return array ['is_spam' => bool, 'score' => int, 'reasons' => array]
     */
    public function analyze(array $data, array $meta = []): array
    {
        $this->reasons = [];
        $this->score = 0;

        // Check honeypot field (should be empty)
        $this->checkHoneypot($meta);

        // Check submission timing (too fast = bot)
        $this->checkTiming($meta);

        // Check for spam patterns in content
        $this->checkContent($data);

        // Check phone number
        $this->checkPhone($data['phone'] ?? '');

        // Check email patterns
        $this->checkEmail($data['email'] ?? '');

        // Check for suspicious URLs in message
        $this->checkUrls($data['message'] ?? '');

        // Non-English / non-Latin-script messages (overseas spam campaigns)
        $this->checkLanguage($data['message'] ?? '');

        // Has this email or IP already been flagged recently?
        $this->checkHistory($data['email'] ?? '', $meta['ip'] ?? null);

        return [
            'is_spam' => $this->score >= self::SPAM_THRESHOLD,
            'score' => min($this->score, 100),
            'reasons' => $this->reasons,
        ];
    }

    /**
     * Check honeypot field - should be empty.
     */
    protected function checkHoneypot(array $meta): void
    {
        if (!empty($meta['honeypot'])) {
            $this->addScore(100, 'Honeypot field filled');
        }
    }

    /**
     * Check submission timing - too fast indicates bot.
     */
    protected function checkTiming(array $meta): void
    {
        // The form carries a server-signed load timestamp. Bots that POST
        // straight to the endpoint (or replay a scraped form) arrive without
        // one or with a forged one; real browsers always have it.
        $state = $meta['token_state'] ?? 'ok';
        if ($state === 'missing') {
            $this->addScore(40, 'Form timing token missing (form was not loaded normally)');
            return;
        }
        if ($state === 'invalid') {
            $this->addScore(60, 'Form timing token invalid or tampered');
            return;
        }

        $startTime = $meta['form_start_time'] ?? null;

        if ($startTime) {
            $elapsed = time() - (int) $startTime;

            // Less than 3 seconds is definitely a bot
            if ($elapsed < 3) {
                $this->addScore(80, 'Form submitted too quickly (< 3s)');
            }
            // Less than 5 seconds is suspicious
            elseif ($elapsed < 5) {
                $this->addScore(40, 'Form submitted very quickly (< 5s)');
            }
            // Less than 10 seconds is slightly suspicious
            elseif ($elapsed < 10) {
                $this->addScore(15, 'Form submitted quickly (< 10s)');
            }
        }
    }

    /**
     * Check content for spam patterns.
     */
    protected function checkContent(array $data): void
    {
        $text = implode(' ', array_filter([
            $data['name'] ?? '',
            $data['first_name'] ?? '',
            $data['last_name'] ?? '',
            $data['company'] ?? '',
            $data['message'] ?? '',
        ]));

        $lowerText = strtolower($text);

        // Check for common spam keywords
        $spamKeywords = [
            'viagra', 'cialis', 'casino', 'poker', 'slots',
            'crypto', 'bitcoin', 'investment opportunity',
            'make money fast', 'work from home',
            'click here', 'act now', 'limited time',
            'free money', 'guaranteed',
            'seo services', 'link building', 'backlinks', 'backlink',
            'adult', 'xxx', 'porn',
            'chatting', 'singles', 'dating', 'meet girls', 'meet women',
            // Marketing/growth service spam
            'youtube growth', 'instagram growth', 'tiktok growth', 'social media growth',
            'increase your subscribers', 'gain you', 'followers per month', 'subscribers per month',
            'growth service', 'marketing service',
            // SEO/website service spam
            'submit your website', 'submit your site', 'free tool', 'free traffic',
            'get exposure', 'get listed', 'classified sites', 'web traffic',
            'website visits', 'skyrocket', 'boost your', 'ranking',
            'improve your seo', 'fast improve', 'daily backlinks',
            'discount code', 'promo code',
            // Seen in the live inbox (Oct 2026)
            'know your price', 'wanted to know your price', 'your price list',
            'lead generation', 'more leads', 'qualified leads', 'book more appointments',
            'first page of google', 'page 1 of google', 'rank higher', 'google maps ranking',
            'are customers finding', 'quick wins', 'website redesign', 'web design services',
            'one-man seo', 'seo army',
        ];

        foreach ($spamKeywords as $keyword) {
            if (str_contains($lowerText, $keyword)) {
                $this->addScore(30, "Contains spam keyword: {$keyword}");
            }
        }

        if (preg_match('/\bseo\b/i', $text)) {
            $this->addScore(25, 'Mentions SEO (unsolicited marketing pitch)');
        }

        // Check for fake/generic company names (single word mega-corps)
        $company = strtolower(trim($data['company'] ?? ''));
        $fakeCompanyNames = ['google', 'facebook', 'meta', 'microsoft', 'apple', 'amazon', 'test', 'asdf', 'none', 'na', 'n/a', 'company', 'business'];
        if (in_array($company, $fakeCompanyNames)) {
            $this->addScore(20, 'Suspicious/generic company name');
        }

        // Check if company name matches the person's full name (common spam pattern)
        $firstName = strtolower(trim($data['first_name'] ?? ''));
        $lastName = strtolower(trim($data['last_name'] ?? ''));
        $fullName = trim("{$firstName} {$lastName}");
        if ($company && $fullName && $company === $fullName) {
            $this->addScore(30, 'Company name matches personal name');
        }

        // Check for excessive caps (shouting)
        if (strlen($text) > 20) {
            $capsRatio = preg_match_all('/[A-Z]/', $text) / strlen($text);
            if ($capsRatio > 0.5) {
                $this->addScore(20, 'Excessive capital letters');
            }
        }

        // Check for repetitive characters (aaaaaaa, !!!!!!)
        if (preg_match('/(.)\1{5,}/', $text)) {
            $this->addScore(25, 'Repetitive characters detected');
        }

        // Non-Latin scripts anywhere in the name/message (the "* * * $3,222"
        // link-spam names are Cyrillic). Message language is scored in checkLanguage().
        if (preg_match('/[\x{0400}-\x{04FF}]/u', $text)) {
            $this->addScore(15, 'Contains Cyrillic characters');
        }

        // Check for duplicate first/last name (common bot pattern)
        $firstName = trim($data['first_name'] ?? '');
        $lastName = trim($data['last_name'] ?? '');
        if ($firstName && $lastName && strcasecmp($firstName, $lastName) === 0) {
            $this->addScore(35, 'Duplicate first and last name');
        }

        // Check for first/last name sharing a common suffix (e.g., "DavidLamGM" / "WilliamLamGM")
        if ($firstName && $lastName && strlen($firstName) > 4 && strlen($lastName) > 4) {
            $minLen = min(strlen($firstName), strlen($lastName));
            for ($suffixLen = $minLen; $suffixLen >= 3; $suffixLen--) {
                $firstSuffix = substr($firstName, -$suffixLen);
                $lastSuffix = substr($lastName, -$suffixLen);
                if (strcasecmp($firstSuffix, $lastSuffix) === 0) {
                    $this->addScore(50, "First and last name share common suffix: {$firstSuffix}");
                    break;
                }
            }
        }

        // Check for gibberish/bot-like names (random strings with unusual patterns)
        $fullName = $firstName . ' ' . $lastName;
        // Names with "nup", "xxy", "qz" or ending in random letter combos like "SK", "XY"
        if (preg_match('/[a-z]{3,}(nup|xxy|qz|zz|xx|qq)[a-z]*[A-Z]{2}$/i', $firstName) ||
            preg_match('/[a-z]{3,}(nup|xxy|qz|zz|xx|qq)[a-z]*[A-Z]{2}$/i', $lastName)) {
            $this->addScore(25, 'Bot-like name pattern');
        }

        // Check for names that look like concatenated strings (e.g., "DavidLamGM" - camelCase in names)
        if (preg_match('/^[A-Z][a-z]+[A-Z][a-z]+[A-Z]+$/', $firstName) ||
            preg_match('/^[A-Z][a-z]+[A-Z][a-z]+[A-Z]+$/', $lastName)) {
            $this->addScore(30, 'Name appears to be concatenated/bot-generated');
        }

        // Check for random character sequences (consonant clusters uncommon in English names)
        if (preg_match('/[bcdfghjklmnpqrstvwxyz]{5,}/i', $fullName)) {
            $this->addScore(20, 'Unusual consonant cluster in name');
        }

        // Empty or very short message
        $messageLength = strlen($data['message'] ?? '');
        if ($messageLength > 0 && $messageLength < 10) {
            $this->addScore(15, 'Very short message');
        }

        // Very long message (often spam)
        if ($messageLength > 5000) {
            $this->addScore(20, 'Extremely long message');
        }

        // Check for mass-email spam indicators
        if (str_contains($lowerText, 'unsubscribe') || str_contains($lowerText, 'to opt out')) {
            $this->addScore(50, 'Contains unsubscribe/opt-out language (mass email spam)');
        }

        // Check for price/service pitch patterns (e.g., "$60 per month", "price is just")
        if (preg_match('/\$\d+.*per month|\bprice is\b/i', $text)) {
            $this->addScore(30, 'Contains pricing/sales pitch language');
        }
    }

    /**
     * Check phone number for spam patterns.
     */
    protected function checkPhone(string $phone): void
    {
        if (empty($phone)) {
            return;
        }

        // Strip non-digits
        $digits = preg_replace('/\D/', '', $phone);
        $digitCount = strlen($digits);

        // US phone numbers should be 10-11 digits (11 with country code starting with 1)
        if ($digitCount > 0 && $digitCount < 10) {
            $this->addScore(20, 'Invalid phone number (too few digits)');
        }

        // Too many digits for a US number
        if ($digitCount > 11) {
            $this->addScore(25, 'Phone number too long for US format');
        }

        // 11 digits but doesn't start with 1 (not a US country code)
        if ($digitCount === 11 && !str_starts_with($digits, '1')) {
            $this->addScore(25, 'Phone number appears to be non-US');
        }
    }

    /**
     * Check email for spam patterns.
     */
    protected function checkEmail(string $email): void
    {
        if (empty($email)) {
            return;
        }

        $lowerEmail = strtolower($email);

        // Disposable email domains
        $disposableDomains = [
            'tempmail.com', 'throwaway.email', '10minutemail.com',
            'guerrillamail.com', 'mailinator.com', 'maildrop.cc',
            'yopmail.com', 'sharklasers.com', 'trashmail.com',
            'temp-mail.org', 'fakeinbox.com', 'getnada.com',
        ];

        $domain = substr($email, strrpos($email, '@') + 1);

        if (in_array($domain, $disposableDomains)) {
            $this->addScore(40, 'Disposable email address');
        }

        // Senders whose domain is itself a marketing pitch (blastleadgeneration.com, thesparkseo…)
        if (preg_match('/(leadgen|lead-gen|leadgeneration|seo|backlink|marketing|growth|traffic)/', strtolower($domain).' '.substr($lowerEmail, 0, (int) strrpos($lowerEmail, '@')))) {
            $this->addScore(25, 'Email address belongs to a marketing/SEO sender');
        }

        // Overseas freemail rarely writes to a small-town Ohio downtown
        if (preg_match('/(\.(ru|ua|by|kz|cn|su)$|^(ukr\.net|mail\.ru|bk\.ru|inbox\.ru|list\.ru|rambler\.ru|yandex\.[a-z]+|qq\.com|163\.com)$)/', strtolower($domain))) {
            $this->addScore(30, 'Overseas freemail domain');
        }

        // Check for suspicious email patterns (random letters + numbers)
        if (preg_match('/^[a-z]{7,}[0-9]+@/', $lowerEmail)) {
            $this->addScore(20, 'Suspicious email pattern');
        }

        // Check for numbers-only local part
        if (preg_match('/^[0-9]+@/', $lowerEmail)) {
            $this->addScore(25, 'Email with only numbers before @');
        }

        // Check for generic/spammy local parts
        $spammyLocalParts = ['joyofchatting', 'meetsingles', 'hotdate', 'loveconnection', 'chatroom', 'meetme'];
        $localPart = substr($lowerEmail, 0, strrpos($lowerEmail, '@'));
        foreach ($spammyLocalParts as $spammy) {
            if (str_contains($localPart, $spammy)) {
                $this->addScore(30, 'Spammy email local part');
                break;
            }
        }
    }

    /**
     * Check for suspicious URLs in message.
     */
    protected function checkUrls(string $message): void
    {
        if (empty($message)) {
            return;
        }

        // Count URLs in message (plain URLs with protocol)
        $urlCount = preg_match_all('/https?:\/\/[^\s]+/', $message);

        // Also catch bare domains (e.g., sitesubmitterpro.com, BonusBacklinks.com)
        $bareDomainCount = preg_match_all('/\b[a-z0-9][-a-z0-9]*\.(com|net|org|io|co|info|biz|xyz|site|online|pro|click|link)\b/i', $message);
        $urlCount += $bareDomainCount;

        // Also check for BBCode/forum markup URLs [url=...] or [url]...[/url]
        $bbcodeUrlCount = preg_match_all('/\[url[=\]]/', strtolower($message));
        if ($bbcodeUrlCount > 0) {
            $this->addScore(40, 'Contains BBCode/forum markup URLs');
        }

        // Check for HTML anchor tags
        if (preg_match('/<a\s+[^>]*href\s*=/i', $message)) {
            $this->addScore(40, 'Contains HTML link tags');
        }

        $urlCount += $bbcodeUrlCount;

        if ($urlCount > 3) {
            $this->addScore(40, 'Too many URLs in message');
        } elseif ($urlCount > 1) {
            $this->addScore(15, 'Multiple URLs in message');
        }

        // Check for URL shorteners (in any context, not just plain URLs)
        $shorteners = ['bit.ly', 'tinyurl.com', 't.co', 'goo.gl', 'ow.ly', 'is.gd', 'cutt.ly', 'rebrand.ly', 'short.io'];
        $lowerMessage = strtolower($message);

        foreach ($shorteners as $shortener) {
            if (str_contains($lowerMessage, $shortener)) {
                $this->addScore(30, 'Contains URL shortener');
                break;
            }
        }
    }

    /**
     * Score messages that aren't in English. Visitors write in English; the
     * "I wanted to know your price" bot cycles through dozens of languages.
     */
    protected function checkLanguage(string $message): void
    {
        $message = trim($message);
        if ($message === '') {
            return;
        }

        // Greek, Cyrillic, Armenian, Hebrew, Arabic, Indic, Thai, Georgian, CJK, Hangul…
        if (preg_match('/[\x{0370}-\x{03FF}\x{0400}-\x{052F}\x{0530}-\x{058F}\x{0590}-\x{06FF}\x{0900}-\x{0DFF}\x{0E00}-\x{0E7F}\x{10A0}-\x{10FF}\x{3040}-\x{30FF}\x{3400}-\x{9FFF}\x{AC00}-\x{D7AF}]/u', $message)) {
            $this->addScore(40, 'Message is written in a non-Latin script');
            return;
        }

        // Accented Latin letters (æ ð þ ė ą ş ...), excluding × and ÷.
        $accents = preg_match_all('/[\x{00C0}-\x{00D6}\x{00D8}-\x{00F6}\x{00F8}-\x{024F}\x{1E00}-\x{1EFF}]/u', $message);
        if ($accents >= 2) {
            $this->addScore(35, 'Message appears to be in a non-English language');
            return;
        }

        // Several words but not one common English word ("Ciao, volevo sapere il tuo prezzo").
        $words = preg_split('/[^a-z\']+/', strtolower($message), -1, PREG_SPLIT_NO_EMPTY);
        $common = ['the', 'a', 'an', 'and', 'or', 'but', 'to', 'of', 'in', 'on', 'for', 'is', 'are', 'was', 'be',
            'i', "i'm", 'you', 'we', 'my', 'our', 'your', 'me', 'it', 'this', 'that', 'with', 'at', 'do', 'does',
            'can', 'would', 'will', 'have', 'has', 'what', 'when', 'where', 'how', 'hi', 'hello', 'hey', 'thanks',
            'thank', 'please', 'there', 'about', 'if', 'not', 'any', 'from', 'love', 'just', 'like', 'know'];
        if (count($words) >= 4 && ! array_intersect($words, $common)) {
            $this->addScore(35, 'Message does not appear to be English');
        }
    }

    /**
     * Repeat offenders: the same email or IP already flagged as spam recently,
     * or the same email submitting over and over.
     */
    protected function checkHistory(string $email, ?string $ip): void
    {
        if ($email === '' && ! $ip) {
            return;
        }

        $flagged = ContactMessage::query()
            ->where('is_spam', true)
            ->where('created_at', '>=', now()->subDays(30))
            ->where(function ($q) use ($email, $ip) {
                if ($email !== '') {
                    $q->orWhere('email', $email);
                }
                if ($ip) {
                    $q->orWhere('ip_address', $ip);
                }
            })
            ->exists();

        if ($flagged) {
            $this->addScore(50, 'Sender was already flagged as spam in the last 30 days');
        }

        if ($email !== '') {
            $recent = ContactMessage::where('email', $email)->where('created_at', '>=', now()->subDays(7))->count();
            if ($recent >= 2) {
                $this->addScore(25, "Same email submitted {$recent} times this week");
            }
        }
    }

    /**
     * Add to spam score with reason.
     */
    protected function addScore(int $points, string $reason): void
    {
        $this->score += $points;
        $this->reasons[] = $reason;
    }
}
