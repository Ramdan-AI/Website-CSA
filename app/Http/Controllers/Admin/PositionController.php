<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\Member;
use App\Models\Position;
use App\Http\Controllers\Controller;
use App\Http\Requests\PositionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PositionController extends Controller
{

    private function getRoleSlugFromPosition(?Position $position): string
    {
        if (!$position) {
            return 'anggota';
        }

        $name = strtolower($position->name);

        return match (true) {
            str_contains($name, 'ketua divisi') => 'ketua-divisi',
            str_contains($name, 'wakil ketua divisi') => 'wakil-ketua-divisi',
            str_contains($name, 'wakil ketua') => 'wakil-ketua',
            str_contains($name, 'ketua') && !str_contains($name, 'wakil') => 'ketua',
            str_contains($name, 'sekretaris') => 'sekretaris',
            str_contains($name, 'bendahara') => 'bendahara',
            default => 'anggota',
        };
    }

    private function syncMemberRole(Member $member): void
    {
        if (!$member->user) {
            return;
        }

        $roleSlug = $this->getRoleSlugFromPosition($member->position);
        $role = Role::where('slug', $roleSlug)->first();

        if ($role) {
            $member->user->update(['role_id' => $role->id]);
        }
    }

    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Position::with(['members' => function ($query) {
            $query->select('id', 'name', 'photo', 'division_id', 'position_id')
                ->with('division:id,name');
        }]);

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                 ->orWhere('description', 'like', "%{$search}%");
        }

        $positions = $query->latest()
            ->paginate(12);

        return view('pages.positions.index', compact('positions', 'search'))
            ->with('prev_cursor', $positions->previousPageUrl());
    }

    public function create()
    {
        return view('pages.positions.form');
    }

    public function store(PositionRequest $request)
    {
        Position::create($request->validated());

        return redirect()
            ->route('admin.positions.index')
            ->with('success', 'Jabatan berhasil ditambahkan.');
    }

    public function edit(Position $position)
    {
        return view('pages.positions.form', compact('position'));
    }

    public function update(PositionRequest $request, Position $position)
    {
        $position->update($request->validated());

        return redirect()
            ->route('admin.positions.index')
            ->with('success', 'Jabatan berhasil diperbarui.');
    }

    public function destroy(Position $position)
    {
        $position->delete();

        return back()->with('success', 'Jabatan berhasil dihapus.');
    }

    public function members(Position $position)
    {
        $members = $position->members()
            ->with('division')
            ->latest()
            ->paginate(10);

        $allMembers = Member::with(['division', 'position'])
            ->orderBy('name')
            ->get();

        return view('pages.positions.members', compact('position', 'members', 'allMembers'));
    }

    public function assignMember(Request $request, Position $position)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
        ]);

        $member = Member::with('user')->findOrFail($request->member_id);

        if ($position->max_members <= $position->members()->count()) {
            return back()->with('error', 'Jabatan ' . $position->name . ' sudah penuh (maksimal ' . $position->max_members . ' orang).');
        }

        DB::transaction(function () use ($request, $member, $position) {
            $currentCount = Member::where('position_id', $position->id)
                ->where('id', '!=', $member->id)
                ->lockForUpdate()
                ->count();

            if ($currentCount >= $position->max_members) {
                throw new \Exception('Jabatan sudah penuh.');
            }

            $oldPositionName = $member->position?->name;
            $member->position_id = $position->id;
            $member->save();
            $member->load('position');
            $this->syncMemberRole($member);
        });

        return back()->with('success', 'Anggota berhasil ditambahkan ke jabatan ' . $position->name);
    }

    public function removeMember(Position $position, $memberId)
    {
        $member = Member::with('user')->findOrFail($memberId);

        $member->position_id = null;
        $member->save();

        $this->syncMemberRole($member);

        return back()->with('success', 'Anggota berhasil dilepaskan dari jabatan ' . $position->name . '. Role dikembalikan ke Anggota.');
    }
}
