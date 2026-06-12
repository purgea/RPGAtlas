<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
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
            ->hideMenu()
            ->fullscreenable(true)
            ->title(config('app.name', 'RPGAtlas'))
            ->maximized();

        $externalGame = File::exists($this->nativeGamePath('index.html'));
        $embeddedGame = Storage::exists('rpgatlas/nativephp-game.html');

        if (env('RPGATLAS_NATIVE_START', 'editor') === 'game' && ($externalGame || $embeddedGame)) {
            $window->route('atlas.native-game');
        }
    }

    private function nativeGamePath(string $path): string
    {
        $root = env('NATIVEPHP_EXTRAS_PATH')
            ? rtrim(env('NATIVEPHP_EXTRAS_PATH'), DIRECTORY_SEPARATOR)
            : base_path('extras');

        return $root.DIRECTORY_SEPARATOR.'game'.DIRECTORY_SEPARATOR.ltrim($path, DIRECTORY_SEPARATOR);
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
