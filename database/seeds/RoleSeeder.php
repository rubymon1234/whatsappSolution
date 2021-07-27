<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Role::truncate();

        $roles = [
            [
                'name' => 'admin',
                'slug' => 'admin.dashboard',
                'display_name' =>  'Admin',
                'description' => 'Admin Role'
            ],
            [
                'name' => 'user',
                'slug' => 'user.dashboard',
                'display_name' =>  'User',
                'description' => 'User Role'
            ],
            [
                'name' => 'reseller',
                'slug' => 'reseller.dashboard',
                'display_name' =>  'Reseller',
                'description' => 'Reseller Role'
            ],
        ];
        //Insertion
        foreach ($roles as $role) {
	        Role::create([
	            'name' => $role['name'],
	            'slug' => $role['slug'],
	            'display_name' => $role['display_name'],
	            'description' => $role['description'],
	        ]);
    	}
	}
}
