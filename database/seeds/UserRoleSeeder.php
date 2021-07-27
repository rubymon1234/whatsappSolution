<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\RoleUser;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       	Schema::disableForeignKeyConstraints();
        RoleUser::truncate();
        $usersRole = [
            [
                'user_id' => 1,
                'role_id' => 1,
            ],
        ];
        //Insertion
        foreach ($usersRole as $userRole) {
	        RoleUser::create([
	            'user_id' => $userRole['user_id'],
	            'role_id' => $userRole['role_id'],
	        ]);
    	}
    }
}
