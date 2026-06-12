<?php

namespace App\Providers;

use Illuminate\Support\Facades\Storage;
use Native\Desktop\Contracts\ProvidesPhpIni;
use Native\Desktop\Facades\Window;

class NativeAppServiceProvider implements ProvidesPhpIni
{
    /**
     * Executed once the native application has been booted.
     * Use this method to open windows, register global shortcuts, etc.
     */
    public function boot(): void
    {
        $window = Window::open()
            ->title(config('app.name', 'RPGAtlas'))
            ->maximized();

        if (env('RPGATLAS_NATIVE_START', 'editor') === 'game' && Storage::exists('rpgatlas/nativephp-game.html')) {
            $window->route('atlas.native-game');
        }
    }

    /**
     * Return an array of php.ini directives to be set.
     */
    public function phpIni(): array
    {
        return [
        ];
    }
}
