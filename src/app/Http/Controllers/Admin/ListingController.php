<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Listings\Models\Listing;
use App\Domain\Shared\Models\ServiceCategory;
use App\Http\Controllers\Controller;
use App\Notifications\ListingApproved;
use App\Notifications\ListingRejected;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    // ── Lista anunturi in asteptare / toate ───────────────────
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');

        $listings = Listing::with(['user:id,name,email', 'category:id,name', 'county:id,name', 'city:id,name'])
            ->when($status !== 'all', fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $counts = [
            'pending'   => Listing::where('status', 'pending')->count(),
            'published' => Listing::where('status', 'published')->count(),
            'rejected'  => Listing::where('status', 'rejected')->count(),
        ];

        return view('admin.listings.index', compact('listings', 'status', 'counts'));
    }

    // ── Detaliu anunt ─────────────────────────────────────────
    public function show(Listing $listing)
    {
        $listing->load(['user.profile', 'category', 'county', 'city', 'images']);
        return view('admin.listings.show', compact('listing'));
    }

    // ── Aproba anunt ──────────────────────────────────────────
    public function approve(Listing $listing)
    {
        $listing->update([
            'status'       => 'published',
            'published_at' => now(),
        ]);

        // Notifica meserasul prin email
        $listing->user->notify(new ListingApproved($listing));

        return back()->with('success', "Anuntul '{$listing->title}' a fost aprobat.");
    }

    // ── Respinge anunt ────────────────────────────────────────
    public function reject(Request $request, Listing $listing)
    {
        $request->validate([
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $listing->update(['status' => 'rejected']);

        // Notifica meserasul prin email cu motivul (daca exista)
        $listing->user->notify(new ListingRejected($listing, $request->reason));

        return back()->with('success', "Anuntul '{$listing->title}' a fost respins.");
    }

    // ── Sterge anunt ──────────────────────────────────────────
    public function destroy(Listing $listing)
    {
        $listing->delete();
        return redirect()->route('admin.listings.index')->with('success', 'Anunțul a fost șters.');
    }
}
