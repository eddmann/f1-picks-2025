<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;

if (! function_exists('inline_public_asset_uri')) {
    /**
     * Generate a base64 data URI for a public asset path.
     */
    function inline_public_asset_uri(string $path): string
    {
        $fullPath = public_path($path);
        $data = File::get($fullPath);
        $mime = File::mimeType($fullPath);

        return 'data:'.$mime.';base64,'.base64_encode($data);
    }
}
