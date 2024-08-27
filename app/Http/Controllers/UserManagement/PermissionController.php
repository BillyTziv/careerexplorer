<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/* MOdels */
use App\Models\UserManagement\User;
use App\Models\UserManagement\Role;
use App\Models\UserManagement\Permission;

/* Support */
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use stdClass;

class PermissionController extends Controller
{
    private function getPermissions() {
        $query = Permission::query();

        // Datatable search parameter.
        if( request('search') ) {
            $query->where('name', 'LIKE', '%'.request('search').'%');
        }

        // Datatable filters parameter.
        if( request('category') ) {
            $query->where('entity', 'LIKE', '%'.request('category').'%');
        }

        // Get all non deleted users with their roles.
        $permissions = $query->get();
        return $permissions;
    }

    private function getPermissionCategories() {
        $categories = Permission::select('entity')
            ->groupBy('entity')
            ->get();

        return $categories;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('UserManagement/Permissions/Index', [
            'permissions' => self::getPermissions(),
            'permissionCategories' => self::getPermissionCategories(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('UserManagement/Permissions/CreateEdit',[
            'permission' => new stdClass(),
            'response' => []
        ]);
    }


    public function store(Request $request) {        
        $validated = $request->validate([
            'name' => ['required'],
            'code' => ['required'],
        ]);

        try {
            DB::beginTransaction();

            $permission = new Permission();

            $permission->name = $request->name;
            $permission->code = $request->code;

            $permission->save();

            DB::commit();

            Inertia::render('UserManagement/Permissions/CreateEdit',[
                'permission' => new stdClass(),
                'status' => 'success',
                'message' => 'Το δικαίωμα χρήστη δημιουργήθηκε με επιτυχία!'
            ]);
            
            // return back()->with([
            //     'status' => 'success',
            //     'message' => 'Το δικαίωμα χρήστη δημιουργήθηκε με επιτυχία!'
            // ]);
        } catch (\Throwable $th) {
            return redirect()->route( 'permissions.index' )->with([
                'status' => 'error',
                'message'=> 'Ουπς, κάτι πήγε στραβά. Παρακαλούμε ξαναπροσπαθήστε.'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $permission = Permission::select('id', 'name', 'code', 'entity')->find($id);

        if( is_null($permission) ) {
            return redirect()->route( 'permissions.index' )
                ->with(['message'=> 'Ουπς, κάτι πήγε στραβά. Το δικαίωμα δεν ενημερώθηκε.']);
        }
        
        return Inertia::render('UserManagement/Permissions/CreateEdit', [
            'response' => [],
            'permission' => $permission
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $permission = Permission::find( $request->id );

        if( is_null($permission) ) {
            return redirect()->route( 'permissions.index' )
                ->with(['message'=> 'Ουπς, κάτι πήγε στραβά. Το δικαίωμα δεν ενημερώθηκε.']);
        }
        
        try {
            DB::beginTransaction();
            
            $permission->name = trim($request->name);
            $permission->code = trim($request->code);
            $permission->entity = $request->entity;
            
            $permission->save();
            
            DB::commit();
        
            return redirect()
                ->route('permissions.index')
                ->with([
                    'message' => 'Το δικαίωμα ενημερώθηκε με επιτυχία!',
                    'status' => 'success'
                ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('permissions.index')
                ->with(['message' => 'Ουπς, κάτι πήγε στραβά. Το δικαίωμα δεν ενημερώθηκε.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( $permissionId )
    {
        if( !auth()->user()->secured ) {
            return redirect()
            ->route('permissions.index')
            ->with([
                'message' => 'Δεν έχετε το δικαίωμα για αυτή την ενέργεια. Επικοινωνήστε με τον διαχειριστή του συστήματος.',
                'status' => 'error',
            ]);
        }
        
        DB::table('permission_role')->where('permission_id', $permissionId)->delete();

        Permission::where('id', $permissionId )->delete();

        return redirect()
            ->route('permissions.index')
            ->with([
                'message' => 'Το δικαίωμα διαγράφτηκε με επιτυχία!',
                'status' => 'success',
            ]);
    }
}
