<?php

namespace App\Filament\Resources\Businesses\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;

class BusinessForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Business Information')
                    ->columns(2)
                    ->schema([
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->label('Owner')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                                'inactive' => 'Inactive',
                            ])
                            ->default('pending')
                            ->required(),
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('slug')
                            ->maxLength(255)
                            ->helperText('Leave blank to auto-generate'),
                        Textarea::make('description')
                            ->columnSpanFull()
                            ->rows(4),
                        Select::make('categories')
                            ->relationship('categories', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->columnSpanFull(),
                    ]),

                Section::make('Locations')
                    ->description('Search for the business address — city, state, ZIP, and coordinates fill in automatically.')
                    ->schema([
                        Repeater::make('locations')
                            ->relationship()
                            ->schema([
                                TextInput::make('name')
                                    ->label('Location Name')
                                    ->placeholder('e.g., Main Store, Downtown Branch')
                                    ->maxLength(255)
                                    ->columnSpanFull(),

                                View::make('filament.forms.places-autocomplete')
                                    ->columnSpanFull(),

                                // Kept in form state, populated by the Places view above.
                                Hidden::make('address'),
                                Hidden::make('city')->default('Bellefontaine'),
                                Hidden::make('state')->default('OH'),
                                Hidden::make('zip'),
                                Hidden::make('latitude'),
                                Hidden::make('longitude'),

                                TextInput::make('phone')
                                    ->tel()
                                    ->maxLength(20),
                                Toggle::make('is_primary')
                                    ->label('Primary Location')
                                    ->default(false),
                                Toggle::make('is_active')
                                    ->label('Active')
                                    ->default(true),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->addActionLabel('Add Location')
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? $state['address'] ?? null),
                    ]),

                Section::make('Contact Information')
                    ->columns(2)
                    ->schema([
                        TextInput::make('phone')
                            ->tel()
                            ->maxLength(20),
                        TextInput::make('email')
                            ->label('Business Email')
                            ->email()
                            ->maxLength(255),
                        TextInput::make('website')
                            ->url()
                            ->maxLength(255)
                            ->columnSpanFull()
                            ->placeholder('https://'),
                    ]),

                Section::make('Social Media')
                    ->columns(2)
                    ->schema([
                        TextInput::make('facebook_url')
                            ->label('Facebook')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://facebook.com/...'),
                        TextInput::make('instagram_url')
                            ->label('Instagram')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://instagram.com/...'),
                        TextInput::make('tiktok_url')
                            ->label('TikTok')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://tiktok.com/@...'),
                        TextInput::make('snapchat_url')
                            ->label('Snapchat')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://snapchat.com/add/...'),
                        TextInput::make('x_url')
                            ->label('X (Twitter)')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://x.com/...'),
                    ]),

                Section::make('Media')
                    ->columns(2)
                    ->schema([
                        FileUpload::make('logo')
                            ->image()
                            ->disk('public')
                            ->directory('businesses/logos')
                            ->imageEditor(),
                        FileUpload::make('featured_image')
                            ->image()
                            ->disk('public')
                            ->directory('businesses')
                            ->imageEditor(),
                    ]),

                Section::make('Approval')
                    ->schema([
                        DateTimePicker::make('approved_at')
                            ->label('Approved Date'),
                    ])
                    ->collapsed(),
            ]);
    }
}
