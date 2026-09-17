<?php

namespace App\Filament\Resources\DayAgendas;

use App\Filament\Resources\DayAgendas\Pages\CreateDayAgenda;
use App\Filament\Resources\DayAgendas\Pages\EditDayAgenda;
use App\Filament\Resources\DayAgendas\Pages\ListDayAgendas;
use App\Filament\Resources\DayAgendas\Schemas\DayAgendaForm;
use App\Filament\Resources\DayAgendas\Tables\DayAgendasTable;
use App\Models\DayAgenda;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class DayAgendaResource extends Resource
{
    protected static ?string $model = DayAgenda::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMap;

    protected static string|UnitEnum|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 45;

    protected static ?string $navigationLabel = 'Day Agendas';

    protected static ?string $modelLabel = 'Day Agenda';

    protected static ?string $pluralModelLabel = 'Day Agendas';

    public static function form(Schema $schema): Schema
    {
        return DayAgendaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DayAgendasTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDayAgendas::route('/'),
            'create' => CreateDayAgenda::route('/create'),
            'edit' => EditDayAgenda::route('/{record}/edit'),
        ];
    }
}
