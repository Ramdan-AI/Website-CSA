@extends('layouts.admin')

@section('content')

<h2>Edit Income</h2>

<form
    action="{{ route('admin.incomes.update', $income) }}"
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
                    value="{{ old('title', $income->title) }}"
                    required>

            </div>

            <div class="mb-3">

                <label>Amount</label>

                <input
                    type="number"
                    name="amount"
                    class="form-control"
                    value="{{ old('amount', $income->amount) }}"
                    required>

            </div>

            <div class="mb-3">

                <label>Transaction Date</label>

                <input
                    type="date"
                    name="transaction_date"
                    class="form-control"
                    value="{{ old('transaction_date', $income->transaction_date?->format('Y-m-d')) }}"
                    required>

            </div>

            <div class="mb-3">

                <label>Description</label>

                <textarea
                    name="description"
                    rows="5"
                    class="form-control">{{ old('description', $income->description) }}</textarea>

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