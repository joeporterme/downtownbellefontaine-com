<?php

namespace App\Filament\Pages;

use App\Jobs\ImportBusinessesJob;
use App\Models\Business;
use App\Models\BusinessCategory;
use App\Services\BusinessImportService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Cache;
use UnitEnum;

class ImportBusinesses extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-arrow-up-tray';
    protected static ?string $navigationLabel = 'Import Businesses';
    protected static string|UnitEnum|null $navigationGroup = 'Tools';
    protected static ?int $navigationSort = 100;
    protected string $view = 'filament.pages.import-businesses';

    public array $previewData = [];
    public bool $showPreview = false;
    public array $importStatus = [];

    public function mount(): void
    {
        $this->loadPreviewFromCache();
        $this->checkImportStatus();
    }

    protected function loadPreviewFromCache(): void
    {
        // Try to get cached preview data first
        $cached = Cache::get('business_import_preview');

        if ($cached) {
            $this->previewData = $cached;
            $this->previewData['existing_businesses'] = Business::count();
            $this->previewData['existing_categories'] = BusinessCategory::count();
            $this->showPreview = true;
            return;
        }

        // If not cached, load it (but only on explicit refresh)
        $this->previewData = [
            'needs_refresh' => true,
            'message' => 'Click "Load Preview" to scan SQL files',
        ];
    }

    public function loadPreview(): void
    {
        $path = resource_path();
        $files = [
            'listings' => "{$path}/sn_listing.sql",
            'categories' => "{$path}/sn_listing_category.sql",
            'assignments' => "{$path}/sn_listing_assigned_cat.sql",
            'images' => "{$path}/sn_listing_images.sql",
        ];

        $allExist = true;
        foreach ($files as $file) {
            if (!file_exists($file)) {
                $allExist = false;
                break;
            }
        }

        if (!$allExist) {
            $this->previewData = [
                'error' => 'SQL files not found in resources folder',
                'files_needed' => [
                    'sn_listing.sql',
                    'sn_listing_category.sql',
                    'sn_listing_assigned_cat.sql',
                    'sn_listing_images.sql',
                ],
            ];
            return;
        }

        $importer = app(BusinessImportService::class);

        $listings = $importer->parseSqlInserts($files['listings'], 'sn_listing');
        $categories = $importer->parseSqlInserts($files['categories'], 'sn_listing_category');
        $assignments = $importer->parseSqlInserts($files['assignments'], 'sn_listing_assigned_cat');
        $images = $importer->parseSqlInserts($files['images'], 'sn_listing_images');

        $this->previewData = [
            'total_businesses' => count($listings),
            'total_categories' => count($categories),
            'total_images' => count($images),
            'categories' => collect($categories)->filter(fn($c) => $c['categoryStatus'] === '1')->values()->all(),
            'sample_businesses' => collect($listings)->take(10)->map(function ($l) use ($assignments, $categories, $images) {
                $catIds = collect($assignments)
                    ->where('listingId', $l['listingId'])
                    ->pluck('categoryId');

                $catNames = collect($categories)
                    ->whereIn('categoryId', $catIds)
                    ->pluck('categoryTitle')
                    ->implode(', ');

                $image = collect($images)->firstWhere('listingId', $l['listingId']);

                return [
                    'id' => $l['listingId'],
                    'name' => $l['listingTitle'],
                    'email' => $l['email'] ?: null,
                    'address' => $l['address'] ?? null,
                    'categories' => $catNames,
                    'has_image' => !empty($image),
                ];
            })->all(),
            'existing_businesses' => Business::count(),
            'existing_categories' => BusinessCategory::count(),
        ];

        // Cache for 1 hour
        Cache::put('business_import_preview', $this->previewData, 3600);

        $this->showPreview = true;

        Notification::make()
            ->title('Preview Loaded')
            ->body("Found {$this->previewData['total_businesses']} businesses to import.")
            ->success()
            ->send();
    }

    public function checkImportStatus(): void
    {
        $this->importStatus = Cache::get('business_import_status', []);
    }

    public function clearImportStatus(): void
    {
        Cache::forget('business_import_status');
        $this->importStatus = [];
    }

    public function startImport(): void
    {
        $path = resource_path();

        // Dispatch the job
        ImportBusinessesJob::dispatch(
            "{$path}/sn_listing.sql",
            "{$path}/sn_listing_category.sql",
            "{$path}/sn_listing_assigned_cat.sql",
            "{$path}/sn_listing_images.sql"
        );

        // Set initial status
        Cache::put('business_import_status', [
            'status' => 'queued',
            'queued_at' => now()->toDateTimeString(),
            'message' => 'Import job queued. Processing will begin shortly...',
        ], 3600);

        $this->importStatus = Cache::get('business_import_status');

        Notification::make()
            ->title('Import Started')
            ->body('The import has been queued and will run in the background. Refresh this page to check progress.')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        $isRunning = ($this->importStatus['status'] ?? null) === 'running';
        $isQueued = ($this->importStatus['status'] ?? null) === 'queued';

        return [
            Action::make('loadPreview')
                ->label('Load Preview')
                ->icon('heroicon-o-eye')
                ->color('gray')
                ->action(fn () => $this->loadPreview())
                ->visible(fn () => !$this->showPreview || isset($this->previewData['needs_refresh'])),

            Action::make('import')
                ->label($isRunning || $isQueued ? 'Import in Progress...' : 'Start Import')
                ->icon($isRunning || $isQueued ? 'heroicon-o-arrow-path' : 'heroicon-o-play')
                ->color('primary')
                ->requiresConfirmation()
                ->modalHeading('Start Business Import')
                ->modalDescription('This will import all businesses from the SQL files. The import runs in the background. You can check progress by refreshing this page.')
                ->modalSubmitActionLabel('Yes, Start Import')
                ->action(fn () => $this->startImport())
                ->disabled(fn () => !$this->showPreview || isset($this->previewData['error']) || $isRunning || $isQueued),

            Action::make('refresh')
                ->label('Refresh Status')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->action(function () {
                    $this->checkImportStatus();
                    $this->previewData['existing_businesses'] = Business::count();
                    $this->previewData['existing_categories'] = BusinessCategory::count();
                }),

            Action::make('clearStatus')
                ->label('Clear Status')
                ->icon('heroicon-o-x-mark')
                ->color('danger')
                ->action(fn () => $this->clearImportStatus())
                ->visible(fn () => !empty($this->importStatus) && ($this->importStatus['status'] ?? null) !== 'running'),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }
}
