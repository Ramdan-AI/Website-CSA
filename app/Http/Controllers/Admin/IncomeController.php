<?php

namespace App\Http\Controllers\Admin;

use App\Models\Income;
use App\Http\Controllers\Controller;
use App\Http\Requests\IncomeRequest;
use Illuminate\Support\Facades\Auth;

class IncomeController extends Controller
{
    public function index()
    {
        $incomes = Income::with('user')
            ->latest()
            ->paginate(10);

        return view('pages.admin.incomes.index',
            compact('incomes')
        );
    }

    public function create()
    {
        return view('pages.admin.incomes.form'
        );
    }

    public function store(
        IncomeRequest $request
    ) {
        Income::create([

            'user_id' => Auth::id(),

            'title' => $request->title,

            'amount' => $request->amount,

            'description' => $request->description,

            'transaction_date' => $request->transaction_date

        ]);

        return redirect()
            ->route('admin.incomes.index')
            ->with(
                'success',
                'Pemasukan berhasil ditambahkan'
            );
    }

    public function edit(
        Income $income
    ) {
        return view('pages.admin.incomes.form',
            compact('income')
        );
    }

    public function update(
        IncomeRequest $request,
        Income $income
    ) {
        $income->update([

            'title' => $request->title,

            'amount' => $request->amount,

            'description' => $request->description,

            'transaction_date' => $request->transaction_date

        ]);

        return redirect()
            ->route('admin.incomes.index')
            ->with(
                'success',
                'Pemasukan berhasil diupdate'
            );
    }

    public function destroy(
        Income $income
    ) {
        $income->delete();

        return back()->with(
            'success',
            'Pemasukan berhasil dihapus'
        );
    }

    public function show(
        Income $income
    ) {
        abort(404);
    }
}
