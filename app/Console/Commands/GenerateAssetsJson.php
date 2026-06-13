<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:generate-assets-json')]
#[Description('Command description')]
class GenerateAssetsJson extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $imgRoot = base_path('public/atlas/img');

        $types = [
            'characters',
            'facesets',
            'enemies',
            'tilesets',
        ];

        $extensions = ['png', 'webp', 'jpg', 'jpeg'];

        $manifest = [];

        foreach ($types as $type) {
            $files = collect(scandir("$imgRoot/$type"))
                ->filter(function ($file) use ($imgRoot, $type, $extensions) {
                    $path = "$imgRoot/$type/$file";

                    return is_file($path)
                        && in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), $extensions);
                })
                ->sort()
                ->values()
                ->all();

            $manifest[$type] = $files;
        }

        file_put_contents(
            "$imgRoot/assets.json",
            json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );

        echo "Updated $imgRoot/assets.json\n";
    }
}
