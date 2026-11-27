<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIndikatorRequest;
use App\Http\Requests\UpdateIndikatorRequest;
use App\Models\Indikator;
use App\Models\KelompokJabatan;
use App\Models\Pilar;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class IndikatorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('admin/administrator/indikator/page', $this->pageProps());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('admin/administrator/indikator/page', $this->pageProps('create'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIndikatorRequest $request): RedirectResponse
    {
        Indikator::create($request->validated());

        return back()->with('success', 'Data Indikator berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Indikator $indikator): Response
    {
        return Inertia::render('admin/administrator/indikator/page', $this->pageProps('show', $indikator->loadMissing(['pilar', 'kelompokJabatan', 'behavioral'])));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Indikator $indikator): Response
    {
        return Inertia::render('admin/administrator/indikator/page', $this->pageProps('edit', $indikator->loadMissing(['pilar', 'kelompokJabatan'])));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIndikatorRequest $request, Indikator $indikator): RedirectResponse
    {
        $indikator->update($request->validated());

        return back()->with('success', 'Data Indikator berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Indikator $indikator): RedirectResponse
    {
        $indikator->delete();

        return back()->with('success', 'Data Indikator berhasil dihapus.');
    }

    /**
     * Build the shared props for the Indikator CRUD page.
     *
     * @return array<string, mixed>
     */
    private function pageProps(?string $mode = null, ?Indikator $item = null): array
    {
        $filters = request()->validate([
            'search' => ['nullable', 'string', 'max:250'],
        ]);

        $search = trim($filters['search'] ?? '');

        $indikators = Indikator::query()
            ->select(['id', 'uuid', 'pilar_id', 'kelompok_jabatan_id', 'title', 'defenisi', 'example', 'created_at', 'updated_at'])
            ->with([
                'pilar' => fn (BelongsTo $query) => $query->select(['id', 'title']),
                'kelompokJabatan' => fn (BelongsTo $query) => $query->select(['id', 'nama_kelompok']),
            ])
            ->when($search !== '', function (Builder $query) use ($search): void {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('defenisi', 'like', "%{$search}%")
                    ->orWhereHas('pilar', fn (Builder $relationQuery) => $relationQuery->where('title', 'like', "%{$search}%"))
                    ->orWhereHas('kelompokJabatan', fn (Builder $relationQuery) => $relationQuery->where('nama_kelompok', 'like', "%{$search}%"));
            })
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return [
            'indikators' => $indikators,
            'filters' => ['search' => $search],
            'formOptions' => [
                'pilars' => Pilar::query()
                    ->select(['id', 'title'])
                    ->orderBy('title')
                    ->get()
                    ->map(fn (Pilar $pilar) => [
                        'value' => $pilar->getKey(),
                        'label' => $pilar->title,
                    ])
                    ->all(),
                'kelompokJabatans' => KelompokJabatan::query()
                    ->select(['id', 'nama_kelompok'])
                    ->orderBy('nama_kelompok')
                    ->get()
                    ->map(fn (KelompokJabatan $kelompokJabatan) => [
                        'value' => $kelompokJabatan->getKey(),
                        'label' => $kelompokJabatan->nama_kelompok,
                    ])
                    ->all(),
            ],
            'initialDialog' => $mode === null ? null : [
                'mode' => $mode,
                'item' => $item,
            ],
        ];
    }
}
