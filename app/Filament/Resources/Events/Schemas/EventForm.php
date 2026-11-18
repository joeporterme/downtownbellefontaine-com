<?php

namespace App\Filament\Resources\Events\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Event Details')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        RichEditor::make('description')
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('events/content')
                            ->toolbarButtons([
                                'attachFiles',
                                'bold',
                                'italic',
                                'underline',
                                'strike',
                                'link',
                                'orderedList',
                                'bulletList',
                                'h2',
                                'h3',
                                'blockquote',
                            ])
                            ->columnSpanFull(),

                        FileUpload::make('featured_image')
                            ->image()
                            ->disk('public')
                            ->directory('events')
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9')
                            ->imageResizeTargetWidth('1920')
                            ->imageResizeTargetHeight('1080')
                            ->maxSize(10240)
                            ->columnSpanFull(),

                        Select::make('business_id')
                            ->label('Associated Business')
                            ->relationship('business', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->helperText('Optional: Link this event to a business'),
                    ])
                    ->columns(2),

                Section::make('Date & Time')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                DatePicker::make('event_date')
                                    ->label('Start Date')
                                    ->required()
                                    ->live()
                                    ->native(false),

                                DatePicker::make('end_date')
                                    ->label('End Date')
                                    ->helperText('Only for events that run several days. Leave empty for a one-day event.')
                                    ->afterOrEqual('event_date')
                                    ->live()
                                    ->native(false),

                                TimePicker::make('start_time')
                                    ->label('Start Time')
                                    ->seconds(false),

                                TimePicker::make('end_time')
                                    ->label('End Time')
                                    ->seconds(false)
                                    // A multi-day event's end time is the last day's, so it can be earlier.
                                    ->after(fn (Get $get) => filled($get('end_date')) && $get('end_date') !== $get('event_date') ? null : 'start_time'),
                            ]),

                        Textarea::make('schedule')
                            ->label('Schedule / Hours')
                            ->rows(3)
                            ->maxLength(1000)
                            ->placeholder("Fri & Sat 5–9 PM\nSun 1–5 PM")
                            ->helperText('Optional. Shows on the event card in place of the start and end time, for events whose hours change by day.'),
                    ]),

                Section::make('Location')
                    ->schema([
                        TextInput::make('location_name')
                            ->label('Venue/Location Name')
                            ->maxLength(255),

                        View::make('filament.forms.components.google-places-autocomplete')
                            ->columnSpanFull(),

                        TextInput::make('address')
                            ->label('Street Address')
                            ->maxLength(255),

                        Grid::make(3)
                            ->schema([
                                TextInput::make('city')
                                    ->maxLength(100)
                                    ->default('Bellefontaine'),

                                TextInput::make('state')
                                    ->maxLength(2)
                                    ->default('OH'),

                                TextInput::make('zip')
                                    ->label('ZIP Code')
                                    ->maxLength(10),
                            ]),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('latitude')
                                    ->numeric()
                                    ->step(0.00000001),

                                TextInput::make('longitude')
                                    ->numeric()
                                    ->step(0.00000001),
                            ]),
                    ]),

                Section::make('Additional Info')
                    ->schema([
                        TextInput::make('more_info_url')
                            ->label('More Info URL')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://...'),

                        Select::make('status')
                            ->options([
                                'pending' => 'Pending Approval',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                            ])
                            ->default('approved')
                            ->required(),
                    ]),
            ]);
    }
}
