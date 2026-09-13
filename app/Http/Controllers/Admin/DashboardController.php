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
        $user = auth()->user();
        
        // Return member dashboard if user has anggota role
        if ($user->role->slug === 'anggota') {
            return $this->memberDashboard();
        }

        // Return admin dashboard for other roles
        return $this->adminDashboard();
    }

    private function memberDashboard()
    {
        $user = auth()->user();
        $member = $user->member;

        // Get upcoming events
        $upcomingEvents = Event::where('event_date', '>=', now()->toDateString())
            ->where('status', 'published')
            ->orderBy('event_date', 'asc')
            ->take(5)
            ->get();

        // Get member attendance stats
        $attendanceStats = [
            'total' => \App\Models\Attendance::where('member_id', $member->id)->count(),
            'present' => \App\Models\Attendance::where('member_id', $member->id)->where('status', 'present')->count(),
            'absent' => \App\Models\Attendance::where('member_id', $member->id)->where('status', 'absent')->count(),
        ];
        
        $attendanceRate = $attendanceStats['total'] > 0 
            ? round(($attendanceStats['present'] / $attendanceStats['total']) * 100, 1) 
            : 0;

        // Get recent attendances
        $recentAttendances = \App\Models\Attendance::with('event')
            ->where('member_id', $member->id)
            ->latest()
            ->take(5)
            ->get();

        // Get division members
        $divisionMembers = Member::where('division_id', $member->division_id)
            ->where('id', '!=', $member->id)
            ->take(5)
            ->get();

        return view('pages.dashboard-member', compact(
            'user',
            'member',
            'upcomingEvents',
            'attendanceStats',
            'attendanceRate',
            'recentAttendances',
            'divisionMembers'
        ));
    }

    private function adminDashboard()
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
