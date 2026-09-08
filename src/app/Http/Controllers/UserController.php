<?php

namespace App\Http\Controllers;

use App\Models\Biro;
use App\Models\Jabatan;
use App\Models\MasterPegawai;
use App\Models\Outsourcing;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index($user): Response
    {
        if ($user == 'outsourcings') {
            $initialUsers = Outsourcing::with([
                'biro',
                'jabatan',
                'user' => fn($q) =>
                $q->withCount([
                    'penugasan as penugasan_count' => fn($q) =>
                    $q->where('siklus_id', 1)
                ])
            ])
                ->orderBy('name', 'asc')
                ->get()
                ->map(fn($o) => [
                    'id'      => $o->uuid,
                    'name'    => $o->name,
                    'image'   => $o->image,
                    'nip'     => $o->user?->nip,
                    'email'   => $o->user?->email,
                    'role'    => $o->user?->role,
                    'is_active' => $o->is_active,
                    'type'    => 'outsourcing',
                    'biro'    => $o->biro?->nama_biro,
                    'kode_biro'    => $o->biro?->kode_biro,
                    'jabatan' => $o->jabatan?->nama_jabatan,
                    'id_jabatan' => $o->jabatan?->id,
                    'jumlahDinilai' => $o->user?->penugasan_count ?? 0,
                ]);
        } elseif ($user == 'evaluators') {
            $initialUsers = MasterPegawai::where('kode_unit', '02')
                ->with([
                    'biro',
                    'user' => fn($q) =>
                    $q->withCount([
                        'penugasan as penugasan_count' => fn($q) =>
                        $q->where('siklus_id', 1)
                    ])
                ])
                ->orderBy('name', 'asc')
                ->get()
                ->map(fn($p) => [
                    'id'      => $p->uuid,
                    'name'    => $p->name,
                    'image'   => $p->image,
                    'email'   => null,
                    'type'    => 'pegawai',
                    'nip'     => $p->user?->nip_sso,
                    'biro'    => $p->biro?->nama_biro,
                    'kode_biro'    => $p->biro?->kode_biro,
                    'role'    => $p->user?->role,
                    'jabatan' => $p->jabatan,
                    'id_jabatan' => $p->jabatan,
                    'jumlahDinilai' => $p->user?->penugasan_count ?? 0,
                ]);
        } else {
            $initialUsers = [];
        }

        $data = [
            'initialUsers' => $initialUsers,
            'totalOutsourcing' => Outsourcing::count(),
            'outsourcingAktif' => Outsourcing::where('is_active', 1)->count(),
            'outsourcingNonAktif' => Outsourcing::where('is_active', 0)->count(),
            'totalPegawai' => MasterPegawai::where('kode_unit', '02')->count(),
            'pegawaiMenilai' => MasterPegawai::where('kode_unit', '02')
                ->whereHas(
                    'user.penugasan',
                    fn($q) =>
                    $q->where('siklus_id', 1)
                )
                ->count(),

            'pegawaiTidakMenilai' => MasterPegawai::where('kode_unit', '02')
                ->whereDoesntHave(
                    'user.penugasan',
                    fn($q) =>
                    $q->where('siklus_id', 1)
                )
                ->count(),
            'jabatans' => Jabatan::orderBy('nama_jabatan', 'asc')->get(),
            'biros' => Biro::where('kode_biro', 'like', '02%')
                ->orderBy('nama_biro', 'asc')
                ->get()
        ];

        return Inertia::render('admin/user/page', $data);
    }
}
