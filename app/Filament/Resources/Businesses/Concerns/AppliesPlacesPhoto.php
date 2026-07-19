<?php

namespace App\Filament\Resources\Businesses\Concerns;

use App\Services\Google\PlacesPhotoService;
use Filament\Notifications\Notification;

/**
 * Shared handling for the "Google photos" picker on the Business form. The
 * picker stashes a chosen photo URL (+ attribution) into transient form fields;
 * here we pull them out of the save payload, download the image after the record
 * is saved, and set it as the listing image.
 */
trait AppliesPlacesPhoto
{
    protected ?string $pendingPlacesPhotoUrl = null;

    protected ?string $pendingPlacesPhotoCredit = null;

    /** Pull the transient picker fields out of the payload so they aren't persisted as columns. */
    protected function capturePlacesPhoto(array $data): array
    {
        $this->pendingPlacesPhotoUrl = filled($data['places_photo_url'] ?? null)
            ? $data['places_photo_url']
            : null;
        $this->pendingPlacesPhotoCredit = $data['places_photo_credit'] ?? null;

        unset($data['places_photo_url'], $data['places_photo_credit']);

        return $data;
    }

    /** After the record is saved, download the chosen Google photo into the listing image. */
    protected function applyPlacesPhoto(): void
    {
        if (blank($this->pendingPlacesPhotoUrl) || ! $this->record) {
            return;
        }

        $path = app(PlacesPhotoService::class)->store($this->record, $this->pendingPlacesPhotoUrl);

        if (! $path) {
            Notification::make()
                ->title('Could not download the selected Google photo. The listing image was left unchanged.')
                ->warning()
                ->send();

            return;
        }

        $this->record->forceFill([
            'listing_image' => $path,
            'listing_image_credit' => $this->pendingPlacesPhotoCredit ?: null,
        ])->save();

        $this->pendingPlacesPhotoUrl = null;
        $this->pendingPlacesPhotoCredit = null;

        Notification::make()
            ->title('Saved the selected Google photo as the listing image.')
            ->success()
            ->send();
    }
}
