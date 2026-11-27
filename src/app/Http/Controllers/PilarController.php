<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePilarRequest;
use App\Http\Requests\UpdatePilarRequest;
use App\Models\BobotSkor;
use App\Models\Pilar;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PilarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('admin/administrator/pilar/page', $this->pageProps());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('admin/administrator/pilar/page', $this->pageProps('create'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePilarRequest $request): RedirectResponse
    {
        Pilar::create($request->validated());

        return back()->with('success', 'Data Pilar berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pilar $pilar): Response
    {
        return Inertia::render('admin/administrator/pilar/page', $this->pageProps('edit', $pilar->loadMissing('bobotSkor')));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePilarRequest $request, Pilar $pilar): RedirectResponse
    {
        $pilar->update($request->validated());

        return back()->with('success', 'Data Pilar berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pilar $pilar): RedirectResponse
    {
        $pilar->delete();

        return back()->with('success', 'Data Pilar berhasil dihapus.');
    }

    /**
     * Build the shared props for the Pilar CRUD page.
     *
     * @return array<string, mixed>
     */
    private function pageProps(?string $mode = null, ?Pilar $item = null): array
    {
        $filters = request()->validate([
            'search' => ['nullable', 'string', 'max:250'],
        ]);

        $search = trim($filters['search'] ?? '');

        $pilars = Pilar::query()
            ->select(['id', 'uuid', 'title', 'bobot_skor_id', 'created_at', 'updated_at'])
            ->with(['bobotSkor' => fn(BelongsTo $query) => $query->select(['id', 'title', 'kode_bobot', 'bobot'])])
            ->when($search !== '', function (Builder $query) use ($search): void {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhereHas('bobotSkor', fn(Builder $relationQuery) => $relationQuery->where('title', 'like', "%{$search}%"));
            })
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return [
            'pilars' => $pilars,
            'filters' => ['search' => $search],
            'formOptions' => [
                'bobotSkors' => BobotSkor::query()
                    ->select(['id', 'title'])
                    ->orderBy('title')
                    ->get()
                    ->map(fn(BobotSkor $bobotSkor) => [
                        'value' => $bobotSkor->getKey(),
                        'label' => $bobotSkor->title,
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
