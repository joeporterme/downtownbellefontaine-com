<?php

namespace App\Filament\Resources\Press\Pages;

use App\Filament\Resources\Press\PressResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPressItem extends EditRecord
{
    protected static string $resource = PressResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
