<?php

/**
 * AUTORES: Erick Cuevas- Camilo Ramirez
 * MATERIA: Cliente-Servidor
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\AuthRedirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        // ======================================================================
        // GUIA 1 - ACTIVIDAD 4: LOGICA DE NODOS
        // El cliente envia datos del formulario y el servidor los valida antes de persistirlos.
        // ======================================================================
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => null,
            'role' => User::ROLE_CLIENT,
            'password' => Hash::make($validated['password']),
            'avatar' => null,
            'auth_provider' => null,
            'provider_id' => null,
            'profile_completed_at' => null,
            'business_requested_at' => null,
            'business_approved_at' => null,
        ]);

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->route(AuthRedirect::routeFor($user));
    }
}
