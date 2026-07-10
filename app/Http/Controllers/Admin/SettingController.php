<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use App\Http\Controllers\Controller;
use App\Http\Requests\SettingRequest;

class SettingController extends Controller
{
    public function edit()
    {
        $setting = Setting::first();

        if (!$setting) {

            $setting = Setting::create([
                'site_name' => 'CSA'
            ]);
        }

        return view('pages.admin.settings.edit',
            compact('setting')
        );
    }

    public function update(
        SettingRequest $request
    ) {
        $setting = Setting::first();

        $setting->update(
            $request->validated()
        );

        return redirect()
            ->route('admin.settings.edit')
            ->with(
                'success',
                'Setting berhasil diupdate'
            );
    }
}
