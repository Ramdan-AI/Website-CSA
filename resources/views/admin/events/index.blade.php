@extends('layouts.admin')

@section('content')

<div class="d-flex justify-content-between mb-3">

    <h2>Events</h2>

    <a
        href="{{ route('admin.events.create') }}"
        class="btn btn-primary">

        Tambah Event

    </a>

</div>

<table class="table table-bordered">

    <thead>

        <tr>

            <th>Title</th>
            <th>Date</th>
            <th>Location</th>
            <th>Status</th>

            <th width="200">
                Aksi
            </th>

        </tr>

    </thead>

    <tbody>

        @forelse($events as $event)

            <tr>

                <td>{{ $event->title }}</td>

                <td>{{ $event->event_date }}</td>

                <td>{{ $event->location }}</td>

                <td>{{ $event->status }}</td>

                <td>

                    <a
                        href="{{ route('admin.events.edit',$event) }}"
                        class="btn btn-warning btn-sm">

                        Edit

                    </a>

                    <form
                        action="{{ route('admin.events.destroy',$event) }}"
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

{{ $events->links() }}

@endsection