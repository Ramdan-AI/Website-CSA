@extends('layouts.admin')

@section('content')

<h2>Edit Posisi</h2>

<form
    action="{{ route('admin.positions.update',$position) }}"
    method="POST">

    @csrf
    @method('PUT')

    <div class="mb-3">

        <label>
            Nama Posisi
        </label>

        <input
            type="text"
            name="name"
            class="form-control"
            value="{{ old('name',$position->name) }}">

    </div>

    <div class="mb-3">

        <label>
            Deskripsi
        </label>

        <textarea
            name="description"
            class="form-control">{{ old('description',$position->description) }}</textarea>

    </div>

    <button class="btn btn-primary">

        Update

    </button>

</form>

@endsection