@extends('layouts.admin')

@section('content')

<div class="d-flex justify-content-between mb-3">

    <h2>Attendances</h2>

    <a
        href="{{ route('admin.attendances.create') }}"
        class="btn btn-primary">

        Tambah Absensi

    </a>

</div>

<table class="table table-bordered">

    <thead>

        <tr>

            <th>Event</th>
            <th>Member</th>
            <th>Status</th>
            <th>Check In</th>

            <th width="200">
                Aksi
            </th>

        </tr>

    </thead>

    <tbody>

        @forelse($attendances as $attendance)

            <tr>

                <td>
                    {{ $attendance->event->title }}
                </td>

                <td>
                    {{ $attendance->member->name }}
                </td>

                <td>
                    {{ $attendance->status }}
                </td>

                <td>
                    {{ $attendance->check_in_at }}
                </td>

                <td>

                    <a
                        href="{{ route('admin.attendances.edit',$attendance) }}"
                        class="btn btn-warning btn-sm">

                        Edit

                    </a>

                    <form
                        action="{{ route('admin.attendances.destroy',$attendance) }}"
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
                    colspan="5"
                    class="text-center">

                    Belum ada data

                </td>

            </tr>

        @endforelse

    </tbody>

</table>

{{ $attendances->links() }}

@endsection