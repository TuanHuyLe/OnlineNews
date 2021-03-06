<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/api/v1/categories',
        '/api/v1/news',
        '/api/v1/roles',
        '/api/v1/users',
        '/api/v1/permissions'
    ];
}
