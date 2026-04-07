<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Business;
use Illuminate\Http\JsonResponse;

class BusinessController extends Controller
{
    public function index(): JsonResponse
    {
        $businesses = Business::query()
            ->withCount(['services', 'appointments', 'hours'])
            ->orderBy('name')
            ->get()
            ->map(fn (Business $business) => [
                'id' => $business->id,
                'name' => $business->name,
                'type' => $business->type,
                'phone' => $business->phone,
                'email' => $business->email,
                'address' => $business->address,
                'services_count' => $business->services_count,
                'appointments_count' => $business->appointments_count,
                'hours_count' => $business->hours_count,
            ]);

        return response()->json([
            'data' => $businesses,
        ]);
    }

    public function show(Business $business): JsonResponse
    {
        $business->load(['services', 'hours']);

        return response()->json([
            'data' => [
                'id' => $business->id,
                'name' => $business->name,
                'type' => $business->type,
                'phone' => $business->phone,
                'email' => $business->email,
                'address' => $business->address,
                'services' => $business->services->map(fn ($service) => [
                    'id' => $service->id,
                    'name' => $service->name,
                    'duration_minutes' => $service->duration_minutes,
                    'price' => $service->price,
                    'active' => $service->active,
                ])->values(),
                'hours' => $business->hours->map(fn ($hour) => [
                    'day_of_week' => $hour->day_of_week,
                    'opens_at' => $hour->opens_at,
                    'closes_at' => $hour->closes_at,
                    'is_active' => $hour->is_active,
                ])->values(),
            ],
        ]);
    }
}
