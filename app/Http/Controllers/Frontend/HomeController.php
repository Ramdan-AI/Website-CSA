<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Member;
use App\Models\Division;
use App\Models\News;
use App\Models\Setting;
use App\Models\Gallery;
use App\Models\Project;

class HomeController extends Controller
{
    public function index()
    {
        $members = Member::with(['division', 'position'])
            ->where('is_active', true)
            ->get();

        $divisions = Division::active()->withCount('members')->get();

        $news = News::with(['category', 'author'])
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();

        // Showcase proyek untuk section "Karya anggota" di landing page.
        $projects = Project::query()
            ->select(['id', 'submitted_by', 'title', 'slug', 'summary', 'description', 'thumbnail', 'images', 'tech_stack', 'repo_url', 'demo_url', 'featured', 'created_at'])
            ->with(['submitter:id,name', 'members:id,name'])
            ->published()
            ->orderByDesc('featured')
            ->latest()
            ->take(6)
            ->get();

        $setting = Setting::first();

        // Hero photos: admin-picked (is_hero). Fallback to latest galleries
        // so the landing page never renders an empty hero.
        $photos = Gallery::where('is_hero', true)->take(8)->get();

        if ($photos->isEmpty()) {
            $photos = Gallery::latest()->take(8)->get();
        }

        // Lightest files first: the bento's first (largest) tile gets the
        // cheapest image, so the hero paints fast even before thumbnails exist.
        $photos = $photos
            ->sortBy(fn ($p) => (int) Storage::disk('public')->size($p->image))
            ->values();

        // Realtime stats for the dark stats band between hero and about.
        $totalAnggota = Member::where('is_active', true)->count();
        $totalDivisi = Division::count();
        $totalPengurus = Member::where('is_active', true)
            ->whereHas('position', fn ($q) => $q->where('name', '!=', 'Anggota'))
            ->count();

        // Anggota & pengurus dibulatkan ke bawah ke kelipatan 10 (mis. 15 -> "10+").
        $statAnggota = (int) floor($totalAnggota / 10) * 10;
        $statDivisi = $totalDivisi;
        $statPengurus = (int) floor($totalPengurus / 10) * 10;

        return view('pages.public.home', compact(
            'members',
            'divisions',
            'news',
            'projects',
            'setting',
            'photos',
            'statAnggota',
            'statDivisi',
            'statPengurus',
        ));
    }

    public function anggota(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $divisionSlug = trim((string) $request->query('divisi', ''));

        $members = Member::active()
            ->with(['position', 'division'])
            ->when($q !== '', fn ($query) => $query->where('name', 'like', "%{$q}%"))
            ->when($divisionSlug !== '', fn ($query) => $query->whereHas(
                'division',
                fn ($q2) => $q2->where('slug', $divisionSlug)
            ))
            ->orderBy('name')
            ->paginate(24)
            ->withQueryString();

        $divisions = Division::active()->get();
        $totalMembers = Member::active()->count();

        return view('pages.public.anggota', compact('members', 'divisions', 'q', 'divisionSlug', 'totalMembers'));
    }
}
