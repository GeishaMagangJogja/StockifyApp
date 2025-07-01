<?php

use Illuminate\Support\Facades\Storage;

/**
 * Pastikan fungsi ini hanya didefinisikan sekali.
 * Ini adalah praktik terbaik untuk file helper.
 */
if (!function_exists('get_favicon_url')) {
    /**
     * Get the application favicon URL with cache-busting support
     *
     * @param bool $useCacheBusting Whether to append cache-busting query string
     * @return string
     */
    function get_favicon_url(bool $useCacheBusting = true): string
    {
        $logoPath = config('app.logo');
        $defaultFavicon = 'favicon.ico';

        // Check for custom logo
        if ($logoPath && Storage::disk('public')->exists($logoPath)) {
            $url = asset('storage/' . $logoPath);

            // Append cache-busting only in development or when explicitly requested
            if ($useCacheBusting && (app()->environment('local') || config('app.debug'))) {
                return $url . '?v=' . time();
            }

            return $url;
        }

        return asset($defaultFavicon);
    }
}
