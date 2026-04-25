<?php

namespace App\Http\Controllers\Account;

use App\Domain\Listings\Models\Listing;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Toate anunțurile meșteșugarului (inclusiv nepublicate)
        $listings = Listing::withTrashed(false)
            ->where('user_id', $userId)
            ->with(['category', 'reviews'])
            ->orderByDesc('created_at')
            ->get();

        // Totale
        $totalViews     = $listings->sum('views_count');
        $totalFavorites = $listings->sum('favorites_count');
        $totalMessages  = $listings->sum('messages_count');
        $totalReviews   = $listings->sum(fn ($l) => $l->reviews->count());
        $avgRating      = $listings->flatMap->reviews->avg('rating');

        // Top 5 dupa views
        $topByViews = $listings->sortByDesc('views_count')->take(5);

        return view('account.statistics.index', compact(
            'listings',
            'totalViews',
            'totalFavorites',
            'totalMessages',
            'totalReviews',
            'avgRating',
            'topByViews'
        ));
    }
}
