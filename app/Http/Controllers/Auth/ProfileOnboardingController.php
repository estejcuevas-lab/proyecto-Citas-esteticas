<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Support\AuthRedirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileOnboardingController extends Controller
{
    public function edit(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if ($user->hasCompletedProfile()) {
            return redirect()->route(AuthRedirect::routeFor($user));
        }

        return view('auth.complete-profile', [
            'user' => $user,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
        ]);

        $user = $request->user();

        $user->forceFill([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'profile_completed_at' => now(),
        ])->save();

        return redirect()
            ->route(AuthRedirect::routeFor($user))
            ->with('status', 'Tu perfil fue completado correctamente.');
    }
}
