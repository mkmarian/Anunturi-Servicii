<?php

namespace App\Http\Controllers\Account;

use App\Domain\Listings\Models\Listing;
use App\Domain\Monetization\Models\PromotedSlot;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PromotionController extends Controller
{
    /**
     * Activează promovarea unui anunț (30 de zile, gratuit la MVP).
     */
    public function activate(Request $request, Listing $listing)
    {
        // Verificare proprietar
        if ($listing->user_id !== Auth::id()) {
            abort(403);
        }

        // Verificare dacă există deja o promovare activă
        $active = PromotedSlot::where('listing_id', $listing->id)
            ->active()
            ->exists();

        if ($active) {
            return back()->with('error', 'Anunțul este deja promovat.');
        }

        $days = (int) $request->input('days', 30);
        $days = min(max($days, 7), 90); // între 7 și 90 zile

        PromotedSlot::create([
            'listing_id'     => $listing->id,
            'user_id'        => Auth::id(),
            'promotion_type' => 'featured',
            'starts_at'      => now(),
            'ends_at'        => now()->addDays($days),
            'status'         => 'active',
            'price_amount'   => 0,
            'currency'       => 'RON',
        ]);

        // Setăm featured_until și pe listing pentru query rapid
        $listing->update(['featured_until' => now()->addDays($days)]);

        return back()->with('success', "Anunțul tău va fi promovat {$days} zile!");
    }

    /**
     * Dezactivează promovarea.
     */
    public function deactivate(Listing $listing)
    {
        if ($listing->user_id !== Auth::id()) {
            abort(403);
        }

        PromotedSlot::where('listing_id', $listing->id)
            ->active()
            ->update(['status' => 'cancelled', 'ends_at' => now()]);

        $listing->update(['featured_until' => null]);

        return back()->with('success', 'Promovarea a fost dezactivată.');
    }
}
