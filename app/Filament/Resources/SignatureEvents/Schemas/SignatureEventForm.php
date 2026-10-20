<?php

namespace App\Filament\Resources\SignatureEvents\Schemas;

use App\Models\SignatureEvent;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SignatureEventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make('Event')
                    ->columnSpan(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Select::make('month')
                            ->options(SignatureEvent::MONTHS)
                            ->required(),
                        TextInput::make('timing_note')
                            ->label('When')
                            ->maxLength(255)
                            ->placeholder('First Friday in June')
                            ->helperText('Optional. Shown under the event name in the guide.'),
                        Textarea::make('blurb')
                            ->maxLength(500)
                            ->rows(3)
                            ->helperText('One or two friendly sentences.'),
                        TextInput::make('url')
                            ->label('More info link')
                            ->url()
                            ->maxLength(255),
                    ]),

                Section::make('Settings')
                    ->columnSpan(1)
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Show in the Visitors Guide')
                            ->default(true),
                        TextInput::make('sort')
                            ->numeric()
                            ->default(0)
                            ->helperText('Orders events within the same month. Lower shows first.'),
                    ]),
            ]);
    }
}
