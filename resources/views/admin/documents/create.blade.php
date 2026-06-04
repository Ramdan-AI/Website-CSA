@extends('layouts.admin')

@section('content')

<h2>Tambah Document</h2>

<form
    action="{{ route('admin.documents.store') }}"
    method="POST">

    @csrf

    <div class="card">

        <div class="card-body">

            <div class="mb-3">

                <label>Title</label>

                <input
                    type="text"
                    name="title"
                    class="form-control"
                    value="{{ old('title') }}"
                    required>

            </div>

            <div class="mb-3">

                <label>File</label>

                <input
                    type="text"
                    name="file"
                    class="form-control"
                    value="{{ old('file') }}"
                    required>

            </div>

            <div class="mb-3">

                <label>Description</label>

                <textarea
                    name="description"
                    rows="5"
                    class="form-control">{{ old('description') }}</textarea>

            </div>

        </div>

        <div class="card-footer">

            <button
                type="submit"
                class="btn btn-primary">

                Simpan

            </button>

        </div>

    </div>

</form>

@endsection