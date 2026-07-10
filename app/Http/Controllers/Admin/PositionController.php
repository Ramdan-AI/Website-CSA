<?php

namespace App\Http\Controllers\Admin;

use App\Models\Position;
use App\Http\Controllers\Controller;
use App\Http\Requests\PositionRequest;

class PositionController extends Controller
{
    public function index()
    {
        $positions = Position::latest()->paginate(10);

        return view('pages.admin.positions.index',
            compact('positions')
        );
    }

    public function create()
    {
        return view('pages.admin.positions.create'
        );
    }

    public function store(
        PositionRequest $request
    ) {
        Position::create(
            $request->validated()
        );

        return redirect()
            ->route('admin.positions.index')
            ->with(
                'success',
                'Posisi berhasil ditambahkan'
            );
    }

    public function edit(
        Position $position
    ) {
        return view('pages.admin.positions.edit',
            compact('position')
        );
    }

    public function update(
        PositionRequest $request,
        Position $position
    ) {
        $position->update(
            $request->validated()
        );

        return redirect()
            ->route('admin.positions.index')
            ->with(
                'success',
                'Posisi berhasil diupdate'
            );
    }

    public function destroy(
        Position $position
    ) {
        $position->delete();

        return back()->with(
            'success',
            'Posisi berhasil dihapus'
        );
    }
}
