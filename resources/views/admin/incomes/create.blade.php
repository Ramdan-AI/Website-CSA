@extends('layouts.admin')

@section('content')

<h2>Tambah Income</h2>

<form
    action="{{ route('admin.incomes.store') }}"
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

                <label>Amount</label>

                <input
                    type="number"
                    name="amount"
                    class="form-control"
                    value="{{ old('amount') }}"
                    required>

            </div>

            <div class="mb-3">

                <label>Transaction Date</label>

                <input
                    type="date"
                    name="transaction_date"
                    class="form-control"
                    value="{{ old('transaction_date') }}"
                    required>

            </div>

            <div class="mb-3">

                <label>Description</label>

                <textarea
                    name="description"
                    rows="5"
                    class="form-control">{{ old('description') }}</textarea>

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