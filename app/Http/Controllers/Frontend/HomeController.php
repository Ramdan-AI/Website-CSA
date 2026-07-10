<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Division;
use App\Models\News;
use App\Models\Setting;

class HomeController extends Controller
{
    public function index()
    {
        $members = Member::with(['division', 'position'])
            ->where('is_active', true)
            ->get();

        $divisions = Division::all();

        $news = News::with(['category', 'author'])
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        $settings = Setting::all()->pluck('value', 'key');

        return view('pages.public.home', compact('members', 'divisions', 'news', 'settings'));
    }
}
