<?php

namespace App\Filament\Resources\Redirects\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RedirectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Redirect')
                    ->columns(2)
                    ->schema([
                        TextInput::make('from_path')
                            ->label('From (old path)')
                            ->required()
                            ->maxLength(500)
                            ->columnSpanFull()
                            ->placeholder('/old-wordpress-path')
                            ->helperText('The old URL path only — no domain. For a Pattern rule, enter a regular expression (e.g. ^/event/(.+)$).'),
                        TextInput::make('to_url')
                            ->label('To (target)')
                            ->maxLength(500)
                            ->columnSpanFull()
                            ->placeholder('/new-path')
                            ->helperText('Where it should go. Leave blank for a 410 Gone. For patterns you can use $1, $2 for captured groups.')
                            ->required(fn ($get) => (int) $get('status_code') !== 410),
                        Select::make('status_code')
                            ->label('Type')
                            ->options([
                                301 => '301 — Permanent redirect',
                                302 => '302 — Temporary redirect',
                                410 => '410 — Gone (no target)',
                            ])
                            ->default(301)
                            ->required()
                            ->live(),
                        Select::make('match_type')
                            ->label('Match')
                            ->options([
                                'exact' => 'Exact path',
                                'pattern' => 'Pattern (regex)',
                            ])
                            ->default('exact')
                            ->required()
                            ->live(),
                        TextInput::make('priority')
                            ->numeric()
                            ->default(0)
                            ->helperText('Higher patterns are evaluated first. Exact matches always win over patterns.')
                            ->visible(fn ($get) => $get('match_type') === 'pattern'),
                        TextInput::make('notes')
                            ->maxLength(255)
                            ->placeholder('e.g. pages, blog, events, media')
                            ->helperText('Optional group label.'),
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ]),
            ]);
    }
}
