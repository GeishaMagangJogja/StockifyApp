<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    public function index()
    {
        return view('pages.admin.settings.index', [
            'app_name' => config('app.name'),
            'app_description' => config('app.description'),
            'app_logo' => $this->getLogoUrl(),
        ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'app_name' => 'required|string|max:255',
            'app_description' => 'required|string|max:255',
            'app_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.settings')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $this->updateEnv('APP_NAME', $request->app_name);
            $this->updateEnv('APP_DESCRIPTION', $request->app_description);
            Log::info('App settings updated', ['name' => $request->app_name, 'description' => $request->app_description]);

            if ($request->hasFile('app_logo')) {
                $path = $this->handleLogoUpload($request->file('app_logo'));
                Log::info('Logo updated', ['path' => $path]);
            }

            Artisan::call('config:clear');
            return redirect()->route('admin.settings')->with('success', 'Pengaturan berhasil diperbarui!');

        } catch (\Exception $e) {
            Log::error('Failed to update settings: ' . $e->getMessage());
            return redirect()->route('admin.settings')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function getLogoUrl()
    {
        $logoPath = env('APP_LOGO');
        if ($logoPath && Storage::disk('public')->exists($logoPath)) {
            return asset('storage/' . $logoPath);
        }
        return null;
    }

    private function handleLogoUpload($file)
    {
        $oldLogoPath = env('APP_LOGO');
        if ($oldLogoPath && Storage::disk('public')->exists($oldLogoPath)) {
            Storage::disk('public')->delete($oldLogoPath);
        }

        $path = $file->store('logos', 'public');
        $this->updateEnv('APP_LOGO', $path);
        return $path;
    }

    private function updateEnv($key, $value)
    {
        $envPath = app()->environmentFilePath();
        $envContent = file_get_contents($envPath);
        $value = preg_match('/\s/', $value) ? '"'.$value.'"' : $value;
        $pattern = "/^{$key}=.*/m";

        if (preg_match($pattern, $envContent)) {
            $envContent = preg_replace($pattern, "{$key}={$value}", $envContent);
        } else {
            $envContent .= PHP_EOL."{$key}={$value}".PHP_EOL;
        }

        file_put_contents($envPath, $envContent);
    }
}
