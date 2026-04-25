<?php

namespace App\Http\Controllers;

use App\Domain\Listings\Models\Listing;
use App\Domain\Requests\Models\ServiceRequest;
use App\Domain\Shared\Models\County;
use App\Domain\Shared\Models\ServiceCategory;

class HomeController extends Controller
{
    public function index()
    {
        // Ultimele 8 anunturi publicate
        $recentListings = Listing::with(['user.profile', 'category', 'city', 'county', 'images'])
            ->published()
            ->latest('published_at')
            ->limit(8)
            ->get();

        // Ultimele 6 cereri publicate
        $recentRequests = ServiceRequest::with(['user.profile', 'category', 'city', 'county'])
            ->published()
            ->latest('published_at')
            ->limit(6)
            ->get();

        // Categorii principale cu numarul de anunturi
        $categories = ServiceCategory::active()
            ->roots()
            ->ordered()
            ->withCount(['listings' => fn ($q) => $q->published()])
            ->get();

        // Judete pentru search
        $counties = County::orderBy('name')->get(['id', 'name']);

        // Statistici hero
        $stats = [
            'listings' => Listing::published()->count(),
            'requests' => ServiceRequest::published()->count(),
            'counties' => $counties->count(),
        ];

        return view('home', compact('recentListings', 'recentRequests', 'categories', 'counties', 'stats'));
    }
}
