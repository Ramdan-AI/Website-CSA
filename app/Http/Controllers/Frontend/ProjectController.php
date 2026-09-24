<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'tech' => ['nullable', 'string', 'max:60'],
        ]);

        $projects = Project::query()
            ->select(['id', 'submitted_by', 'title', 'slug', 'summary', 'description', 'thumbnail', 'images', 'tech_stack', 'repo_url', 'demo_url', 'featured', 'created_at'])
            ->with(['submitter:id,name', 'members:id,name'])
            ->published()
            ->when($validated['q'] ?? null, fn ($query, $search) =>
                $query->where(function ($sub) use ($search) {
                    $sub->where('title', 'like', "%{$search}%")
                        ->orWhere('summary', 'like', "%{$search}%");
                })
            )
            ->when($validated['tech'] ?? null, fn ($query, $tech) =>
                $query->whereJsonContains('tech_stack', $tech)
            )
            ->orderByDesc('featured')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $techList = $this->techList();

        return view('pages.public.projects', [
            'projects' => $projects,
            'techList' => $techList,
            'q' => $validated['q'] ?? '',
            'activeTech' => $validated['tech'] ?? '',
        ]);
    }

    public function show(string $slug)
    {
        $project = Project::query()
            ->with(['submitter:id,name', 'members:id,name,division_id', 'members.division:id,name'])
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        $related = Project::query()
            ->select(['id', 'title', 'slug', 'thumbnail', 'images', 'tech_stack', 'created_at'])
            ->with(['submitter:id,name', 'members:id,name'])
            ->published()
            ->where('id', '!=', $project->id)
            ->latest()
            ->take(3)
            ->get();

        return view('pages.public.project-show', compact('project', 'related'));
    }

    /**
     * Daftar teknologi unik yang dipakai proyek terbit, untuk filter.
     */
    private function techList(): array
    {
        return Project::query()
            ->published()
            ->whereNotNull('tech_stack')
            ->pluck('tech_stack')
            ->flatten()
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->take(20)
            ->all();
    }
}
