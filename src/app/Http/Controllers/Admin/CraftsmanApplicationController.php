<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Accounts\Models\CraftsmanApplication;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CraftsmanApplicationController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');

        $applications = CraftsmanApplication::with(['user', 'user.profile', 'category', 'reviewer'])
            ->when($status !== 'all', fn($q) => $q->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $counts = [
            'pending'  => CraftsmanApplication::pending()->count(),
            'approved' => CraftsmanApplication::approved()->count(),
            'rejected' => CraftsmanApplication::rejected()->count(),
        ];

        return view('admin.craftsman-applications.index', compact('applications', 'status', 'counts'));
    }

    public function show(CraftsmanApplication $craftsmanApplication)
    {
        $craftsmanApplication->load(['user', 'user.profile', 'category', 'reviewer']);

        return view('admin.craftsman-applications.show', [
            'application' => $craftsmanApplication,
        ]);
    }

    public function approve(Request $request, CraftsmanApplication $craftsmanApplication)
    {
        if (! $craftsmanApplication->isPending()) {
            return back()->with('error', 'Cererea nu mai este în așteptare.');
        }

        $validated = $request->validate([
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ]);

        // Schimba rolul utilizatorului in meserias
        $craftsmanApplication->user->update(['role' => 'craftsman']);

        // Marca cererea ca aprobata
        $craftsmanApplication->update([
            'status'      => 'approved',
            'admin_note'  => $validated['admin_note'] ?? null,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return redirect()
            ->route('admin.craftsman-applications.index')
            ->with('success', "Cererea lui {$craftsmanApplication->user->name} a fost aprobată. Rolul a fost schimbat în Meseriaș.");
    }

    public function reject(Request $request, CraftsmanApplication $craftsmanApplication)
    {
        if (! $craftsmanApplication->isPending()) {
            return back()->with('error', 'Cererea nu mai este în așteptare.');
        }

        $validated = $request->validate([
            'admin_note' => ['required', 'string', 'max:1000'],
        ], [
            'admin_note.required' => 'Motivul respingerii este obligatoriu.',
        ]);

        $craftsmanApplication->update([
            'status'      => 'rejected',
            'admin_note'  => $validated['admin_note'],
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return redirect()
            ->route('admin.craftsman-applications.index')
            ->with('success', "Cererea lui {$craftsmanApplication->user->name} a fost respinsă.");
    }
}
