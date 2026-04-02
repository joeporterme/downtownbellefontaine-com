<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Events\EventResource;
use App\Models\Event;
use Filament\Tables;
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
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('')
                    ->disk('public')
                    ->circular()
                    ->size(40),
                Tables\Columns\TextColumn::make('title')
                    ->weight('bold')
                    ->limit(30),
                Tables\Columns\TextColumn::make('event_date')
                    ->label('Date')
                    ->date('M j'),
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil')
                    ->url(fn (Event $record) => EventResource::getUrl('edit', ['record' => $record])),
            ])
            ->emptyStateHeading('No upcoming events')
            ->emptyStateIcon('heroicon-o-calendar')
            ->paginated(false);
    }
}
