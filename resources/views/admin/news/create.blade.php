@extends('layouts.admin')

@section('content')

<h2>Tambah Berita</h2>

<form
    action="{{ route('admin.news.store') }}"
    method="POST">

    @csrf

    <div class="card">

        <div class="card-body">

            <div class="mb-3">

                <label class="form-label">
                    Kategori
                </label>

                <select
                    name="category_id"
                    class="form-control"
                    required>

                    <option value="">
                        Pilih Kategori
                    </option>

                    @foreach($categories as $category)

                        <option
                            value="{{ $category->id }}"
                            {{ old('category_id') == $category->id ? 'selected' : '' }}>

                            {{ $category->name }}

                        </option>

                    @endforeach

                </select>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Judul
                </label>

                <input
                    type="text"
                    name="title"
                    class="form-control"
                    value="{{ old('title') }}"
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
                    value="{{ old('slug') }}"
                    required>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Konten
                </label>

                <textarea
                    name="content"
                    rows="10"
                    class="form-control"
                    required>{{ old('content') }}</textarea>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Status
                </label>

                <select
                    name="status"
                    class="form-control">

                    <option value="draft">

                        Draft

                    </option>

                    <option value="published">

                        Published

                    </option>

                </select>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Tanggal Publish
                </label>

                <input
                    type="datetime-local"
                    name="published_at"
                    class="form-control">

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