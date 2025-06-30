<?php

namespace App\Http\Controllers;

use Dotenv\Dotenv;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        return view('pages.admin.settings.index', [
            'app_name' => config('app.name'),
            'app_logo' => $this->getLogoUrl(),
        ]);
    }

private function handleLogoUpload($file)
{
    // Hapus logo lama jika ada
    $oldLogo = config('app.logo');
    if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
        Storage::disk('public')->delete($oldLogo);
    }

    // Simpan logo baru
    $path = $file->store('logos', 'public');

    // Update .env
    $this->updateEnv('APP_LOGO', $path);

    return $path;
}

public function update(Request $request)
{
    Log::info('Mulai update settings', $request->all());

    try {
        $this->updateEnv('APP_NAME', $request->app_name);
        Log::info('APP_NAME berhasil diupdate');

        if ($request->hasFile('app_logo')) {
            $path = $this->handleLogoUpload($request->file('app_logo'));
            Log::info('Logo berhasil diupload ke: '.$path);
        }

        Artisan::call('config:clear');
        Log::info('Config cleared');

        return back()->with('success', 'Pengaturan berhasil diperbarui!');
    } catch (\Exception $e) {
        Log::error('Error update settings: '.$e->getMessage());
        return back()->with('error', 'Gagal memperbarui: '.$e->getMessage());
    }
}

private function getLogoUrl()
{
    $logoPath = config('app.logo');
    if ($logoPath && Storage::disk('public')->exists($logoPath)) {
        return asset('storage/' . $logoPath);
    }
    return null;
}



private function updateEnv($key, $value)
{
    $envPath = app()->environmentFilePath();

    // Pastikan file .env ada dan bisa diakses
    if (!file_exists($envPath)) {
        throw new \Exception("File .env tidak ditemukan");
    }

    // Baca konten file
    $envContent = file_get_contents($envPath);
    if ($envContent === false) {
        throw new \Exception("Gagal membaca file .env");
    }

    // Escape value jika mengandung spasi
    if (preg_match('/\s/', $value) && !preg_match('/^[\'"].*[\'"]$/', $value)) {
        $value = '"'.$value.'"';
    }

    // Update existing key atau tambahkan baru
    $pattern = "/^{$key}=[^\r\n]*/m";
    if (preg_match($pattern, $envContent)) {
        $envContent = preg_replace($pattern, "{$key}={$value}", $envContent);
    } else {
        $envContent .= "\n{$key}={$value}\n";
    }

    // Tulis kembali ke file
    $written = file_put_contents($envPath, $envContent);
    if ($written === false) {
        throw new \Exception("Gagal menulis ke file .env");
    }

    // Verifikasi perubahan
    $newContent = file_get_contents($envPath);
    if (!preg_match("/^{$key}={$value}/m", $newContent)) {
        throw new \Exception("Gagal memverifikasi perubahan .env");
    }
}
}
