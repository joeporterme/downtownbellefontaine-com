<?php

namespace App\Filament\Resources\ScavengerClues\Pages;

use App\Filament\Resources\ScavengerClues\ScavengerClueResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditScavengerClue extends EditRecord
{
    protected static string $resource = ScavengerClueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
