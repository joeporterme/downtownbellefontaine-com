<?php

namespace App\Filament\Resources\Press;

use App\Filament\Resources\Press\Pages\CreatePressItem;
use App\Filament\Resources\Press\Pages\EditPressItem;
use App\Filament\Resources\Press\Pages\ListPressItems;
use App\Filament\Resources\Press\Schemas\PressItemForm;
use App\Filament\Resources\Press\Tables\PressItemsTable;
use App\Models\PressItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PressResource extends Resource
{
    protected static ?string $model = PressItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

    protected static string|UnitEnum|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 60;

    protected static ?string $navigationLabel = 'Press / Media';

    protected static ?string $modelLabel = 'press item';

    public static function form(Schema $schema): Schema
    {
        return PressItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PressItemsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPressItems::route('/'),
            'create' => CreatePressItem::route('/create'),
            'edit' => EditPressItem::route('/{record}/edit'),
        ];
    }
}
