<?php

namespace App\Http\Controllers;

use App\Domain\Shared\Models\County;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user()->load('profile');
        $counties = County::orderBy('name')->get();
        return view('profile.edit', compact('user', 'counties'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Campuri User
        $user->fill($request->only('name', 'email', 'phone'));
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        $user->save();

        // Campuri UserProfile
        $profileData = $request->only('public_name', 'company_name', 'bio', 'county_id', 'city_id', 'website', 'is_business');

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            if ($user->profile?->avatar_path) {
                Storage::disk('public')->delete($user->profile->avatar_path);
            }
            $profileData['avatar_path'] = $path;
        }

        $user->profile()->updateOrCreate(['user_id' => $user->id], $profileData);

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
