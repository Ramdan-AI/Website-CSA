@extends('layouts.admin')

@section('content')

<div class="d-flex justify-content-between mb-3">

    <h2>Event Registrations</h2>

    <a
        href="{{ route('admin.event-registrations.create') }}"
        class="btn btn-primary">

        Tambah Registrasi

    </a>

</div>

<table class="table table-bordered">

    <thead>

        <tr>

            <th>Event</th>
            <th>Member</th>
            <th>Registered At</th>

            <th width="200">

                Aksi

            </th>

        </tr>

    </thead>

    <tbody>

        @forelse($registrations as $registration)

            <tr>

                <td>
                    {{ $registration->event->title }}
                </td>

                <td>
                    {{ $registration->member->name }}
                </td>

                <td>
                    {{ $registration->registered_at }}
                </td>

                <td>

                    <a
                        href="{{ route('admin.event-registrations.edit', $registration) }}"
                        class="btn btn-warning btn-sm">

                        Edit

                    </a>

                    <form
                        action="{{ route('admin.event-registrations.destroy', $registration) }}"
                        method="POST"
                        class="d-inline">

                        @csrf
                        @method('DELETE')

                        <button
                            class="btn btn-danger btn-sm">

                            Hapus

                        </button>

                    </form>

                </td>

            </tr>

        @empty

            <tr>

                <td
                    colspan="4"
                    class="text-center">

                    Belum ada data

                </td>

            </tr>

        @endforelse

    </tbody>

</table>

{{ $registrations->links() }}

@endsection