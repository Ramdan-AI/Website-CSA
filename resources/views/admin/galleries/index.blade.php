@extends('layouts.admin')

@section('content')

<div class="d-flex justify-content-between mb-3">

    <h2>Galleries</h2>

    <a
        href="{{ route('admin.galleries.create') }}"
        class="btn btn-primary">

        Tambah Gallery

    </a>

</div>

<table class="table table-bordered">

    <thead>

        <tr>

            <th>ID</th>
            <th>Album</th>
            <th>Image</th>
            <th>Caption</th>

            <th width="200">

                Aksi

            </th>

        </tr>

    </thead>

    <tbody>

        @forelse($galleries as $gallery)

            <tr>

                <td>{{ $gallery->id }}</td>

                <td>{{ $gallery->album->title }}</td>

                <td>{{ $gallery->image }}</td>

                <td>{{ $gallery->caption }}</td>

                <td>

                    <a
                        href="{{ route('admin.galleries.edit',$gallery) }}"
                        class="btn btn-warning btn-sm">

                        Edit

                    </a>

                    <form
                        action="{{ route('admin.galleries.destroy',$gallery) }}"
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
                    colspan="5"
                    class="text-center">

                    Belum ada data

                </td>

            </tr>

        @endforelse

    </tbody>

</table>

{{ $galleries->links() }}

@endsection