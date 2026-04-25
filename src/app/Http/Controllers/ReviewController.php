<?php

namespace App\Http\Controllers;

use App\Domain\Listings\Models\Listing;
use App\Domain\Reviews\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Listing $listing)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Nu poți recenza propriul anunț
        if ($listing->user_id === Auth::id()) {
            return back()->with('error', 'Nu poți lăsa o recenzie pentru propriul tău anunț.');
        }

        // Un singur review per anunț per user
        $existing = Review::where('listing_id', $listing->id)
            ->where('reviewer_id', Auth::id())
            ->exists();

        if ($existing) {
            return back()->with('error', 'Ai lăsat deja o recenzie pentru acest anunț.');
        }

        Review::create([
            'listing_id'   => $listing->id,
            'reviewer_id'  => Auth::id(),
            'craftsman_id' => $listing->user_id,
            'rating'       => $request->rating,
            'comment'      => $request->comment,
        ]);

        return back()->with('success', 'Recenzia ta a fost adăugată. Mulțumim!');
    }

    public function destroy(Review $review)
    {
        // Doar autorul sau admin poate șterge
        if ($review->reviewer_id !== Auth::id() && !Auth::user()->isModerator()) {
            abort(403);
        }

        $review->delete();

        return back()->with('success', 'Recenzia a fost ștearsă.');
    }
}
