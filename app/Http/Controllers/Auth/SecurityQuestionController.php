<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SecurityQuestionController extends Controller
{
    /**
     * Show the security question for the given email.
     */
    public function show(Request $request): View|RedirectResponse
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user || !$user->security_question) {
            return back()->withErrors(['email' => 'No se encontró una pregunta de seguridad para este correo.']);
        }

        return view('auth.security-question', [
            'email' => $request->email,
            'security_question' => $user->security_question,
        ]);
    }

    /**
     * Verify the security answer and redirect to reset password.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'security_answer' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->security_answer, $user->security_answer)) {
            return back()->withErrors(['security_answer' => 'La respuesta es incorrecta.']);
        }

        // Generate a token for password reset (mimicking Laravel's behavior but for local use)
        $token = Password::createToken($user);

        return redirect()->route('password.reset', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }
}