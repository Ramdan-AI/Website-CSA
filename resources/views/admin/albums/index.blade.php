@extends('layouts.admin')

@section('content')

<div class="d-flex justify-content-between mb-3">

    <h2>Albums</h2>

    <a
        href="{{ route('admin.albums.create') }}"
        class="btn btn-primary">

        Tambah Album

    </a>

</div>

<table class="table table-bordered">

    <thead>

        <tr>

            <th>ID</th>

            <th>Judul</th>

            <th>Cover</th>

            <th width="200">
                Aksi
            </th>

        </tr>

    </thead>

    <tbody>

        @forelse($albums as $album)

            <tr>

                <td>{{ $album->id }}</td>

                <td>{{ $album->title }}</td>

                <td>{{ $album->cover_image }}</td>

                <td>

                    <a
                        href="{{ route('admin.albums.edit',$album) }}"
                        class="btn btn-warning btn-sm">

                        Edit

                    </a>

                    <form
                        action="{{ route('admin.albums.destroy',$album) }}"
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

                <td
                    colspan="4"
                    class="text-center">

                    Belum ada data

                </td>

            </tr>

        @endforelse

    </tbody>

</table>

{{ $albums->links() }}

@endsection