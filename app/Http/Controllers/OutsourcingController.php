<?php

namespace App\Http\Controllers;

use App\Models\Outsourcing;
use App\Http\Requests\StoreOutsourcingRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Requests\UpdateOutsourcingRequest;
use App\Services\Uploadfile\FotoUserService;
use Illuminate\Support\Facades\DB;

class OutsourcingController extends Controller
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */


    public function store(StoreOutsourcingRequest $request)
    {
        DB::transaction(function () use ($request) {
            $moveImageFromTemp = app(FotoUserService::class)->moveImageFromTemp(...);
            $finalImagePath = $moveImageFromTemp($request->image, 'os');

            Outsourcing::create([
                'name' => $request->name,
                'jabatan_id' => $request->jabatan,
                'kode_biro' => $request->unit_kerja,
                'is_active' => $request->status,
                'nip' => $request->nip,
                'image' => $finalImagePath ?? 'foto_default.png',
            ]);

            $emailPrefix = Str::of($request->name)
                ->lower()
                ->replaceMatches('/\s+/', '.')
                ->toString();

            $email = "{$emailPrefix}@set.wapresri.go.id";

            while (User::where('email', $email)->exists()) {
                $suffix = Str::lower(Str::random(3));

                $email = "{$emailPrefix}.{$suffix}@set.wapresri.go.id";
            }

            User::create([
                'userable_id' => $request->nip,
                'userable_type' => Outsourcing::class,
                'nip' => $request->nip,
                'is_ldap' => false,
                'email' => $email,
                'role' => ['evaluator'],
                'password' => Hash::make($request->nip),
            ]);
        });

        return redirect()->back()->with('success', 'Data Outsourcing berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Outsourcing $outsourcing)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Outsourcing $outsourcing)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOutsourcingRequest $request, Outsourcing $outsourcing, FotoUserService $service)
    {
        DB::transaction(function () use ($request, $outsourcing, $service) {
            $finalImagePath = $service->moveImageFromTemp($request->image, 'os');

            $outsourcing->update([
                'name' => $request->name,
                'jabatan_id' => $request->jabatan,
                'kode_biro' => $request->unit_kerja,
                'is_active' => $request->status,
            ]);

            if ($finalImagePath) {
                $outsourcing->update(['image' => $finalImagePath]);
            }

            $userData = [
                'email' => $request->email,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $outsourcing->user->update($userData);
        });

        return redirect()->back()->with('success', 'Data Outsourcing berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Outsourcing $outsourcing)
    {
        //
    }
}
