<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Business;
use Illuminate\Http\JsonResponse;

class ServiceController extends Controller
{
    public function index(Business $business): JsonResponse
    {
        $services = $business->services()
            ->orderBy('name')
            ->get()
            ->map(fn ($service) => [
                'id' => $service->id,
                'business_id' => $service->business_id,
                'name' => $service->name,
                'description' => $service->description,
                'duration_minutes' => $service->duration_minutes,
                'price' => $service->price,
                'active' => $service->active,
            ]);

        return response()->json([
            'data' => $services,
        ]);
    }
}
