<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Division;
use App\Http\Requests\DivisionRequest;

class DivisionController extends Controller
{
    public function index()
    {
        $divisions = Division::latest()->paginate(10);

        return view(
            'admin.divisions.index',
            compact('divisions')
        );
    }

    public function create()
    {
        return view(
            'admin.divisions.create'
        );
    }

    public function store(
        DivisionRequest $request
    ) {
        Division::create(
            $request->validated()
        );

        return redirect()
            ->route('admin.divisions.index')
            ->with(
                'success',
                'Divisi berhasil ditambahkan'
            );
    }

    public function edit(
        Division $division
    ) {
        return view(
            'admin.divisions.edit',
            compact('division')
        );
    }

    public function update(
        DivisionRequest $request,
        Division $division
    ) {
        $division->update(
            $request->validated()
        );

        return redirect()
            ->route('admin.divisions.index')
            ->with(
                'success',
                'Divisi berhasil diupdate'
            );
    }

    public function destroy(
        Division $division
    ) {
        $division->delete();

        return back()->with(
            'success',
            'Divisi berhasil dihapus'
        );
    }
}
