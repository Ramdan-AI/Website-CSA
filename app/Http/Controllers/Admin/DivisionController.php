<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\Member;
use App\Http\Requests\DivisionRequest;
use Illuminate\Support\Facades\Storage;

class DivisionController extends Controller
{
    public function index()
    {
        $divisions = Division::with(['members' => function ($query) {
            $query->select('id', 'name', 'division_id', 'position_id')
                ->with('position:id,name');
        }])
            ->withCount('members')
            ->latest()
            ->paginate(12);

        $divisions->each(function ($division) {
            $leader = $division->members->first(function ($member) {
                return $member->position && (
                    str_contains(strtolower($member->position->name), 'ketua divisi') ||
                    str_contains(strtolower($member->position->name), 'ketua')
                );
            });
            $division->leader_name = $leader ? $leader->name : null;
        });

        return view('pages.admin.divisions.index', compact('divisions'));
    }

    public function create()
    {
        $division = new Division();
        $isEdit = false;
        return view('pages.admin.divisions.form', compact('division', 'isEdit'));
    }

    public function store(DivisionRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('divisions', 'public');
        }

        Division::create($data);

        return redirect()
            ->route('admin.divisions.index')
            ->with('success', 'Divisi berhasil ditambahkan.');
    }

    public function edit(Division $division)
    {
        $isEdit = true;
        return view('pages.admin.divisions.form', compact('division', 'isEdit'));
    }

    public function update(DivisionRequest $request, Division $division)
    {
        $data = $request->validated();

        if ($request->has('remove_image') && $request->remove_image == '1') {
            if ($division->image) {
                Storage::disk('public')->delete($division->image);
            }
            $data['image'] = null;
        }

        if ($request->hasFile('image')) {
            if ($division->image) {
                Storage::disk('public')->delete($division->image);
            }
            $data['image'] = $request->file('image')->store('divisions', 'public');
        }

        unset($data['remove_image']);

        $division->update($data);

        return redirect()
            ->route('admin.divisions.index')
            ->with('success', 'Divisi berhasil diperbarui.');
    }

    public function destroy(Division $division)
    {
        if ($division->image) {
            Storage::disk('public')->delete($division->image);
        }

        $division->delete();

        return back()->with('success', 'Divisi berhasil dihapus.');
    }

    public function members(Division $division)
    {
        $members = $division->members()
            ->with('position')
            ->latest()
            ->paginate(10);

        return view('pages.admin.divisions.members', compact('division', 'members'));
    }

    public function removeMember(Division $division, $memberId)
    {
        $member = Member::findOrFail($memberId);
        $memberName = $member->name;
        $member->division_id = null;
        $member->save();

        return back()->with('success', $memberName . ' berhasil dikeluarkan dari ' . $division->name);
    }
}
