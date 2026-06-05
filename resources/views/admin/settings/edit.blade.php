@extends('layouts.admin')

@section('content')

<h2>Settings</h2>

<form
    action="{{ route('admin.settings.update') }}"
    method="POST">

    @csrf
    @method('PUT')

    <div class="card">

        <div class="card-body">

            <div class="mb-3">

                <label>Site Name</label>

                <input
                    type="text"
                    name="site_name"
                    class="form-control"
                    value="{{ old('site_name', $setting->site_name) }}">

            </div>

            <div class="mb-3">

                <label>Email</label>

                <input
                    type="email"
                    name="email"
                    class="form-control"
                    value="{{ old('email', $setting->email) }}">

            </div>

            <div class="mb-3">

                <label>Phone</label>

                <input
                    type="text"
                    name="phone"
                    class="form-control"
                    value="{{ old('phone', $setting->phone) }}">

            </div>

            <div class="mb-3">

                <label>Address</label>

                <textarea
                    name="address"
                    rows="3"
                    class="form-control">{{ old('address', $setting->address) }}</textarea>

            </div>

            <div class="mb-3">

                <label>Instagram</label>

                <input
                    type="text"
                    name="instagram"
                    class="form-control"
                    value="{{ old('instagram', $setting->instagram) }}">

            </div>

            <div class="mb-3">

                <label>Youtube</label>

                <input
                    type="text"
                    name="youtube"
                    class="form-control"
                    value="{{ old('youtube', $setting->youtube) }}">

            </div>

            <div class="mb-3">

                <label>LinkedIn</label>

                <input
                    type="text"
                    name="linkedin"
                    class="form-control"
                    value="{{ old('linkedin', $setting->linkedin) }}">

            </div>

            <div class="mb-3">

                <label>About</label>

                <textarea
                    name="about"
                    rows="6"
                    class="form-control">{{ old('about', $setting->about) }}</textarea>

            </div>

        </div>

        <div class="card-footer">

            <button
                type="submit"
                class="btn btn-primary">

                Save Settings

            </button>

        </div>

    </div>

</form>

@endsection