<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Event;
use App\Models\Document;
use App\Models\News;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = trim($request->get('q', $request->get('search', '')));

        if (strlen($query) < 2) {
            return redirect()->back();
        }

        $members = Member::where('name', 'like', "%{$query}%")
            ->orWhere('nim', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->take(10)
            ->get();

        $events = Event::where('title', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->take(10)
            ->get();

        $documents = Document::where('title', 'like', "%{$query}%")
            ->take(10)
            ->get();

        $news = News::where('title', 'like', "%{$query}%")
            ->take(10)
            ->get();

        return view('pages.search.index', compact('query', 'members', 'events', 'documents', 'news'));
    }
}
