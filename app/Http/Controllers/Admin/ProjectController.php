<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    /**
     * Daftar proyek. Pengguna dengan manage-projects melihat semua,
     * anggota lain hanya melihat proyek yang mereka unggah.
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'scope' => ['nullable', 'in:mine,all'],
        ]);

        $canManageAll = Gate::allows('manage-projects');
        $scope = $validated['scope'] ?? ($canManageAll ? 'all' : 'mine');

        if (!$canManageAll) {
            $scope = 'mine';
        }

        $projects = Project::query()
            ->with(['submitter:id,name', 'members:id,name'])
            ->when($scope === 'mine', fn ($query) => $query->where('submitted_by', Auth::id()))
            ->when($validated['search'] ?? null, fn ($query, $search) =>
                $query->where(function ($sub) use ($search) {
                    $sub->where('title', 'like', "%{$search}%")
                        ->orWhere('summary', 'like', "%{$search}%");
                })
            )
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('pages.showcase.index', [
            'projects' => $projects,
            'scope' => $scope,
            'canManageAll' => $canManageAll,
        ]);
    }

    public function create()
    {
        return view('pages.showcase.form', [
            'project' => null,
            'members' => $this->memberOptions(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        $project = new Project($data);
        $project->submitted_by = Auth::id();
        $project->save();

        $this->syncMembers($project, $request);

        return redirect()
            ->route('admin.showcase.index')
            ->with('success', 'Proyek berhasil ditambahkan.');
    }

    public function edit(Project $project)
    {
        $this->authorizeProject($project);

        return view('pages.showcase.form', [
            'project' => $project->load('members:id'),
            'members' => $this->memberOptions(),
        ]);
    }

    public function update(Request $request, Project $project)
    {
        $this->authorizeProject($project);

        $data = $this->validated($request, $project);

        $project->fill($data)->save();

        $this->syncMembers($project, $request);

        return redirect()
            ->route('admin.showcase.index')
            ->with('success', 'Proyek berhasil diperbarui.');
    }

    public function destroy(Project $project)
    {
        $this->authorizeProject($project);

        if ($project->thumbnail) {
            Storage::disk('public')->delete($project->thumbnail);
        }

        foreach ($project->images ?? [] as $image) {
            Storage::disk('public')->delete($image);
        }

        $project->delete();

        return redirect()
            ->route('admin.showcase.index')
            ->with('success', 'Proyek berhasil dihapus.');
    }

    /**
     * Pratinjau publik: menampilkan proyek apa adanya seperti di landing page,
     * termasuk proyek yang masih berstatus draf.
     */
    public function preview(Project $project)
    {
        $this->authorizeProject($project);

        return view('pages.public.project-show', [
            'project' => $project->load([
                'submitter:id,name',
                'members:id,name,division_id',
                'members.division:id,name',
            ]),
            'related' => collect(),
            'isPreview' => true,
        ]);
    }

    // ==================== HELPERS ====================

    private function validated(Request $request, ?Project $project = null): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'summary' => ['nullable', 'string', 'max:300'],
            'description' => ['nullable', 'string', 'max:20000'],
            'tech_stack' => ['nullable', 'string', 'max:300'],
            'repo_url' => ['nullable', 'url', 'max:255'],
            'demo_url' => ['nullable', 'url', 'max:255'],
            'status' => ['required', 'in:draft,published'],
            'featured' => ['nullable', 'boolean'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'images.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'member_ids' => ['nullable', 'array'],
            'member_ids.*' => ['integer', 'exists:members,id'],
        ]);

        $payload = [
            'title' => $validated['title'],
            'summary' => $validated['summary'] ?? null,
            'description' => $validated['description'] ?? null,
            'repo_url' => $validated['repo_url'] ?? null,
            'demo_url' => $validated['demo_url'] ?? null,
            'status' => $validated['status'],
            'featured' => $request->boolean('featured'),
            'tech_stack' => $this->parseTechStack($validated['tech_stack'] ?? null),
        ];

        if ($request->hasFile('thumbnail')) {
            if ($project?->thumbnail) {
                Storage::disk('public')->delete($project->thumbnail);
            }
            $payload['thumbnail'] = $request->file('thumbnail')->store('projects', 'public');
        }

        if ($request->hasFile('images')) {
            $stored = collect($request->file('images'))
                ->map(fn ($file) => $file->store('projects', 'public'))
                ->all();

            $payload['images'] = $project
                ? array_values(array_merge($project->images ?? [], $stored))
                : $stored;
        }

        return $payload;
    }

    /**
     * "Laravel, Vue, MySQL" -> ["Laravel", "Vue", "MySQL"]
     */
    private function parseTechStack(?string $raw): array
    {
        if (!$raw) {
            return [];
        }

        return collect(explode(',', $raw))
            ->map(fn ($item) => trim($item))
            ->filter()
            ->unique()
            ->take(12)
            ->values()
            ->all();
    }

    private function syncMembers(Project $project, Request $request): void
    {
        $ids = collect($request->input('member_ids', []))
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        $project->members()->sync($ids);
    }

    private function memberOptions()
    {
        return Member::query()
            ->select(['id', 'name', 'division_id'])
            ->with('division:id,name')
            ->orderBy('name')
            ->get();
    }

    /**
     * Pemilik proyek atau pemegang manage-projects yang boleh mengubah.
     */
    private function authorizeProject(Project $project): void
    {
        $isOwner = $project->submitted_by === Auth::id();

        abort_unless($isOwner || Gate::allows('manage-projects'), 403);
    }
}
