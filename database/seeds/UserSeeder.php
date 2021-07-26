<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	Schema::disableForeignKeyConstraints();
        User::truncate();
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@mailinator.com',
                'password' =>  Hash::make('admin123'),
                'domain_id' => 1,
                'is_status' => 1,
                'mobile' => '8220509953',
            ],
        ];
        //Insertion
        foreach ($users as $user) {
            User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => $user['password'],
                'domain_id' => $user['domain_id'],
                'is_status'=> $user['is_status'],
                'mobile' => $user['mobile'],
            ]);
        }
    }
}
