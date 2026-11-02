<?php

namespace App\Http\Responses;

use App\Http\Responses\Concerns\RedirectsInertiaVisits;
use Laravel\Fortify\Http\Responses\TwoFactorLoginResponse as BaseTwoFactorLoginResponse;

class TwoFactorLoginResponse extends BaseTwoFactorLoginResponse
{
    use RedirectsInertiaVisits;

    /**
     * The Fortify redirect name used to resolve the default destination.
     */
    protected string $redirectName = 'login';
}
