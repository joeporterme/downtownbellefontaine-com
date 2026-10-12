<?php

namespace App\Models\Concerns;

use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

trait SanitizesHtml
{
    /**
     * Sanitize user/CMS-authored HTML for safe render (strips scripts, event
     * handlers, javascript: URLs, etc.). Pass $allowMedia=false for content
     * that shouldn't embed images/media (e.g. short event descriptions).
     */
    protected function sanitizeHtml(?string $html, bool $allowMedia = true): ?string
    {
        if (blank($html)) {
            return null;
        }

        $config = (new HtmlSanitizerConfig())
            ->allowSafeElements()
            ->allowRelativeLinks()
            ->allowLinkSchemes(['https', 'http', 'mailto']);

        if ($allowMedia) {
            $config = $config
                ->allowRelativeMedias()
                ->allowMediaSchemes(['https', 'http', 'data']);
        }

        return (new HtmlSanitizer($config))->sanitize($html);
    }
}
