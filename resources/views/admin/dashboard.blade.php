@extends('layouts.admin')

@section('content')

<h1 class="mb-4">

Dashboard CSA

</h1>

<div class="alert alert-success">

Selamat datang,

<strong>{{ auth()->user()->name }}</strong>

</div>

<div class="row">

<div class="col-md-3 mb-3">

    <div class="card text-center">

        <div class="card-body">

            <h6>Total Users</h6>

            <h2>{{ $totalUsers }}</h2>

        </div>

    </div>

</div>

<div class="col-md-3 mb-3">

    <div class="card text-center">

        <div class="card-body">

            <h6>Total Members</h6>

            <h2>{{ $totalMembers }}</h2>

        </div>

    </div>

</div>

<div class="col-md-3 mb-3">

    <div class="card text-center">

        <div class="card-body">

            <h6>Total Divisions</h6>

            <h2>{{ $totalDivisions }}</h2>

        </div>

    </div>

</div>

<div class="col-md-3 mb-3">

    <div class="card text-center">

        <div class="card-body">

            <h6>Total Positions</h6>

            <h2>{{ $totalPositions }}</h2>

        </div>

    </div>

</div>

</div>

<div class="row">

<div class="col-md-4 mb-3">

    <div class="card text-center">

        <div class="card-body">

            <h6>Total News</h6>

            <h2>{{ $totalNews }}</h2>

        </div>

    </div>

</div>

<div class="col-md-4 mb-3">

    <div class="card text-center">

        <div class="card-body">

            <h6>Total Events</h6>

            <h2>{{ $totalEvents }}</h2>

        </div>

    </div>

</div>

<div class="col-md-4 mb-3">

    <div class="card text-center">

        <div class="card-body">

            <h6>Total Documents</h6>

            <h2>{{ $totalDocuments }}</h2>

        </div>

    </div>

</div>

</div>

<div class="row">

<div class="col-md-4 mb-3">

    <div class="card">

        <div class="card-body">

            <h5>Total Income</h5>

            <h3>
                Rp {{ number_format($totalIncome, 0, ',', '.') }}
            </h3>

        </div>

    </div>

</div>

<div class="col-md-4 mb-3">

    <div class="card">

        <div class="card-body">

            <h5>Total Expense</h5>

            <h3>
                Rp {{ number_format($totalExpense, 0, ',', '.') }}
            </h3>

        </div>

    </div>

</div>

<div class="col-md-4 mb-3">

    <div class="card">

        <div class="card-body">

            <h5>Balance</h5>

            <h3>
                Rp {{ number_format($balance, 0, ',', '.') }}
            </h3>

        </div>

    </div>

</div>

</div>

@endsection
