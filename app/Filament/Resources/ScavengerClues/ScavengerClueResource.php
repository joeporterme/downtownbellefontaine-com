<?php

namespace App\Filament\Resources\ScavengerClues;

use App\Filament\Resources\ScavengerClues\Pages\CreateScavengerClue;
use App\Filament\Resources\ScavengerClues\Pages\EditScavengerClue;
use App\Filament\Resources\ScavengerClues\Pages\ListScavengerClues;
use App\Filament\Resources\ScavengerClues\Schemas\ScavengerClueForm;
use App\Filament\Resources\ScavengerClues\Tables\ScavengerCluesTable;
use App\Models\ScavengerClue;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ScavengerClueResource extends Resource
{
    protected static ?string $model = ScavengerClue::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMagnifyingGlass;

    protected static string|UnitEnum|null $navigationGroup = 'Visitors Guide';

    protected static ?int $navigationSort = 20;

    protected static ?string $navigationLabel = 'Scavenger Hunt';

    protected static ?string $modelLabel = 'Scavenger Clue';

    protected static ?string $pluralModelLabel = 'Scavenger Clues';

    public static function form(Schema $schema): Schema
    {
        return ScavengerClueForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ScavengerCluesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListScavengerClues::route('/'),
            'create' => CreateScavengerClue::route('/create'),
            'edit' => EditScavengerClue::route('/{record}/edit'),
        ];
    }
}
