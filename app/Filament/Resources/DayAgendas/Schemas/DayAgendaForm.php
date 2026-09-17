<?php

namespace App\Filament\Resources\DayAgendas\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DayAgendaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make('Itinerary')
                    ->columnSpan(2)
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set, $operation) {
                                if ($operation === 'create') {
                                    $set('slug', \Illuminate\Support\Str::slug($state));
                                }
                            }),
                        TextInput::make('slug')
                            ->maxLength(255)
                            ->helperText('Leave blank to auto-generate. Keep it stable — it is the page URL.'),
                        Textarea::make('excerpt')
                            ->maxLength(500)
                            ->rows(2)
                            ->helperText('Short teaser shown on the itineraries list.'),
                        RichEditor::make('content')
                            ->required()
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('day-agendas/content')
                            ->toolbarButtons([
                                'attachFiles', 'blockquote', 'bold', 'bulletList', 'h2', 'h3',
                                'italic', 'link', 'orderedList', 'redo', 'strike', 'underline', 'undo',
                            ]),
                    ]),

                Section::make('Settings')
                    ->columnSpan(1)
                    ->schema([
                        FileUpload::make('featured_image')
                            ->image()
                            ->disk('public')
                            ->directory('day-agendas/featured')
                            ->imageEditor()
                            ->maxSize(5120)
                            ->helperText('The big photo at the top of the itinerary.'),
                        FileUpload::make('pdf_url')
                            ->label('Printable PDF (optional)')
                            ->disk('public')
                            ->directory('day-agendas/pdfs')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(20480),
                        Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                            ])
                            ->default('published')
                            ->required(),
                        DateTimePicker::make('published_at')
                            ->label('Publish Date')
                            ->default(now()),
                        TextInput::make('sort')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower shows first.'),
                    ]),
            ]);
    }
}
