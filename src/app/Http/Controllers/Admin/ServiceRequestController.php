<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Requests\Models\ServiceRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ServiceRequestController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');

        $counts = [
            'pending'   => ServiceRequest::where('status', 'pending')->count(),
            'published' => ServiceRequest::where('status', 'published')->count(),
            'rejected'  => ServiceRequest::where('status', 'rejected')->count(),
            'all'       => ServiceRequest::count(),
        ];

        $query = ServiceRequest::with(['user', 'category', 'county', 'city']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $requests = $query->latest()->paginate(20)->withQueryString();

        return view('admin.requests.index', compact('requests', 'status', 'counts'));
    }

    public function show(ServiceRequest $serviceRequest)
    {
        $serviceRequest->load(['user.profile', 'category', 'county', 'city']);
        return view('admin.requests.show', compact('serviceRequest'));
    }

    public function approve(ServiceRequest $serviceRequest)
    {
        $serviceRequest->update([
            'status'       => 'published',
            'published_at' => now(),
        ]);

        return back()->with('success', 'Cererea a fost aprobată.');
    }

    public function reject(Request $request, ServiceRequest $serviceRequest)
    {
        $serviceRequest->update(['status' => 'rejected']);

        return back()->with('success', 'Cererea a fost respinsă.');
    }

    public function destroy(ServiceRequest $serviceRequest)
    {
        $serviceRequest->delete();
        return redirect()->route('admin.requests.index')->with('success', 'Cererea a fost ștearsă.');
    }
}
