<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\ProfileDeleteRequest;
use App\Http\Requests\Settings\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show the user's profile settings page.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validated();
        $avatar = $validated['avatar'] ?? null;
        unset($validated['avatar']);

        $user->fill($validated);
        $user->username = Str::lower($user->username);
        $user->save();

        if ($avatar && $user->biodata) {
            if ($user->biodata->avatar) {
                Storage::disk('public')->delete($user->biodata->avatar);
            }

            $user->biodata->update([
                'avatar' => $avatar->store('avatars', 'public'),
            ]);
        }

        return to_route('profile.edit')
            ->with('status', 'Profil berhasil diperbarui.');
    }

    /**
     * Delete the user's profile.
     */
    public function destroy(ProfileDeleteRequest $request): RedirectResponse
    {
        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
