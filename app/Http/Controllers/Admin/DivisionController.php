<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Http\Requests\DivisionRequest;
use Illuminate\Support\Facades\Storage;

class DivisionController extends Controller
{
    public function index()
    {
        $divisions = Division::latest()->paginate(10);

        return view('pages.admin.divisions.index', compact('divisions'));
    }

    public function create()
    {
        return view('pages.admin.divisions.create');
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
        return view('pages.admin.divisions.edit', compact('division'));
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
}
