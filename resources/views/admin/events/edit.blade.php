@extends('layouts.admin')

@section('content')

<h2>Edit Event</h2>

<form
    action="{{ route('admin.events.update', $event) }}"
    method="POST">

    @csrf
    @method('PUT')

    <div class="card">

        <div class="card-body">

            <div class="mb-3">
                <label>Title</label>

                <input
                    type="text"
                    name="title"
                    class="form-control"
                    value="{{ old('title', $event->title) }}"
                    required>
            </div>

            <div class="mb-3">
                <label>Slug</label>

                <input
                    type="text"
                    name="slug"
                    class="form-control"
                    value="{{ old('slug', $event->slug) }}"
                    required>
            </div>

            <div class="mb-3">
                <label>Description</label>

                <textarea
                    name="description"
                    rows="6"
                    class="form-control"
                    required>{{ old('description', $event->description) }}</textarea>

            </div>

            <div class="mb-3">
                <label>Location</label>

                <input
                    type="text"
                    name="location"
                    class="form-control"
                    value="{{ old('location', $event->location) }}">
            </div>

            <div class="mb-3">
                <label>Event Date</label>

                <input
                    type="date"
                    name="event_date"
                    class="form-control"
                    value="{{ old('event_date', $event->event_date->format('Y-m-d')) }}"
                    required>
            </div>

            <div class="mb-3">
                <label>Banner</label>

                <input
                    type="text"
                    name="banner"
                    class="form-control"
                    value="{{ old('banner', $event->banner) }}">
            </div>

            <div class="mb-3">
                <label>Registration Link</label>

                <input
                    type="text"
                    name="registration_link"
                    class="form-control"
                    value="{{ old('registration_link', $event->registration_link) }}">
            </div>

            <div class="mb-3">
                <label>Status</label>

                <select
                    name="status"
                    class="form-control">

                    <option
                        value="draft"
                        {{ old('status', $event->status) == 'draft' ? 'selected' : '' }}>

                        Draft

                    </option>

                    <option
                        value="published"
                        {{ old('status', $event->status) == 'published' ? 'selected' : '' }}>

                        Published

                    </option>

                    <option
                        value="closed"
                        {{ old('status', $event->status) == 'closed' ? 'selected' : '' }}>

                        Closed

                    </option>

                </select>

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