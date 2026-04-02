<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Businesses\BusinessResource;
use App\Models\Business;
use Filament\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

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
                ImageColumn::make('featured_image')
                    ->label('')
                    ->disk('public')
                    ->circular()
                    ->size(40),
                TextColumn::make('name')
                    ->searchable()
                    ->weight('bold'),
                TextColumn::make('user.name')
                    ->label('Owner'),
                TextColumn::make('created_at')
                    ->label('Submitted')
                    ->since(),
            ])
            ->recordActions([
                Action::make('review')
                    ->label('Review')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Business $record) => BusinessResource::getUrl('edit', ['record' => $record])),
            ])
            ->emptyStateHeading('All caught up!')
            ->emptyStateDescription('No businesses waiting for approval.')
            ->emptyStateIcon('heroicon-o-check-circle')
            ->paginated(false);
    }
}
