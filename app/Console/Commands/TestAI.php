<?php

namespace App\Console\Commands;

use App\Services\AI\AIService;
use Illuminate\Console\Command;

class TestAI extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:test {--type=business : Type of content to generate (business, event, social)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the AI service with the Downtown Bellefontaine brand voice';

    /**
     * Execute the console command.
     */
    public function handle(AIService $ai)
    {
        $type = $this->option('type');

        $this->info('Testing AI Service with Downtown Bellefontaine brand voice...');
        $this->newLine();

        $this->line('Configuration:');
        $this->table(
            ['Setting', 'Value'],
            [
                ['Model', config('ai.providers.openai.model')],
                ['Max Tokens', config('ai.providers.openai.max_tokens')],
                ['Temperature', config('ai.providers.openai.temperature')],
                ['Brand Name', config('ai.brand.name')],
                ['Tagline', config('ai.brand.tagline')],
            ]
        );

        $this->newLine();

        match ($type) {
            'business' => $this->testBusinessDescription($ai),
            'event' => $this->testEventDescription($ai),
            'social' => $this->testSocialContent($ai),
            default => $this->testBusinessDescription($ai),
        };

        return Command::SUCCESS;
    }

    protected function testBusinessDescription(AIService $ai): void
    {
        $this->info('Generating sample business description...');
        $this->newLine();

        $sampleBusiness = [
            'name' => 'Corner Coffee House',
            'category' => 'Food & Drink',
            'existing_description' => 'A coffee shop in downtown.',
            'services' => 'Specialty coffee, pastries, light breakfast items',
        ];

        $this->line('Input data:');
        $this->table(['Field', 'Value'], collect($sampleBusiness)->map(fn($v, $k) => [$k, $v])->values()->all());

        $this->newLine();
        $this->line('Generating...');

        $result = $ai->generateBusinessDescription($sampleBusiness);

        if ($result) {
            $this->newLine();
            $this->info('Generated Description:');
            $this->line($result);
        } else {
            $this->error('Failed to generate content. Check your OPENAI_API_KEY in .env');
        }
    }

    protected function testEventDescription(AIService $ai): void
    {
        $this->info('Generating sample event description...');
        $this->newLine();

        $sampleEvent = [
            'title' => 'Downtown Summer Festival',
            'date' => 'July 15, 2026',
            'time' => '10:00 AM - 8:00 PM',
            'location' => 'Main Street, Downtown Bellefontaine',
            'type' => 'Community Festival',
            'notes' => 'Live music, food vendors, craft booths, kids activities',
        ];

        $this->line('Input data:');
        $this->table(['Field', 'Value'], collect($sampleEvent)->map(fn($v, $k) => [$k, $v])->values()->all());

        $this->newLine();
        $this->line('Generating...');

        $result = $ai->generateEventDescription($sampleEvent);

        if ($result) {
            $this->newLine();
            $this->info('Generated Description:');
            $this->line($result);
        } else {
            $this->error('Failed to generate content. Check your OPENAI_API_KEY in .env');
        }
    }

    protected function testSocialContent(AIService $ai): void
    {
        $this->info('Generating sample social media content...');
        $this->newLine();

        $topic = 'New boutique shop opening on Main Street this weekend';
        $platform = 'Facebook';

        $this->line("Topic: {$topic}");
        $this->line("Platform: {$platform}");

        $this->newLine();
        $this->line('Generating...');

        $result = $ai->generateSocialContent($topic, $platform);

        if ($result) {
            $this->newLine();
            $this->info('Generated Social Post:');
            $this->line($result);
        } else {
            $this->error('Failed to generate content. Check your OPENAI_API_KEY in .env');
        }
    }
}
