<?php

namespace App\Filament\Resources\Events\Pages;

use App\Filament\Resources\Events\EventResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Admin-created events are automatically approved
        $data['status'] = $data['status'] ?? 'approved';
        $data['approved_at'] = now();
        $data['approved_by'] = auth()->id();

        return $data;
    }
}
