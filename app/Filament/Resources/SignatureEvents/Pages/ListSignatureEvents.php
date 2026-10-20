<?php

namespace App\Filament\Resources\SignatureEvents\Pages;

use App\Filament\Resources\SignatureEvents\SignatureEventResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSignatureEvents extends ListRecords
{
    protected static string $resource = SignatureEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
