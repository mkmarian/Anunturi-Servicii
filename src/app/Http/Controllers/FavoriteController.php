<?php

namespace App\Http\Controllers;

use App\Domain\Listings\Models\Favorite;
use App\Domain\Listings\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Toggle favorite pentru un anunt.
     * Returneaza JSON: { favorited: true/false, count: int }
     */
    public function toggle(Listing $listing)
    {
        $userId = Auth::id();

        $existing = Favorite::where('user_id', $userId)
            ->where('listing_id', $listing->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $favorited = false;
        } else {
            Favorite::create([
                'user_id'    => $userId,
                'listing_id' => $listing->id,
            ]);
            $favorited = true;
        }

        $count = Favorite::where('listing_id', $listing->id)->count();

        return response()->json([
            'favorited' => $favorited,
            'count'     => $count,
        ]);
    }

    /**
     * Lista anunturilor favorite ale utilizatorului curent.
     */
    public function index()
    {
        $favorites = Favorite::with(['listing.images', 'listing.category', 'listing.city', 'listing.county'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(12);

        return view('account.favorites.index', compact('favorites'));
    }
}
