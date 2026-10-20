<?php

namespace App\Filament\Resources\SignatureEvents\Pages;

use App\Filament\Resources\SignatureEvents\SignatureEventResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSignatureEvent extends CreateRecord
{
    protected static string $resource = SignatureEventResource::class;
}
