<?php

namespace App\Filament\Resources\Press\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PressItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Press Mention')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull()
                            ->helperText('The headline of the article/feature.'),
                        TextInput::make('url')
                            ->label('Link')
                            ->url()
                            ->required()
                            ->maxLength(500)
                            ->columnSpanFull()
                            ->placeholder('https://'),
                        TextInput::make('source')
                            ->label('Publication / Outlet')
                            ->maxLength(255)
                            ->placeholder('e.g., Ohio Magazine, 10TV'),
                        DatePicker::make('published_date')
                            ->label('Date')
                            ->required()
                            ->default(now()),
                        Toggle::make('is_active')
                            ->label('Show on the Media page')
                            ->default(true),
                    ]),
            ]);
    }
}
