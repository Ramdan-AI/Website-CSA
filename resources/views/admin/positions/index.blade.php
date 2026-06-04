@extends('layouts.admin')

@section('content')

<div class="d-flex justify-content-between mb-3">

    <h2>Positions</h2>

    <a
        href="{{ route('admin.positions.create') }}"
        class="btn btn-primary">

        Tambah Posisi

    </a>

</div>

<table class="table table-bordered">

    <thead>

        <tr>

            <th>ID</th>

            <th>Nama</th>

            <th>Deskripsi</th>

            <th width="200">
                Aksi
            </th>

        </tr>

    </thead>

    <tbody>

    @forelse($positions as $position)

        <tr>

            <td>
                {{ $position->id }}
            </td>

            <td>
                {{ $position->name }}
            </td>

            <td>
                {{ $position->description }}
            </td>

            <td>

                <a
                    href="{{ route('admin.positions.edit',$position) }}"
                    class="btn btn-warning btn-sm">

                    Edit

                </a>

                <form
                    action="{{ route('admin.positions.destroy',$position) }}"
                    method="POST"
                    class="d-inline">

                    @csrf
                    @method('DELETE')

                    <button
                        class="btn btn-danger btn-sm">

                        Hapus

                    </button>

                </form>

            </td>

        </tr>

    @empty

        <tr>

            <td colspan="4" class="text-center">

                Belum ada data

            </td>

        </tr>

    @endforelse

    </tbody>

</table>

{{ $positions->links() }}

@endsection