<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Events\EventResource;
use App\Models\Event;
use Filament\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class UpcomingEvents extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 1;

    protected static ?string $heading = 'Upcoming Events';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Event::query()
                    ->approved()
                    ->upcoming()
                    ->orderBy('event_date')
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
                TextColumn::make('event_date')
                    ->label('Date')
                    ->date('M j'),
            ])
            ->recordActions([
                Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil')
                    ->url(fn (Event $record) => EventResource::getUrl('edit', ['record' => $record])),
            ])
            ->emptyStateHeading('No upcoming events')
            ->emptyStateIcon('heroicon-o-calendar')
            ->paginated(false);
    }
}
