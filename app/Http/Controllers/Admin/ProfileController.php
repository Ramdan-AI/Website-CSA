<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $member = $user->member;

        return view('pages.profile.edit', compact('user', 'member'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $member = $user->member;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'whatsapp' => ['required', 'string', 'regex:/^[0-9+\-\s]+$/', 'max:20'],
            'nim' => $member
                ? ['nullable', 'numeric', 'digits_between:6,20', 'unique:members,nim,'.$member->id]
                : ['nullable'],
            'bio' => ['nullable', 'string', 'max:500'],
            'linkedin' => ['nullable', 'url'],
            'instagram' => ['nullable', 'url'],
            'github' => ['nullable', 'url'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'nim.numeric' => 'NIM hanya boleh berisi angka.',
            'nim.digits_between' => 'NIM harus berupa 6-20 digit angka.',
            'nim.unique' => 'NIM sudah digunakan anggota lain.',
        ]);

        // Update User
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'whatsapp' => $validated['whatsapp'] ?? $user->whatsapp,
        ]);

        // Update Member (bila pengguna punya profil anggota).
        if ($member) {
            $member->update([
                'nim' => $validated['nim'] ?? $member->nim,
                'bio' => $validated['bio'] ?? $member->bio,
                'linkedin' => $validated['linkedin'] ?? $member->linkedin,
                'instagram' => $validated['instagram'] ?? $member->instagram,
                'github' => $validated['github'] ?? $member->github,
            ]);
        }

        // Handle photo upload: simpan ke member.photo DAN user.avatar agar
        // foto yang sama dipakai di sidebar, topbar, dan kartu publik.
        if ($request->hasFile('photo')) {
            $stored = $this->storeCroppedPhoto($request->file('photo'));

            $previous = array_filter([$member?->photo, $user->avatar]);
            foreach ($previous as $old) {
                if (Storage::disk('public')->exists($old)) {
                    Storage::disk('public')->delete($old);
                }
            }

            $member?->update(['photo' => $stored]);
            $user->update(['avatar' => $stored]);
        }

        return redirect()
            ->route('admin.profile.edit')
            ->with('success', 'Profile berhasil diperbarui');
    }

    /**
     * Simpan file foto yang sudah dipotong lewat cropper di sisi klien.
     * Bila cropper tidak dipakai, file asli disimpan apa adanya.
     */
    private function storeCroppedPhoto($file): string
    {
        $name = Str::uuid().'.'.strtolower($file->getClientOriginalExtension());
        $file->storeAs('profile', $name, 'public');

        return 'profile/'.$name;
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = auth()->user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Password berhasil diubah');
    }
}
