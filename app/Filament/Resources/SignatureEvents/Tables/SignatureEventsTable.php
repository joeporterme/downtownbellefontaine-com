<?php

namespace App\Filament\Resources\SignatureEvents\Tables;

use App\Models\SignatureEvent;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class SignatureEventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('month')
                    ->formatStateUsing(fn (int $state): string => SignatureEvent::MONTHS[$state] ?? '')
                    ->sortable(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('timing_note')
                    ->label('When')
                    ->toggleable(),
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
            ->defaultSort('month', 'asc');
    }
}
