@extends('layouts.admin')

@section('content')

<div class="d-flex justify-content-between mb-3">

    <h2>Documents</h2>

    <a
        href="{{ route('admin.documents.create') }}"
        class="btn btn-primary">

        Tambah Document

    </a>

</div>

<table class="table table-bordered">

    <thead>

        <tr>

            <th>Title</th>
            <th>File</th>
            <th>Uploader</th>

            <th width="200">
                Aksi
            </th>

        </tr>

    </thead>

    <tbody>

        @forelse($documents as $document)

            <tr>

                <td>{{ $document->title }}</td>

                <td>{{ $document->file }}</td>

                <td>{{ $document->user->name }}</td>

                <td>

                    <a
                        href="{{ route('admin.documents.edit',$document) }}"
                        class="btn btn-warning btn-sm">

                        Edit

                    </a>

                    <form
                        action="{{ route('admin.documents.destroy',$document) }}"
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

{{ $documents->links() }}

@endsection