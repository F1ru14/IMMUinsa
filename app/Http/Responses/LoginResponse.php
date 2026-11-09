<?php

namespace App\Http\Responses;

use App\Http\Responses\Concerns\RedirectsInertiaVisits;
use Laravel\Fortify\Http\Responses\LoginResponse as BaseLoginResponse;

class LoginResponse extends BaseLoginResponse
{
    use RedirectsInertiaVisits;

    protected string $redirectName = 'login';
}
