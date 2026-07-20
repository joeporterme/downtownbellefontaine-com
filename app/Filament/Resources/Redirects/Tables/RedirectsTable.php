<?php

namespace App\Filament\Resources\Redirects\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class RedirectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('from_path')
                    ->label('From')
                    ->searchable()
                    ->limit(50)
                    ->wrap(),
                TextColumn::make('to_url')
                    ->label('To')
                    ->searchable()
                    ->limit(40)
                    ->placeholder('— (410 Gone)')
                    ->toggleable(),
                TextColumn::make('status_code')
                    ->label('Type')
                    ->badge()
                    ->color(fn (int $state) => match ($state) {
                        301 => 'success',
                        302 => 'warning',
                        410 => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('match_type')
                    ->label('Match')
                    ->badge()
                    ->color(fn (string $state) => $state === 'pattern' ? 'info' : 'gray'),
                TextColumn::make('notes')
                    ->label('Group')
                    ->badge()
                    ->toggleable(),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                TextColumn::make('hits')
                    ->sortable()
                    ->alignEnd(),
                TextColumn::make('last_hit_at')
                    ->label('Last hit')
                    ->dateTime('M j, Y g:ia')
                    ->placeholder('—')
                    ->toggleable()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status_code')
                    ->label('Type')
                    ->options([
                        301 => '301 Permanent',
                        302 => '302 Temporary',
                        410 => '410 Gone',
                    ]),
                SelectFilter::make('match_type')
                    ->label('Match')
                    ->options([
                        'exact' => 'Exact',
                        'pattern' => 'Pattern',
                    ]),
                SelectFilter::make('notes')
                    ->label('Group')
                    ->options(fn () => \App\Models\Redirect::query()
                        ->whereNotNull('notes')
                        ->distinct()
                        ->orderBy('notes')
                        ->pluck('notes', 'notes')
                        ->all()),
                TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('hits', 'desc');
    }
}
