<?php

namespace App\Http\Controllers\Admin;

use App\Models\Attendance;
use App\Models\Division;
use App\Models\Document;
use App\Models\Event;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Letter;
use App\Models\Member;
use App\Models\Minute;
use App\Models\News;
use App\Models\Position;
use App\Models\Project;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return match ($user->role?->slug) {
            'sekretaris' => $this->secretaryDashboard($user),
            'bendahara' => $this->treasurerDashboard($user),
            'ketua-divisi', 'wakil-ketua-divisi' => $this->divisionLeaderDashboard($user),
            'anggota' => $this->memberDashboard($user),
            default => $this->leadershipDashboard($user),
        };
    }

    // ==================== PIMPINAN (ketua, wakil, super-admin) ====================

    private function leadershipDashboard(User $user)
    {
        $currentYear = now()->year;

        $monthlyIncomes = $this->monthlyTotals(Income::query(), $currentYear);
        $monthlyExpenses = $this->monthlyTotals(Expense::query(), $currentYear);

        $chartIncomes = [];
        $chartExpenses = [];
        for ($month = 1; $month <= 12; $month++) {
            $chartIncomes[] = $monthlyIncomes[$month] ?? 0;
            $chartExpenses[] = $monthlyExpenses[$month] ?? 0;
        }

        $totalIncome = (float) Income::sum('amount');
        $totalExpense = (float) Expense::sum('amount');

        // Kehadiran bulan ini sebagai indikator kesehatan organisasi.
        $attendanceThisMonth = Attendance::whereHas(
            'event',
            fn ($query) => $query->whereMonth('event_date', now()->month)->whereYear('event_date', $currentYear)
        );

        return view('pages.dashboard', [
            // Statistik utama
            'totalMembers' => Member::where('is_active', true)->count(),
            'totalDivisions' => Division::count(),
            'totalPositions' => Position::count(),
            'totalEvents' => Event::count(),
            'activeEvents' => Event::where('event_date', '>=', now()->toDateString())->count(),

            // Keuangan
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'balance' => $totalIncome - $totalExpense,
            'chartIncomes' => $chartIncomes,
            'chartExpenses' => $chartExpenses,

            // Kehadiran bulan ini
            'attendanceTotal' => (clone $attendanceThisMonth)->count(),
            'attendancePresent' => (clone $attendanceThisMonth)->where('status', 'present')->count(),

            // Konten
            'totalNews' => News::count(),
            'totalDocuments' => Document::count(),

            // Proyek
            'totalProjects' => Project::count(),
        ]);
    }

    // ==================== SEKRETARIS ====================

    private function secretaryDashboard(User $user)
    {
        return view('pages.dashboard-secretary', [
            'totalLetters' => Letter::count(),
            'recentLetters' => Letter::with('creator:id,name')->latest('letter_date')->take(5)->get(),
            'totalMinutes' => Minute::count(),
            'recentMinutes' => Minute::withCount('attendees')->latest('meeting_date')->take(5)->get(),
            'totalNews' => News::count(),
            'draftNews' => News::where('status', 'draft')->count(),
            'totalDocuments' => Document::count(),
            'recentDocuments' => Document::with('user:id,name')->latest()->take(5)->get(),
            'attendanceThisMonth' => Attendance::whereHas(
                'event',
                fn ($query) => $query->whereMonth('event_date', now()->month)
            )->where('status', 'present')->count(),
        ]);
    }

    // ==================== BENDAHARA ====================

    private function treasurerDashboard(User $user)
    {
        $currentYear = now()->year;

        $monthlyIncomes = $this->monthlyTotals(Income::query(), $currentYear);
        $monthlyExpenses = $this->monthlyTotals(Expense::query(), $currentYear);

        $chartIncomes = [];
        $chartExpenses = [];
        for ($month = 1; $month <= 12; $month++) {
            $chartIncomes[] = $monthlyIncomes[$month] ?? 0;
            $chartExpenses[] = $monthlyExpenses[$month] ?? 0;
        }

        $totalIncome = (float) Income::sum('amount');
        $totalExpense = (float) Expense::sum('amount');

        $incomeThisMonth = (float) Income::whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', $currentYear)->sum('amount');
        $expenseThisMonth = (float) Expense::whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', $currentYear)->sum('amount');

        return view('pages.dashboard-treasurer', [
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'balance' => $totalIncome - $totalExpense,
            'incomeThisMonth' => $incomeThisMonth,
            'expenseThisMonth' => $expenseThisMonth,
            'chartIncomes' => $chartIncomes,
            'chartExpenses' => $chartExpenses,
            'recentIncomes' => Income::with('user:id,name')->latest('transaction_date')->take(5)->get(),
            'recentExpenses' => Expense::with('user:id,name')->latest('transaction_date')->take(5)->get(),
        ]);
    }

    // ==================== KETUA / WAKIL DIVISI ====================

    private function divisionLeaderDashboard(User $user)
    {
        $member = $user->member;
        $division = $member?->division;

        if (!$division) {
            return view('pages.dashboard-division', [
                'division' => null,
                'members' => collect(),
                'upcomingEvents' => collect(),
                'rows' => collect(),
                'divisionAverage' => 0,
            ]);
        }

        $members = Member::where('division_id', $division->id)->orderBy('name')->get();
        $memberIds = $members->pluck('id');

        // Rekap kehadiran divisi.
        $summary = Attendance::query()
            ->selectRaw('member_id')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw("SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present")
            ->whereIn('member_id', $memberIds)
            ->groupBy('member_id')
            ->get()
            ->keyBy('member_id');

        $rows = $members->map(function (Member $m) use ($summary) {
            $stat = $summary->get($m->id);
            $total = (int) ($stat->total ?? 0);
            $present = (int) ($stat->present ?? 0);

            return [
                'member' => $m,
                'total' => $total,
                'present' => $present,
                'rate' => $total > 0 ? round(($present / $total) * 100, 1) : 0,
            ];
        });

        $upcomingEvents = Event::where('event_date', '>=', now()->toDateString())
            ->where('status', 'published')
            ->orderBy('event_date')
            ->take(5)
            ->get();

        return view('pages.dashboard-division', [
            'division' => $division,
            'members' => $members,
            'upcomingEvents' => $upcomingEvents,
            'rows' => $rows,
            'divisionAverage' => $rows->count() > 0 ? round($rows->avg('rate'), 1) : 0,
        ]);
    }

    // ==================== ANGGOTA ====================

    private function memberDashboard(User $user)
    {
        $member = $user->member;

        // Pengurus yang tidak punya profil anggota tetap bisa membuka dashboard.
        if (!$member) {
            return view('pages.dashboard-member', [
                'user' => $user,
                'member' => null,
                'upcomingEvents' => Event::where('event_date', '>=', now()->toDateString())
                    ->where('status', 'published')->orderBy('event_date')->take(5)->get(),
                'attendanceStats' => ['total' => 0, 'present' => 0, 'absent' => 0],
                'attendanceRate' => 0,
                'recentAttendances' => collect(),
                'myProjects' => Project::where('submitted_by', $user->id)->latest()->take(4)->get(),
            ]);
        }

        $upcomingEvents = Event::where('event_date', '>=', now()->toDateString())
            ->where('status', 'published')
            ->orderBy('event_date')
            ->take(5)
            ->get();

        $attendanceQuery = Attendance::where('member_id', $member->id);

        $attendanceStats = [
            'total' => (clone $attendanceQuery)->count(),
            'present' => (clone $attendanceQuery)->where('status', 'present')->count(),
            'absent' => (clone $attendanceQuery)->where('status', 'absent')->count(),
        ];

        $attendanceRate = $attendanceStats['total'] > 0
            ? round(($attendanceStats['present'] / $attendanceStats['total']) * 100, 1)
            : 0;

        $recentAttendances = Attendance::with('event')
            ->where('member_id', $member->id)
            ->latest()
            ->take(5)
            ->get();

        return view('pages.dashboard-member', [
            'user' => $user,
            'member' => $member,
            'upcomingEvents' => $upcomingEvents,
            'attendanceStats' => $attendanceStats,
            'attendanceRate' => $attendanceRate,
            'recentAttendances' => $recentAttendances,
            'myProjects' => Project::where('submitted_by', $user->id)->latest()->take(4)->get(),
        ]);
    }

    // ==================== HELPERS ====================

    /**
     * Total per bulan untuk satu jenis transaksi dalam satu tahun.
     */
    private function monthlyTotals($query, int $year): array    {
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
}
