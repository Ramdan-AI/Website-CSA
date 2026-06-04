@extends('layouts.admin')

@section('content')

<h2>Tambah Gallery</h2>

<form
    action="{{ route('admin.galleries.store') }}"
    method="POST">

    @csrf

    <div class="card">

        <div class="card-body">

            <div class="mb-3">

                <label>Album</label>

                <select
                    name="album_id"
                    class="form-control"
                    required>

                    <option value="">
                        Pilih Album
                    </option>

                    @foreach($albums as $album)

                        <option
                            value="{{ $album->id }}">

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
                    required>

            </div>

            <div class="mb-3">

                <label>Caption</label>

                <input
                    type="text"
                    name="caption"
                    class="form-control">

            </div>

        </div>

        <div class="card-footer">

            <button
                class="btn btn-primary">

                Simpan

            </button>

        </div>

    </div>

</form>

@endsection