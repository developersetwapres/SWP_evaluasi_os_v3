<?php

namespace App\Services\Synchronization;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\ConnectionException;
use InvalidArgumentException;
use RuntimeException;

class DataUser
{
    public function get(string $type): array
    {
        if (!in_array($type, ['os', 'evaluators'], true)) {
            throw new InvalidArgumentException(
                "Tipe pegawai tidak valid: {$type}"
            );
        }

        $apiUrl = config('services.simdatuk.api_image_url')
            . '/api/employees/';

        $token = config('services.simdatuk.token_api');

        try {
            if ($type === 'os') {
                $response = Http::withToken($token)
                    ->timeout(30)
                    ->get($apiUrl, [
                        'type' => 3,
                        'employment_type_id' => 19,
                        'employment_status' => 1,
                    ]);

                $response->throw();

                return $response->json('data') ?? [];
            }

            // Ambil ASN (type 2)
            $asn = Http::withToken($token)
                ->timeout(30)
                ->get($apiUrl, [
                    'type' => 2,
                    'employment_status' => 1,
                ]);

            $asn->throw();

            // Ambil Non ASN (type 3)
            $nonAsn = Http::withToken($token)
                ->timeout(30)
                ->get($apiUrl, [
                    'type' => 3,
                    'employment_status' => 1,
                ]);

            $nonAsn->throw();

            return array_merge(
                $asn->json('data') ?? [],
                $nonAsn->json('data') ?? []
            );
        } catch (ConnectionException $e) {
            throw new RuntimeException(
                'Tidak dapat terhubung ke API Simdatuk: '
                    . $e->getMessage(),
                0,
                $e
            );
        } catch (RequestException $e) {
            throw new RuntimeException(
                'API Simdatuk mengembalikan HTTP '
                    . $e->response->status()
                    . ': ' . $e->response->body(),
                $e->response->status(),
                $e
            );
        }
    }
}
