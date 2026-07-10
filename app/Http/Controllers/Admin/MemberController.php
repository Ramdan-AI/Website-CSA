<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Member;
use App\Models\Division;
use App\Models\Position;
use App\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\MemberRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{
    private function getRoleSlugFromPosition(?Position $position): string
    {
        if (!$position) {
            return 'anggota';
        }

        $name = strtolower($position->name);

        return match (true) {
            str_contains($name, 'ketua') && !str_contains($name, 'wakil') => 'ketua',
            str_contains($name, 'wakil ketua') => 'wakil-ketua',
            str_contains($name, 'sekretaris') => 'sekretaris',
            str_contains($name, 'bendahara') => 'bendahara',
            str_contains($name, 'kepala divisi') => 'kepala-divisi',
            default => 'anggota',
        };
    }

    private function getRoleFromPosition(?Position $position): ?Role
    {
        $roleSlug = $this->getRoleSlugFromPosition($position);
        return Role::where('slug', $roleSlug)->first();
    }

    public function index()
    {
        $members = Member::with(['division', 'position', 'user'])
            ->latest()
            ->paginate(12);

        return view('pages.admin.members.index', compact('members'));
    }

    public function create()
    {
        $divisions = Division::active()->orderBy('name')->get();
        $positions = Position::orderBy('name')->get();

        return view('pages.admin.members.form', compact('divisions', 'positions'));
    }

    public function store(MemberRequest $request)
    {
        $data = $request->validated();

        $position = Position::find($data['position_id']);
        $role = $this->getRoleFromPosition($position);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $role?->id,
            'is_active' => true,
        ]);

        $data['user_id'] = $user->id;
        $data['is_active'] = true;
        unset($data['password'], $data['password_confirmation']);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('members', 'public');
        }

        Member::create($data);

        return redirect()
            ->route('admin.members.index')
            ->with('success', 'Anggota dan akun berhasil dibuat.');
    }

    public function edit(Member $member)
    {
        $member->load('user');
        $divisions = Division::active()->orderBy('name')->get();
        $positions = Position::orderBy('name')->get();

        return view('pages.admin.members.form', compact('member', 'divisions', 'positions'));
    }

    public function update(MemberRequest $request, Member $member)
    {
        $data = $request->validated();

        if ($member->user) {
            if ($request->filled('password')) {
                $member->user->update([
                    'password' => Hash::make($data['password']),
                ]);
            }
        } else {
            if ($request->filled('password')) {
                $position = Position::find($data['position_id']);
                $role = $this->getRoleFromPosition($position);

                $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                    'role_id' => $role?->id,
                    'is_active' => true,
                ]);

                $data['user_id'] = $user->id;
            }
        }

        unset($data['password'], $data['password_confirmation']);

        $oldPositionId = $member->position_id;

        if ($request->hasFile('photo')) {
            if ($member->photo) {
                Storage::disk('public')->delete($member->photo);
            }
            $data['photo'] = $request->file('photo')->store('members', 'public');
        }

        $member->update($data);

        if ($member->user && $oldPositionId !== (int) $data['position_id']) {
            $role = $this->getRoleFromPosition($member->position);
            $member->user->update(['role_id' => $role?->id]);
        }

        return redirect()
            ->route('admin.members.index')
            ->with('success', 'Anggota berhasil diperbarui.');
    }

    public function destroy(Member $member)
    {
        if ($member->photo) {
            Storage::disk('public')->delete($member->photo);
        }

        $member->delete();

        return back()->with('success', 'Anggota berhasil dihapus.');
    }
}
