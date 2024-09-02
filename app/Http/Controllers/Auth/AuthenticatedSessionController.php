<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\UserManagement\User;
use App\Models\UserManagement\Permission;

use App\Models\Volunteer\Volunteer;
use App\Models\CareerRequest\CareerRequest;
use App\Models\Career\Career;
use App\Models\SessionRequest\SessionRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): Response
    {
        $request->authenticate();

        $request->session()->regenerate();

        $loginUser = Auth::user();
        $userRole = User::with('roles')->find( $loginUser->id )->roles[0];
        
        $userPermissions = Permission::whereHas('roles', function ($query) use ($userRole) {
            $query->where('roles.id', $userRole->id);
        })->pluck('code')->toArray();

        return Inertia::render('Dashboard', [
            'user' => [
                'firstName' => $loginUser->firstname,
                'lastName' =>$loginUser->lastname,
                'email' => $loginUser->email,
                'role' => $userRole->name,
                'isSuperAdmin' => ( $loginUser->secured === 1 ) ? true : false,
                'permissions' => $userPermissions,
                'lastVersionSeen' => $loginUser->last_version_seen ?? null
            ]
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return Inertia::render('Auth/Login');
    }
}
