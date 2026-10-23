<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Outsourcing;
use App\Http\Resources\Api\IntegrationPengemudiResource;

class OutsourcingController extends Controller
{

    public function integrationPengemudi()
    {
        $outsourcing = Outsourcing::whereHas('jabatan', function ($query) {
            $query->where('nama_jabatan', 'Pengemudi');
        })
            ->where('is_active', true)
            ->with('user')
            ->get();

        return IntegrationPengemudiResource::collection($outsourcing);
    }
}
