<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MemberRequest;
use App\Models\Division;
use App\Models\Member;
use App\Models\Position;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{
    /**
     * Level hanya berlaku untuk divisi bertingkat. Divisi lain dikosongkan.
     */
    private function resolveLevel(array $data): ?int
    {
        $hasLevels = ! empty($data['division_id'])
            && Division::whereKey($data['division_id'])->value('has_levels');

        return $hasLevels && ! empty($data['level']) ? (int) $data['level'] : null;
    }

    private function getRoleSlugFromPosition(?Position $position): string
    {
        if (! $position) {
            return 'anggota';
        }

        $name = strtolower($position->name);

        return match (true) {
            str_contains($name, 'wakil ketua divisi') => 'wakil-ketua-divisi',
            str_contains($name, 'ketua divisi') => 'ketua-divisi',
            str_contains($name, 'wakil ketua') => 'wakil-ketua',
            str_contains($name, 'ketua') => 'ketua',
            str_contains($name, 'sekretaris') => 'sekretaris',
            str_contains($name, 'bendahara') => 'bendahara',
            default => 'anggota',
        };
    }

    private function getRoleFromPosition(?Position $position): ?Role
    {
        $roleSlug = $this->getRoleSlugFromPosition($position);

        return Role::where('slug', $roleSlug)->first();
    }

    public function index(Request $request)
    {
        $query = Member::with(['division', 'position', 'user']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by division
        if ($request->filled('division_id')) {
            $query->where('division_id', $request->division_id);
        }

        // Filter by position
        if ($request->filled('position_id')) {
            $query->where('position_id', $request->position_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }

        // Sorting
        $sort = $request->input('sort', 'newest');
        match ($sort) {
            'name_asc' => $query->orderBy('name'),
            'name_desc' => $query->orderByDesc('name'),
            'nim_asc' => $query->orderBy('nim'),
            'nim_desc' => $query->orderByDesc('nim'),
            'division_asc' => $query->orderBy(
                Division::select('name')->whereColumn('divisions.id', 'members.division_id')
            ),
            'division_desc' => $query->orderByDesc(
                Division::select('name')->whereColumn('divisions.id', 'members.division_id')
            ),
            'position_asc' => $query->orderBy(
                Position::select('name')->whereColumn('positions.id', 'members.position_id')
            ),
            'position_desc' => $query->orderByDesc(
                Position::select('name')->whereColumn('positions.id', 'members.position_id')
            ),
            'oldest' => $query->oldest(),
            default => $query->latest(), // newest
        };

        $members = $query->paginate(12)->withQueryString();

        $divisions = Division::active()->orderBy('name')->get();
        $positions = Position::orderBy('name')->get();

        return view('pages.members.index', compact('members', 'divisions', 'positions', 'request'));
    }

    public function create()
    {
        $divisions = Division::active()->orderBy('name')->get();
        $positions = Position::orderBy('name')->get();

        return view('pages.members.form', compact('divisions', 'positions'));
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

        $data['level'] = $this->resolveLevel($data);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('members', 'public');
        }

        Member::create($data);

        return redirect()
            ->route('admin.members.index')
            ->with('success', 'Anggota dan akun berhasil dibuat.');
    }

    public function show(Member $member)
    {
        $member->load(['user', 'division', 'position']);

        $attendances = $member->attendances()
            ->with('event:id,title,event_date')
            ->latest()
            ->take(10)
            ->get();

        $attendanceStats = [
            'present' => $member->attendances()->where('status', 'present')->count(),
            'permit' => $member->attendances()->where('status', 'permit')->count(),
            'sick' => $member->attendances()->where('status', 'sick')->count(),
            'absent' => $member->attendances()->where('status', 'absent')->count(),
        ];

        $projects = $member->projects()->latest()->take(6)->get();

        return view('pages.members.show', compact('member', 'attendances', 'attendanceStats', 'projects'));
    }

    public function edit(Member $member)
    {
        $member->load('user');
        $divisions = Division::active()->orderBy('name')->get();
        $positions = Position::orderBy('name')->get();

        return view('pages.members.form', compact('member', 'divisions', 'positions'));
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

        $data['level'] = $this->resolveLevel($data);

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

        // Hapus akun login terkait. Selain lewat relasi user_id, cari juga
        // berdasarkan email agar akun yatim (user_id kosong) ikut terhapus
        // dan tidak memblokir email untuk anggota berikutnya.
        $linkedUserIds = collect([$member->user_id])
            ->merge(User::where('email', $member->email)->pluck('id'))
            ->filter()
            ->unique();

        User::whereIn('id', $linkedUserIds)->delete();

        $member->delete();

        return back()->with('success', 'Anggota dan akun terkait berhasil dihapus.');
    }
}
