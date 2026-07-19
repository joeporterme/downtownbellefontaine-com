<?php

namespace App\Filament\Resources\Businesses\Pages;

use App\Filament\Resources\Businesses\BusinessResource;
use App\Filament\Resources\Businesses\Concerns\AppliesPlacesPhoto;
use Filament\Resources\Pages\CreateRecord;

class CreateBusiness extends CreateRecord
{
    use AppliesPlacesPhoto;

    protected static string $resource = BusinessResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->capturePlacesPhoto($data);
    }

    protected function afterCreate(): void
    {
        $this->applyPlacesPhoto();
    }
}
