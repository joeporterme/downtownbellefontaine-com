<?php

namespace App\Filament\Resources\ScavengerClues\Pages;

use App\Filament\Resources\ScavengerClues\ScavengerClueResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListScavengerClues extends ListRecords
{
    protected static string $resource = ScavengerClueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
