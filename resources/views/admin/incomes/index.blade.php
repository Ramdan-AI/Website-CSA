@extends('layouts.admin')

@section('content')

<div class="d-flex justify-content-between mb-3">

    <h2>Incomes</h2>

    <a
        href="{{ route('admin.incomes.create') }}"
        class="btn btn-primary">

        Tambah Income

    </a>

</div>

<table class="table table-bordered">

    <thead>

        <tr>

            <th>Title</th>
            <th>Amount</th>
            <th>Date</th>
            <th>Created By</th>

            <th width="200">
                Action
            </th>

        </tr>

    </thead>

    <tbody>

        @forelse($incomes as $income)

            <tr>

                <td>{{ $income->title }}</td>

                <td>
                    Rp {{ number_format($income->amount, 0, ',', '.') }}
                </td>

                <td>
                    {{ $income->transaction_date }}
                </td>

                <td>
                    {{ $income->user->name }}
                </td>

                <td>

                    <a
                        href="{{ route('admin.incomes.edit', $income) }}"
                        class="btn btn-warning btn-sm">

                        Edit

                    </a>

                    <form
                        action="{{ route('admin.incomes.destroy', $income) }}"
                        method="POST"
                        class="d-inline">

                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="btn btn-danger btn-sm">

                            Hapus

                        </button>

                    </form>

                </td>

            </tr>

        @empty

            <tr>

                <td colspan="5" class="text-center">

                    Belum ada data

                </td>

            </tr>

        @endforelse

    </tbody>

</table>

{{ $incomes->links() }}

@endsection