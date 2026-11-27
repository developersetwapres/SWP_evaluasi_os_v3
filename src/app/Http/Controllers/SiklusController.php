<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSiklusRequest;
use App\Http\Requests\UpdateSiklusRequest;
use App\Models\Siklus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class SiklusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('admin/administrator/siklus-semester/page', $this->pageProps());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('admin/administrator/siklus-semester/page', $this->pageProps('create'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSiklusRequest $request): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($data): void {
            if ((bool) $data['is_active']) {
                Siklus::query()
                    ->where('is_active', true)
                    ->update(['is_active' => false]);
            }

            Siklus::create($data);
        });

        return back()->with('success', 'Data Siklus Semester berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Siklus $siklus): Response
    {
        return Inertia::render('admin/administrator/siklus-semester/page', $this->pageProps('edit', $siklus));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSiklusRequest $request, Siklus $siklus): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($data, $siklus): void {
            if ((bool) $data['is_active']) {
                Siklus::query()
                    ->where('is_active', true)
                    ->where('id', '!=', $siklus->getKey())
                    ->update(['is_active' => false]);
            }

            $siklus->update($data);
        });

        return back()->with('success', 'Data Siklus Semester berhasil diperbarui.');
    }

    /**
     * Build the shared props for the Siklus Semester CRUD page.
     *
     * @return array<string, mixed>
     */
    private function pageProps(?string $mode = null, ?Siklus $item = null): array
    {
        $filters = request()->validate([
            'search' => ['nullable', 'string', 'max:250'],
        ]);

        $search = trim((string) ($filters['search'] ?? ''));

        $sikluses = Siklus::query()
            ->select(['id', 'uuid', 'title', 'tanggal_mulai', 'tanggal_selesai', 'is_active', 'created_at', 'updated_at'])
            ->when($search !== '', fn (Builder $query) => $query->where('title', 'like', "%{$search}%"))
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return [
            'sikluses' => $sikluses,
            'filters' => ['search' => $search],
            'formOptions' => [],
            'initialDialog' => $mode === null ? null : [
                'mode' => $mode,
                'item' => $item,
            ],
        ];
    }
}
