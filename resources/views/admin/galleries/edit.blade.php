@extends('layouts.admin')

@section('content')

<h2>Edit Gallery</h2>

<form
    action="{{ route('admin.galleries.update',$gallery) }}"
    method="POST">

    @csrf
    @method('PUT')

    <div class="card">

        <div class="card-body">

            <div class="mb-3">

                <label>Album</label>

                <select
                    name="album_id"
                    class="form-control">

                    @foreach($albums as $album)

                        <option
                            value="{{ $album->id }}"
                            {{ old('album_id',$gallery->album_id) == $album->id ? 'selected' : '' }}>

                            {{ $album->title }}

                        </option>

                    @endforeach

                </select>

            </div>

            <div class="mb-3">

                <label>Image</label>

                <input
                    type="text"
                    name="image"
                    class="form-control"
                    value="{{ old('image',$gallery->image) }}">

            </div>

            <div class="mb-3">

                <label>Caption</label>

                <input
                    type="text"
                    name="caption"
                    class="form-control"
                    value="{{ old('caption',$gallery->caption) }}">

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