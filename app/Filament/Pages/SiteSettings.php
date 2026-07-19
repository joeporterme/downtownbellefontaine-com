<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use UnitEnum;

class SiteSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Site Settings';
    protected static string|UnitEnum|null $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 1;
    protected string $view = 'filament.pages.site-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(SiteSetting::current()->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Site Identity')
                    ->columns(2)
                    ->schema([
                        TextInput::make('site_name')->required()->maxLength(255),
                        TextInput::make('tagline')->maxLength(255),
                        Textarea::make('default_meta_description')
                            ->label('Default meta description')
                            ->rows(2)->maxLength(255)->columnSpanFull()
                            ->helperText('Fallback description for pages without their own.'),
                        FileUpload::make('default_og_image')
                            ->label('Default social share image')
                            ->image()->disk('public')->directory('site')->maxSize(5120)
                            ->columnSpanFull()
                            ->helperText('Used when a page has no OG image. Recommended 1200x630px.'),
                    ]),
                Section::make('Contact')
                    ->columns(2)
                    ->schema([
                        TextInput::make('contact_email')->email()->maxLength(255),
                        TextInput::make('contact_phone')->tel()->maxLength(50),
                        TextInput::make('address')->maxLength(255),
                        TextInput::make('city')->maxLength(120),
                        TextInput::make('state')->maxLength(20),
                        TextInput::make('zip')->maxLength(20),
                    ]),
                Section::make('Social Media')
                    ->columns(2)
                    ->schema([
                        TextInput::make('facebook_url')->label('Facebook URL')->url()->maxLength(255),
                        TextInput::make('instagram_url')->label('Instagram URL')->url()->maxLength(255),
                        TextInput::make('x_url')->label('X (Twitter) URL')->url()->maxLength(255),
                        TextInput::make('tiktok_url')->label('TikTok URL')->url()->maxLength(255),
                        TextInput::make('youtube_url')->label('YouTube URL')->url()->maxLength(255),
                    ]),
                Section::make('Analytics')
                    ->schema([
                        TextInput::make('google_analytics_id')
                            ->label('Google Analytics ID')
                            ->placeholder('G-XXXXXXXXXX')->maxLength(50),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        SiteSetting::current()->update($this->form->getState());

        Notification::make()->title('Settings saved')->success()->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Save changes')
                ->icon('heroicon-o-check')
                ->action('save'),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }
}
