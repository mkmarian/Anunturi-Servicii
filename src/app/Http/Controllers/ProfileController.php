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
        $profileData = $request->only('public_name', 'company_name', 'bio', 'county_id', 'city_id', 'website');

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $mime = $file->getMimeType();

            // Citeste imaginea originala
            $source = match(true) {
                str_contains($mime, 'jpeg') => imagecreatefromjpeg($file->getRealPath()),
                str_contains($mime, 'png')  => imagecreatefrompng($file->getRealPath()),
                str_contains($mime, 'gif')  => imagecreatefromgif($file->getRealPath()),
                str_contains($mime, 'webp') => imagecreatefromwebp($file->getRealPath()),
                default => null,
            };

            if ($source) {
                $srcW = imagesx($source);
                $srcH = imagesy($source);
                $size = min($srcW, $srcH);
                $srcX = (int)(($srcW - $size) / 2);
                $srcY = (int)(($srcH - $size) / 2);

                $canvas = imagecreatetruecolor(400, 400);
                $white = imagecolorallocate($canvas, 255, 255, 255);
                imagefill($canvas, 0, 0, $white);
                imagecopyresampled($canvas, $source, 0, 0, $srcX, $srcY, 400, 400, $size, $size);
                imagedestroy($source);

                ob_start();
                imagewebp($canvas, null, 85);
                $webpData = ob_get_clean();
                imagedestroy($canvas);

                $filename = 'avatars/' . uniqid() . '.webp';
                Storage::disk('uploads')->put($filename, $webpData);

                if ($user->profile?->avatar_path) {
                    Storage::disk('uploads')->delete($user->profile->avatar_path);
                }
                $profileData['avatar_path'] = $filename;
            }
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
