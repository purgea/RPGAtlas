<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class NativePhpGameExportTest extends TestCase
{
    public function test_nativephp_game_payload_can_be_prepared_and_served(): void
    {
        Storage::fake('local');

        $html = '<!DOCTYPE html><html><body>Atlas Quest</body></html>';

        $this->postJson('/nativephp-game-export', [
            'html' => $html,
            'title' => 'Atlas Quest',
            'baseName' => 'Atlas_Quest',
        ])->assertOk()
            ->assertJsonPath('buildCommand', 'php artisan native:build win x64');

        Storage::assertExists('rpgatlas/nativephp-game.html');

        $this->get('/native-game')
            ->assertOk()
            ->assertSee('Atlas Quest', false);
    }
}
