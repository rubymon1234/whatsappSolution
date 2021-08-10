<?php

namespace App\Http\Controllers\Admin;

use DB;
use Crypt;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use App\Models\RoleUser;
use App\Models\PermissionRole;
use App\Models\Domain;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Helper;

class RechargeController extends Controller
{
    /**
     * ViewRole 
     * @author Ruban
    */
   	public function getRequestView(Request $request)
    {
        try{

    	$key = $request->qa;
        $plans = DB::table('plan_requests')
            ->leftJoin('plans', 'plans.id', '=', 'plan_requests.plan_id')
            ->leftJoin('users', 'users.id', '=', 'plan_requests.user_id')
            ->select('plans.plan_name','plans.id as plan_id','plan_requests.id as id','plans.daily_count','plans.plan_validity','plan_requests.credit','plan_requests.created_at','users.name as user_name','plan_requests.reseller_id','plans.scrub_count')
            ->where('plan_requests.is_status','=','2') //pending for approval
            ->paginate(10);

        return view('admin.recharge.requestView',compact('plans','key'));

        }catch(\Exception $e){

           return redirect()->route('admin.user.recharge.request.view')->with('warning_message', $e->getMessage());
        }
    }
}