<?php

namespace App\Filament\Resources\Events\Pages;

use App\Filament\Resources\Events\EventResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEvent extends EditRecord
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // If status changed to approved, record who approved it
        if ($data['status'] === 'approved' && $this->record->status !== 'approved') {
            $data['approved_at'] = now();
            $data['approved_by'] = auth()->id();
        }

        // If status changed from approved, clear approval info
        if ($data['status'] !== 'approved') {
            $data['approved_at'] = null;
            $data['approved_by'] = null;
        }

        return $data;
    }
}
