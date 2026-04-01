<?php

namespace App\Services;

use App\Models\Business;
use App\Models\BusinessCategory;
use App\Models\BusinessLocation;
use App\Models\User;
use App\Services\AI\AIService;
use App\Services\Google\GeocodingService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BusinessImportService
{
    protected AIService $ai;
    protected GeocodingService $geocoding;
    protected array $categoryMap = [];
    protected array $stats = [
        'total' => 0,
        'imported' => 0,
        'skipped' => 0,
        'errors' => 0,
    ];
    protected array $errors = [];
    protected ?\Closure $progressCallback = null;

    public function __construct(AIService $ai, GeocodingService $geocoding)
    {
        $this->ai = $ai;
        $this->geocoding = $geocoding;
    }

    /**
     * Set a callback for progress updates.
     */
    public function onProgress(\Closure $callback): self
    {
        $this->progressCallback = $callback;
        return $this;
    }

    /**
     * Import businesses from the legacy SQL data.
     */
    public function importFromSqlFiles(
        string $listingsPath,
        string $categoriesPath,
        string $assignedCatPath,
        string $imagesPath
    ): array {
        // Parse all the SQL files
        $listings = $this->parseSqlInserts($listingsPath, 'sn_listing');
        $categories = $this->parseSqlInserts($categoriesPath, 'sn_listing_category');
        $assignedCats = $this->parseSqlInserts($assignedCatPath, 'sn_listing_assigned_cat');
        $images = $this->parseSqlInserts($imagesPath, 'sn_listing_images');

        // Build lookup maps
        $categoryById = collect($categories)->keyBy('categoryId');
        $assignmentsByListing = collect($assignedCats)->groupBy('listingId');
        $imagesByListing = collect($images)->groupBy('listingId');

        // Import categories first
        $this->importCategories($categories);

        $this->stats['total'] = count($listings);

        foreach ($listings as $index => $listing) {
            $this->progress("Processing {$listing['listingTitle']}...", $index + 1, $this->stats['total']);

            try {
                $this->importBusiness(
                    $listing,
                    $categoryById,
                    $assignmentsByListing->get($listing['listingId'], collect()),
                    $imagesByListing->get($listing['listingId'], collect())->first()
                );
                $this->stats['imported']++;
            } catch (\Exception $e) {
                $this->stats['errors']++;
                $this->errors[] = [
                    'business' => $listing['listingTitle'],
                    'error' => $e->getMessage(),
                ];
                Log::error('Business import error', [
                    'business' => $listing['listingTitle'],
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return [
            'stats' => $this->stats,
            'errors' => $this->errors,
            'categories_imported' => count($this->categoryMap),
        ];
    }

    /**
     * Import a single business from legacy data.
     */
    protected function importBusiness(
        array $listing,
        $categoryById,
        $assignments,
        ?array $imageData
    ): Business {
        // Check if business already exists by name
        $existingBusiness = Business::where('name', $listing['listingTitle'])->first();
        if ($existingBusiness) {
            $this->stats['skipped']++;
            throw new \Exception('Business already exists');
        }

        // Create or find user account
        $user = $this->getOrCreateUser($listing);

        // Generate AI description
        $description = $this->generateDescription($listing);

        // Geocode address
        $geocoded = $this->geocodeAddress($listing);

        // Download and store image
        $featuredImage = null;
        if ($imageData && !empty($imageData['imageSrc'])) {
            $featuredImage = $this->downloadImage($imageData['imageSrc'], $listing['listingTitle']);
        }

        // Create the business
        $business = Business::create([
            'user_id' => $user->id,
            'name' => $listing['listingTitle'],
            'slug' => $this->generateUniqueSlug($listing['listingTitle']),
            'description' => $description,
            'address' => $geocoded['address'] ?? $listing['address'] ?? null,
            'city' => $geocoded['city'] ?? 'Bellefontaine',
            'state' => $geocoded['state'] ?? 'OH',
            'zip' => $geocoded['zip'] ?? null,
            'phone' => $this->cleanPhone($listing['phone'] ?? null),
            'email' => $listing['email'] ?? null,
            'website' => $this->cleanUrl($listing['website'] ?? null),
            'facebook_url' => $this->cleanUrl($listing['facebook'] ?? null),
            'instagram_url' => $this->cleanUrl($listing['instagram'] ?? null),
            'featured_image' => $featuredImage,
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        // Create primary location
        if ($geocoded || !empty($listing['address'])) {
            BusinessLocation::create([
                'business_id' => $business->id,
                'name' => 'Main Location',
                'address' => $geocoded['address'] ?? $listing['address'] ?? null,
                'city' => $geocoded['city'] ?? 'Bellefontaine',
                'state' => $geocoded['state'] ?? 'OH',
                'zip' => $geocoded['zip'] ?? null,
                'phone' => $this->cleanPhone($listing['phone'] ?? null),
                'latitude' => $geocoded['latitude'] ?? $listing['latitude'] ?? null,
                'longitude' => $geocoded['longitude'] ?? $listing['longitude'] ?? null,
                'is_primary' => true,
                'is_active' => true,
            ]);
        }

        // Attach categories
        $categoryIds = [];
        foreach ($assignments as $assignment) {
            $catId = $assignment['categoryId'];
            if (isset($this->categoryMap[$catId])) {
                $categoryIds[] = $this->categoryMap[$catId];
            }
        }
        if (!empty($categoryIds)) {
            $business->categories()->attach($categoryIds);
        }

        return $business;
    }

    /**
     * Import categories from legacy data.
     */
    protected function importCategories(array $categories): void
    {
        foreach ($categories as $cat) {
            // Skip inactive categories
            if ($cat['categoryStatus'] === '0') {
                continue;
            }

            $existing = BusinessCategory::where('slug', $cat['categoryURL'])->first();

            if ($existing) {
                $this->categoryMap[$cat['categoryId']] = $existing->id;
            } else {
                $newCategory = BusinessCategory::create([
                    'name' => $cat['categoryTitle'],
                    'slug' => $cat['categoryURL'],
                    'is_active' => true,
                ]);
                $this->categoryMap[$cat['categoryId']] = $newCategory->id;
            }
        }
    }

    /**
     * Get or create a user account for the business.
     */
    protected function getOrCreateUser(array $listing): User
    {
        $email = $listing['email'] ?? null;

        // If no email, generate a temp one
        if (empty($email)) {
            $slug = Str::slug($listing['listingTitle']);
            $random = Str::random(6);
            $email = "{$slug}-{$random}@downtownbellefontaine.com";
        }

        // Check if user exists
        $user = User::where('email', $email)->first();
        if ($user) {
            return $user;
        }

        // Create new user
        return User::create([
            'name' => $listing['listingTitle'],
            'email' => $email,
            'password' => Hash::make(Str::random(16)),
            'role' => 'business_owner',
            'email_verified_at' => now(),
        ]);
    }

    /**
     * Generate an AI description for the business.
     */
    protected function generateDescription(array $listing): ?string
    {
        // Add variety hints to help AI generate unique descriptions
        $styleHints = [
            'Start with what they sell or offer.',
            'Lead with what makes them unique.',
            'Start with a welcoming invitation.',
            'Focus on the customer experience.',
            'Highlight their specialty or expertise.',
            'Mention what locals love about them.',
            'Start with the type of business they are.',
            'Lead with their most popular offering.',
        ];

        $randomHint = $styleHints[array_rand($styleHints)];

        try {
            $description = $this->ai->generateBusinessDescription([
                'name' => $listing['listingTitle'],
                'category' => $listing['category'] ?? null,
                'existing_description' => $listing['description'] ?? null,
                'website' => $listing['website'] ?? null,
                'services' => null,
                'notes' => "Style hint: {$randomHint}",
            ]);

            return $description;
        } catch (\Exception $e) {
            Log::warning('AI description generation failed', [
                'business' => $listing['listingTitle'],
                'error' => $e->getMessage(),
            ]);
            return $listing['description'] ?? null;
        }
    }

    /**
     * Geocode the business address.
     */
    protected function geocodeAddress(array $listing): ?array
    {
        $address = $listing['address'] ?? null;

        if (empty($address)) {
            return null;
        }

        // If we already have lat/lng from legacy data, use them but still try to parse address
        $legacyLat = $listing['latitude'] ?? null;
        $legacyLng = $listing['longitude'] ?? null;

        $geocoded = $this->geocoding->geocodeWithFallback($address, 'Bellefontaine', 'OH');

        if ($geocoded) {
            return $geocoded;
        }

        // Fall back to legacy coordinates if available
        if ($legacyLat && $legacyLng) {
            return [
                'address' => $address,
                'city' => 'Bellefontaine',
                'state' => 'OH',
                'zip' => null,
                'latitude' => $legacyLat,
                'longitude' => $legacyLng,
            ];
        }

        return null;
    }

    /**
     * Download an image and store it locally.
     */
    protected function downloadImage(string $url, string $businessName): ?string
    {
        try {
            // Clean up the URL
            $url = $this->cleanImageUrl($url);

            if (empty($url)) {
                return null;
            }

            $response = Http::timeout(30)->get($url);

            if (!$response->successful()) {
                Log::warning('Failed to download image', [
                    'url' => $url,
                    'status' => $response->status(),
                ]);
                return null;
            }

            // Determine file extension from content type
            $contentType = $response->header('Content-Type');
            $extension = $this->getExtensionFromContentType($contentType) ?? 'jpg';

            // Generate filename
            $slug = Str::slug($businessName);
            $filename = "businesses/featured/{$slug}-" . Str::random(8) . ".{$extension}";

            // Store the image
            Storage::disk('public')->put($filename, $response->body());

            return $filename;
        } catch (\Exception $e) {
            Log::warning('Image download exception', [
                'url' => $url,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Clean up image URL.
     */
    protected function cleanImageUrl(string $url): string
    {
        $url = trim($url);

        // If it's a relative path, prepend the base URL
        if (!str_starts_with($url, 'http')) {
            $url = 'https://downtownbellefontaine.com' . (str_starts_with($url, '/') ? '' : '/') . $url;
        }

        return $url;
    }

    /**
     * Get file extension from content type.
     */
    protected function getExtensionFromContentType(?string $contentType): ?string
    {
        if (!$contentType) {
            return null;
        }

        return match (true) {
            str_contains($contentType, 'jpeg') => 'jpg',
            str_contains($contentType, 'jpg') => 'jpg',
            str_contains($contentType, 'png') => 'png',
            str_contains($contentType, 'gif') => 'gif',
            str_contains($contentType, 'webp') => 'webp',
            default => null,
        };
    }

    /**
     * Generate a unique slug for a business.
     */
    protected function generateUniqueSlug(string $name): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (Business::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    /**
     * Clean phone number.
     */
    protected function cleanPhone(?string $phone): ?string
    {
        if (empty($phone)) {
            return null;
        }

        // Remove all non-numeric characters except 'x' for extensions
        $cleaned = preg_replace('/[^0-9x]/i', '', $phone);

        return $cleaned ?: null;
    }

    /**
     * Clean and validate URL.
     */
    protected function cleanUrl(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        $url = trim($url);

        // Add https:// if no protocol specified
        if (!empty($url) && !preg_match('/^https?:\/\//i', $url)) {
            $url = 'https://' . $url;
        }

        // Validate URL format
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return null;
        }

        return $url;
    }

    /**
     * Parse SQL INSERT statements from a file.
     */
    public function parseSqlInserts(string $filepath, string $tableName): array
    {
        $content = file_get_contents($filepath);
        $records = [];

        // Find the INSERT statement
        $pattern = '/INSERT INTO `' . preg_quote($tableName, '/') . '`\s*\([^)]+\)\s*VALUES\s*/i';

        if (!preg_match($pattern, $content, $matches, \PREG_OFFSET_CAPTURE)) {
            return $records;
        }

        // Extract column names
        preg_match('/INSERT INTO `' . preg_quote($tableName, '/') . '`\s*\(([^)]+)\)/i', $content, $columnMatch);
        $columns = array_map(function ($col) {
            return trim(str_replace('`', '', $col));
        }, explode(',', $columnMatch[1]));

        // Find all value sets using proper parser
        $valuesStart = $matches[0][1] + strlen($matches[0][0]);
        $valuesSection = substr($content, $valuesStart);

        // Parse value sets properly handling strings with parentheses
        $valueSets = $this->extractValueSets($valuesSection);

        foreach ($valueSets as $valueSet) {
            $values = $this->parseValueSet($valueSet);

            if (count($values) === count($columns)) {
                $record = [];
                foreach ($columns as $i => $col) {
                    $record[$col] = $values[$i];
                }
                $records[] = $record;
            }
        }

        return $records;
    }

    /**
     * Extract value sets from SQL VALUES section, properly handling strings.
     */
    protected function extractValueSets(string $valuesSection): array
    {
        $valueSets = [];
        $current = '';
        $depth = 0;
        $inString = false;
        $stringChar = null;
        $escaped = false;
        $len = strlen($valuesSection);

        for ($i = 0; $i < $len; $i++) {
            $char = $valuesSection[$i];

            // Handle escape sequences
            if ($escaped) {
                $current .= $char;
                $escaped = false;
                continue;
            }

            if ($char === '\\' && $inString) {
                $current .= $char;
                $escaped = true;
                continue;
            }

            // Handle string boundaries
            if (!$inString && ($char === "'" || $char === '"')) {
                $inString = true;
                $stringChar = $char;
                $current .= $char;
                continue;
            }

            if ($inString && $char === $stringChar) {
                // Check for escaped quote ('' or "")
                if ($i + 1 < $len && $valuesSection[$i + 1] === $stringChar) {
                    $current .= $char . $valuesSection[$i + 1];
                    $i++;
                    continue;
                }
                $inString = false;
                $stringChar = null;
                $current .= $char;
                continue;
            }

            // Track parentheses depth (only when not in string)
            if (!$inString) {
                if ($char === '(') {
                    $depth++;
                    if ($depth === 1) {
                        // Start of a new value set
                        $current = '';
                        continue;
                    }
                } elseif ($char === ')') {
                    $depth--;
                    if ($depth === 0) {
                        // End of value set
                        $valueSets[] = $current;
                        $current = '';
                        continue;
                    }
                } elseif ($char === ';' && $depth === 0) {
                    // End of INSERT statement
                    break;
                }
            }

            if ($depth > 0) {
                $current .= $char;
            }
        }

        return $valueSets;
    }

    /**
     * Parse a single value set from SQL.
     */
    protected function parseValueSet(string $valueSet): array
    {
        $values = [];
        $current = '';
        $inString = false;
        $stringChar = null;
        $escaped = false;

        for ($i = 0; $i < strlen($valueSet); $i++) {
            $char = $valueSet[$i];

            if ($escaped) {
                $current .= $char;
                $escaped = false;
                continue;
            }

            if ($char === '\\') {
                $escaped = true;
                continue;
            }

            if (!$inString && ($char === "'" || $char === '"')) {
                $inString = true;
                $stringChar = $char;
                continue;
            }

            if ($inString && $char === $stringChar) {
                $inString = false;
                $stringChar = null;
                continue;
            }

            if (!$inString && $char === ',') {
                $values[] = $this->cleanValue(trim($current));
                $current = '';
                continue;
            }

            $current .= $char;
        }

        // Don't forget the last value
        if ($current !== '' || count($values) > 0) {
            $values[] = $this->cleanValue(trim($current));
        }

        return $values;
    }

    /**
     * Clean a SQL value.
     */
    protected function cleanValue(string $value): ?string
    {
        if ($value === 'NULL' || $value === 'null') {
            return null;
        }

        // Remove surrounding quotes if present
        if ((str_starts_with($value, "'") && str_ends_with($value, "'")) ||
            (str_starts_with($value, '"') && str_ends_with($value, '"'))) {
            $value = substr($value, 1, -1);
        }

        // Unescape special characters
        $value = str_replace(["\\n", "\\r", "\\t", "\\'", '\\"'], ["\n", "\r", "\t", "'", '"'], $value);

        return $value;
    }

    /**
     * Report progress.
     */
    protected function progress(string $message, int $current, int $total): void
    {
        if ($this->progressCallback) {
            ($this->progressCallback)($message, $current, $total);
        }
    }

    /**
     * Get current stats.
     */
    public function getStats(): array
    {
        return $this->stats;
    }

    /**
     * Get errors.
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
