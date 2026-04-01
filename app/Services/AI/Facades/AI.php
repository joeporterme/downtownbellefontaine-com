<?php

namespace App\Services\AI\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string|null generate(string $prompt, ?string $systemPrompt = null)
 * @method static string|null generateBusinessDescription(array $businessData)
 * @method static string|null generateEventDescription(array $eventData)
 * @method static string|null generateSocialContent(string $topic, string $platform = 'general')
 * @method static string|null chat(string $systemPrompt, string $userPrompt, array $options = [])
 * @method static \App\Services\AI\AIService withModel(string $model)
 * @method static \App\Services\AI\AIService withMaxTokens(int $maxTokens)
 * @method static \App\Services\AI\AIService withTemperature(float $temperature)
 * @method static array getBrandConfig()
 * @method static string|null getSystemPrompt(string $key)
 *
 * @see \App\Services\AI\AIService
 */
class AI extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'ai';
    }
}
