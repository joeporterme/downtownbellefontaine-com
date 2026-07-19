<?php

namespace App\Filament\Resources\Pages\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('key')->badge()->color('gray')->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(function ($state, $record) {
                        if ($state === 'published' && $record->published_at && $record->published_at > now()) {
                            return 'Scheduled';
                        }
                        return ucfirst($state);
                    }),
                TextColumn::make('published_at')->label('Publish date')->dateTime()->sortable()->toggleable(),
                TextColumn::make('sort')->sortable()->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'published' => 'Published',
                    'draft' => 'Draft',
                ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort');
    }
}
