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
use App\Models\ButtonApplication;
use App\Models\ListApplication;
use App\Models\ListBody;
use App\Models\Group;
use App\Models\GroupContact;
use Illuminate\Support\Str;
use App\Models\TimeConditionApplication;
use Exception;

class Helper {
	
    public static function getNextAppNameHelpher($app_name,$app_value =null) {
       $nameList = [];
       try {
           switch ($app_name) {
                case 'text':
                    $nameList = TextApplication::where("user_id", Auth::user()->id)->select("name", "id")->get();
                   break;

                case 'image':
                    $nameList = ImageApplication::where("user_id", Auth::user()->id)->select("name", "id")->get();
                    break;

                case 'video':
                    $nameList = VideoApplication::where("user_id", Auth::user()->id)->select("name", "id")->get();
                    break;

                case 'capture':
                    $nameList = CaptureApplication::where("user_id", Auth::user()->id)->select("name", "id")->get();
                    break;

                case 'api':
                    $nameList = ApiApplication::where("user_id", Auth::user()->id)->select("name", "id")->get();
                    break;
                            
                case 'location':
                    $nameList = LocationApplication::where("user_id", Auth::user()->id)->select("name", "id")->get();
                    break;
                    
                case 'timeCondition':
                    $nameList = TimeConditionApplication::where("user_id", Auth::user()->id)->select("name", "id")->get();
                    break;
                case 'menu':
                    $nameList = InteractiveMenu::where("user_id", Auth::user()->id)->select("name", "id")->get();
                    break;
                case 'button':
                    $nameList = ButtonApplication::where("user_id", Auth::user()->id)->select("name", "id")->get();
                    break;
                case 'list':
                    $nameList = ListApplication::where("user_id", Auth::user()->id)->select("name", "id")->get();
                    break;
                default:
                   # code...
                   break;
           }
           /*$selected ='';
           foreach($nameList as $row) {
            if(isset($app_value)){
                if($row->id == $app_value){
                    $selected = 'selected';
                }else{
                    $selected = '';
                }
            }else{
                $selected ='';
            }
               $response .= "<option value='" . $row->id . "' " . $selected . ">" . $row->name . "</option>";
            
           }
           if(count($nameList) == 0) {
                $response .= "<option value='null'></option>";
           }*/
           return $nameList;

       } catch (\Exception $e) {
            return $nameList;
       }
   }
   public static function getBodiesHelpher($combination_type,$body_id) {
       $nameList = [];
       try {
           switch ($combination_type) {
                case 'button':
                    $nameList = ButtonBodies::where("button_application_id", $body_id)->select("body", "id")->get();
                    break;
                case 'list':
                    $nameList = ListBody::where("list_application_id", $body_id)->select("body", "description","id")->get();
                    break;
                default:
                   # code...
                   break;
           }
           return $nameList;
       } catch (\Exception $e) {
            return $nameList;
       }
   }
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
   public static function getInstanceDetailToken($token) {

        return Instance::where('token',$token)->first();
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
   public static function getGroupContactCount($group_id){
   // $group_id = Crypt::decrypt($id);
    $groupContact = GroupContact::where('group_id',$group_id)->where('is_status',1)->get();
    return count($groupContact);
   }
   public static function getListDetail($id){
        $list_id = Crypt::decrypt($id);
        $listDetail = ListBody::where('list_application_id',$list_id)->get();
        return $listDetail;
   }
   public static function getBearerToken($request)
    {
       $header = $request->header('Authorization', '');
       if (Str::startsWith($header, 'Bearer ')) {
                return Str::substr($header, 7);
       }
    }
    public static function convert_number($number){
        if (($number < 0) || ($number > 999999999)) 
        {
            throw new Exception("Number is out of range");
        }
        $giga = floor($number / 1000000);
        // Millions (giga)
        $number -= $giga * 1000000;
        $kilo = floor($number / 1000);
        // Thousands (kilo)
        $number -= $kilo * 1000;
        $hecto = floor($number / 100);
        // Hundreds (hecto)
        $number -= $hecto * 100;
        $deca = floor($number / 10);
        // Tens (deca)
        $n = $number % 10;
        // Ones
        $result = "";
        if ($giga) 
        {
            $result .= $this->convert_number($giga) .  "Million";
        }
        if ($kilo) 
        {
            $result .= (empty($result) ? "" : " ") .$this->convert_number($kilo) . " Thousand";
        }
        if ($hecto) 
        {
            $result .= (empty($result) ? "" : " ") .$this->convert_number($hecto) . " Hundred";
        }
        $ones = array("", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", "Nineteen");
        $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty", "Seventy", "Eigthy", "Ninety");
        if ($deca || $n) {
            if (!empty($result)) 
            {
                $result .= " and ";
            }
            if ($deca < 2) 
            {
                $result .= $ones[$deca * 10 + $n];
            } else {
                $result .= $tens[$deca];
                if ($n) 
                {
                    $result .= "-" . $ones[$n];
                }
            }
        }
        if (empty($result)) 
        {
            $result = "zero";
        }
        return $result;
    }
}