@extends('layouts.admin')

@section('content')

<h2>Edit Divisi</h2>

<form
    action="{{ route('admin.divisions.update',$division) }}"
    method="POST">

    @csrf
    @method('PUT')

    <div class="mb-3">

        <label>
            Nama Divisi
        </label>

        <input
            type="text"
            name="name"
            class="form-control"
            value="{{ old('name',$division->name) }}">

    </div>

    <div class="mb-3">

        <label>
            Deskripsi
        </label>

        <textarea
            name="description"
            class="form-control">{{ old('description',$division->description) }}</textarea>

    </div>

    <button class="btn btn-primary">

        Update

    </button>

</form>

@endsection