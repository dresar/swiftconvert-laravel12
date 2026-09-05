<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function edit()
    {
        $settings = Setting::pluck('value', 'key')->all();
        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'app_name' => 'required|string|max:255',
            'app_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.settings.edit')
                        ->withErrors($validator)
                        ->withInput();
        }

        try {
            Setting::updateOrCreate(
                ['key' => 'app_name'],
                ['value' => $request->input('app_name')]
            );

            if ($request->hasFile('app_logo')) {
                $logo = $request->file('app_logo');
                $newLogoPath = $logo->store('logos', 'public');

                $oldLogoSetting = Setting::firstOrNew(['key' => 'app_logo']);
                $oldLogoPath = $oldLogoSetting->value;

                if ($oldLogoPath && $oldLogoPath !== $newLogoPath && Storage::disk('public')->exists($oldLogoPath)) {
                    Storage::disk('public')->delete($oldLogoPath);
                }

                $oldLogoSetting->value = $newLogoPath;
                $oldLogoSetting->save();
            }

            Cache::forget('app_settings');

            return redirect()->route('admin.settings.edit')->with('success', 'Pengaturan berhasil diperbarui.');

        } catch (\Exception $e) {
             return redirect()->route('admin.settings.edit')->with('error', 'Gagal memperbarui pengaturan: ' . $e->getMessage());
        }
    }
}