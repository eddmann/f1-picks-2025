<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Servable Assets
    |--------------------------------------------------------------------------
    |
    | A whitelist of public assets that should be served directly by Lambda
    | through the Laravel app, instead of only via S3/CloudFront. Paths are
    | relative to the `public/` directory. This is used by Bref's
    | ServeStaticAssets middleware.
    |
    */

    'assets' => array_values(array_unique(array_merge([
        // Top-level branding assets
        'logo.png',

        // Vite build outputs (CSS/JS chunks are under public/build/assets)
        'build/manifest.json',
    ], (function () {
        // Include all current built asset files under public/build/assets
        $assetDir = public_path('build/assets');
        if (! is_dir($assetDir)) {
            return [];
        }

        $paths = [];
        foreach (scandir($assetDir) ?: [] as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            $paths[] = 'build/assets/'.$file;
        }

        return $paths;
    })()))),

    /*
    |--------------------------------------------------------------------------
    | Shared Log Context
    |--------------------------------------------------------------------------
    |
    | Add the Lambda X-Request-ID to the shared log context (disable by false).
    |
    */

    'request_context' => false,

    /*
    |--------------------------------------------------------------------------
    | Jobs Logging
    |--------------------------------------------------------------------------
    |
    | Enable detailed logging of every job execution.
    |
    */

    'log_jobs' => true,
];
