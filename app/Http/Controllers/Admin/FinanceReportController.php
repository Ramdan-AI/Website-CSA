<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceReportController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
        ]);

        $year = (int) ($validated['year'] ?? now()->year);

        $monthlyIncomes = $this->monthlyTotals(Income::query(), $year);
        $monthlyExpenses = $this->monthlyTotals(Expense::query(), $year);

        // Susun 12 bulan lengkap beserta saldo berjalan.
        $rows = [];
        $running = (float) Income::whereYear('transaction_date', '<', $year)->sum('amount')
            - (float) Expense::whereYear('transaction_date', '<', $year)->sum('amount');

        for ($month = 1; $month <= 12; $month++) {
            $income = (float) ($monthlyIncomes[$month] ?? 0);
            $expense = (float) ($monthlyExpenses[$month] ?? 0);
            $running += $income - $expense;

            $rows[] = [
                'month' => $month,
                'label' => now()->setDate($year, $month, 1)->translatedFormat('F'),
                'income' => $income,
                'expense' => $expense,
                'net' => $income - $expense,
                'balance' => $running,
            ];
        }

        $totalIncome = array_sum(array_column($rows, 'income'));
        $totalExpense = array_sum(array_column($rows, 'expense'));

        // Tahun yang tersedia untuk filter.
        $years = collect()
            ->merge(Income::query()->selectRaw('YEAR(transaction_date) as y')->distinct()->pluck('y'))
            ->merge(Expense::query()->selectRaw('YEAR(transaction_date) as y')->distinct()->pluck('y'))
            ->push(now()->year)
            ->filter()
            ->unique()
            ->sortDesc()
            ->values();

        return view('pages.finance-report.index', [
            'rows' => $rows,
            'year' => $year,
            'years' => $years,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'balance' => $totalIncome - $totalExpense,
            'openingBalance' => $rows[0]['balance'] - $rows[0]['net'],
        ]);
    }

    /**
     * Total per bulan untuk satu jenis transaksi dalam satu tahun.
     */
    private function monthlyTotals($query, int $year): array
    {
        return $query
            ->select(
                DB::raw('SUM(amount) as total'),
                DB::raw('MONTH(transaction_date) as month')
            )
            ->whereYear('transaction_date', $year)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();
    }

    /**
     * Ekspor laporan tahunan sebagai CSV.
     */
    public function export(Request $request)
    {
        $validated = $request->validate([
            'year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
        ]);

        $year = (int) ($validated['year'] ?? now()->year);

        $monthlyIncomes = $this->monthlyTotals(Income::query(), $year);
        $monthlyExpenses = $this->monthlyTotals(Expense::query(), $year);

        $filename = "laporan-keuangan-{$year}.csv";

        return response()->streamDownload(function () use ($year, $monthlyIncomes, $monthlyExpenses) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Bulan', 'Pemasukan', 'Pengeluaran', 'Selisih']);

            for ($month = 1; $month <= 12; $month++) {
                $income = (float) ($monthlyIncomes[$month] ?? 0);
                $expense = (float) ($monthlyExpenses[$month] ?? 0);

                fputcsv($handle, [
                    now()->setDate($year, $month, 1)->translatedFormat('F'),
                    $income,
                    $expense,
                    $income - $expense,
                ]);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
