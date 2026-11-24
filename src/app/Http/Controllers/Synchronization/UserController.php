<?php

namespace App\Http\Controllers\Synchronization;

use App\Http\Controllers\Controller;
use App\Models\Outsourcing;
use App\Services\Synchronization\DataUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Throwable;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
    public function update(string $type, DataUser $dataUser): RedirectResponse
    {
        try {
            $users = $dataUser->get($type);

            $nips = collect($users)
                ->pluck('employee_id_number')
                ->filter()
                ->unique()
                ->values();

            $outsourcings = Outsourcing::whereIn('nip', $nips)
                ->get()
                ->keyBy('nip');

            $updated = 0;

            DB::transaction(function () use ($users, $outsourcings, &$updated) {
                foreach ($users as $user) {
                    $nip = $user['employee_id_number'] ?? null;

                    if (!$nip || !isset($outsourcings[$nip])) {
                        continue;
                    }

                    $outsourcing = $outsourcings[$nip];

                    $outsourcing->name = $user['name'];
                    $outsourcing->image = $user['photo_profile'] ?? $outsourcing->image;

                    $outsourcing->save();

                    $updated++;
                }
            });

            return back()->with(
                'success',
                "Sinkronisasi berhasil. {$updated} data Outsourcing diperbarui."
            );

        } catch (Throwable $e) {
            Log::error('Sinkronisasi data Outsourcing gagal', [
                'type' => $type,
                'message' => $e->getMessage(),
                'exception' => $e,
            ]);

            return back()->with('error', 'Sinkronisasi gagal: ' . $e->getMessage());
        }
    }
}
