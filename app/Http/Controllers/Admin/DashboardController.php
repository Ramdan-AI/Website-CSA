<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Member;
use App\Models\Division;
use App\Models\Position;
use App\Models\News;
use App\Models\Event;
use App\Models\Document;
use App\Models\Income;
use App\Models\Expense;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Monthly Income (Current Year)
        $monthlyIncomes = Income::select(
            DB::raw('sum(amount) as total'),
            DB::raw('MONTH(transaction_date) as month')
        )
        ->whereYear('transaction_date', date('Y'))
        ->groupBy('month')
        ->pluck('total', 'month')->toArray();

        // Monthly Expense (Current Year)
        $monthlyExpenses = Expense::select(
            DB::raw('sum(amount) as total'),
            DB::raw('MONTH(transaction_date) as month')
        )
        ->whereYear('transaction_date', date('Y'))
        ->groupBy('month')
        ->pluck('total', 'month')->toArray();

        $chartIncomes = [];
        $chartExpenses = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartIncomes[] = $monthlyIncomes[$i] ?? 0;
            $chartExpenses[] = $monthlyExpenses[$i] ?? 0;
        }

        return view(
            'pages.dashboard',
            [
                'totalUsers' => User::count(),
                'totalMembers' => Member::count(),
                'totalDivisions' => Division::count(),
                'totalPositions' => Position::count(),
                'totalNews' => News::count(),
                'totalEvents' => Event::count(),
                'totalDocuments' => Document::count(),
                'totalIncome' => Income::sum('amount'),
                'totalExpense' => Expense::sum('amount'),
                'balance' => Income::sum('amount') - Expense::sum('amount'),
                'chartIncomes' => $chartIncomes,
                'chartExpenses' => $chartExpenses,
            ]
        );
    }
}
