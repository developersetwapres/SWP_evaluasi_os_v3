<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBehavioralRequest;
use App\Http\Requests\UpdateBehavioralRequest;
use App\Models\Behavioral;
use App\Models\Indikator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class BehavioralController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('admin/administrator/behavioral/page', $this->pageProps());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('admin/administrator/behavioral/page', $this->pageProps('create'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBehavioralRequest $request): RedirectResponse
    {
        Behavioral::create($request->validated());

        return back()->with('success', 'Data Behavioral berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Behavioral $behavioral): Response
    {
        return Inertia::render('admin/administrator/behavioral/page', $this->pageProps('show', $behavioral->loadMissing('indikator')));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Behavioral $behavioral): Response
    {
        return Inertia::render('admin/administrator/behavioral/page', $this->pageProps('edit', $behavioral->loadMissing('indikator')));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBehavioralRequest $request, Behavioral $behavioral): RedirectResponse
    {
        $behavioral->update($request->validated());

        return back()->with('success', 'Data Behavioral berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Behavioral $behavioral): RedirectResponse
    {
        $behavioral->delete();

        return back()->with('success', 'Data Behavioral berhasil dihapus.');
    }

    /**
     * Build the shared props for the Behavioral CRUD page.
     *
     * @return array<string, mixed>
     */
    private function pageProps(?string $mode = null, ?Behavioral $item = null): array
    {
        $filters = request()->validate([
            'search' => ['nullable', 'string', 'max:250'],
        ]);

        $search = trim($filters['search'] ?? '');

        $behaviorals = Behavioral::query()
            ->select(['id', 'uuid', 'indikator_id', 'behavioral', 'skor', 'created_at', 'updated_at'])
            ->with(['indikator' => fn (BelongsTo $query) => $query->select(['id', 'title'])])
            ->when($search !== '', function (Builder $query) use ($search): void {
                $query->where('behavioral', 'like', "%{$search}%")
                    ->orWhere('skor', 'like', "%{$search}%")
                    ->orWhereHas('indikator', fn (Builder $relationQuery) => $relationQuery->where('title', 'like', "%{$search}%"));
            })
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return [
            'behaviorals' => $behaviorals,
            'filters' => ['search' => $search],
            'formOptions' => [
                'indikators' => Indikator::query()
                    ->select(['id', 'title'])
                    ->orderBy('title')
                    ->get()
                    ->map(fn (Indikator $indikator) => [
                        'value' => $indikator->getKey(),
                        'label' => $indikator->title,
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
