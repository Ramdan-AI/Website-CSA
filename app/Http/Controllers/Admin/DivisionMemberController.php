<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DivisionMemberController extends Controller
{
    /**
     * Kelola anggota dalam divisi pengguna sendiri.
     *
     * Berbeda dari MemberController yang global, controller ini selalu
     * membatasi akses ke division_id milik pemanggil.
     */
    public function index(Request $request)
    {
        $division = $this->currentDivision();

        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
        ]);

        $members = Member::query()
            ->with('position:id,name')
            ->where('division_id', $division->id)
            ->when($validated['search'] ?? null, fn ($query, $search) =>
                $query->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('nim', 'like', "%{$search}%");
                })
            )
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        // Kandidat: anggota yang belum punya divisi.
        $candidates = Member::query()
            ->select(['id', 'name', 'nim'])
            ->whereNull('division_id')
            ->orderBy('name')
            ->get();

        return view('pages.division-manage.index', [
            'division' => $division,
            'members' => $members,
            'candidates' => $candidates,
        ]);
    }

    public function store(Request $request)
    {
        $division = $this->currentDivision();

        $validated = $request->validate([
            'member_ids' => ['required', 'array'],
            'member_ids.*' => ['integer', 'exists:members,id'],
        ]);

        // Hanya anggota tanpa divisi yang boleh dipindahkan ke sini.
        $added = Member::query()
            ->whereIn('id', $validated['member_ids'])
            ->whereNull('division_id')
            ->update(['division_id' => $division->id]);

        return back()->with('success', $added . ' anggota ditambahkan ke divisi.');
    }

    public function destroy(Member $member)
    {
        $division = $this->currentDivision();

        // Tolak bila anggota bukan bagian dari divisi pengguna.
        abort_unless($member->division_id === $division->id, 403);

        $member->update(['division_id' => null]);

        return back()->with('success', $member->name . ' dikeluarkan dari divisi.');
    }

    private function currentDivision()
    {
        $member = Auth::user()->member;

        abort_if(!$member || !$member->division, 403, 'Anda belum terhubung ke divisi mana pun.');

        return $member->division;
    }
}
