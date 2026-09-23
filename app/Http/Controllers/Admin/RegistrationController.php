<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class RegistrationController extends Controller
{
    /**
     * Halaman khusus mengatur pembukaan pendaftaran dan grup komunitas.
     *
     * Dipisah dari Pengaturan umum agar admin langsung menemukan
     * kontrol operasional pendaftaran tanpa menggulir halaman.
     */
    public function edit()
    {
        $setting = Setting::firstOrCreate(['id' => 1], ['site_name' => 'CSA']);

        return view('pages.registration.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'registration_open' => ['nullable', 'boolean'],
            'whatsapp_group_link' => ['nullable', 'url', 'max:500'],
        ]);

        $setting = Setting::firstOrFail();

        $setting->update([
            'registration_open' => $request->boolean('registration_open'),
            'whatsapp_group_link' => $validated['whatsapp_group_link'] ?? null,
        ]);

        // Halaman publik membaca nilai ini dari cache.
        Cache::forget('setting.registration_open');
        Cache::forget('setting.footer');

        return redirect()
            ->route('admin.registration.edit')
            ->with('success', 'Pengaturan pendaftaran disimpan.');
    }
}
