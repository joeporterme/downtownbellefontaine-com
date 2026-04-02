<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\BlogPosts\BlogPostResource;
use App\Models\BlogPost;
use Filament\Tables;
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
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('')
                    ->disk('public')
                    ->circular()
                    ->size(40),
                Tables\Columns\TextColumn::make('title')
                    ->weight('bold')
                    ->limit(30),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'gray' => 'draft',
                        'success' => 'published',
                        'danger' => 'archived',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil')
                    ->url(fn (BlogPost $record) => BlogPostResource::getUrl('edit', ['record' => $record])),
            ])
            ->emptyStateHeading('No posts yet')
            ->emptyStateIcon('heroicon-o-document-text')
            ->paginated(false);
    }
}
