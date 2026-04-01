<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->icon(fn ($record) => str_ends_with($record->email, '@downtownbellefontaine.com')
                        ? 'heroicon-m-clock'
                        : null)
                    ->tooltip(fn ($record) => str_ends_with($record->email, '@downtownbellefontaine.com')
                        ? 'Temporary email - needs update'
                        : null),
                TextColumn::make('role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',
                        'business_owner' => 'primary',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'admin' => 'Admin',
                        'business_owner' => 'Business Owner',
                        default => ucfirst($state),
                    }),
                TextColumn::make('businesses_count')
                    ->counts('businesses')
                    ->label('Businesses')
                    ->sortable(),
                IconColumn::make('email_verified_at')
                    ->label('Verified')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->getStateUsing(fn ($record) => $record->email_verified_at !== null),
                TextColumn::make('created_at')
                    ->label('Registered')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'business_owner' => 'Business Owner',
                    ]),
                TernaryFilter::make('email_verified_at')
                    ->label('Email Verified')
                    ->nullable()
                    ->trueLabel('Verified')
                    ->falseLabel('Not Verified'),
                TernaryFilter::make('temp_email')
                    ->label('Temp Email')
                    ->queries(
                        true: fn ($query) => $query->where('email', 'like', '%@downtownbellefontaine.com'),
                        false: fn ($query) => $query->where('email', 'not like', '%@downtownbellefontaine.com'),
                    )
                    ->trueLabel('Has Temp Email')
                    ->falseLabel('Has Real Email'),
            ])
            ->recordActions([
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
