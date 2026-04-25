<?php

namespace App\Http\Controllers\Account;

use App\Domain\Listings\Models\Listing;
use App\Domain\Listings\Models\ListingImage;
use App\Domain\Shared\Models\County;
use App\Domain\Shared\Models\ServiceCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Listing\StoreListingRequest;
use App\Http\Requests\Listing\UpdateListingRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ListingController extends Controller
{
    public function index(Request $request): View
    {
        $listings = Listing::where('user_id', auth()->id())
            ->with(['category', 'city', 'primaryImage'])
            ->latest()
            ->paginate(10);

        return view('account.listings.index', compact('listings'));
    }

    public function create(): View
    {
        $categories = ServiceCategory::active()->roots()->with('children')->ordered()->get();
        $counties   = County::orderBy('name')->get();

        return view('account.listings.create', compact('categories', 'counties'));
    }

    public function store(StoreListingRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Limitare anunturi gratuite conform config
        $freeLimit = config('marketplace.limits.free_listings_per_user', 5);
        $count = Listing::where('user_id', auth()->id())
            ->whereNotIn('status', ['archived', 'rejected'])
            ->withTrashed(false)
            ->count();

        if ($count >= $freeLimit) {
            return back()->withErrors(['limit' => "Ai atins limita de {$freeLimit} anunturi active. Upgradeaza planul pentru mai multe."]);
        }

        DB::transaction(function () use ($data, $request) {
            $listing = Listing::create([
                'user_id'           => auth()->id(),
                'category_id'       => $data['category_id'],
                'county_id'         => $data['county_id'],
                'city_id'           => $data['city_id'],
                'title'             => $data['title'],
                'slug'              => $this->uniqueSlug($data['title']),
                'short_description' => $data['short_description'] ?? null,
                'description'       => $data['description'],
                'price_type'        => $data['price_type'] ?? null,
                'price_from'        => $data['price_from'] ?? null,
                'price_to'          => $data['price_to'] ?? null,
                'currency'          => 'RON',
                'phone'             => $data['phone'] ?? null,
                'show_phone'        => $data['show_phone'],
                'status'            => 'pending',
            ]);

            $this->handleImages($request, $listing);
        });

        return redirect()->route('craftsman.listings.index')
            ->with('success', 'Anuntul a fost trimis spre aprobare. Vei fi notificat cand este publicat.');
    }

    public function edit(Listing $listing): View
    {
        $this->authorizeListing($listing);

        $categories = ServiceCategory::active()->roots()->with('children')->ordered()->get();
        $counties   = County::orderBy('name')->get();
        $listing->load('images', 'city.county');

        return view('account.listings.edit', compact('listing', 'categories', 'counties'));
    }

    public function update(UpdateListingRequest $request, Listing $listing): RedirectResponse
    {
        $this->authorizeListing($listing);

        $data = $request->validated();

        DB::transaction(function () use ($data, $request, $listing) {
            // Daca titlul s-a schimbat generam un nou slug
            $slug = $listing->title !== $data['title']
                ? $this->uniqueSlug($data['title'])
                : $listing->slug;

            $listing->update([
                'category_id'       => $data['category_id'],
                'county_id'         => $data['county_id'],
                'city_id'           => $data['city_id'],
                'title'             => $data['title'],
                'slug'              => $slug,
                'short_description' => $data['short_description'] ?? null,
                'description'       => $data['description'],
                'price_type'        => $data['price_type'] ?? null,
                'price_from'        => $data['price_from'] ?? null,
                'price_to'          => $data['price_to'] ?? null,
                'phone'             => $data['phone'] ?? null,
                'show_phone'        => $data['show_phone'],
                // La editare revine la pending daca era publicat
                'status'            => in_array($listing->status, ['published']) ? 'pending' : $listing->status,
            ]);

            // Stergere imagini marcate de utilizator
            if (! empty($data['remove_images'])) {
                $toDelete = ListingImage::where('listing_id', $listing->id)
                    ->whereIn('id', $data['remove_images'])
                    ->get();

                foreach ($toDelete as $img) {
                    Storage::disk('public')->delete($img->path);
                    $img->delete();
                }
            }

            $this->handleImages($request, $listing);
        });

        return redirect()->route('craftsman.listings.index')
            ->with('success', 'Anuntul a fost actualizat si retrimis spre aprobare.');
    }

    public function destroy(Listing $listing): RedirectResponse
    {
        $this->authorizeListing($listing);

        // Sterge fizic imaginile de pe disk
        foreach ($listing->images as $img) {
            Storage::disk('public')->delete($img->path);
        }

        $listing->delete(); // softDelete

        return redirect()->route('craftsman.listings.index')
            ->with('success', 'Anuntul a fost sters.');
    }

    // ── Helpers private ──────────────────────────────────────

    private function authorizeListing(Listing $listing): void
    {
        if ($listing->user_id !== auth()->id()) {
            abort(403);
        }
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i    = 1;

        while (Listing::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    private function handleImages(Request $request, Listing $listing): void
    {
        if (! $request->hasFile('images')) {
            return;
        }

        $existing = ListingImage::where('listing_id', $listing->id)->count();
        $maxImages = config('marketplace.limits.max_listing_images', 8);

        foreach ($request->file('images') as $file) {
            if ($existing >= $maxImages) break;

            $path = $file->store("listings/{$listing->id}", 'public');

            ListingImage::create([
                'listing_id' => $listing->id,
                'path'       => $path,
                'alt_text'   => Str::limit($listing->title, 100),
                'sort_order' => $existing,
                'width'      => null,
                'height'     => null,
                'size_bytes' => $file->getSize(),
            ]);

            $existing++;
        }
    }
}
