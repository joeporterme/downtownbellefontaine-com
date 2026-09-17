<?php

namespace App\Filament\Resources\DayAgendas\Pages;

use App\Filament\Resources\DayAgendas\DayAgendaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDayAgendas extends ListRecords
{
    protected static string $resource = DayAgendaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
