<?php

use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Http\Request;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\Middleware\ShareErrorsFromSession;

Route::get('/', function () {
    return inertia('AtlasEditor');
});

Route::get('/play', function () {
    return inertia('AtlasPlayer');
})->name('atlas.play');

$storeNativePhpGame = function (Request $request) {
    $data = $request->validate([
        'html' => ['required', 'string'],
        'title' => ['nullable', 'string', 'max:120'],
        'baseName' => ['nullable', 'string', 'max:120'],
    ]);

    Storage::put('rpgatlas/nativephp-game.html', $data['html']);
    Storage::put('rpgatlas/nativephp-game.json', json_encode([
        'title' => $data['title'] ?? 'RPGAtlas Game',
        'baseName' => $data['baseName'] ?? 'RPGAtlas_Game',
        'exportedAt' => now()->toIso8601String(),
    ], JSON_PRETTY_PRINT));

    return response()->json([
        'message' => 'NativePHP desktop payload prepared.',
        'route' => route('atlas.native-game'),
        'buildCommand' => 'php artisan native:build win x64',
        'devCommand' => 'composer run native:dev',
    ]);
};

Route::post('/nativephp-game-export', $storeNativePhpGame)
    ->withoutMiddleware([PreventRequestForgery::class, StartSession::class, ShareErrorsFromSession::class])
    ->name('atlas.nativephp-game.store');

Route::post('/atlas/nativephp-game', $storeNativePhpGame)
    ->withoutMiddleware([PreventRequestForgery::class, StartSession::class, ShareErrorsFromSession::class]);

Route::get('/native-game', function () {
    if (! Storage::exists('rpgatlas/nativephp-game.html')) {
        return redirect()->route('atlas.play');
    }

    return response(Storage::get('rpgatlas/nativephp-game.html'))
        ->header('Content-Type', 'text/html; charset=utf-8');
})->name('atlas.native-game');
