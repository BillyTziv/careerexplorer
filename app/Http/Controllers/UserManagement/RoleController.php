<?php

namespace App\Http\Controllers\UserManagement;

use Inertia\Inertia;
use Inertia\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;

/* MOdels */
use App\Models\UserManagement\User;
use App\Models\UserManagement\Role;
use App\Models\UserManagement\Permission;

/* Support */
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use stdClass;

class RoleController extends Controller {
    private function getRolesWithPermissions() {
        $query = Role::query();

        if( request('search') ) {
            $query->where('name', 'LIKE', '%'.request('search').'%');
        }

        // Get all non deleted users with their roles.
        $roles = $query->where('deleted', false)->with('permissions')->paginate(10);
        return $roles;
    }
    
    public function index(): Response {
        return Inertia::render('UserManagement/Roles/Index', [
            'response' => [],
            'roles' => self::getRolesWithPermissions(),
            'search' => request('search') ? request('search') : ''
        ]);
    }

    public function create(): Response {
        return Inertia::render('UserManagement/Roles/CreateEdit',[
            'permissions' => Permission::all(),
            'role' => new stdClass(),
            'response' => []
        ]);
    }

    public function store(Request $request): Response {
        $validated = $request->validate([
            'name' => ['required'],
        ]);

        try {
            DB::beginTransaction();

            // Create the new role.
            $role = new Role();
            $role->name = $request->name;
            $role->save();

            // Give the role its selected permissions.
            $permissionIds = collect($request->permissions)->pluck('id')->all();
            $role->permissions()->sync($permissionIds);

            DB::commit();

            return Inertia::render('UserManagement/Roles/Index',[
                'response' => []
            ]);
        } catch (\Throwable $th) {
            return redirect()->route( 'roles.index' )->with([
                'status' => 'error',
                'message'=> 'Ουπς, κάτι πήγε στραβά. Παρακαλούμε ξαναπροσπαθήστε.'
            ]);
        }
    }

    public function show($id): Response {
        // Intentionally left empty.
    }

    public function edit( $roleId ): Response {
        $role = Role::find($roleId);
        $allPermissions = Permission::all()->map(function ($permission) use ($role) {
            $permission->value = $role->permissions->contains('id', $permission->id);
            return $permission;
        });

        return Inertia::render('UserManagement/Roles/CreateEdit',[
            'response' => [],
            'role' => $role,
            'permissions' => $allPermissions
        ]);
    }

    public function update(Request $request, $id): RedirectResponse {
        try {
            DB::beginTransaction();

            // Update the selected role.
            $role = Role::find( $request->id );
            $role->name = $request->name;
            $role->save();

            // Give the role its selected permissions.
            $permissionIds = collect($request->permissions)->pluck('id')->all();
            $role->permissions()->sync($permissionIds);

            DB::commit();

            return back()->with([
                'message' => 'Ο ρόλος ενημερώθηκε με επιτυχία!',
                'status' => 'success',
            ]);
        } catch (\Throwable $th) {
            return redirect()->route( 'roles.index' )->with([
                'status' => 'error',
                'message'=> 'Ουπς, κάτι πήγε στραβά. Παρακαλούμε ξαναπροσπαθήστε.'
            ]);
        }
    }

    public function destroy( $roleId ) {
        if( !auth()->user()->secured ) {
            return redirect()
            ->route('roles.index')
            ->with([
                'message' => 'Δεν έχετε το δικαίωμα για αυτή την ενέργεια. Επικοινωνήστε με τον διαχειριστή του συστήματος.',
                'status' => 'error',
            ]);
        }

        $role = Role::find( $roleId );
        $role->deleted = true;
        $role->save();

        return redirect()
            ->route('roles.index')
            ->with([
                'message' => 'Ο ρόλος διαγράφτηκε με επιτυχία!',
                'status' => 'success',
            ]);
    }
}