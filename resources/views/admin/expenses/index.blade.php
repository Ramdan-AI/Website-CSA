@extends('layouts.admin')

@section('content')

<div class="d-flex justify-content-between mb-3">

    <h2>Expenses</h2>

    <a
        href="{{ route('admin.expenses.create') }}"
        class="btn btn-primary">

        Tambah Expense

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

        @forelse($expenses as $expense)

            <tr>

                <td>{{ $expense->title }}</td>

                <td>
                    Rp {{ number_format($expense->amount, 0, ',', '.') }}
                </td>

                <td>
                    {{ $expense->transaction_date }}
                </td>

                <td>
                    {{ $expense->user->name }}
                </td>

                <td>

                    <a
                        href="{{ route('admin.expenses.edit', $expense) }}"
                        class="btn btn-warning btn-sm">

                        Edit

                    </a>

                    <form
                        action="{{ route('admin.expenses.destroy', $expense) }}"
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

{{ $expenses->links() }}

@endsection