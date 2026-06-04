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

class DashboardController extends Controller
{
    public function index()
    {
        return view(
            'admin.dashboard',
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

                'balance' =>
                Income::sum('amount')
                    - Expense::sum('amount'),

            ]
        );
    }
}
