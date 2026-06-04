@extends('layouts.admin')

@section('content')

<div class="d-flex justify-content-between mb-3">

    <h2>News</h2>

    <a
        href="{{ route('admin.news.create') }}"
        class="btn btn-primary">

        Tambah Berita

    </a>

</div>

<table class="table table-bordered">

    <thead>

        <tr>

            <th>Judul</th>

            <th>Kategori</th>

            <th>Author</th>

            <th>Status</th>

            <th width="200">

                Aksi

            </th>

        </tr>

    </thead>

    <tbody>

        @foreach($news as $item)

            <tr>

                <td>

                    {{ $item->title }}

                </td>

                <td>

                    {{ $item->category->name }}

                </td>

                <td>

                    {{ $item->author->name }}

                </td>

                <td>

                    {{ $item->status }}

                </td>

                <td>

                    <a
                        href="{{ route('admin.news.edit',$item) }}"
                        class="btn btn-warning btn-sm">

                        Edit

                    </a>

                    <form
                        action="{{ route('admin.news.destroy',$item) }}"
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

        @endforeach

    </tbody>

</table>

{{ $news->links() }}

@endsection