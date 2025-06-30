<?php

namespace App\Http\Controllers;

use Dotenv\Dotenv;
use Illuminate\Http\Request;
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
    $request->validate([
        'app_name' => 'required|string|max:255',
        'app_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    try {
        // Update APP_NAME
        $this->updateEnv('APP_NAME', $request->app_name);

        // Handle logo upload
        if ($request->hasFile('app_logo')) {
            $this->handleLogoUpload($request->file('app_logo'));
        }

        // Clear semua cache
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');

        // Reload environment
        $dotenv = Dotenv::createImmutable(base_path());
        $dotenv->load();

        return back()->with('success', 'Pengaturan berhasil diperbarui!');
    } catch (\Exception $e) {
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

    // Baca konten file
    $envContent = file_get_contents($envPath);

    // Escape value jika mengandung spasi
    if (preg_match('/\s/', $value) && !preg_match('/^[\'"].*[\'"]$/', $value)) {
        $value = '"'.$value.'"';
    }

    // Update atau tambahkan key
    if (strpos($envContent, "$key=") !== false) {
        $envContent = preg_replace(
            "/^{$key}=.*/m",
            "{$key}={$value}",
            $envContent
        );
    } else {
        $envContent .= "\n{$key}={$value}\n";
    }

    // Tulis kembali ke file
    file_put_contents($envPath, $envContent);

    // Pastikan permission file benar
    chmod($envPath, 0644);
}

    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');

        return back()->with('success', 'Cache berhasil dibersihkan!');
    }
}
