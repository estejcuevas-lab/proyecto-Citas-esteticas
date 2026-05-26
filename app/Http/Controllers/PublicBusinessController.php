<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\BusinessHour;
use App\Models\User;
use Illuminate\Contracts\View\View;

class PublicBusinessController extends Controller
{
    public function index(): View
    {
        $businesses = Business::query()
            ->whereHas('user', function ($query): void {
                $query->where('role', User::ROLE_ADMIN)
                    ->orWhereNotNull('business_approved_at');
            })
            ->with([
                'services' => fn ($query) => $query
                    ->where('active', true)
                    ->orderBy('name'),
            ])
            ->orderBy('name')
            ->get();

        return view('businesses.public-index', [
            'businesses' => $businesses,
        ]);
    }

    public function show(Business $business): View
    {
        abort_unless(
            $business->user()
                ->where(function ($query): void {
                    $query->where('role', User::ROLE_ADMIN)
                        ->orWhereNotNull('business_approved_at');
                })
                ->exists(),
            404
        );

        $business->load([
            'services' => fn ($query) => $query
                ->where('active', true)
                ->orderBy('name'),
            'hours' => fn ($query) => $query
                ->where('is_active', true)
                ->orderBy('day_of_week'),
        ]);

        return view('businesses.public-show', [
            'business' => $business,
            'dayLabels' => BusinessHour::dayOptions(),
        ]);
    }
}
