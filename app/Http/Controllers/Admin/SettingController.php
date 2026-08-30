<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use App\Http\Controllers\Controller;
use App\Http\Requests\SettingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function edit()
    {
        $setting = Setting::firstOrCreate(['id' => 1], [
            'site_name' => 'CSA',
        ]);

        return view('pages.settings.edit', compact('setting'));
    }

    public function update(SettingRequest $request)
    {
        $setting = Setting::firstOrFail();
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            if ($setting->logo) {
                Storage::disk('public')->delete($setting->logo);
            }
            $data['logo'] = $request->file('logo')->store('system/logos', 'public');
        }

        $setting->update($data);
        Cache::forget('setting.registration_open');

        return redirect()
            ->route('admin.settings.edit')
            ->with('success', 'Pengaturan berhasil disimpan.');
    }
}
