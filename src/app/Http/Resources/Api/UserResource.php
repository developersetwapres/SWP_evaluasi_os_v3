<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\JsonApi\JsonApiResource;

class UserResource extends JsonApiResource
{
    /**
     * The resource's attributes.
     */
    public $attributes = [
        'id',
        'email',
        'password',
    ];

    /**
     * The resource's relationships.
     */
    public $relationships = [
        // ...
    ];
}
