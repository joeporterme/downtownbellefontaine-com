<?php

namespace App\Filament\Resources\ScavengerClues\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ScavengerClueForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make('Clue')
                    ->columnSpan(2)
                    ->schema([
                        Textarea::make('clue')
                            ->required()
                            ->maxLength(500)
                            ->rows(3),
                        TextInput::make('answer')
                            ->maxLength(255)
                            ->helperText('Hidden until a visitor taps "Reveal answer". Leave blank to hide the reveal button.'),
                        Select::make('business_id')
                            ->label('Answer is a business (optional)')
                            ->relationship('business', 'name')
                            ->searchable()
                            ->preload()
                            ->helperText('Links the revealed answer to that business page.'),
                    ]),

                Section::make('Settings')
                    ->columnSpan(1)
                    ->schema([
                        FileUpload::make('image')
                            ->image()
                            ->disk('public')
                            ->directory('visitors-guide/clues')
                            ->imageEditor()
                            ->maxSize(5120)
                            ->helperText('Optional hint photo.'),
                        Toggle::make('is_active')
                            ->label('Show in the Visitors Guide')
                            ->default(true),
                        TextInput::make('sort')
                            ->numeric()
                            ->default(0)
                            ->helperText('Clue order. Lower shows first.'),
                    ]),
            ]);
    }
}
