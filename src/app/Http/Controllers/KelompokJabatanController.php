<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKelompokJabatanRequest;
use App\Http\Requests\UpdateKelompokJabatanRequest;
use App\Models\KelompokJabatan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class KelompokJabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('admin/administrator/kelompok-jabatan/page', $this->pageProps());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('admin/administrator/kelompok-jabatan/page', $this->pageProps('create'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKelompokJabatanRequest $request): RedirectResponse
    {
        KelompokJabatan::create($request->validated());

        return back()->with('success', 'Data Kelompok Jabatan berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(KelompokJabatan $kelompokJabatan): Response
    {
        return Inertia::render('admin/administrator/kelompok-jabatan/page', $this->pageProps('show', $kelompokJabatan->loadMissing(['jabatan', 'indikator'])));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KelompokJabatan $kelompokJabatan): Response
    {
        return Inertia::render('admin/administrator/kelompok-jabatan/page', $this->pageProps('edit', $kelompokJabatan));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKelompokJabatanRequest $request, KelompokJabatan $kelompokJabatan): RedirectResponse
    {
        $kelompokJabatan->update($request->validated());

        return back()->with('success', 'Data Kelompok Jabatan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KelompokJabatan $kelompokJabatan): RedirectResponse
    {
        $kelompokJabatan->delete();

        return back()->with('success', 'Data Kelompok Jabatan berhasil dihapus.');
    }

    /**
     * Build the shared props for the Kelompok Jabatan CRUD page.
     *
     * @return array<string, mixed>
     */
    private function pageProps(?string $mode = null, ?KelompokJabatan $item = null): array
    {
        $filters = request()->validate([
            'search' => ['nullable', 'string', 'max:250'],
        ]);

        $search = trim($filters['search'] ?? '');

        $kelompokJabatans = KelompokJabatan::query()
            ->select(['id', 'uuid', 'nama_kelompok', 'created_at', 'updated_at'])
            ->withCount(['jabatan', 'indikator'])
            ->when($search !== '', fn (Builder $query) => $query->where('nama_kelompok', 'like', "%{$search}%"))
            ->orderBy('nama_kelompok')
            ->paginate(15)
            ->withQueryString();

        return [
            'kelompokJabatans' => $kelompokJabatans,
            'filters' => ['search' => $search],
            'formOptions' => [],
            'initialDialog' => $mode === null ? null : [
                'mode' => $mode,
                'item' => $item,
            ],
        ];
    }
}
