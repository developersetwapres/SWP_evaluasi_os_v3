<?php

namespace App\Http\Controllers;

use App\Models\Penilaian;
use App\Http\Requests\StorePenilaianRequest;
use App\Http\Requests\UpdatePenilaianRequest;
use App\Models\Outsourcing;
use App\Models\Penugasan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class PenilaianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Penugasan $penugasan): Response | RedirectResponse
    {
        abort_if(!$penugasan->outsourcings, 404);

        if ($penugasan->evaluators->id !== Auth::id()) {
            return to_route('home');
        }

        $jabatanId = $penugasan->outsourcings->jabatan_id;

        $evaluator = $penugasan->evaluators?->userable;

        if ($penugasan->evaluators?->userable instanceof Outsourcing) {
            $evaluator->load('jabatan');
        }

        $data = [
            'outsourcing' => $penugasan->outsourcings->load(['jabatan', 'biro']),
            'evaluator' => $evaluator,
            'uuidPenugasanPeer' => $penugasan->uuid,
            'tipePenilai' => $penugasan->tipe_penilai,
            'overallNotes' =>  $penugasan->catatan,
        ];

        if ($penugasan->status === 'completed') {
            return Inertia::render('evaluator/viewscore', $data);
        }

        return Inertia::render('evaluator/evaluation-form', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePenilaianRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Penilaian $penilaian)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Penilaian $penilaian)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePenilaianRequest $request, Penilaian $penilaian)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Penilaian $penilaian)
    {
        //
    }

    public function rekaphasil(): Response
    {
        return Inertia::render('admin/rekaphasil/page');
    }
}
