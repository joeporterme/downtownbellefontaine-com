<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Models\Page;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make('Page')
                    ->columnSpan(2)
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set, $operation) {
                                if ($operation === 'create') {
                                    $set('key', Str::slug($state));
                                }
                            }),
                        TextInput::make('key')
                            ->required()
                            ->maxLength(255)
                            ->disabled(fn (?Page $record) => $record !== null)
                            ->dehydrated()
                            ->helperText('Route key (e.g. "stay" → /stay). Do not change for existing pages.'),
                        TextInput::make('nav_label')
                            ->label('Navigation label')
                            ->maxLength(255),
                    ]),

                Section::make('Publish')
                    ->columnSpan(1)
                    ->schema([
                        Select::make('status')
                            ->options([
                                'published' => 'Published',
                                'draft' => 'Draft',
                            ])
                            ->default('published')
                            ->required(),
                        DateTimePicker::make('published_at')
                            ->label('Publish date')
                            ->default(now())
                            ->helperText('Future date = scheduled.'),
                        TextInput::make('sort')
                            ->numeric()
                            ->default(0),
                    ]),

                Section::make('Hero')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('hero_eyebrow')->maxLength(255),
                        TextInput::make('hero_heading')->maxLength(255),
                        Textarea::make('hero_subheading')->rows(2)->maxLength(500)->columnSpanFull(),
                        FileUpload::make('hero_image')
                            ->image()->disk('public')->directory('pages/heroes')
                            ->imageEditor()->maxSize(5120)->columnSpanFull()
                            ->helperText('Large hero background. Recommended 2000px wide.'),
                    ]),

                Section::make('SEO')
                    ->description('Search engine + social sharing')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('seo_title')
                            ->label('SEO title')->maxLength(60)
                            ->helperText('Recommended 50-60 characters. Defaults to the page title.'),
                        Textarea::make('seo_description')
                            ->label('SEO description')->rows(3)->maxLength(160)
                            ->helperText('Recommended 150-160 characters.'),
                        FileUpload::make('og_image')
                            ->label('Social share image (OG)')
                            ->image()->disk('public')->directory('pages/og')->maxSize(5120)
                            ->helperText('Defaults to the hero image, then the site default.'),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }
}
