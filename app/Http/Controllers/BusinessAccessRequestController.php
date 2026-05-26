<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BusinessAccessRequestController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return redirect()->route('dashboard');
        }

        if ($user->needsProfileCompletion()) {
            return redirect()->route('onboarding.profile.edit');
        }

        if ($user->isBusinessApproved()) {
            return redirect()->route('businesses.index');
        }

        if ($user->hasPendingBusinessRequest()) {
            return redirect()->route('business-access.pending');
        }

        return view('auth.request-business-access', [
            'user' => $user,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        abort_if($user->isAdmin(), 403, 'Los administradores no requieren solicitar acceso business.');

        if ($user->needsProfileCompletion()) {
            return redirect()->route('onboarding.profile.edit');
        }

        if ($user->isBusinessApproved()) {
            return redirect()->route('businesses.index');
        }

        if ($user->hasPendingBusinessRequest()) {
            return redirect()->route('business-access.pending');
        }

        $user->forceFill([
            'business_requested_at' => now(),
            'business_approved_at' => null,
        ])->save();

        return redirect()
            ->route('business-access.pending')
            ->with('status', 'Tu solicitud de acceso business fue enviada. Un administrador debe aprobarla.');
    }

    public function pending(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if ($user->isBusinessApproved()) {
            return redirect()->route('businesses.index');
        }

        if (! $user->hasPendingBusinessRequest()) {
            return redirect()->route('dashboard');
        }

        return view('auth.business-pending', [
            'user' => $user,
        ]);
    }

    public function approve(Request $request, User $user): RedirectResponse
    {
        abort_unless($request->user()?->isAdmin(), 403);

        abort_unless($user->business_requested_at !== null && $user->business_approved_at === null, 404);

        $user->forceFill([
            'role' => User::ROLE_BUSINESS,
            'business_approved_at' => now(),
        ])->save();

        return back()->with('status', 'El acceso business fue aprobado correctamente.');
    }
}
