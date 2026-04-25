<?php

namespace App\Http\Controllers\Public;

use App\Domain\Requests\Models\ServiceRequest;
use App\Domain\Shared\Models\County;
use App\Domain\Shared\Models\ServiceCategory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceRequestController extends Controller
{
    public function index(Request $request): View
    {
        $query = ServiceRequest::active()
            ->with(['category', 'county', 'city', 'user.profile']);

        if ($request->filled('q')) {
            $term = '%' . addcslashes($request->q, '%_') . '%';
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', $term)
                  ->orWhere('description', 'like', $term);
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

        $requests   = $query->orderByDesc('published_at')
                            ->paginate(10)
                            ->withQueryString();

        $categories = ServiceCategory::active()->roots()->ordered()->get();
        $counties   = County::orderBy('name')->get();

        return view('requests.index', compact('requests', 'categories', 'counties'));
    }

    public function show(string $slug): View
    {
        $serviceRequest = ServiceRequest::active()
            ->where('slug', $slug)
            ->with(['category', 'county', 'city', 'user.profile'])
            ->firstOrFail();

        return view('requests.show', compact('serviceRequest'));
    }
}
