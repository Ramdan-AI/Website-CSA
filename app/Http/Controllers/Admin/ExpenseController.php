<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExpenseRequest;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
        ]);

        $query = Expense::with('user')
            ->when($validated['search'] ?? null, fn ($q, $search) => $q->where(function ($sub) use ($search) {
                $sub->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            }));

        $total = (clone $query)->sum('amount');
        $monthTotal = (clone $query)
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->sum('amount');

        $expenses = $query->latest('transaction_date')->paginate(10)->withQueryString();

        return view('pages.expenses.index', compact('expenses', 'total', 'monthTotal'));
    }

    public function create()
    {
        return view('pages.expenses.form'
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

            'transaction_date' => $request->transaction_date,

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
        return view('pages.expenses.form',
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

            'transaction_date' => $request->transaction_date,

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
