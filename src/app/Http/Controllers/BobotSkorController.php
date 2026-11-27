<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBobotSkorRequest;
use App\Http\Requests\UpdateBobotSkorRequest;
use App\Models\BobotSkor;
use App\Models\Siklus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class BobotSkorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('admin/administrator/bobot-nilai/page', $this->pageProps());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('admin/administrator/bobot-nilai/page', $this->pageProps('create'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBobotSkorRequest $request): RedirectResponse
    {
        BobotSkor::create($request->validated());

        return back()->with('success', 'Data Bobot Nilai berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BobotSkor $bobotSkor): Response
    {
        return Inertia::render('admin/administrator/bobot-nilai/page', $this->pageProps('edit', $bobotSkor->loadMissing('siklus')));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBobotSkorRequest $request, BobotSkor $bobotSkor): RedirectResponse
    {
        $bobotSkor->update($request->validated());

        return back()->with('success', 'Data Bobot Nilai berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BobotSkor $bobotSkor): RedirectResponse
    {
        $bobotSkor->delete();

        return back()->with('success', 'Data Bobot Nilai berhasil dihapus.');
    }

    /**
     * Build the shared props for the Bobot Nilai CRUD page.
     *
     * @return array<string, mixed>
     */
    private function pageProps(?string $mode = null, ?BobotSkor $item = null): array
    {
        $filters = request()->validate([
            'search' => ['nullable', 'string', 'max:250'],
        ]);

        $search = trim((string) ($filters['search'] ?? ''));

        $bobotSkors = BobotSkor::query()
            ->select(['id', 'siklus_id', 'title', 'kode_bobot', 'bobot', 'created_at', 'updated_at'])
            ->with(['siklus' => fn (BelongsTo $query) => $query->select(['id', 'title'])])
            ->when($search !== '', function (Builder $query) use ($search): void {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('kode_bobot', 'like', "%{$search}%")
                    ->orWhereHas('siklus', fn (Builder $relationQuery) => $relationQuery->where('title', 'like', "%{$search}%"));
            })
            ->orderBy('kode_bobot')
            ->paginate(15)
            ->withQueryString();

        return [
            'bobotSkors' => $bobotSkors,
            'filters' => ['search' => $search],
            'formOptions' => [
                'sikluses' => Siklus::query()
                    ->select(['id', 'title'])
                    ->orderByDesc('id')
                    ->get()
                    ->map(fn (Siklus $siklus) => [
                        'value' => $siklus->getKey(),
                        'label' => $siklus->title,
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
