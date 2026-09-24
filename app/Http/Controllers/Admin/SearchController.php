<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Event;
use App\Models\Member;
use App\Models\News;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Halaman hasil pencarian penuh.
     */
    public function search(Request $request)
    {
        $query = trim($request->get('q', $request->get('search', '')));

        if (strlen($query) < 2) {
            return view('pages.search.index', [
                'query' => $query,
                'members' => collect(),
                'events' => collect(),
                'documents' => collect(),
                'news' => collect(),
            ]);
        }

        $results = $this->queryAll($query);

        return view('pages.search.index', [
            'query' => $query,
            ...$results,
        ]);
    }

    /**
     * Kueri seragam untuk pencarian halaman.
     */
    private function queryAll(string $query, int $perGroup = 10): array
    {
        return [
            'members' => Member::where('name', 'like', "%{$query}%")
                ->orWhere('nim', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%")
                ->take($perGroup)
                ->get(),

            'events' => Event::where('title', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ->take($perGroup)
                ->get(),

            'documents' => Document::with('user:id,name')
                ->where('title', 'like', "%{$query}%")
                ->take($perGroup)
                ->get(),

            'news' => News::where('title', 'like', "%{$query}%")
                ->take($perGroup)
                ->get(),
        ];
    }
}
