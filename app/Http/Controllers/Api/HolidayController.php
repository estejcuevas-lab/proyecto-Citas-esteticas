<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use App\Services\HolidaySyncService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;

class HolidayController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $year = $request->integer('year');

        $holidays = Holiday::query()
            ->when($year, fn ($query) => $query->whereYear('holiday_date', $year))
            ->orderBy('holiday_date')
            ->get();

        return response()->json($holidays);
    }

    public function sync(Request $request, HolidaySyncService $holidaySyncService): JsonResponse
    {
        abort_unless(
            $request->user()?->isAdmin() || $request->user()?->isBusiness(),
            403
        );

        try {
            $result = $holidaySyncService->sync(
                (int) $request->integer('year', now()->year),
                (string) $request->string('country_code', 'CO')
            );
        } catch (RuntimeException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 502);
        }

        return response()->json([
            'message' => 'Festivos sincronizados correctamente.',
            'data' => $result,
        ]);
    }
}
