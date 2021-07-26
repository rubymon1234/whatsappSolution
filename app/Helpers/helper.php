<?php
namespace App\Helpers;

use Redirect;
use Validator;
use App\Models\Domain;
use App\Models\RoleUser;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;

class Helper {
	
	public static function getFqdn($fqdn =null)
    {
    	$fqdn = trim($fqdn);
		if (!$fqdn){
			@$fqdn = $_SERVER['HTTP_HOST'];
		}
		$domain = explode(chr(46),$fqdn);
		if($domain['0'] == 'www'){
			if(count($domain) > 3){
				return  $domain['1'].chr(46).$domain['2'].chr(46).$domain['3'];
			}
			return  $domain['1'].chr(46).$domain['2'];
		}else{
			return $fqdn;
		}
    }
    public static function getDomainDetail($domain){
    	$domainDetail = Domain::where('domain_name',$domain)->where('is_active',1)->first();
    	return $domainDetail;
    }
    public static function getUserRole($user_id){
    	$id = Crypt::decrypt($user_id);
    	$role = RoleUser::where('user_id',$id)->first();
    	$roleUser = Role::find($role->role_id);
    	return $roleUser;
    }
}