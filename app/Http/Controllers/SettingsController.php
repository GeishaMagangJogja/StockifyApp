<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SettingsController extends Controller
{
    public function index()
    {
        return view('pages.admin.settings.index', [
            'app_name' => config('app.name'),
            'app_logo' => $this->getLogoUrl(),
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            // Update app name dalam tanda kutip untuk handle spasi
            $this->updateEnv('APP_NAME', '"' . $request->app_name . '"');

            // Handle logo upload
            if ($request->hasFile('app_logo')) {
                $this->handleLogoUpload($request->file('app_logo'));
            }

            // Hapus cache agar perubahan .env terbaca
            Artisan::call('config:clear');
            Artisan::call('cache:clear');

            return back()->with('success', 'Pengaturan berhasil diperbarui!');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui pengaturan: '.$e->getMessage());
        }
    }

    private function getLogoUrl()
    {
        $logoPath = config('app.logo');
        // Pastikan path ada dan file-nya benar-benar ada di storage
        if ($logoPath && Storage::disk('public')->exists($logoPath)) {
            // Gunakan Storage::url untuk mendapatkan URL yang benar (/storage/logos/...)
            return Storage::disk('public')->url($logoPath);
        }
        return null; // Kembalikan null jika tidak ada logo
    }

    private function handleLogoUpload($file)
    {
        // Hapus logo lama jika ada
        $oldLogoPath = config('app.logo');
        if ($oldLogoPath && Storage::disk('public')->exists($oldLogoPath)) {
            Storage::disk('public')->delete($oldLogoPath);
        }

        // Simpan logo baru di `storage/app/public/logos`
        // `store` akan mengembalikan path relatif seperti `logos/namafile.ext`
        $path = $file->store('logos', 'public');
        
        // Simpan path relatif ini ke .env
        $this->updateEnv('APP_LOGO', $path);
    }

    private function updateEnv($key, $value)
    {
        $envPath = app()->environmentFilePath();
        $envContent = file_get_contents($envPath);
        
        $oldValue = env($key);
        $oldLine = "{$key}={$oldValue}";
        $newLine = "{$key}={$value}";

        if (preg_match("/^{$key}=/m", $envContent)) {
            // Jika key sudah ada, ganti barisnya
            $envContent = preg_replace("/^{$key}=.*/m", $newLine, $envContent);
        } else {
            // Jika key belum ada, tambahkan di akhir
            $envContent .= "\n" . $newLine . "\n";
        }

        file_put_contents($envPath, $envContent);
    }
}