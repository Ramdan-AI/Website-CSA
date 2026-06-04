@extends('layouts.admin')

@section('content')

<h2>Tambah Divisi</h2>

<form
    action="{{ route('admin.divisions.store') }}"
    method="POST">

    @csrf

    <div class="mb-3">

        <label>
            Nama Divisi
        </label>

        <input
            type="text"
            name="name"
            class="form-control"
            value="{{ old('name') }}">

    </div>

    <div class="mb-3">

        <label>
            Deskripsi
        </label>

        <textarea
            name="description"
            class="form-control"></textarea>

    </div>

    <button class="btn btn-success">

        Simpan

    </button>

</form>

@endsection