<?php

namespace App\Http\Responses;

use App\Http\Responses\Concerns\RedirectsInertiaVisits;
use Laravel\Fortify\Http\Responses\TwoFactorLoginResponse as BaseTwoFactorLoginResponse;

class TwoFactorLoginResponse extends BaseTwoFactorLoginResponse
{
    use RedirectsInertiaVisits;

    protected string $redirectName = 'login';
}
