<?php

namespace App\Http\Controllers;

use App\Models\Biro;
use App\Models\Jabatan;
use App\Models\MasterPegawai;
use App\Models\Outsourcing;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index($user): Response
    {
        if ($user == 'outsourcings') {
            $initialUsers = Outsourcing::query()
                ->select(['id', 'uuid', 'nip', 'name', 'image', 'jabatan_id', 'kode_biro', 'is_active'])
                ->with([
                    'biro:kode_biro,nama_biro',
                    'jabatan:id,nama_jabatan',
                    'user' => fn($q) =>
                    $q->select(['id', 'userable_id', 'userable_type', 'nip', 'email', 'role'])
                        ->withCount([
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
            $initialUsers = MasterPegawai::query()
                ->select(['id', 'uuid', 'nip', 'name', 'image', 'jabatan', 'kode_biro', 'kode_unit'])
                ->where('kode_unit', '02')
                ->with([
                    'biro:kode_biro,nama_biro',
                    'user' => fn($q) =>
                    $q->select(['id', 'userable_id', 'userable_type', 'nip', 'email', 'role', 'nip_sso'])
                        ->withCount([
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
                    'nip'     => $p->user?->nip . ' / ' . $p->user?->nip_sso,
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
            'jabatans' => Jabatan::select(['id', 'nama_jabatan', 'kode_jabatan'])
                ->orderBy('nama_jabatan', 'asc')->get(),
            'biros' => Biro::select(['kode_biro', 'nama_biro'])
                ->where('kode_biro', 'like', '02%')
                ->orderBy('nama_biro', 'asc')
                ->get()
        ];

        return Inertia::render('admin/user/page', $data);
    }


    public function indexAdministrator(Request $request): Response
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string'],
            'user_type' => ['nullable', 'in:all,outsourcing,pegawai'],
        ]);

        $search = trim($filters['search'] ?? '');
        $userType = $filters['user_type'] ?? 'all';

        $userableTypes = match ($userType) {
            'outsourcing' => [Outsourcing::class],
            'pegawai' => [MasterPegawai::class],
            default => [Outsourcing::class, MasterPegawai::class],
        };

        $user = User::query()
            ->select(['id', 'userable_id', 'userable_type', 'nip', 'nip_sso', 'email', 'role'])
            ->with([
                'userable' => fn($q) => $q->select(['id', 'uuid', 'nip', 'name'])
            ])
            ->whereHasMorph('userable', $userableTypes)
            ->when($search !== '', function (Builder $query) use ($search): void {
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('nip', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhereHasMorph(
                            'userable',
                            [Outsourcing::class, MasterPegawai::class],
                            fn(Builder $userableQuery) => $userableQuery->where('name', 'like', "%{$search}%")
                        );
                });
            })
            ->orderBy('role', 'asc')
            ->paginate(15)
            ->withQueryString();

        $data = [
            'users' => $user,
            'filters' => [
                'search' => $search,
                'user_type' => $userType,
            ],
        ];

        return Inertia::render('admin/administrator/user/page', $data);
    }

    public function updateAdministrator(Request $request, User $user): RedirectResponse
    {
        $userable = $user->userable;
        abort_unless($userable instanceof Outsourcing || $userable instanceof MasterPegawai, 404);

        $validated = $request->validate([
            'role' => ['required', 'array', 'min:1', 'max:3'],
            'role.*' => ['required', 'string', 'distinct', 'in:evaluator,administrator,operator'],
        ]);

        $user->update(['role' => $validated['role']]);

        return back()->with('success', 'Role user berhasil diperbarui.');
    }

    public function destroyAdministrator(User $user): RedirectResponse
    {
        $userable = $user->userable;
        abort_unless($userable instanceof Outsourcing || $userable instanceof MasterPegawai, 404);

        DB::transaction(function () use ($user, $userable): void {
            $user->delete();
            $userable->delete();
        });

        return back()->with('success', 'User dan data outsourcing terkait berhasil dihapus.');
    }
}
