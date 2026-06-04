@extends('layouts.admin')

@section('content')

<div class="d-flex justify-content-between mb-3">

    <h2>News Categories</h2>

    <a
        href="{{ route('admin.news-categories.create') }}"
        class="btn btn-primary">

        Tambah Kategori

    </a>

</div>

<table class="table table-bordered">

    <thead>

        <tr>

            <th>ID</th>

            <th>Nama</th>

            <th>Slug</th>

            <th>Deskripsi</th>

            <th width="200">
                Aksi
            </th>

        </tr>

    </thead>

    <tbody>

        @forelse($categories as $category)

            <tr>

                <td>
                    {{ $category->id }}
                </td>

                <td>
                    {{ $category->name }}
                </td>

                <td>
                    {{ $category->slug }}
                </td>

                <td>
                    {{ $category->description }}
                </td>

                <td>

                    <a
                        href="{{ route('admin.news-categories.edit', $category) }}"
                        class="btn btn-warning btn-sm">

                        Edit

                    </a>

                    <form
                        action="{{ route('admin.news-categories.destroy', $category) }}"
                        method="POST"
                        class="d-inline">

                        @csrf
                        @method('DELETE')

                        <button
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Yakin hapus kategori ini?')">

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

{{ $categories->links() }}

@endsection