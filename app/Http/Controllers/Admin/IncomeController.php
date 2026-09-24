<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\IncomeRequest;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncomeController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
        ]);

        $query = Income::with('user')
            ->when($validated['search'] ?? null, fn ($q, $search) => $q->where(function ($sub) use ($search) {
                $sub->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            }));

        $total = (clone $query)->sum('amount');
        $monthTotal = (clone $query)
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->sum('amount');

        $incomes = $query->latest('transaction_date')->paginate(10)->withQueryString();

        return view('pages.incomes.index', compact('incomes', 'total', 'monthTotal'));
    }

    public function create()
    {
        return view('pages.incomes.form'
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

            'transaction_date' => $request->transaction_date,

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
        return view('pages.incomes.form',
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

            'transaction_date' => $request->transaction_date,

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
