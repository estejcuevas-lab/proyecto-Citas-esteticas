<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\Business;
use App\Models\Service;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request, Business $business): View
    {
        $this->ensureBusinessOwnership($request, $business);

        return view('services.index', [
            'business' => $business,
            'services' => $business->services()->latest()->get(),
        ]);
    }

    public function create(Request $request, Business $business): View
    {
        $this->ensureBusinessOwnership($request, $business);

        return view('services.create', [
            'business' => $business,
        ]);
    }

    public function store(StoreServiceRequest $request, Business $business): RedirectResponse
    {
        $this->ensureBusinessOwnership($request, $business);

        $business->services()->create($request->validated());

        return redirect()
            ->route('businesses.services.index', $business)
            ->with('status', 'Servicio creado correctamente.');
    }

    public function edit(Request $request, Business $business, Service $service): View
    {
        $this->ensureSameBusiness($business, $service);
        $this->ensureBusinessOwnership($request, $business);

        return view('services.edit', [
            'business' => $business,
            'service' => $service,
        ]);
    }

    public function update(UpdateServiceRequest $request, Business $business, Service $service): RedirectResponse
    {
        $this->ensureSameBusiness($business, $service);
        $this->ensureBusinessOwnership($request, $business);

        $service->update($request->validated());

        return redirect()
            ->route('businesses.services.edit', [$business, $service])
            ->with('status', 'Servicio actualizado correctamente.');
    }

    private function ensureBusinessOwnership(Request $request, Business $business): void
    {
        abort_unless(
            $request->user()?->isAdmin() || $business->user_id === $request->user()?->id,
            403,
            'No puedes gestionar servicios de un negocio que no te pertenece.'
        );
    }

    private function ensureSameBusiness(Business $business, Service $service): void
    {
        abort_unless(
            $service->business_id === $business->id,
            404
        );
    }
}
