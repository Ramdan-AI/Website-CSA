@extends('layouts.admin')

@section('content')

<h2>Tambah Registrasi Event</h2>

<form
    action="{{ route('admin.event-registrations.store') }}"
    method="POST">

    @csrf

    <div class="card">

        <div class="card-body">

            <div class="mb-3">

                <label>Event</label>

                <select
                    name="event_id"
                    class="form-control"
                    required>

                    <option value="">
                        Pilih Event
                    </option>

                    @foreach($events as $event)

                        <option
                            value="{{ $event->id }}"
                            {{ old('event_id') == $event->id ? 'selected' : '' }}>

                            {{ $event->title }}

                        </option>

                    @endforeach

                </select>

            </div>

            <div class="mb-3">

                <label>Member</label>

                <select
                    name="member_id"
                    class="form-control"
                    required>

                    <option value="">
                        Pilih Member
                    </option>

                    @foreach($members as $member)

                        <option
                            value="{{ $member->id }}"
                            {{ old('member_id') == $member->id ? 'selected' : '' }}>

                            {{ $member->name }}

                        </option>

                    @endforeach

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