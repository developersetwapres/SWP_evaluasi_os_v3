<?php

namespace App\Services\Api;

use Illuminate\Support\Facades\Http;

class ApiImage
{
    public function get(string $nik): ?string
    {
        $apiUrl = config('services.simdatuk.api_image_url') . 'api/image/photo_profile/';
        $token = config('services.simdatuk.token_api');

        $url = $apiUrl . $nik;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get($url);

        if ($response->successful()) {
            return $response->body();
        } else {
            // Handle error response
            return null;
        }
    }
}
