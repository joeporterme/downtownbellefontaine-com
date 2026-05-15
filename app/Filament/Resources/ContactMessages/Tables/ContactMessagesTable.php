<?php

namespace App\Filament\Resources\ContactMessages\Tables;

use App\Models\ContactMessage;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ContactMessagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Received')
                    ->dateTime('M j, g:i A')
                    ->sortable(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight(fn ($record) => $record->status === 'new' ? 'bold' : 'normal'),
                TextColumn::make('email')
                    ->searchable()
                    ->toggleable()
                    ->copyable(),
                TextColumn::make('subject')
                    ->searchable()
                    ->limit(40)
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('message')
                    ->limit(60)
                    ->wrap()
                    ->toggleable(),
                BadgeColumn::make('status')
                    ->formatStateUsing(fn ($state) => ContactMessage::STATUSES[$state] ?? $state)
                    ->colors([
                        'warning' => 'new',
                        'primary' => 'read',
                        'success' => 'replied',
                        'gray' => 'archived',
                    ]),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(ContactMessage::STATUSES),
            ])
            ->recordActions([
                Action::make('markReplied')
                    ->label('Mark Replied')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status !== 'replied')
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'replied',
                            'replied_at' => now(),
                        ]);
                    }),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
