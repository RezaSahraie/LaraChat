<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class AuthController extends Controller
{
    /**
     * Display the login page.
     *
     * This method renders the login form using Inertia.js.
     * The login form will be displayed as a Vue/React component
     * located at resources/js/Pages/Auth/Login.vue (or .jsx).
     *
     * @return Response The Inertia response rendering the login view
     */
    public function showLogin(): Response {
        return Inertia::render('Auth/Login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * This method validates the user's credentials and attempts to log them in.
     * If authentication fails, it redirects back with an error message.
     * If authentication succeeds, it regenerates the session to prevent
     * session fixation attacks and redirects to the intended page.
     *
     * @param Request $request The incoming HTTP request with login credentials
     * @return RedirectResponse Redirects to chat index on success, or back with errors on failure
     */
    public function login(Request $request): RedirectResponse {
        // Validate the incoming request data
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Attempt to authenticate the user with the provided credentials
        if (! Auth::attempt($credentials)) {
            // Authentication failed: redirect back with error message
            return back()->withErrors([
                'email' => 'Incorrect email or password.',
            ])->onlyInput('email'); // Preserve the email input for the user
        }

        $request->session()->regenerate();  // Authentication successful: regenerate the session ID
        // Redirect the user to their intended destination
        return redirect()->intended(route('chat.index'));
    }

    /**
     * Log the user out of the application.
     *
     * This method logs out the currently authenticated user,
     * invalidates the session to remove all session data,
     * regenerates the CSRF token for security,
     * and redirects the user to the login page.
     *
     * @param Request $request The incoming HTTP request
     * @return RedirectResponse Redirects to the login page
     */
    public function logout(Request $request): RedirectResponse {
        Auth::logout();     // Log out the currently authenticated user

        $request->session()->invalidate();      // Invalidate the session: clear all session data
        $request->session()->regenerateToken();     // Regenerate the CSRF token to prevent cross-site request forgery

        return redirect(route('login'));      // Redirect the user to the login page
    }
}