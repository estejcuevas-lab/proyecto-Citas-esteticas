<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBusinessRequest;
use App\Http\Requests\UpdateBusinessRequest;
use App\Models\Business;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $businesses = $user->isAdmin()
            ? Business::query()->latest()->get()
            : $user->businesses()->latest()->get();

        return view('businesses.index', [
            'businesses' => $businesses,
            'user' => $user,
        ]);
    }

    public function create(Request $request): View
    {
        $this->ensureBusinessAccess($request);

        return view('businesses.create');
    }

    public function store(StoreBusinessRequest $request): RedirectResponse
    {
        $business = $request->user()->businesses()->create($request->validated());

        return redirect()
            ->route('businesses.edit', $business)
            ->with('status', 'Negocio creado correctamente.');
    }

    public function edit(Request $request, Business $business): View
    {
        $this->ensureBusinessOwnership($request, $business);

        return view('businesses.edit', [
            'business' => $business,
        ]);
    }

    public function update(UpdateBusinessRequest $request, Business $business): RedirectResponse
    {
        $this->ensureBusinessOwnership($request, $business);

        $business->update($request->validated());

        return redirect()
            ->route('businesses.edit', $business)
            ->with('status', 'Negocio actualizado correctamente.');
    }

    private function ensureBusinessAccess(Request $request): void
    {
        abort_unless(
            $request->user()?->isBusiness() || $request->user()?->isAdmin(),
            403,
            'Solo los negocios o administradores pueden gestionar negocios.'
        );
    }

    private function ensureBusinessOwnership(Request $request, Business $business): void
    {
        $this->ensureBusinessAccess($request);

        abort_unless(
            $request->user()->isAdmin() || $business->user_id === $request->user()->id,
            403,
            'No puedes modificar un negocio que no te pertenece.'
        );
    }
}
