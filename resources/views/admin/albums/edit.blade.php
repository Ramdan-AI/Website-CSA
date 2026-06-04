@extends('layouts.admin')

@section('content')

<h2>Edit Album</h2>

<form
    action="{{ route('admin.albums.update',$album) }}"
    method="POST">

    @csrf
    @method('PUT')

    <div class="card">

        <div class="card-body">

            <div class="mb-3">

                <label>
                    Judul Album
                </label>

                <input
                    type="text"
                    name="title"
                    class="form-control"
                    value="{{ old('title',$album->title) }}"
                    required>

            </div>

            <div class="mb-3">

                <label>
                    Deskripsi
                </label>

                <textarea
                    name="description"
                    rows="5"
                    class="form-control">{{ old('description',$album->description) }}</textarea>

            </div>

            <div class="mb-3">

                <label>
                    Cover Image
                </label>

                <input
                    type="text"
                    name="cover_image"
                    class="form-control"
                    value="{{ old('cover_image',$album->cover_image) }}">

            </div>

        </div>

        <div class="card-footer">

            <button
                class="btn btn-primary">

                Update

            </button>

        </div>

    </div>

</form>

@endsection