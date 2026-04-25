<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->query('role', 'all');

        $counts = [
            'all'       => User::count(),
            'craftsman' => User::where('role', 'craftsman')->count(),
            'customer'  => User::where('role', 'customer')->count(),
            'admin'     => User::whereIn('role', ['admin', 'moderator'])->count(),
        ];

        $query = User::with('profile');

        if ($role !== 'all') {
            if ($role === 'admin') {
                $query->whereIn('role', ['admin', 'moderator']);
            } else {
                $query->where('role', $role);
            }
        }

        if ($request->filled('q')) {
            $term = '%' . $request->q . '%';
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                  ->orWhere('email', 'like', $term);
            });
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users', 'role', 'counts'));
    }

    public function show(User $user)
    {
        $user->load(['profile', 'listings', 'serviceRequests']);
        return view('admin.users.show', compact('user'));
    }

    public function toggleStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Nu îți poți schimba propriul status.');
        }

        $user->update([
            'status' => $user->status === 'active' ? 'suspended' : 'active',
        ]);

        return back()->with('success', 'Statusul utilizatorului a fost actualizat.');
    }
}
