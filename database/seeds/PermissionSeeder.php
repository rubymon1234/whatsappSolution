<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\Permission;
use Illuminate\Support\Facades\Hash;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Permission::truncate();
        $permissions = [
        	[
                'name' => 'default.blank',
                'display_name' => 'default.blank',
                'description' => 'default.blank',
            ],
            [
                'name' => 'admin.dashboard',
                'display_name' => 'Admin Dashboard',
                'description' => 'Admin Dashboard Created',
            ],
            [
                'name' => 'acl.role.view',
                'display_name' => 'acl.role.view',
                'description' => 'acl.role.view',
            ],
            [
                'name' => 'acl.assign.role.permission',
                'display_name' => 'assign.role.permission',
                'description' => 'assign.role.permission',
            ],
            [
                'name' => 'acl.perms.view',
                'display_name' => 'acl.perms.view',
                'description' => 'acl.perms.view',
            ],
            [
                'name' => 'acl.perms.create',
                'display_name' => 'acl.perms.create',
                'description' => 'acl.perms.create',
            ],
            [
                'name' => 'acl.role.create',
                'display_name' => 'acl.role.create',
                'description' => 'acl.role.create',
            ],[
                'name' => 'admin.user.view',
                'display_name' => 'admin.user.view',
                'description' => 'admin.user.view',
            ],[
                'name' => 'admin.user.create',
                'display_name' => 'admin.user.create',
                'description' => 'admin.user.create',
            ],[
                'name' => 'user.dashboard',
                'display_name' => 'user.dashboard',
                'description' => 'user.dashboard',
            ],[
                'name' => 'admin.user.reseller.create',
                'display_name' => 'admin.user.reseller.create',
                'description' => 'admin.user.reseller.create',
            ],[
                'name' => 'reseller.dashboard',
                'display_name' => 'reseller.dashboard',
                'description' => 'reseller.dashboard',
            ],[
                'name' => 'reseller.user.view',
                'display_name' => 'reseller.user.view',
                'description' => 'reseller.user.view',
            ],[
                'name' => 'reseller.user.create',
                'display_name' => 'reseller.user.create',
                'description' => 'reseller.user.create',
            ],[
                'name' => 'reseller.user.reseller.create',
                'display_name' => 'reseller.user.reseller.create',
                'description' => 'reseller.user.reseller.create',
            ],[
                'name' => 'global.plan.view',
                'display_name' => 'global.plan.view',
                'description' => 'Admin plan view',
            ],
            [
                'name' => 'global.plan.create',
                'display_name' => 'global.plan.create',
                'description' => 'Admin plan create',
            ],
            [
                'name' => 'global.plan.update',
                'display_name' => 'global.plan.update',
                'description' => 'Admin plan update',
            ],[
                'name' => 'global.reseller.plan.view',
                'display_name' => 'global.reseller.plan.view',
                'description' => 'Reseller plan view',
            ],[
                'name' => 'reseller.user.recharge.request',
                'display_name' => 'reseller.user.recharge.request',
                'description' => 'Reseller recharge request',
            ],[
                'name' => 'admin.user.recharge.request.view',
                'display_name' => 'admin.user.recharge.request.view',
                'description' => 'Admin recharge request',
            ],[
                'name' => 'ajax.request.approve',
                'display_name' => 'ajax.request.approve',
                'description' => 'Admin recharge request approve',
            ],[
                'name' => 'ajax.request.reject',
                'display_name' => 'ajax.request.reject',
                'description' => 'Admin recharge request reject',
            ],
        ];
        //Insertion
        foreach ($permissions as $permission) {
	        Permission::create([
	            'name' => $permission['name'],
	            'display_name' => $permission['display_name'],
	            'description' => $permission['description'],
	        ]);
    	}
    }
}
