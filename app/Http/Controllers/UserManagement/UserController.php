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

class UserController extends Controller {
    private function getUsersWithRoles()
    {
    
        // Define the columns to be selected from the users table.
        // This ensures we are explicitly selecting only the columns we need, 
        // making the query more efficient and compliant with ONLY_FULL_GROUP_BY.
        // $userColumns = [
        //     'users.id', 
        //     'users.email', 
        //     'users.firstname', 
        //     'users.lastname', 
        //     'users.phone'
        // ];
    
        // Start building the query.
        $query = User::query();
            // ->select(array_merge($userColumns, [DB::raw("GROUP_CONCAT(DISTINCT roles.name ORDER BY roles.name SEPARATOR ', ') AS user_roles")]))
            // ->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
            // ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
            
    
        // Apply search filter if provided.
        // if ($searchTerm = request('search')) {
        //     $searchTerm = '%' . $searchTerm . '%';
        //     $query->where(function ($query) use ($searchTerm) {
        //         $query->where('users.email', 'LIKE', $searchTerm)
        //               ->orWhere('users.firstname', 'LIKE', $searchTerm)
        //               ->orWhere('users.lastname', 'LIKE', $searchTerm);
        //     });
        // }
    
        // Apply role filter if provided.
        if ($roleId = request('role')) {
            $query->whereExists(function ($subQuery) use ($roleId) {
                $subQuery->select(DB::raw(1))
                            ->from('role_user')
                            ->whereColumn('role_user.user_id', 'users.id')
                            ->where('role_user.role_id', '=', $roleId);
            });
        }
    
        // Group by the user id to ensure that each user is listed once with their roles aggregated.
        // Pagination is applied to make the query more efficient on large datasets.
        return $query->paginate(10);
    }

    private function getUserRoles() {
        return Role::where('deleted', false)->select('id', 'name as label')->get();
    }

    public function index() {
        return Inertia::render('UserManagement/Users/Index', [
            'response' => [],
            'users' => self::getUsersWithRoles(),
            'roleDropdownOptions' => self::getUserRoles(),
            'filters' => [
                'role' => request('filters') ? request('filters') : '',
                'search' => request('search') ? request('search') : ''
            ]
        ]);
    }

    public function create() {
        return Inertia::render('UserManagement/Users/CreateEdit', [
            'response' => [],
            'userData' => new stdClass(),
            'roles' => self::getUserRoles()
        ]);
    }

    public function edit( $userId ) {
        // Get user information.
        $user = User::find( $userId );
        $user->role = DB::table('role_user')->where('user_id', $user->id)->get()->pluck('role_id')->first();
       
        return Inertia::render('UserManagement/Users/CreateEdit', [
            'userData' => $user,
            'roles' => self::getUserRoles()
        ]);
    }

    public function store( Request $request ) {
        $validated = $request->validate([
            'username' => ['required'],
            'firstname' => ['required', 'max:50'],
            'lastname' => ['required', 'max:50'],
            'email' => ['required', 'max:50', 'email'],
            'phone' => ['nullable', 'regex:/^69[0-9]{8}$/', 'numeric'],
            'role' => ['required', 'numeric'],
            'password' => ['required', 'max:50', 'confirmed'],
        ]);
        
        try {
            DB::beginTransaction();

            // Create the user.
            $user = new User();
            $user->firstname = $request->firstname;
            $user->lastname = $request->lastname;
            $user->phone = $request->phone;
            $user->email = $request->email;
            $user->username = $request->username;
            $user->password = Hash::make($request->password);
            $user->save();

            // Associate the user with a role.
            DB::insert('insert into role_user (role_id, user_id) values (?, ?)', [intVal($request->role), $user->id]);
           
            DB::commit();
            return redirect()
                ->route('users.index')
                ->with([
                    'message' => 'Ο χρήστης δημιουργήθηκε με επιτυχία!',
                    'status' => 'success',
                ]);
        } catch (\Throwable $ex) {
            DB::rollBack();

            return Inertia::render('UserManagement/Users/Index', [
                'response' => [
                    'status' => 'error',
                    'message' => 'Oups, something went wrong while trying to save the user.'
                ],
                'users' => [],
                'roles' => [],
                'search' => ''
            ]);
        }
    }

    public function show( $id ) {
        return Inertia::render('UserManagement/Users/Show', [
            'userData' => User::with('roles')->find( $id )
        ]);
    }

    public function update(Request $request, $id) {
        // Error :: Id was not found.
        if( !isset($request->id) ) {
            return redirect()->route( 'users' )
                ->with(['message'=> 'Oups, something went wrong. User was not updated [CODE 001].']);
        }

        // Error :: Id is not a number.
        if( !is_int($request->id) ) {
            return redirect()->route( 'users' )
                ->with(['message'=> 'Oups, something went wrong. User was not updated [CODE 002].']);
        }

        // Find the user with that ID.
        $user = User::find( $request->id );
  
        // Error :: User was not found.
        if( is_null($user) ) {
            return redirect()->route( 'users' )
                ->with(['message'=> 'Oups, something went wrong. User was not updated [CODE 003].']);
        }

        // Update user information.
        $user->username = explode("@", $request->email)[0];
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->phone = $request->phone;
        $user->email = $request->email;

        // Change the user password only if the user has defined a new one.
        if( !empty($request->password) ) $user->password = Hash::make($request->password);

        $user->save();
        
        // Find the role with that ID.
        $role = Role::find( $request->role );
  
        // Error :: User was not found.
        if( is_null($role) ) {
            return redirect()->route( 'users' )
                ->with(['message'=> 'Oups, something went wrong. User updated but selected role was not found [CODE 004].']);
        }

        DB::table('role_user')->where('user_id', $user->id)->updateOrInsert(
            ['user_id' => $user->id],
            ['role_id' => $request->role]
        );

        return redirect()
            ->route('users.index')
            ->with([
                'message' => 'Ο χρήστης ενημερώθηκε με επιτυχία!',
                'status' => 'success',
            ]);
    }

    public function destroy(Request $request, $id) {
        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);

            abort_if($user->secured, 403);

            $user->update([
                'deleted' => true
            ]);

            DB::commit();

            return redirect()->route( 'users.index' )->with([
                'message' => 'Η διαγραφή ολοκληρώθηκε με επιτυχία!',
                'status' => 'success',
            ]);
        } catch (\Throwable $th) {
            return redirect()->route( 'users.index' )->with([
                'status' => 'error',
                'message'=> 'Ουπς, κάτι πήγε στραβά. Παρακαλούμε ξαναπροσπαθήστε.'
            ]);
        }
    }

    public function updateVersion(Request $request) {
        $request->validate([
            'version' => 'required|string',
        ]);
    
        // Update the version of the currently authenticated user
        $user = auth()->user();
        $user->last_version_seen = $request->version;
        $user->save();
    
        // Return a response, for example, a success message or the updated user
        return back();
    }
}
