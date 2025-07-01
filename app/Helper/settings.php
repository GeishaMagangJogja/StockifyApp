<?php

use Illuminate\Support\Facades\Storage;

/**
 * Pastikan fungsi ini hanya didefinisikan sekali.
 * Ini adalah praktik terbaik untuk file helper.
 */
if (!function_exists('get_favicon_url')) {

    /**
     * Fungsi untuk mendapatkan URL favicon (logo aplikasi).
     * Fungsi ini akan secara otomatis memeriksa apakah logo kustom sudah diatur.
     * Jika tidak ada, fungsi ini akan mengembalikan path ke favicon default.
     *
     * @return string URL ke file favicon.
     */
    function get_favicon_url()
    {
        // 1. Ambil path logo dari file konfigurasi (yang membaca dari .env)
        $logoPath = config('app.logo');

        // 2. Periksa apakah path tersebut ada dan file-nya benar-benar ada di storage
        if ($logoPath && Storage::disk('public')->exists($logoPath)) {
            // 3. Jika ada, kembalikan URL yang bisa diakses publik.
            // Tambahkan timestamp '?v=' . time() untuk "cache-busting".
            // Ini memaksa browser untuk selalu mengunduh versi terbaru dari favicon
            // setiap kali halaman dimuat, sehingga perubahan langsung terlihat.
            return asset('storage/' . $logoPath) . '?v=' . time();
        }

        // 4. Jika tidak ada logo kustom, kembalikan path ke favicon.ico default
        //    yang biasanya ada di folder /public.
        return asset('favicon.ico');
    }
}