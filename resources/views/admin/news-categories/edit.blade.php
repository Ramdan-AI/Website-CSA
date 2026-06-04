@extends('layouts.admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">

    <h2>Edit Kategori Berita</h2>

    <a
        href="{{ route('admin.news-categories.index') }}"
        class="btn btn-secondary">

        Kembali

    </a>

</div>

@if ($errors->any())

    <div class="alert alert-danger">

        <ul class="mb-0">

            @foreach ($errors->all() as $error)

                <li>{{ $error }}</li>

            @endforeach

        </ul>

    </div>

@endif

<form
    action="{{ route('admin.news-categories.update', $newsCategory) }}"
    method="POST">

    @csrf
    @method('PUT')

    <div class="card">

        <div class="card-body">

            <div class="mb-3">

                <label class="form-label">

                    Nama Kategori

                </label>

                <input
                    type="text"
                    name="name"
                    class="form-control"
                    value="{{ old('name', $newsCategory->name) }}"
                    required>

            </div>

            <div class="mb-3">

                <label class="form-label">

                    Slug

                </label>

                <input
                    type="text"
                    name="slug"
                    class="form-control"
                    value="{{ old('slug', $newsCategory->slug) }}"
                    required>

            </div>

            <div class="mb-3">

                <label class="form-label">

                    Deskripsi

                </label>

                <textarea
                    name="description"
                    rows="4"
                    class="form-control">{{ old('description', $newsCategory->description) }}</textarea>

            </div>

        </div>

        <div class="card-footer">

            <button
                type="submit"
                class="btn btn-primary">

                Update

            </button>

        </div>

    </div>

</form>

@endsection