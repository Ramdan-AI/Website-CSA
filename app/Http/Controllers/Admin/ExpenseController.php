<?php

namespace App\Http\Controllers\Admin;

use App\Models\Expense;
use App\Http\Controllers\Controller;
use App\Http\Requests\ExpenseRequest;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with('user')
            ->latest()
            ->paginate(10);

        return view('pages.admin.expenses.index',
            compact('expenses')
        );
    }

    public function create()
    {
        return view('pages.admin.expenses.form'
        );
    }

    public function store(
        ExpenseRequest $request
    ) {
        Expense::create([

            'user_id' => Auth::id(),

            'title' => $request->title,

            'amount' => $request->amount,

            'description' => $request->description,

            'transaction_date' => $request->transaction_date

        ]);

        return redirect()
            ->route('admin.expenses.index')
            ->with(
                'success',
                'Pengeluaran berhasil ditambahkan'
            );
    }

    public function edit(
        Expense $expense
    ) {
        return view('pages.admin.expenses.form',
            compact('expense')
        );
    }

    public function update(
        ExpenseRequest $request,
        Expense $expense
    ) {
        $expense->update([

            'title' => $request->title,

            'amount' => $request->amount,

            'description' => $request->description,

            'transaction_date' => $request->transaction_date

        ]);

        return redirect()
            ->route('admin.expenses.index')
            ->with(
                'success',
                'Pengeluaran berhasil diupdate'
            );
    }

    public function destroy(
        Expense $expense
    ) {
        $expense->delete();

        return back()->with(
            'success',
            'Pengeluaran berhasil dihapus'
        );
    }

    public function show(
        Expense $expense
    ) {
        abort(404);
    }
}
