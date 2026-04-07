<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\BusinessHour;
use Illuminate\Http\JsonResponse;

class BusinessHourController extends Controller
{
    public function index(Business $business): JsonResponse
    {
        $days = BusinessHour::dayOptions();

        $hours = $business->hours()
            ->orderBy('day_of_week')
            ->get()
            ->map(fn ($hour) => [
                'id' => $hour->id,
                'day_of_week' => $hour->day_of_week,
                'day_name' => $days[$hour->day_of_week] ?? $hour->day_of_week,
                'opens_at' => $hour->opens_at,
                'closes_at' => $hour->closes_at,
                'is_active' => $hour->is_active,
            ]);

        return response()->json([
            'data' => $hours,
        ]);
    }
}
