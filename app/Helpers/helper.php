<?php
namespace App\Helpers;

use DB;
use Redirect;
use Validator;
use App\Models\Domain;
use App\Models\RoleUser;
use App\Models\Role;
use App\Models\User;
use App\Models\Plan;
use App\Models\Accounts;
use App\Models\Instance;
use App\Models\CurrentPlan;
use App\Models\InteractiveMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;
use App\Models\ApiApplication;
use App\Models\TextApplication;
use App\Models\CaptureApplication;
use App\Models\ImageApplication;
use App\Models\LocationApplication;
use App\Models\VideoApplication;
use App\Models\ButtonBodies;
use App\Models\ListBody;
use App\Models\TimeConditionApplication;
use Exception;

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
        //$domain = self::getFqdn($domain);
    	$domainDetail = Domain::where('domain_name',$domain)->where('is_active',1)->first();
    	return $domainDetail;
    }
    public static function getDomainNameId($domain_id){
        
        $domainDetails = Domain::find(Crypt::decrypt($domain_id));
        return $domainDetails;
    }
    public static function getUserRole($user_id){
    	$id = Crypt::decrypt($user_id);
    	$role = RoleUser::where('user_id',$id)->first();
    	$roleUser = Role::find($role->role_id);
    	return $roleUser;
    }
    public static function getUserDetail($user_id){
    	$user = array();
    	$id = Crypt::decrypt($user_id);
    	$user = User::find($id);
    	if($user){
    		return $user;
    	}
    	return $user;
    }
    public static function getPlanDetail($plan_id){

    	$plan_id = Crypt::decrypt($plan_id);
    	return $planDetail = Plan::find($plan_id);
    }
    public static function getPlanDetailView($current_plan_id){

        $current_plan_id = Crypt::decrypt($current_plan_id);
        $planDetail = DB::table('current_plans')
                            ->join('plans', 'plans.id', '=', 'current_plans.plan_id')
                            ->where('current_plans.id','=',$current_plan_id)
                            ->select('plans.id as pId','current_plans.id as currentPid','current_plans.bot_instance_count','current_plans.user_id','current_plans.plan_validity','plans.plan_name')
                            ->first();
        return $planDetail;
    }
    public static function generateToken()
    {
        mt_srand((double)microtime()*10000);
        $charid = strtolower(md5(uniqid(rand(), true)));
        $salt =  substr($charid, 0, 2).substr($charid, 4, 2).substr($charid,9, 2).substr($charid,12, 2);
        return $salt;
    }
    public static function generateUniqueId()
    {
        mt_srand((double)microtime()*10000);
        $charid = strtolower(md5(uniqid(rand(), true)));
        $salt =  substr($charid, 0, 4).substr($charid, 4, 4).substr($charid,9, 4).substr($charid,12, 4);
        return $salt;
    }

    public static function getUserActivePlans($user_id){
        $plan = array();
        $user_id = Crypt::decrypt($user_id);
        $plans = DB::table('current_plans')->where('current_plans.user_id',$user_id)->where('current_plans.is_status',1)
                 ->leftJoin('plans', 'plans.id', '=', 'current_plans.plan_id')
                 ->select('current_plans.*','plans.plan_validity as planValidity','plans.plan_name')
                 ->first();
        if(isset($plans)){
            $today_date = date('Y-m-d');
            if($plans->plan_validity >= $today_date){
                $status = 'Active';
            }else{
                $status = 'Expired';
            }
            if($plans->plan_validity ==NULL){
                $status = '<a href="'.route('user.plan.my.plans').'">Pack Need to active</a>';
            }
            $plan['plan_name'] = $plans->plan_name;
            $plan['status'] = $status;
            $plan['daily'] = $plans->daily_count; 
        }
        return $plan;
    }
    public static function getNextAppNameView($combination,$id) {
        $response = '';
       try {
           $nameList = [];
           
            $id     = Crypt::decryptString($id);
           switch ($combination) {
                case 'text':
                    $nameList = TextApplication::where("id", $id)->select("name")->get();
                   break;

                case 'image':
                    $nameList = ImageApplication::where("id", $id)->select("name")->get();
                    break;

                case 'video':
                    $nameList = VideoApplication::where("id", $id)->select("name")->get();
                    break;

                case 'capture':
                    $nameList = CaptureApplication::where("id", $id)->select("name")->get();
                    break;

                case 'api':
                    $nameList = ApiApplication::where("id", $id)->select("name")->get();
                    break;
                            
                case 'location':
                    $nameList = LocationApplication::where("id", $id)->select("name")->get();
                    break;
                    
                case 'timeCondition':
                    $nameList = TimeConditionApplication::where("id", $id)->select("name")->get();
                    break;
                case 'menu':
                    $nameList = InteractiveMenu::where("id", $id)->select("name")->get();
                    break;
                default:
                   # code...
                   break;
           }
          foreach($nameList as $row) {
               $response = $row->name;
           }
           if(count($nameList) == 0) {
                $response = "";
           }
           return $response;
       } catch (\Exception $e) {
            return $response;
       }
   }

   public static function getChatInstanceDetail($instance_id) {

        $instance_id = Crypt::decrypt($instance_id);
        return Instance::find($instance_id);
   }

   public static function getCredits($user_id){
        $user_id = Crypt::decrypt($user_id);
        $creditsDetail = Accounts::where('user_id',$user_id)->first();

        if($creditsDetail){
            return $creditsDetail;
        }
        
        return false;
   }
   public static function getButtonDetail($id){
        $button_id = Crypt::decrypt($id);
        $buttonDetail = ButtonBodies::where('button_application_id',$button_id)->get();
        return $buttonDetail;
   }
   public static function getListDetail($id){
        $list_id = Crypt::decrypt($id);
        $listDetail = ListBody::where('list_application_id',$list_id)->get();
        return $listDetail;
   }
}