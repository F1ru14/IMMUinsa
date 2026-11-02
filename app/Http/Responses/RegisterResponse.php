<?php

namespace App\Http\Responses;

use App\Http\Responses\Concerns\RedirectsInertiaVisits;
use Laravel\Fortify\Http\Responses\RegisterResponse as BaseRegisterResponse;

class RegisterResponse extends BaseRegisterResponse
{
    use RedirectsInertiaVisits;

    /**
     * The Fortify redirect name used to resolve the default destination.
     */
    protected string $redirectName = 'register';
}
