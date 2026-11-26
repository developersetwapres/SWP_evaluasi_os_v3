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

        $apiUrl = config('services.simdatuk.api_image_url');

        $token = config('services.simdatuk.token_api');

        try {
            if ($type === 'os') {
                $response = Http::withToken($token)
                    ->timeout(30)
                    ->get($apiUrl . '/api/employees/', [
                        'type' => 3,
                        'employment_type_id' => 19,
                        'employment_status' => 1,
                    ]);

                $response->throw();

                return $response->json('data') ?? [];
            }

            // Ambil ASN (type 1)
            $asn = Http::withToken($token)
                ->timeout(30)
                ->get($apiUrl . '/api/employees/', [
                    'type' => 1,
                    'employment_status' => 1,
                ]);

            $asn->throw();

            // Ambil Non ASN (type 2)
            $nonAsn = Http::withToken($token)
                ->timeout(30)
                ->get($apiUrl . '/api/employees/', [
                    'type' => 2,
                    'employment_status' => 1,
                ]);

            $nonAsn->throw();


            // Ambil Perbantuan Setneg (type 2)
            $perbantuan = Http::withToken($token)
                ->timeout(30)
                ->get($apiUrl . '/api/diagrams/', [
                    'id' => 4,
                ]);

            $perbantuan->throw();

            $data = $perbantuan->json('data') ?? [];

            $perbantuanSetneg = collect($data['childs'] ?? [])
                ->flatMap(fn($child) => $child['users'] ?? [])
                ->values()
                ->all();

            return array_merge(
                $asn->json('data') ?? [],
                $nonAsn->json('data') ?? [],
                $perbantuanSetneg
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

    public function getX(string $type): array
    {
        $typeParams = $type === 'os'
            ? '&type=3' . '&employment_type_id=19'
            : 'type=1';

        if ($typeParams === null) {
            throw new InvalidArgumentException("Tipe pegawai tidak valid: {$type}");
        }

        $apiUrl = config('services.simdatuk.api_image_url')
            . '/api/employees/?'
            . $typeParams
            . '&employment_status=1';

        $token = config('services.simdatuk.token_api');

        try {
            $response = Http::withToken($token)
                ->timeout(30)
                ->get($apiUrl);

            if ($response->status() === 429) {
                throw new RuntimeException(
                    'API Simdatuk membatasi request (429 Too Many Requests). '
                        . 'Retry-After: '
                        . ($response->header('Retry-After') ?? 'Tidak tersedia')
                );
            }

            $response->throw();

            return $response->json('data') ?? [];
        } catch (ConnectionException $e) {
            throw new RuntimeException(
                'Tidak dapat terhubung ke API Simdatuk: ' . $e->getMessage(),
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
