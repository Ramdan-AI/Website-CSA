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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'whatsapp' => ['required', 'string', 'regex:/^[0-9+\-\s]+$/', 'max:20'],
            'nim' => ['nullable', 'string', 'max:20', 'unique:members,nim,' . $member->id],
            'bio' => ['nullable', 'string', 'max:500'],
            'linkedin' => ['nullable', 'url'],
            'instagram' => ['nullable', 'url'],
            'github' => ['nullable', 'url'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        // Update User
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'whatsapp' => $validated['whatsapp'] ?? $user->whatsapp,
        ]);

        // Update Member
        $member->update([
            'nim' => $validated['nim'] ?? $member->nim,
            'bio' => $validated['bio'] ?? $member->bio,
            'linkedin' => $validated['linkedin'] ?? $member->linkedin,
            'instagram' => $validated['instagram'] ?? $member->instagram,
            'github' => $validated['github'] ?? $member->github,
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            if ($member->photo && Storage::disk('public')->exists($member->photo)) {
                Storage::disk('public')->delete($member->photo);
            }

            $photo = $request->file('photo');
            $photoName = Str::uuid() . '.' . $photo->getClientOriginalExtension();
            $photo->storeAs('profile', $photoName, 'public');

            $member->update(['photo' => 'profile/' . $photoName]);
        }

        return redirect()
            ->route('admin.profile.edit')
            ->with('success', 'Profile berhasil diperbarui');
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
