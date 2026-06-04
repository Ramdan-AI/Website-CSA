@extends('layouts.admin')

@section('content')

<h2>Tambah Event</h2>

<form
    action="{{ route('admin.events.store') }}"
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
                <label>Slug</label>
                <input
                    type="text"
                    name="slug"
                    class="form-control"
                    value="{{ old('slug') }}"
                    required>
            </div>

            <div class="mb-3">
                <label>Description</label>

                <textarea
                    name="description"
                    rows="6"
                    class="form-control"
                    required>{{ old('description') }}</textarea>

            </div>

            <div class="mb-3">
                <label>Location</label>

                <input
                    type="text"
                    name="location"
                    class="form-control"
                    value="{{ old('location') }}">
            </div>

            <div class="mb-3">
                <label>Event Date</label>

                <input
                    type="date"
                    name="event_date"
                    class="form-control"
                    value="{{ old('event_date') }}"
                    required>
            </div>

            <div class="mb-3">
                <label>Banner</label>

                <input
                    type="text"
                    name="banner"
                    class="form-control"
                    value="{{ old('banner') }}">
            </div>

            <div class="mb-3">
                <label>Registration Link</label>

                <input
                    type="text"
                    name="registration_link"
                    class="form-control"
                    value="{{ old('registration_link') }}">
            </div>

            <div class="mb-3">
                <label>Status</label>

                <select
                    name="status"
                    class="form-control">

                    <option value="draft">
                        Draft
                    </option>

                    <option value="published">
                        Published
                    </option>

                    <option value="closed">
                        Closed
                    </option>

                </select>

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