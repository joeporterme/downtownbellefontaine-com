<?php

namespace App\Filament\Resources\Authors\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AuthorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Author Information')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('slug')
                            ->maxLength(255)
                            ->helperText('Leave blank to auto-generate'),
                        TextInput::make('title')
                            ->label('Title / Role')
                            ->placeholder('e.g., Staff Writer, Guest Contributor')
                            ->maxLength(255),
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Inactive authors will not appear in dropdowns'),
                        Textarea::make('bio')
                            ->label('Short Bio')
                            ->rows(3)
                            ->columnSpanFull(),
                        FileUpload::make('photo')
                            ->image()
                            ->directory('authors')
                            ->imageEditor()
                            ->circleCropper()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
