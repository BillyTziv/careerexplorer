<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Illuminate\Support\Facades\Auth;

use App\Models\UserManagement\Role;
use App\Models\UserManagement\User;
use App\Models\UserManagement\Permission;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        if( Auth::check() ) {
            $loginUser = Auth::user();
            $userRole = User::with('roles')->find( $loginUser->id )->roles[0];
            
            $userPermissions = Permission::whereHas('roles', function ($query) use ($userRole) {
                $query->where('roles.id', $userRole->id);
            })->pluck('code')->toArray();

            return [
                ...parent::share($request),
                'auth' => [
                    'user' => [
                        'firstName' => $loginUser->firstname,
                        'lastName' =>$loginUser->lastname,
                        'email' => $loginUser->email,
                        'role' => $userRole->name,
                        'isSuperAdmin' => ( $loginUser->secured === 1 ) ? true : false,
                        'permissions' => $userPermissions,
                        'lastVersionSeen' => $loginUser->last_version_seen ?? null
                    ],

                ],
            ];
        }else {
            return [
                ...parent::share($request),
                'auth' => [
                    'user' => $request->user(),
                ],
            ];
        }
    }
}
