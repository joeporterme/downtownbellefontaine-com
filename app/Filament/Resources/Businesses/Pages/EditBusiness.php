<?php

namespace App\Filament\Resources\Businesses\Pages;

use App\Filament\Resources\Businesses\BusinessResource;
use App\Services\Google\StreetViewService;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditBusiness extends EditRecord
{
    protected static string $resource = BusinessResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('refreshStreetView')
                ->label('Refresh Street View')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->action(function () {
                    $service = app(StreetViewService::class);
                    $count = 0;

                    foreach ($this->record->locations as $location) {
                        $path = $service->snapshot($location);

                        if ($path) {
                            $location->streetview_image = $path;
                            $location->saveQuietly();
                            $count++;
                        }
                    }

                    Notification::make()
                        ->title($count
                            ? "Refreshed {$count} Street View image(s)."
                            : 'No Street View images could be generated. Frame a view and confirm imagery exists.')
                        ->{$count ? 'success' : 'warning'}()
                        ->send();
                }),

            DeleteAction::make(),
        ];
    }
}
