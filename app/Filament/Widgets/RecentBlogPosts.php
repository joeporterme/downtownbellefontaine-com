<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\BlogPosts\BlogPostResource;
use App\Models\BlogPost;
use Filament\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentBlogPosts extends BaseWidget
{
    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 1;

    protected static ?string $heading = 'Recent Blog Posts';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                BlogPost::query()
                    ->latest('published_at')
                    ->limit(5)
            )
            ->columns([
                ImageColumn::make('featured_image')
                    ->label('')
                    ->disk('public')
                    ->circular()
                    ->size(40),
                TextColumn::make('title')
                    ->weight('bold')
                    ->limit(30),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'published' => 'success',
                        'archived' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->recordActions([
                Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil')
                    ->url(fn (BlogPost $record) => BlogPostResource::getUrl('edit', ['record' => $record])),
            ])
            ->emptyStateHeading('No posts yet')
            ->emptyStateIcon('heroicon-o-document-text')
            ->paginated(false);
    }
}
