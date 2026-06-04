@extends('layouts.admin')

@section('content')

<h2>Edit Berita</h2>

<form
    action="{{ route('admin.news.update', $news) }}"
    method="POST">

    @csrf
    @method('PUT')

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

                    @foreach($categories as $category)

                        <option
                            value="{{ $category->id }}"
                            {{ old('category_id', $news->category_id) == $category->id ? 'selected' : '' }}>

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
                    value="{{ old('title', $news->title) }}"
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
                    value="{{ old('slug', $news->slug) }}"
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
                    required>{{ old('content', $news->content) }}</textarea>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Status
                </label>

                <select
                    name="status"
                    class="form-control">

                    <option
                        value="draft"
                        {{ old('status', $news->status) == 'draft' ? 'selected' : '' }}>

                        Draft

                    </option>

                    <option
                        value="published"
                        {{ old('status', $news->status) == 'published' ? 'selected' : '' }}>

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
                    class="form-control"
                    value="{{ old('published_at', $news->published_at ? $news->published_at->format('Y-m-d\TH:i') : '') }}">

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