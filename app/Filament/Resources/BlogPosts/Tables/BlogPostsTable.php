<?php

namespace App\Filament\Resources\BlogPosts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BlogPostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('featured_image')
                    ->label('Image')
                    ->disk('public')
                    ->square()
                    ->size(60),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                TextColumn::make('author.name')
                    ->label('Author')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('category.name')
                    ->label('Category')
                    ->badge()
                    ->color(fn ($record) => $record->category?->color ? null : 'gray')
                    ->toggleable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'published' => 'success',
                        'archived' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(function ($state, $record) {
                        if ($state === 'published' && $record->published_at > now()) {
                            return 'Scheduled';
                        }
                        return ucfirst($state);
                    }),
                TextColumn::make('published_at')
                    ->label('Publish Date')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('businesses_count')
                    ->counts('businesses')
                    ->label('Tagged')
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'archived' => 'Archived',
                    ]),
                SelectFilter::make('blog_category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->preload(),
                SelectFilter::make('author_id')
                    ->label('Author')
                    ->relationship('author', 'name')
                    ->preload(),
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
