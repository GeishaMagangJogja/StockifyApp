<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator; // Import Validator

class SettingsController extends Controller
{
    /**
     * Menampilkan halaman pengaturan.
     */
    public function index()
    {
        return view('pages.admin.settings.index', [
            'app_name' => config('app.name'),
            'app_logo' => $this->getLogoUrl(),
        ]);
    }

    /**
     * Memperbarui pengaturan aplikasi.
     */
    public function update(Request $request)
    {
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'app_name' => 'required|string|max:255',
            'app_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // max 2MB
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.settings')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // 2. Update Nama Aplikasi di .env
            $this->updateEnv('APP_NAME', $request->app_name);
            Log::info('APP_NAME berhasil diupdate di file .env');

            // 3. Handle Upload Logo
            if ($request->hasFile('app_logo')) {
                $path = $this->handleLogoUpload($request->file('app_logo'));
                Log::info('Logo berhasil diupload dan path disimpan di .env: ' . $path);
            }

            // 4. Bersihkan Cache (Sangat Penting!)
            Artisan::call('config:clear');
            Log::info('Cache konfigurasi berhasil dibersihkan.');

            // 5. Redirect dengan pesan sukses
            return redirect()->route('admin.settings')->with('success', 'Pengaturan berhasil diperbarui!');

        } catch (\Exception $e) {
            Log::error('Gagal memperbarui pengaturan: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('admin.settings')->with('error', 'Terjadi kesalahan saat memperbarui pengaturan: ' . $e->getMessage());
        }
    }

    /**
     * Mendapatkan URL logo saat ini.
     */
    private function getLogoUrl()
    {
        // Gunakan env() untuk mendapatkan nilai mentah dari file .env
        $logoPath = env('APP_LOGO');
        if ($logoPath && Storage::disk('public')->exists($logoPath)) {
            return asset('storage/' . $logoPath);
        }
        return null; // Return null jika tidak ada logo
    }

    /**
     * Mengelola upload logo baru dan menghapus yang lama.
     */
    private function handleLogoUpload($file)
    {
        // Hapus logo lama jika ada path-nya di .env
        $oldLogoPath = env('APP_LOGO');
        if ($oldLogoPath && Storage::disk('public')->exists($oldLogoPath)) {
            Storage::disk('public')->delete($oldLogoPath);
            Log::info('Logo lama dihapus: ' . $oldLogoPath);
        }

        // Simpan logo baru di 'public/logos'
        $path = $file->store('logos', 'public');

        // Update path logo baru di .env
        $this->updateEnv('APP_LOGO', $path);

        return $path;
    }

    /**
     * Fungsi untuk menulis perubahan ke file .env
     */
    private function updateEnv($key, $value)
    {
        $envPath = app()->environmentFilePath();
        $envContent = file_get_contents($envPath);

        // Bungkus value dengan kutip jika mengandung spasi
        if (preg_match('/\s/', $value)) {
            $value = '"' . $value . '"';
        }

        $newEntry = "{$key}={$value}";
        $pattern = "/^{$key}=.*/m";

        if (preg_match($pattern, $envContent)) {
            // Update key yang sudah ada
            $envContent = preg_replace($pattern, $newEntry, $envContent);
        } else {
            // Tambahkan key baru di akhir file
            $envContent .= "\n" . $newEntry . "\n";
        }

        file_put_contents($envPath, $envContent);
    }
}