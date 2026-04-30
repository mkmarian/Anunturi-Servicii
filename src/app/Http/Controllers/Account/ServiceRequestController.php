<?php

namespace App\Http\Controllers\Account;

use App\Domain\Requests\Models\ServiceRequest;
use App\Domain\Shared\Models\County;
use App\Domain\Shared\Models\ServiceCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest\StoreServiceRequestRequest;
use App\Http\Requests\ServiceRequest\UpdateServiceRequestRequest;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ServiceRequestController extends Controller
{
    public function index(): View
    {
        $requests = ServiceRequest::where('user_id', auth()->id())
            ->with(['category', 'city', 'county'])
            ->latest()
            ->paginate(10);

        return view('account.requests.index', compact('requests'));
    }

    public function create(): View
    {
        $categories = ServiceCategory::active()->roots()->ordered()->get();
        $counties   = County::orderBy('name')->get();

        return view('account.requests.create', compact('categories', 'counties'));
    }

    public function store(StoreServiceRequestRequest $request): RedirectResponse
    {
        $data = $request->validated();

        ServiceRequest::create([
            'user_id'      => auth()->id(),
            'category_id'  => $data['category_id'],
            'county_id'    => $data['county_id'],
            'city_id'      => $data['city_id'],
            'title'        => $data['title'],
            'slug'         => $this->uniqueSlug($data['title']),
            'description'  => $data['description'],
            'currency'     => 'RON',
            'status'       => 'pending',
        ]);

        return redirect()->route('customer.requests.index')
            ->with('success', 'Cererea ta a fost trimisa spre aprobare. Meseriasii din zona ta vor putea raspunde.');
    }

    public function edit(ServiceRequest $cerere): View
    {
        $this->authorizeRequest($cerere);

        $categories = ServiceCategory::active()->roots()->ordered()->get();
        $counties   = County::orderBy('name')->get();
        $cerere->load('city');

        return view('account.requests.edit', compact('cerere', 'categories', 'counties'));
    }

    public function update(UpdateServiceRequestRequest $request, ServiceRequest $cerere): RedirectResponse
    {
        $this->authorizeRequest($cerere);

        $data = $request->validated();

        // Slug nou daca titlul s-a schimbat
        $slug = $cerere->title !== $data['title']
            ? $this->uniqueSlug($data['title'])
            : $cerere->slug;

        $cerere->update([
            'category_id'  => $data['category_id'],
            'county_id'    => $data['county_id'],
            'city_id'      => $data['city_id'],
            'title'        => $data['title'],
            'slug'         => $slug,
            'description'  => $data['description'],
            'status'       => in_array($cerere->status, ['published']) ? 'pending' : $cerere->status,
        ]);

        return redirect()->route('customer.requests.index')
            ->with('success', 'Cererea a fost actualizata.');
    }

    public function destroy(ServiceRequest $cerere): RedirectResponse
    {
        $this->authorizeRequest($cerere);
        $cerere->delete();

        return redirect()->route('customer.requests.index')
            ->with('success', 'Cererea a fost stearsa.');
    }

    // ── Helpers ──────────────────────────────────────────────

    private function authorizeRequest(ServiceRequest $cerere): void
    {
        if ((int) $cerere->user_id !== (int) auth()->id()) {
            abort(403);
        }
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i    = 1;

        while (ServiceRequest::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
