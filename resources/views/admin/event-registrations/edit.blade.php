@extends('layouts.admin')

@section('content')

<h2>Edit Registrasi Event</h2>

<form
    action="{{ route('admin.event-registrations.update', $eventRegistration) }}"
    method="POST">

    @csrf
    @method('PUT')

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
                            {{ old('event_id', $eventRegistration->event_id) == $event->id ? 'selected' : '' }}>

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
                            {{ old('member_id', $eventRegistration->member_id) == $member->id ? 'selected' : '' }}>

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

                Update

            </button>

        </div>

    </div>

</form>

@endsection