<?php

namespace App\Http\Controllers\Public;

use App\Domain\Listings\Models\Favorite;
use App\Domain\Listings\Models\Listing;
use App\Domain\Reviews\Models\Review;
use App\Domain\Shared\Models\County;
use App\Domain\Shared\Models\ServiceCategory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ListingController extends Controller
{
    public function index(Request $request): View
    {
        $query = Listing::active()
            ->with(['category', 'county', 'city', 'primaryImage', 'user.profile']);

        // Filtre
        if ($request->filled('q')) {
            $term = '%' . addcslashes($request->q, '%_') . '%';
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', $term)
                  ->orWhere('short_description', 'like', $term);
            });
        }

        if ($request->filled('category')) {
            $query->inCategory((int) $request->category);
        }

        if ($request->filled('county')) {
            $query->inCounty((int) $request->county);
        }

        if ($request->filled('city')) {
            $query->inCity((int) $request->city);
        }

        $listings   = $query->orderByDesc('featured_until')
                            ->orderByDesc('published_at')
                            ->paginate(10)
                            ->withQueryString();

        $categories = ServiceCategory::active()->roots()->ordered()->get();
        $counties   = County::orderBy('name')->get();

        $favoriteIds = Auth::check()
            ? Favorite::where('user_id', Auth::id())->pluck('listing_id')->toArray()
            : [];

        return view('listings.index', compact('listings', 'categories', 'counties', 'favoriteIds'));
    }

    public function show(string $slug): View
    {
        $listing = Listing::active()
            ->where('slug', $slug)
            ->with(['category', 'county', 'city', 'images', 'user.profile'])
            ->firstOrFail();

        // Incrementam views (nu conteaza bot-urile, e MVP)
        $listing->increment('views_count');

        $reviews = $listing->reviews()
            ->with('reviewer')
            ->latest()
            ->get();

        $avgRating = $reviews->avg('rating');

        $userReview = Auth::check()
            ? $reviews->firstWhere('reviewer_id', Auth::id())
            : null;

        return view('listings.show', compact('listing', 'reviews', 'avgRating', 'userReview'));
    }
}
