<?php

namespace App\Http\Responses;

use App\Http\Responses\Concerns\RedirectsInertiaVisits;
use Laravel\Fortify\Http\Responses\RegisterResponse as BaseRegisterResponse;

class RegisterResponse extends BaseRegisterResponse
{
    use RedirectsInertiaVisits;

    protected string $redirectName = 'register';
}
