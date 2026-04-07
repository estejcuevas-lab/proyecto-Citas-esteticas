<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBusinessHourRequest;
use App\Http\Requests\UpdateBusinessHourRequest;
use App\Models\Business;
use App\Models\BusinessHour;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BusinessHourController extends Controller
{
    public function index(Request $request, Business $business): View
    {
        $this->ensureOwnership($request, $business);

        return view('business-hours-index', [
            'business' => $business,
            'hours' => $business->hours()->orderBy('day_of_week')->get(),
            'days' => BusinessHour::dayOptions(),
        ]);
    }

    public function create(Request $request, Business $business): View
    {
        $this->ensureOwnership($request, $business);

        return view('business-hours-create', [
            'business' => $business,
            'days' => BusinessHour::dayOptions(),
        ]);
    }

    public function store(StoreBusinessHourRequest $request, Business $business): RedirectResponse
    {
        $this->ensureOwnership($request, $business);

        $business->hours()->create($request->validated());

        return redirect()
            ->route('businesses.hours.index', $business)
            ->with('status', 'Horario guardado correctamente.');
    }

    public function edit(Request $request, Business $business, BusinessHour $hour): View
    {
        $this->ensureOwnership($request, $business);
        $this->ensureSameBusiness($business, $hour);

        return view('business-hours-edit', [
            'business' => $business,
            'hour' => $hour,
            'days' => BusinessHour::dayOptions(),
        ]);
    }

    public function update(UpdateBusinessHourRequest $request, Business $business, BusinessHour $hour): RedirectResponse
    {
        $this->ensureOwnership($request, $business);
        $this->ensureSameBusiness($business, $hour);

        $hour->update($request->validated());

        return redirect()
            ->route('businesses.hours.edit', [$business, $hour])
            ->with('status', 'Horario actualizado correctamente.');
    }

    private function ensureOwnership(Request $request, Business $business): void
    {
        abort_unless(
            $request->user()?->isAdmin() || $business->user_id === $request->user()?->id,
            403,
            'No puedes gestionar los horarios de un negocio que no te pertenece.'
        );
    }

    private function ensureSameBusiness(Business $business, BusinessHour $hour): void
    {
        abort_unless($hour->business_id === $business->id, 404);
    }
}
