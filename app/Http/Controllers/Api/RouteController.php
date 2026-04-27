<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Route;
use Illuminate\Http\JsonResponse;

class RouteController extends Controller
{
    public function index(): JsonResponse
    {
        $routes = Route::query()
            ->with(['startZone:id,name,campus', 'endZone:id,name,campus'])
            ->where('is_active', true)
            ->get();

        return response()->json(['data' => $routes]);
    }

    public function show(Route $route): JsonResponse
    {
        $route->load([
            'startZone',
            'endZone',
            'trips' => fn ($q) => $q->whereIn('status', [
                'threshold_pending', 'scheduled', 'boarding',
            ])->orderBy('departure_time'),
        ]);

        return response()->json(['data' => $route]);
    }
}
