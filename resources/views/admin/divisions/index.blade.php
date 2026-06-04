@extends('layouts.admin')

@section('content')

<div class="d-flex justify-content-between mb-3">

    <h2>Divisions</h2>

    <a
        href="{{ route('admin.divisions.create') }}"
        class="btn btn-primary">

        Tambah Divisi

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

    @forelse($divisions as $division)

        <tr>

            <td>
                {{ $division->id }}
            </td>

            <td>
                {{ $division->name }}
            </td>

            <td>
                {{ $division->description }}
            </td>

            <td>

                <a
                    href="{{ route('admin.divisions.edit',$division) }}"
                    class="btn btn-warning btn-sm">

                    Edit

                </a>

                <form
                    action="{{ route('admin.divisions.destroy',$division) }}"
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

{{ $divisions->links() }}

@endsection