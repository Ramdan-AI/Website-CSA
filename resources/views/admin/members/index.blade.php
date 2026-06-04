@extends('layouts.admin')

@section('content')

<div class="d-flex justify-content-between mb-3">

    <h2>Members</h2>

    <a
        href="{{ route('admin.members.create') }}"
        class="btn btn-primary">

        Tambah Member

    </a>

</div>

<table class="table table-bordered">

    <thead>

        <tr>

            <th>Nama</th>

            <th>Divisi</th>

            <th>Posisi</th>

            <th>User</th>

            <th>Status</th>

            <th width="200">
                Aksi
            </th>

        </tr>

    </thead>

    <tbody>

    @forelse($members as $member)

        <tr>

            <td>{{ $member->name }}</td>

            <td>{{ $member->division->name }}</td>

            <td>{{ $member->position->name }}</td>

            <td>
                {{ $member->user?->email ?? '-' }}
            </td>

            <td>

                @if($member->is_active)

                    Aktif

                @else

                    Nonaktif

                @endif

            </td>

            <td>

                <a
                    href="{{ route('admin.members.edit',$member) }}"
                    class="btn btn-warning btn-sm">

                    Edit

                </a>

                <form
                    action="{{ route('admin.members.destroy',$member) }}"
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

            <td colspan="6" class="text-center">

                Belum ada data

            </td>

        </tr>

    @endforelse

    </tbody>

</table>

{{ $members->links() }}

@endsection