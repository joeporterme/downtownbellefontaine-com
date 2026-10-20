<?php

namespace App\Filament\Resources\SignatureEvents\Pages;

use App\Filament\Resources\SignatureEvents\SignatureEventResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSignatureEvent extends EditRecord
{
    protected static string $resource = SignatureEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
