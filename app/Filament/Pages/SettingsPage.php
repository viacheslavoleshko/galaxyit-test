<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Config;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class SettingsPage extends Page
{
    use InteractsWithForms;

    public $appName = '';
    public $adminPhone = '';
    public $appDescription = '';
    public $logo = '';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.settings-page';

    public static function canView(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public function mount(): void
    {
        $this->appName = Setting::where('key', 'app_name')->value('value') ?? '';
        $this->adminPhone = Setting::where('key', 'admin_phone')->value('value') ?? '';
        $this->appDescription = Setting::where('key', 'app_description')->value('value') ?? '';
        $this->logo = Setting::where('key', 'app_logo')->value('value') ?? '';
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('appName')
                ->label('Application Name')
                ->required()
                ->maxLength(255),
            TextInput::make('adminPhone')
                ->label('Admin Phone')
                ->tel()
                ->maxLength(255),
            Textarea::make('appDescription')
                ->label('Application Description')
                ->rows(3)
                ->maxLength(65535),
            // FileUpload::make('logo')
            //     ->label('Логотип')
            //     ->image()
            //     ->directory('logos')
            //     ->imagePreviewHeight('150'),
        ];
    }

    public function save(): void
    {
        Setting::updateOrCreate(['key' => 'app_name'], ['value' => $this->appName]);
        Setting::updateOrCreate(['key' => 'admin_phone'], ['value' => $this->adminPhone]);
        Setting::updateOrCreate(['key' => 'app_description'], ['value' => $this->appDescription]);

        if ($this->logo) {
            Setting::updateOrCreate(['key' => 'app_logo'], ['value' => $this->logo]);
        }

        Notification::make()
            ->title('Settings saved successfully!')
            ->success()
            ->send();
    }
}
