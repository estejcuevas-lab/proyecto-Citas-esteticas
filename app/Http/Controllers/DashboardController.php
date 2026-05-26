<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Business;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();
        $pendingBusinessRequests = collect();

        $stats = [
            'businesses' => 0,
            'services' => 0,
            'appointments' => 0,
            'pending_appointments' => 0,
        ];

        if ($user->isAdmin()) {
            $stats['businesses'] = Business::count();
            $stats['services'] = Service::count();
            $stats['appointments'] = Appointment::count();
            $stats['pending_appointments'] = Appointment::where('status', Appointment::STATUS_PENDING)->count();
            $pendingBusinessRequests = User::query()
                ->whereNotNull('business_requested_at')
                ->whereNull('business_approved_at')
                ->latest('business_requested_at')
                ->get();
        } elseif ($user->isBusiness()) {
            $businessIds = $user->businesses()->pluck('id');

            $stats['businesses'] = $businessIds->count();
            $stats['services'] = Service::whereIn('business_id', $businessIds)->count();
            $stats['appointments'] = Appointment::whereIn('business_id', $businessIds)->count();
            $stats['pending_appointments'] = Appointment::whereIn('business_id', $businessIds)
                ->where('status', Appointment::STATUS_PENDING)
                ->count();
        } else {
            $stats['appointments'] = $user->appointments()->count();
            $stats['pending_appointments'] = $user->appointments()
                ->where('status', Appointment::STATUS_PENDING)
                ->count();
        }

        return view('dashboard', [
            'user' => $user,
            'stats' => $stats,
            'pendingBusinessRequests' => $pendingBusinessRequests,
        ]);
    }
}
