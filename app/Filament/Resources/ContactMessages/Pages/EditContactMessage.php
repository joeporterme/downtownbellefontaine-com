<?php

namespace App\Filament\Resources\ContactMessages\Pages;

use App\Filament\Resources\ContactMessages\ContactMessageResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditContactMessage extends EditRecord
{
    protected static string $resource = ContactMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('markReplied')
                ->label('Mark Replied')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn () => $this->record->status !== 'replied')
                ->action(function () {
                    $this->record->update([
                        'status' => 'replied',
                        'replied_at' => now(),
                    ]);
                    $this->refreshFormData(['status', 'replied_at']);
                }),
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Auto-mark "new" messages as "read" when opened
        if (($data['status'] ?? null) === 'new') {
            $this->record->update(['status' => 'read']);
            $data['status'] = 'read';
        }
        return $data;
    }
}
