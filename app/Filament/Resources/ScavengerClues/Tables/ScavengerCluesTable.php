<?php

namespace App\Filament\Resources\ScavengerClues\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class ScavengerCluesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sort')
                    ->label('#')
                    ->sortable(),
                TextColumn::make('clue')
                    ->searchable()
                    ->limit(70)
                    ->wrap(),
                TextColumn::make('answer')
                    ->placeholder('(not set)')
                    ->limit(40),
                ToggleColumn::make('is_active')
                    ->label('Shown'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->reorderable('sort')
            ->defaultSort('sort', 'asc');
    }
}
