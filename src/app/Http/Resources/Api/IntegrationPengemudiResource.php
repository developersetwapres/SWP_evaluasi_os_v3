<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\JsonApi\JsonApiResource;

class IntegrationPengemudiResource extends JsonApiResource
{
    /**
     * The resource's attributes.
     */
    public $attributes = [
        'uuid',
        'name',
        'jabatan_id',
        'nip',
        'is_active',
        'image',
    ];

    /**
     * The resource's relationships.
     */
    public $relationships = [
        'user' => UserResource::class
    ];
}
