<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

use App\Models\UserManagement\Permission;
use App\Models\UserManagement\User;
use App\Models\UserManagement\Role;

use Exception;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            $permissions = config('permissions');

            // Find the super admin role.
            $superAdmin = User::with('roles')->where('secured', 1)->first();
          
            
            if (!$superAdmin) {
                throw new Exception("Superadmin not found. Please create a superadmin user with the 'secured' field set to 1.");
            }
            
            $superAdminRoleId = $superAdmin->roles[0]->id;
            $superAdminRole = Role::find($superAdminRoleId);

            if (!$superAdminRole) {
                throw new Exception("Role with ID {$superAdminRoleId} not found");
            }

           
            // For each permission in the config file, create a new permission if it doesn't exist. Then, attach the permission to the super admin role.
            foreach ($permissions as $permissionData) {
                // Create the permission if it doesn't exist.
                $permission = Permission::firstOrCreate([
                    'name' => $permissionData['name'],
                    'code' => $permissionData['code'],
                    'entity' => $permissionData['entity'],
                ], $permissionData);
       
                // If the super admin role doesn't have the permission, attach it.
                if (!$superAdminRole->permissions->contains($permission->id)) {
                    $superAdminRole->permissions()->attach($permission->id);
                    echo "New permission added: " . $permission['code'] . "\n";
                }
            }
        } catch (Exception $e) {
            // Handle the exception here
            echo "An error occurred: " . $e->getMessage();
        }
    }
}
