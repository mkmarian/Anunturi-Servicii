<?php

namespace App\Http\Controllers\Api;

use App\Domain\Shared\Models\City;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class CityController extends Controller
{
    public function byCounty(int $countyId): JsonResponse
    {
        $cities = City::where('county_id', $countyId)
            ->orderBy('name')
            ->select('id', 'name')
            ->get();

        return response()->json($cities);
    }
}
