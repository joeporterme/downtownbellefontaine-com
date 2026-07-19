<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;

class AIService
{
    protected string $model;
    protected int $maxTokens;
    protected float $temperature;

    public function __construct()
    {
        $this->model = config('ai.providers.openai.model', 'gpt-4o');
        $this->maxTokens = config('ai.providers.openai.max_tokens', 2048);
        $this->temperature = config('ai.providers.openai.temperature', 0.7);
    }

    /**
     * Generate content using the base brand voice system prompt.
     */
    public function generate(string $prompt, ?string $systemPrompt = null): ?string
    {
        $systemPrompt = $systemPrompt ?? config('ai.prompts.base_system');

        return $this->chat($systemPrompt, $prompt);
    }

    /**
     * Generate a business description using the brand voice.
     */
    public function generateBusinessDescription(array $businessData): ?string
    {
        $systemPrompt = config('ai.prompts.base_system') . "\n\n" . config('ai.prompts.business_description');

        $prompt = $this->buildBusinessPrompt($businessData);

        return $this->chat($systemPrompt, $prompt);
    }

    /**
     * Generate an event description using the brand voice.
     */
    public function generateEventDescription(array $eventData): ?string
    {
        $systemPrompt = config('ai.prompts.base_system') . "\n\n" . config('ai.prompts.event_description');

        $prompt = $this->buildEventPrompt($eventData);

        return $this->chat($systemPrompt, $prompt);
    }

    /**
     * Generate social media content using the brand voice.
     */
    public function generateSocialContent(string $topic, string $platform = 'general'): ?string
    {
        $systemPrompt = config('ai.prompts.base_system') . "\n\n" . config('ai.prompts.social_media');

        $prompt = "Create a {$platform} post about: {$topic}";

        return $this->chat($systemPrompt, $prompt);
    }

    /**
     * Classify a local image file as a designed "logo" or a real "photo"
     * (storefront exterior/interior). Returns ['type','confidence','reason']
     * or null on failure. Uses a vision model with low detail — plenty for a
     * logo-vs-photo decision and cheap/fast at scale.
     */
    public function classifyImageType(string $absolutePath, ?string $model = null): ?array
    {
        if (! is_file($absolutePath)) {
            return null;
        }

        $bytes = @file_get_contents($absolutePath);
        if ($bytes === false) {
            return null;
        }

        $mime = @mime_content_type($absolutePath) ?: 'image/jpeg';
        $dataUrl = 'data:'.$mime.';base64,'.base64_encode($bytes);

        try {
            $response = OpenAI::chat()->create([
                'model' => $model ?? $this->model,
                'max_tokens' => 200,
                'temperature' => 0,
                'response_format' => ['type' => 'json_object'],
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You classify a single business image. A "logo" is a designed brand mark, wordmark, icon, or flat graphic (often on a solid or transparent background). A "photo" is a real photograph of a physical place: a storefront/building exterior, an interior, products on shelves, food, or people in the space. Reply with strict JSON only.',
                    ],
                    [
                        'role' => 'user',
                        'content' => [
                            ['type' => 'text', 'text' => 'Classify this image. Respond as JSON: {"type":"logo" or "photo","confidence":0-1,"reason":"a few words"}'],
                            ['type' => 'image_url', 'image_url' => ['url' => $dataUrl, 'detail' => 'low']],
                        ],
                    ],
                ],
            ]);

            $json = json_decode($response->choices[0]->message->content ?? '', true);

            if (! is_array($json) || ! in_array($json['type'] ?? null, ['logo', 'photo'], true)) {
                return null;
            }

            return [
                'type' => $json['type'],
                'confidence' => (float) ($json['confidence'] ?? 0),
                'reason' => (string) ($json['reason'] ?? ''),
            ];
        } catch (\Throwable $e) {
            Log::warning('Image classification failed', ['path' => $absolutePath, 'error' => $e->getMessage()]);

            return null;
        }
    }

    /**
     * Perform a chat completion with OpenAI.
     */
    public function chat(string $systemPrompt, string $userPrompt, array $options = []): ?string
    {
        try {
            $response = OpenAI::chat()->create([
                'model' => $options['model'] ?? $this->model,
                'max_tokens' => $options['max_tokens'] ?? $this->maxTokens,
                'temperature' => $options['temperature'] ?? $this->temperature,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userPrompt],
                ],
            ]);

            return $response->choices[0]->message->content;
        } catch (\Exception $e) {
            Log::error('OpenAI API Error: ' . $e->getMessage(), [
                'model' => $this->model,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Build a prompt for business description generation.
     */
    protected function buildBusinessPrompt(array $data): string
    {
        $parts = ["Write a description for this Downtown Bellefontaine business:"];

        if (!empty($data['name'])) {
            $parts[] = "Business Name: {$data['name']}";
        }

        if (!empty($data['category'])) {
            $parts[] = "Category: {$data['category']}";
        }

        if (!empty($data['existing_description'])) {
            $parts[] = "Current Description (improve this): {$data['existing_description']}";
        }

        if (!empty($data['website'])) {
            $parts[] = "Website: {$data['website']}";
        }

        if (!empty($data['services'])) {
            $parts[] = "Services/Products: {$data['services']}";
        }

        if (!empty($data['notes'])) {
            $parts[] = "Additional Notes: {$data['notes']}";
        }

        return implode("\n", $parts);
    }

    /**
     * Build a prompt for event description generation.
     */
    protected function buildEventPrompt(array $data): string
    {
        $parts = ["Write a description for this Downtown Bellefontaine event:"];

        if (!empty($data['title'])) {
            $parts[] = "Event Name: {$data['title']}";
        }

        if (!empty($data['date'])) {
            $parts[] = "Date: {$data['date']}";
        }

        if (!empty($data['time'])) {
            $parts[] = "Time: {$data['time']}";
        }

        if (!empty($data['location'])) {
            $parts[] = "Location: {$data['location']}";
        }

        if (!empty($data['existing_description'])) {
            $parts[] = "Current Description (improve this): {$data['existing_description']}";
        }

        if (!empty($data['type'])) {
            $parts[] = "Event Type: {$data['type']}";
        }

        if (!empty($data['notes'])) {
            $parts[] = "Additional Notes: {$data['notes']}";
        }

        return implode("\n", $parts);
    }

    /**
     * Set a custom model for this instance.
     */
    public function withModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Set custom max tokens for this instance.
     */
    public function withMaxTokens(int $maxTokens): self
    {
        $this->maxTokens = $maxTokens;

        return $this;
    }

    /**
     * Set custom temperature for this instance.
     */
    public function withTemperature(float $temperature): self
    {
        $this->temperature = $temperature;

        return $this;
    }

    /**
     * Get the brand configuration.
     */
    public function getBrandConfig(): array
    {
        return config('ai.brand', []);
    }

    /**
     * Get a specific system prompt.
     */
    public function getSystemPrompt(string $key): ?string
    {
        return config("ai.prompts.{$key}");
    }
}
