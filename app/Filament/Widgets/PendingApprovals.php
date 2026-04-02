<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Businesses\BusinessResource;
use App\Filament\Resources\Events\EventResource;
use App\Models\Business;
use App\Models\Event;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class PendingApprovals extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Pending Approvals';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Business::query()
                    ->where('status', 'pending')
                    ->latest()
            )
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('')
                    ->disk('public')
                    ->circular()
                    ->size(40),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Owner'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Submitted')
                    ->since(),
            ])
            ->actions([
                Tables\Actions\Action::make('review')
                    ->label('Review')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Business $record) => BusinessResource::getUrl('edit', ['record' => $record])),
            ])
            ->emptyStateHeading('All caught up!')
            ->emptyStateDescription('No businesses or events waiting for approval.')
            ->emptyStateIcon('heroicon-o-check-circle')
            ->paginated(false);
    }
}
