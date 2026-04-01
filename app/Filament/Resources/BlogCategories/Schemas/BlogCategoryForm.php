<?php

namespace App\Filament\Resources\BlogCategories\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BlogCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Category Information')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('slug')
                            ->maxLength(255)
                            ->helperText('Leave blank to auto-generate'),
                        ColorPicker::make('color')
                            ->label('Badge Color')
                            ->helperText('Color for category badges'),
                        TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers appear first'),
                        Textarea::make('description')
                            ->rows(2)
                            ->columnSpanFull(),
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Inactive categories will not appear in dropdowns'),
                    ]),
            ]);
    }
}
