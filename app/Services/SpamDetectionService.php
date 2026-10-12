<?php

namespace App\Services;

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
        ];

        foreach ($spamKeywords as $keyword) {
            if (str_contains($lowerText, $keyword)) {
                $this->addScore(30, "Contains spam keyword: {$keyword}");
            }
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

        // Check for Cyrillic or other non-Latin scripts (unless specifically expected)
        if (preg_match('/[\x{0400}-\x{04FF}]/u', $text)) {
            $this->addScore(15, 'Contains Cyrillic characters');
        }

        // Check for non-English messages (Latin-script languages with diacritics)
        $message = $data['message'] ?? '';
        if ($message && preg_match('/[\x{0100}-\x{024F}\x{1E00}-\x{1EFF}]/u', $message)) {
            $nonAsciiCount = preg_match_all('/[\x{0100}-\x{024F}\x{1E00}-\x{1EFF}]/u', $message);
            if ($nonAsciiCount >= 3) {
                $this->addScore(35, 'Message appears to be in a non-English language');
            }
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
     * Add to spam score with reason.
     */
    protected function addScore(int $points, string $reason): void
    {
        $this->score += $points;
        $this->reasons[] = $reason;
    }
}
