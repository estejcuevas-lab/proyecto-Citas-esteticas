<?php

namespace App\Http\Controllers;

use App\Services\HolidaySyncService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use RuntimeException;

class HolidayController extends Controller
{
    public function sync(Request $request, HolidaySyncService $holidaySyncService): RedirectResponse
    {
        abort_unless(
            $request->user()?->isAdmin() || $request->user()?->isBusiness(),
            403
        );

        $year = (int) $request->integer('year', now()->year);

        try {
            $result = $holidaySyncService->sync($year);
        } catch (RuntimeException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return back()->with(
            'success',
            "Se sincronizaron {$result['count']} festivos para {$result['year']}."
        );
    }
}
