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
            'registration_mode' => ['required', 'in:open,schedule,closed'],
            'registration_opens_at' => ['nullable', 'date'],
            'registration_closes_at' => ['nullable', 'date', 'after_or_equal:registration_opens_at'],
            'whatsapp_group_link' => ['nullable', 'url', 'max:500'],
        ]);

        $setting = Setting::firstOrFail();
        $mode = $validated['registration_mode'];

        $setting->update([
            'registration_open' => $mode === 'open',
            'registration_use_schedule' => $mode === 'schedule',
            'registration_opens_at' => $mode === 'schedule' ? ($validated['registration_opens_at'] ?? null) : null,
            'registration_closes_at' => $mode === 'schedule' ? ($validated['registration_closes_at'] ?? null) : null,
            'whatsapp_group_link' => $validated['whatsapp_group_link'] ?? null,
        ]);

        Cache::forget('setting.registration_open');
        Cache::forget('setting.footer');

        return redirect()
            ->route('admin.registration.edit')
            ->with('success', 'Pengaturan pendaftaran disimpan.');
    }
}
