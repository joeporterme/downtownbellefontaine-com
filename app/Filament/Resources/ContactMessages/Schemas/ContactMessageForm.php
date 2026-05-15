<?php

namespace App\Filament\Resources\ContactMessages\Schemas;

use App\Models\ContactMessage;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContactMessageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Message')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('email')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('phone')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('subject')
                            ->disabled()
                            ->dehydrated(false),
                        Textarea::make('message')
                            ->disabled()
                            ->dehydrated(false)
                            ->rows(6)
                            ->columnSpanFull(),
                    ]),

                Section::make('Triage')
                    ->columns(2)
                    ->schema([
                        Select::make('status')
                            ->options(ContactMessage::STATUSES)
                            ->required()
                            ->default('new'),
                        DateTimePicker::make('replied_at')
                            ->label('Replied At')
                            ->helperText('Set when you respond to track follow-up timing'),
                        Textarea::make('internal_notes')
                            ->label('Internal Notes')
                            ->rows(3)
                            ->columnSpanFull()
                            ->helperText('Not visible to the sender'),
                    ]),

                Section::make('Metadata')
                    ->columns(2)
                    ->collapsed()
                    ->schema([
                        TextInput::make('ip_address')
                            ->label('IP Address')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('created_at')
                            ->label('Received')
                            ->disabled()
                            ->dehydrated(false),
                        Textarea::make('user_agent')
                            ->label('User Agent')
                            ->disabled()
                            ->dehydrated(false)
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
