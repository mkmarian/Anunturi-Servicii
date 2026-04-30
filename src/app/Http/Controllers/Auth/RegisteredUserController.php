<?php

namespace App\Http\Controllers\Auth;

use App\Domain\Accounts\Models\UserProfile;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:120'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:190', 'unique:'.User::class],
            'phone'    => ['nullable', 'string', 'max:30'],
            'role'     => ['required', 'in:customer,craftsman'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $phone = $request->phone
            ? preg_replace('/^(\+40|0040)/', '0', preg_replace('/[\s\-\.\(\)]/', '', $request->phone))
            : null;

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $phone,
            'role'     => $request->role,
            'password' => Hash::make($request->password),
            'status'   => 'active',
        ]);

        // Cream profilul automat la inregistrare
        UserProfile::create(['user_id' => $user->id]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('home', absolute: false));
    }
}

