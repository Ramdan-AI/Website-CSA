<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\Member;
use App\Http\Requests\DivisionRequest;
use Illuminate\Http\Request;
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
            ->oldest('id')
            ->get();

        $divisions->each(function ($division) {
            $leader = $division->members->first(function ($member) {
                return $member->position && (
                    str_contains(strtolower($member->position->name), 'ketua divisi') ||
                    str_contains(strtolower($member->position->name), 'ketua')
                );
            });
            $division->leader_name = $leader ? $leader->name : null;
        });

        $canCreate = $divisions->count() < 5;

        return view('pages.divisions.index', compact('divisions', 'canCreate'));
    }

    public function create()
    {
        $division = new Division();
        $isEdit = false;
        return view('pages.divisions.form', compact('division', 'isEdit'));
    }

    public function store(DivisionRequest $request)
    {
        $data = $request->validated();

        if (Division::count() >= 5) {
            return redirect()
                ->route('admin.divisions.index')
                ->with('error', 'Maksimal 5 divisi sudah tercapai.');
        }

        $data['skills'] = $this->normalizeSkills($request->input('skills'));

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
        return view('pages.divisions.form', compact('division', 'isEdit'));
    }

    public function update(DivisionRequest $request, Division $division)
    {
        $data = $request->validated();

        if ($division->image && $request->has('remove_image') && $request->remove_image == '1') {
            Storage::disk('public')->delete($division->image);
            $data['image'] = null;
        }

        if ($request->hasFile('image')) {
            if ($division->image) {
                Storage::disk('public')->delete($division->image);
            }
            $data['image'] = $request->file('image')->store('divisions', 'public');
        }

        $data['skills'] = $this->normalizeSkills($request->input('skills'));
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

    public function members(Request $request, Division $division)
    {
        $members = $division->members()
            ->with('position')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('nim', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $availableMembers = Member::with('position')
            ->where('is_active', true)
            ->whereNull('division_id')
            ->when($request->filled('candidate_search'), function ($query) use ($request) {
                $search = $request->candidate_search;
                $query->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('nim', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->limit(50)
            ->get();

        return view('pages.divisions.members', compact('division', 'members', 'availableMembers'));
    }

    public function addMembers(Request $request, Division $division)
    {
        $validated = $request->validate([
            'member_ids' => ['required', 'array', 'min:1'],
            'member_ids.*' => ['integer', 'exists:members,id'],
        ]);

        $updated = Member::whereIn('id', $validated['member_ids'])
            ->whereNull('division_id')
            ->update(['division_id' => $division->id]);

        return back()->with('success', $updated . ' anggota berhasil ditambahkan ke ' . $division->name . '.');
    }

    public function removeMember(Division $division, Member $member)
    {
        abort_unless($member->division_id === $division->id, 404);

        $memberName = $member->name;
        $member->update(['division_id' => null]);

        return back()->with('success', $memberName . ' berhasil dikeluarkan dari ' . $division->name);
    }

    private function normalizeSkills($skills)
    {
        if (is_string($skills)) {
            $skills = array_filter(array_map('trim', explode(',', $skills)));
        }

        if (!is_array($skills)) {
            return null;
        }

        $cleaned = array_values(array_filter($skills, fn ($s) => $s !== ''));

        return $cleaned === [] ? null : $cleaned;
    }
}
