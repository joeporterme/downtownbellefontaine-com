<?php

namespace App\Console\Commands;

use App\Models\Business;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class ImportWordPressEvents extends Command
{
    protected $signature = 'import:wordpress-events
                            {--dry-run : Show what would be imported without actually importing}
                            {--skip-images : Skip downloading and processing images}
                            {--skip-geocoding : Skip Google Geocoding API calls}
                            {--skip-ai : Skip AI business matching}
                            {--limit= : Limit the number of events to import}';

    protected $description = 'Import events from WordPress XML export (only future/upcoming events)';

    protected array $mediaLookup = [];
    protected array $businesses = [];

    public function handle(): int
    {
        $eventsXmlPath = resource_path('events.xml');
        $mediaXmlPath = resource_path('media.xml');

        if (!file_exists($eventsXmlPath)) {
            $this->error('events.xml not found in resources folder');
            return 1;
        }

        $this->info('Loading businesses from database...');
        $this->businesses = Business::pluck('name', 'id')->toArray();
        $this->info('Found ' . count($this->businesses) . ' businesses');

        // Build media lookup table
        if (file_exists($mediaXmlPath) && !$this->option('skip-images')) {
            $this->info('Building media lookup table...');
            $this->buildMediaLookup($mediaXmlPath);
            $this->info('Found ' . count($this->mediaLookup) . ' media items');
        }

        $this->info('Parsing events.xml...');
        $events = $this->parseEventsXml($eventsXmlPath);
        $this->info('Found ' . count($events) . ' total events in XML');

        // Filter to only future events
        $today = Carbon::today();
        $futureEvents = array_filter($events, function ($event) use ($today) {
            if (empty($event['event_date'])) {
                return false;
            }
            return Carbon::createFromTimestamp($event['event_date'])->gte($today);
        });

        $this->info('Found ' . count($futureEvents) . ' future/upcoming events');

        if (empty($futureEvents)) {
            $this->warn('No future events to import!');
            return 0;
        }

        // Apply limit if specified
        if ($limit = $this->option('limit')) {
            $futureEvents = array_slice($futureEvents, 0, (int) $limit);
            $this->info("Limited to {$limit} events");
        }

        $imported = 0;
        $skipped = 0;

        $bar = $this->output->createProgressBar(count($futureEvents));
        $bar->start();

        foreach ($futureEvents as $eventData) {
            $result = $this->importEvent($eventData);
            if ($result) {
                $imported++;
            } else {
                $skipped++;
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Import complete!");
        $this->info("Imported: {$imported}");
        $this->info("Skipped: {$skipped}");

        return 0;
    }

    protected function buildMediaLookup(string $path): void
    {
        $xml = simplexml_load_file($path);
        $namespaces = $xml->getNamespaces(true);
        $wp = $namespaces['wp'] ?? 'http://wordpress.org/export/1.2/';

        foreach ($xml->channel->item as $item) {
            $wpData = $item->children($wp);
            $postId = (string) $wpData->post_id;
            $attachmentUrl = (string) $wpData->attachment_url;

            if ($postId && $attachmentUrl) {
                $this->mediaLookup[$postId] = $attachmentUrl;
            }
        }
    }

    protected function parseEventsXml(string $path): array
    {
        $xml = simplexml_load_file($path);
        $namespaces = $xml->getNamespaces(true);
        $wp = $namespaces['wp'] ?? 'http://wordpress.org/export/1.2/';
        $content = $namespaces['content'] ?? 'http://purl.org/rss/1.0/modules/content/';

        $events = [];

        foreach ($xml->channel->item as $item) {
            $wpData = $item->children($wp);
            $contentData = $item->children($content);

            // Only process published events
            $status = (string) $wpData->status;
            if ($status !== 'publish') {
                continue;
            }

            // Parse postmeta
            $meta = [];
            foreach ($wpData->postmeta as $postmeta) {
                $key = (string) $postmeta->meta_key;
                $value = (string) $postmeta->meta_value;
                $meta[$key] = $value;
            }

            $events[] = [
                'wp_post_id' => (string) $wpData->post_id,
                'title' => (string) $item->title,
                'description' => (string) $contentData->encoded,
                'event_date' => $meta['event-start-date'] ?? $meta['event-date'] ?? null,
                'event_time' => $meta['event-time'] ?? null,
                'location' => $meta['event-location'] ?? null,
                'thumbnail_id' => $meta['_thumbnail_id'] ?? null,
                'more_info_url' => $meta['event-link'] ?? null,
            ];
        }

        return $events;
    }

    protected function importEvent(array $data): bool
    {
        // Check if already imported (by title + date)
        $eventDate = Carbon::createFromTimestamp($data['event_date'])->toDateString();
        $baseSlug = Str::slug($data['title']);

        $existing = Event::where('title', $data['title'])
            ->where('event_date', $eventDate)
            ->first();

        if ($existing) {
            $this->newLine();
            $this->warn("Skipping duplicate: {$data['title']} on {$eventDate}");
            return false;
        }

        // Generate unique slug
        $slug = $baseSlug;
        $count = 1;
        while (Event::where('slug', $slug)->exists()) {
            $count++;
            $slug = $baseSlug . '-' . $count;
        }

        if ($this->option('dry-run')) {
            $this->newLine();
            $this->info("[DRY RUN] Would import: {$data['title']} on {$eventDate}");
            return true;
        }

        // Parse time - first try event-time meta, then extract from timestamp
        $startTime = null;
        $endTime = null;
        if (!empty($data['event_time'])) {
            $parsed = $this->parseTimeString($data['event_time']);
            $startTime = $parsed['start'];
            $endTime = $parsed['end'];
        } elseif (!empty($data['event_date'])) {
            // Extract time from the event-start-date timestamp
            $eventDateTime = Carbon::createFromTimestamp($data['event_date']);
            // Only set time if it's not midnight (which would indicate date-only)
            if ($eventDateTime->format('H:i') !== '00:00') {
                $startTime = $eventDateTime->format('H:i:s');
            }
        }

        // Parse location and geocode
        $locationData = $this->parseAndGeocodeLocation($data['location'] ?? '');

        // Match business using AI
        $businessId = null;
        if (!$this->option('skip-ai') && !empty($data['description'])) {
            $businessId = $this->matchBusinessWithAI($data['title'], $data['description'], $data['location'] ?? '');
        }

        // Download and process image
        $featuredImage = null;
        if (!$this->option('skip-images') && !empty($data['thumbnail_id'])) {
            $featuredImage = $this->downloadAndProcessImage($data['thumbnail_id'], $data['title']);
        }

        // Clean description HTML
        $description = $this->cleanDescription($data['description']);

        // Create the event
        $event = Event::create([
            'title' => $data['title'],
            'slug' => $slug,
            'description' => $description,
            'event_date' => $eventDate,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'featured_image' => $featuredImage,
            'business_id' => $businessId,
            'more_info_url' => $data['more_info_url'] ?? null,
            'location_name' => $locationData['name'],
            'address' => $locationData['address'],
            'city' => $locationData['city'] ?? 'Bellefontaine',
            'state' => $locationData['state'] ?? 'OH',
            'zip' => $locationData['zip'],
            'latitude' => $locationData['lat'],
            'longitude' => $locationData['lng'],
            'status' => 'approved', // Auto-approve as requested
            'approved_at' => now(),
        ]);

        return true;
    }

    protected function parseTimeString(string $timeStr): array
    {
        $result = ['start' => null, 'end' => null];

        // Common patterns: "10am-3pm", "10:00 AM - 3:00 PM", "6pm", "6:00pm - 9:00pm"
        $timeStr = strtolower(trim($timeStr));

        // Try to match range pattern
        if (preg_match('/(\d{1,2}(?::\d{2})?\s*(?:am|pm)?)\s*[-–to]+\s*(\d{1,2}(?::\d{2})?\s*(?:am|pm)?)/i', $timeStr, $matches)) {
            $result['start'] = $this->normalizeTime($matches[1], $matches[2]);
            $result['end'] = $this->normalizeTime($matches[2]);
        } elseif (preg_match('/(\d{1,2}(?::\d{2})?\s*(?:am|pm))/i', $timeStr, $matches)) {
            $result['start'] = $this->normalizeTime($matches[1]);
        }

        return $result;
    }

    protected function normalizeTime(string $time, ?string $referenceTime = null): ?string
    {
        $time = strtolower(trim($time));

        // If no am/pm specified, try to infer from reference time
        if (!preg_match('/(am|pm)/', $time) && $referenceTime) {
            if (preg_match('/(pm)/', strtolower($referenceTime))) {
                $time .= 'pm';
            } else {
                $time .= 'am';
            }
        }

        try {
            return Carbon::parse($time)->format('H:i:s');
        } catch (\Exception $e) {
            return null;
        }
    }

    protected function parseAndGeocodeLocation(string $location): array
    {
        $result = [
            'name' => null,
            'address' => null,
            'city' => 'Bellefontaine',
            'state' => 'OH',
            'zip' => null,
            'lat' => null,
            'lng' => null,
        ];

        if (empty($location)) {
            return $result;
        }

        // Try to extract zip code
        if (preg_match('/(\d{5})(?:-\d{4})?/', $location, $matches)) {
            $result['zip'] = $matches[1];
        }

        // Try to extract state
        if (preg_match('/\b([A-Z]{2})\b/', $location, $matches)) {
            $result['state'] = $matches[1];
        }

        // Simple heuristic: if it contains a street number, treat it as address
        if (preg_match('/^\d+/', trim($location))) {
            $result['address'] = $location;
        } else {
            // Might be a venue name followed by address
            $parts = preg_split('/[,\n]/', $location, 2);
            if (count($parts) > 1) {
                $result['name'] = trim($parts[0]);
                $result['address'] = trim($parts[1]);
            } else {
                $result['address'] = $location;
            }
        }

        // Geocode if not skipped
        if (!$this->option('skip-geocoding') && config('services.google.maps_api_key')) {
            $geocoded = $this->geocodeAddress($location);
            if ($geocoded) {
                $result = array_merge($result, $geocoded);
            }
        }

        return $result;
    }

    protected function geocodeAddress(string $address): ?array
    {
        $apiKey = config('services.google.maps_api_key');
        if (!$apiKey) {
            return null;
        }

        try {
            $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
                'address' => $address,
                'key' => $apiKey,
            ]);

            if (!$response->successful()) {
                return null;
            }

            $data = $response->json();
            if (empty($data['results'])) {
                return null;
            }

            $result = $data['results'][0];
            $location = $result['geometry']['location'];

            $parsed = [
                'lat' => $location['lat'],
                'lng' => $location['lng'],
            ];

            // Parse address components
            foreach ($result['address_components'] as $component) {
                $type = $component['types'][0] ?? null;
                switch ($type) {
                    case 'street_number':
                        $streetNumber = $component['long_name'];
                        break;
                    case 'route':
                        $route = $component['long_name'];
                        break;
                    case 'locality':
                        $parsed['city'] = $component['long_name'];
                        break;
                    case 'administrative_area_level_1':
                        $parsed['state'] = $component['short_name'];
                        break;
                    case 'postal_code':
                        $parsed['zip'] = $component['long_name'];
                        break;
                }
            }

            if (isset($streetNumber) && isset($route)) {
                $parsed['address'] = $streetNumber . ' ' . $route;
            }

            return $parsed;
        } catch (\Exception $e) {
            $this->warn("Geocoding failed: " . $e->getMessage());
            return null;
        }
    }

    protected function matchBusinessWithAI(string $title, string $description, string $location): ?int
    {
        if (empty($this->businesses)) {
            return null;
        }

        // Build context for AI
        $businessList = implode("\n", array_map(
            fn($name, $id) => "{$id}: {$name}",
            $this->businesses,
            array_keys($this->businesses)
        ));

        $prompt = <<<PROMPT
Given this event information and list of businesses, identify if the event is associated with any business.
Return ONLY the business ID number if there's a match, or "none" if no match.

Event Title: {$title}
Event Location: {$location}
Event Description (first 500 chars): {$this->truncate($description, 500)}

Businesses:
{$businessList}

Response (just the ID number or "none"):
PROMPT;

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.openai.api_key'),
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'max_tokens' => 50,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ]
            ]);

            if (!$response->successful()) {
                return null;
            }

            $content = $response->json('choices.0.message.content', 'none');
            $content = trim(strtolower($content));

            if ($content === 'none' || !is_numeric($content)) {
                return null;
            }

            $businessId = (int) $content;
            return isset($this->businesses[$businessId]) ? $businessId : null;
        } catch (\Exception $e) {
            $this->warn("AI matching failed: " . $e->getMessage());
            return null;
        }
    }

    protected function downloadAndProcessImage(string $thumbnailId, string $title): ?string
    {
        if (!isset($this->mediaLookup[$thumbnailId])) {
            $this->warn("Thumbnail ID {$thumbnailId} not found in media lookup");
            return null;
        }

        $imageUrl = $this->mediaLookup[$thumbnailId];

        try {
            // Download image
            $response = Http::timeout(30)->get($imageUrl);
            if (!$response->successful()) {
                $this->warn("Failed to download image (HTTP {$response->status()}): {$imageUrl}");
                return null;
            }

            $imageContent = $response->body();
            if (empty($imageContent)) {
                $this->warn("Empty image content from: {$imageUrl}");
                return null;
            }

            $extension = pathinfo(parse_url($imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
            // Normalize extension to jpg since we're converting to JPEG anyway
            $filename = Str::slug($title) . '-' . uniqid() . '.jpg';

            // Process with Intervention Image - resize and compress
            $image = Image::read($imageContent);

            // Resize to max 1920x1080 maintaining aspect ratio
            $image->scaleDown(1920, 1080);

            // Encode with quality reduction for compression
            $encoded = $image->toJpeg(80);

            // Save to storage
            $path = 'events/' . $filename;
            Storage::disk('public')->put($path, $encoded);

            return $path;
        } catch (\Exception $e) {
            $this->warn("Image processing failed for '{$title}': " . $e->getMessage());
            return null;
        }
    }

    protected function cleanDescription(string $html): string
    {
        // Remove WordPress-specific shortcodes
        $html = preg_replace('/\[.*?\]/', '', $html);

        // Remove Facebook emoji images but keep the alt text
        $html = preg_replace('/<img[^>]*alt="([^"]*)"[^>]*>/', '$1', $html);

        // Basic HTML cleanup - keep allowed tags
        $html = strip_tags($html, '<p><br><strong><em><b><i><ul><ol><li><a><h2><h3><h4><blockquote>');

        // Remove excessive whitespace
        $html = preg_replace('/\s+/', ' ', $html);
        $html = preg_replace('/(<br\s*\/?>\s*)+/', '<br>', $html);

        return trim($html);
    }

    protected function truncate(string $text, int $length): string
    {
        $text = strip_tags($text);
        if (strlen($text) <= $length) {
            return $text;
        }
        return substr($text, 0, $length) . '...';
    }
}
