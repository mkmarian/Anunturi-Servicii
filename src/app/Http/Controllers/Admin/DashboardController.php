<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Listings\Models\Listing;
use App\Domain\Requests\Models\ServiceRequest;
use App\Http\Controllers\Controller;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'listings' => [
                'total'     => Listing::count(),
                'pending'   => Listing::where('status', 'pending')->count(),
                'published' => Listing::where('status', 'published')->count(),
                'rejected'  => Listing::where('status', 'rejected')->count(),
            ],
            'requests' => [
                'total'     => ServiceRequest::count(),
                'pending'   => ServiceRequest::where('status', 'pending')->count(),
                'published' => ServiceRequest::where('status', 'published')->count(),
            ],
            'users' => [
                'total'      => User::count(),
                'craftsmen'  => User::where('role', 'craftsman')->count(),
                'customers'  => User::where('role', 'customer')->count(),
                'admins'     => User::whereIn('role', ['admin', 'moderator'])->count(),
                'new_today'  => User::whereDate('created_at', today())->count(),
            ],
        ];

        $recentPendingListings = Listing::with(['user', 'category'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $recentPendingRequests = ServiceRequest::with(['user', 'category'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $recentUsers = User::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentPendingListings',
            'recentPendingRequests',
            'recentUsers'
        ));
    }
}
