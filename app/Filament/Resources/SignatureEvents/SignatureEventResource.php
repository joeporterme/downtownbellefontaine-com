<?php

namespace App\Filament\Resources\SignatureEvents;

use App\Filament\Resources\SignatureEvents\Pages\CreateSignatureEvent;
use App\Filament\Resources\SignatureEvents\Pages\EditSignatureEvent;
use App\Filament\Resources\SignatureEvents\Pages\ListSignatureEvents;
use App\Filament\Resources\SignatureEvents\Schemas\SignatureEventForm;
use App\Filament\Resources\SignatureEvents\Tables\SignatureEventsTable;
use App\Models\SignatureEvent;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SignatureEventResource extends Resource
{
    protected static ?string $model = SignatureEvent::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static string|UnitEnum|null $navigationGroup = 'Visitors Guide';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationLabel = 'Signature Events';

    protected static ?string $modelLabel = 'Signature Event';

    protected static ?string $pluralModelLabel = 'Signature Events';

    public static function form(Schema $schema): Schema
    {
        return SignatureEventForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SignatureEventsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSignatureEvents::route('/'),
            'create' => CreateSignatureEvent::route('/create'),
            'edit' => EditSignatureEvent::route('/{record}/edit'),
        ];
    }
}
