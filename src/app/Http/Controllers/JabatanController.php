<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJabatanRequest;
use App\Http\Requests\UpdateJabatanRequest;
use App\Models\Jabatan;
use App\Models\KelompokJabatan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class JabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('admin/administrator/jabatan/page', $this->pageProps());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('admin/administrator/jabatan/page', $this->pageProps('create'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJabatanRequest $request): RedirectResponse
    {
        Jabatan::create($request->validated());

        return back()->with('success', 'Data Jabatan Outsourcing berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Jabatan $jabatan): Response
    {
        return Inertia::render('admin/administrator/jabatan/page', $this->pageProps('edit', $jabatan->loadMissing('kelompokJabatan')));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJabatanRequest $request, Jabatan $jabatan): RedirectResponse
    {
        $jabatan->update($request->validated());

        return back()->with('success', 'Data Jabatan Outsourcing berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jabatan $jabatan): RedirectResponse
    {
        $jabatan->delete();

        return back()->with('success', 'Data Jabatan Outsourcing berhasil dihapus.');
    }

    /**
     * Build the shared props for the Jabatan CRUD page.
     *
     * @return array<string, mixed>
     */
    private function pageProps(?string $mode = null, ?Jabatan $item = null): array
    {
        $filters = request()->validate([
            'search' => ['nullable', 'string', 'max:250'],
        ]);

        $search = trim($filters['search'] ?? '');

        $jabatans = Jabatan::query()
            ->select(['id', 'kelompok_jabatan_id', 'nama_jabatan', 'kode_jabatan', 'created_at', 'updated_at'])
            ->with(['kelompokJabatan' => fn (BelongsTo $query) => $query->select(['id', 'nama_kelompok'])])
            ->when($search !== '', function (Builder $query) use ($search): void {
                $query->where('nama_jabatan', 'like', "%{$search}%")
                    ->orWhere('kode_jabatan', 'like', "%{$search}%")
                    ->orWhereHas('kelompokJabatan', fn (Builder $relationQuery) => $relationQuery->where('nama_kelompok', 'like', "%{$search}%"));
            })
            ->orderBy('nama_jabatan')
            ->paginate(15)
            ->withQueryString();

        return [
            'jabatans' => $jabatans,
            'filters' => ['search' => $search],
            'formOptions' => [
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
