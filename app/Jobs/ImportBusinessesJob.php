<?php

namespace App\Jobs;

use App\Services\BusinessImportService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ImportBusinessesJob implements ShouldQueue
{
    use Queueable;

    public int $timeout = 3600; // 1 hour
    public int $tries = 1;

    protected string $listingsPath;
    protected string $categoriesPath;
    protected string $assignedCatPath;
    protected string $imagesPath;

    public function __construct(
        string $listingsPath,
        string $categoriesPath,
        string $assignedCatPath,
        string $imagesPath
    ) {
        $this->listingsPath = $listingsPath;
        $this->categoriesPath = $categoriesPath;
        $this->assignedCatPath = $assignedCatPath;
        $this->imagesPath = $imagesPath;
    }

    public function handle(BusinessImportService $importer): void
    {
        Cache::put('business_import_status', [
            'status' => 'running',
            'started_at' => now()->toDateTimeString(),
            'message' => 'Starting import...',
        ], 3600);

        try {
            $importer->onProgress(function ($message, $current, $total) {
                Cache::put('business_import_status', [
                    'status' => 'running',
                    'started_at' => Cache::get('business_import_status')['started_at'] ?? now()->toDateTimeString(),
                    'message' => $message,
                    'current' => $current,
                    'total' => $total,
                    'percent' => $total > 0 ? round(($current / $total) * 100) : 0,
                ], 3600);
            });

            $result = $importer->importFromSqlFiles(
                $this->listingsPath,
                $this->categoriesPath,
                $this->assignedCatPath,
                $this->imagesPath
            );

            Cache::put('business_import_status', [
                'status' => 'completed',
                'completed_at' => now()->toDateTimeString(),
                'message' => 'Import completed successfully',
                'results' => $result,
            ], 3600);

            Log::info('Business import completed', $result);

        } catch (\Exception $e) {
            Cache::put('business_import_status', [
                'status' => 'failed',
                'failed_at' => now()->toDateTimeString(),
                'message' => $e->getMessage(),
                'error' => $e->getMessage(),
            ], 3600);

            Log::error('Business import failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }
}
