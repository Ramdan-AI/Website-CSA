<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Member;
use App\Models\Division;
use App\Models\Position;
use App\Http\Controllers\Controller;
use App\Http\Requests\MemberRequest;

class MemberController extends Controller
{
    public function index()
    {
        $members = Member::with([
            'division',
            'position',
            'user'
        ])
            ->latest()
            ->paginate(10);

        return view(
            'admin.members.index',
            compact('members')
        );
    }

    public function create()
    {
        $users = User::orderBy('name')->get();

        $divisions = Division::orderBy('name')->get();

        $positions = Position::orderBy('name')->get();

        return view(
            'admin.members.create',
            compact(
                'users',
                'divisions',
                'positions'
            )
        );
    }

    public function store(MemberRequest $request)
    {
        Member::create(
            $request->validated()
        );

        return redirect()
            ->route('admin.members.index')
            ->with(
                'success',
                'Member berhasil ditambahkan'
            );
    }

    public function edit(Member $member)
    {
        $users = User::orderBy('name')->get();

        $divisions = Division::orderBy('name')->get();

        $positions = Position::orderBy('name')->get();

        return view(
            'admin.members.edit',
            compact(
                'member',
                'users',
                'divisions',
                'positions'
            )
        );
    }

    public function update(
        MemberRequest $request,
        Member $member
    ) {
        $member->update(
            $request->validated()
        );

        return redirect()
            ->route('admin.members.index')
            ->with(
                'success',
                'Member berhasil diupdate'
            );
    }

    public function destroy(Member $member)
    {
        $member->delete();

        return back()->with(
            'success',
            'Member berhasil dihapus'
        );
    }
}
