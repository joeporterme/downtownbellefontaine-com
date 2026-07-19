<?php

namespace App\Filament\Resources\GalleryImages\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class GalleryImageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('image')
                    ->required()
                    ->image()
                    ->disk('public')
                    ->directory('gallery')
                    ->imageEditor()
                    ->imageResizeMode('cover')
                    ->imageResizeTargetWidth('1600')
                    ->imageResizeTargetHeight('1067')
                    ->maxSize(8192)
                    ->helperText('Best "showcase the town" photos. Resized to ~1600px on upload.'),
                TextInput::make('caption')->maxLength(255),
                TextInput::make('sort')->numeric()->default(0)->helperText('Lower numbers show first.'),
                Toggle::make('is_active')->label('Active')->default(true),
            ]);
    }
}
