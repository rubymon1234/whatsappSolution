<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\PermissionRole;
use Illuminate\Support\Facades\Hash;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        PermissionRole::truncate();
        $permissionsRole = [
            [
                'permission_id' => 2,
                'role_id' => 1,
            ],[
                'permission_id' => 3,
                'role_id' => 1,
            ],
            [
                'permission_id' => 4,
                'role_id' => 1,
            ],
            [
                'permission_id' => 5,
                'role_id' => 1,
            ],[
                'permission_id' => 6,
                'role_id' => 1,
            ],[
                'permission_id' => 7,
                'role_id' => 1,
            ],[
                'permission_id' => 8,
                'role_id' => 1,
            ],
            [
                'permission_id' => 11,
                'role_id' => 1,
            ],
            [
                'permission_id' => 9,
                'role_id' => 1,
            ],
        ];
        //Insertion
        foreach ($permissionsRole as $permissionRole) {
	        PermissionRole::create([
	            'permission_id' => $permissionRole['permission_id'],
	            'role_id' => $permissionRole['role_id'],
	        ]);
    	}
    }
}
