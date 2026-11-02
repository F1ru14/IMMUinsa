<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Enums\Role;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        $input['username'] = Str::lower(trim((string) ($input['username'] ?? '')));

        Validator::make($input, [
            'username' => $this->usernameRules(),
            'password' => $this->passwordRules(),
            'role' => ['nullable', 'string', Rule::in(Role::publicRoles())],
        ])->validate();

        $username = $input['username'];
        $role = isset($input['role']) ? Role::from($input['role']) : Role::Kader;

        return User::create([
            // Nama tampilan sementara memakai username; akan disinkronkan
            // dengan nama lengkap saat kader mengisi biodata.
            'name' => $username,
            'username' => $username,
            'password' => $input['password'],
            'role' => $role,
            'is_active' => false,
        ]);
    }
}
