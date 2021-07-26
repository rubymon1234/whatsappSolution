<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\Domain;
use Illuminate\Support\Facades\Hash;

class DomainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Domain::truncate();

        $domains = [
            [
                'domain_name' => '127.0.0.1',
                'layout' => 'test',
                'company_name' =>  'Reseller1',
                'owner_id' => 1,
                'reseller_id' => 1,
                'user_role_id' => 2,
                'reseller_role_id' => 3,
                'is_active' => 1,
            ],
        ];
        //Insertion
        foreach ($domains as $domain) {
	        Domain::create([
	            'domain_name' => $domain['domain_name'],
	            'layout' => $domain['layout'],
	            'company_name' => $domain['company_name'],
	            'owner_id' => $domain['owner_id'],
	            'reseller_id' => $domain['reseller_id'],
	            'user_role_id' => $domain['user_role_id'],
	            'reseller_role_id' => $domain['reseller_role_id'],
	            'is_active' => $domain['is_active'],
	        ]);
    	}
    }
}
