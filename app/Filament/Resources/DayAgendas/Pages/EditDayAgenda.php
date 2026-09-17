<?php

namespace App\Filament\Resources\DayAgendas\Pages;

use App\Filament\Resources\DayAgendas\DayAgendaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDayAgenda extends EditRecord
{
    protected static string $resource = DayAgendaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
