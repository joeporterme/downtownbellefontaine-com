<?php

namespace App\Filament\Resources\Events\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('featured_image')
                    ->label('Image')
                    ->disk('public')
                    ->circular(false)
                    ->width(80)
                    ->height(45),

                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(40),

                TextColumn::make('event_date')
                    ->label('Date')
                    ->date('M d, Y')
                    ->sortable(),

                TextColumn::make('start_time')
                    ->label('Time')
                    ->time('g:i A')
                    ->placeholder('—'),

                TextColumn::make('business.name')
                    ->label('Business')
                    ->sortable()
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('location_name')
                    ->label('Location')
                    ->limit(25)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    }),

                TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('event_date', 'asc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
