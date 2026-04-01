<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Account Information')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->rule(Password::default())
                            ->helperText(fn (string $operation): ?string =>
                                $operation === 'edit' ? 'Leave blank to keep current password' : null
                            ),
                        Select::make('role')
                            ->options([
                                'admin' => 'Admin',
                                'business_owner' => 'Business Owner',
                            ])
                            ->default('business_owner')
                            ->required(),
                    ]),

                Section::make('Verification')
                    ->schema([
                        DateTimePicker::make('email_verified_at')
                            ->label('Email Verified At')
                            ->helperText('Set this to verify the user\'s email manually'),
                    ])
                    ->collapsed(),

                Section::make('Associated Businesses')
                    ->schema([
                        Select::make('businesses')
                            ->relationship('businesses', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->helperText('Businesses owned by this user'),
                    ])
                    ->collapsed()
                    ->visible(fn (string $operation): bool => $operation === 'edit'),
            ]);
    }
}
