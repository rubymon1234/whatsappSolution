<?php

namespace App\Http\Controllers\Admin;

use Crypt;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use App\Models\RoleUser;
use App\Models\Plan;
use App\Models\Accounts;
use App\Models\PermissionRole;
use App\Models\Domain;
use App\Models\PlanRequest;
use App\Models\PurchaseHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Helper;

class UserController extends Controller
{
    /**
     * ViewRole 
     * @author Ruban
    */
   	public function getUserView(Request $request)
    {
    	$key = $request->qa;
    	$users = User::where('name', 'LIKE', '%' . $key . '%')
                        //->leftJoin('domains', 'domains.id', '=', 'users.domain_id')
        				->orWhere('email', 'LIKE', '%' . $key . '%')
        				->orderBy('updated_at', ' DESC')->paginate(10);

        return view('admin.user.userView',compact('users','key'));
    }
    /**
     * Create User (GET)
     * @author Ruban
    */
    public function getUserCreate(){

    	return view('admin.user.userCreate');
    }
    /**
     * Create User (POST) 
     * @author Ruban
    */
    public function postUserCreate(Request $request){

    	try {
    		
	    	if($request->Update =='Save'){
          
                $rule = [
                    'name' => 'required',
                    'email' => 'required|email',
                    'password' => 'required',
                    'mobile'        => 'required',
                ];
		        $messages = [
		            'name.required' => 'user Name is required',
		            'name.unique' => 'user Name Already Exist',
		            'email.unique' => 'email is required',
		            'password.required' => 'password is required',
                    'mobile.required' => 'mobile is required',
		        ];

		        $validator = Validator::make(Input::all(), $rule, $messages);

		        if ($validator->fails()) {

		        	return redirect()->route('admin.user.create')->withInput(Input::all())->withErrors($validator);

		        }else{
		        		$domainDetail = Helper::getDomainDetail($_SERVER['SERVER_NAME']);
		        		$emailValid = User::where('email',$request->email)->where('domain_id',$domainDetail->id)->get();
		        		if($emailValid->count()==0){
		        			 $insertUser = User::create([
                            'name' => $request->name,
                            'email' => $request->email,
                            'password' => Hash::make($request->password),
                            'mobile' => $request->mobile,
                            'reseller_id' => Auth::user()->id,
                            'domain_id' => $domainDetail->id,
                            'is_status' => 1,
                        ]);
                        $user_id = $insertUser->id;
                        $role = Role::where('name','user')->first();
                        RoleUser::create([
                        	'user_id' => $user_id,
                        	'role_id' => $role->id,
                        ]);
                        Accounts::create([
                            'user_id' => $user_id,
                            'reseller_id' => Auth::user()->id,
                            'credits' => 0,
                        ]);
	                     if($insertUser){
	                     	return redirect()->route('admin.user.view')->with('success_message', 'New User successfully Added');
	                     }  
		        		return redirect()->route('admin.user.create')->with('warning_message', 'Oops something went wrong');
		        	}
		        	return redirect()->route('admin.user.create')->with('warning_message', 'Email is already exist');
                       
		        }
	    	}
	    	if ($request->Cancel =='cancel') {
	    		 return redirect()->route('admin.user.view')->with('warning_message', 'Request is Rollback');
	    	}
    	} catch (\Exception $e) {
        	return redirect()->route('admin.user.view')->with('warning_message', $e->getMessage());
    	}
    }
    /**
     * Create Reseller (GET)
     * @author Ruban
    */
    public function getResellerCreate(Request $request){
    	$roles = Role::all();
    	return view('admin.user.resellerCreate',compact('roles'));
    }
     /**
     * Create Reseller (POST)
     * @author Ruban
    */
    public function postResellerCreate(Request $request){

    	try {
    		
	    	if($request->Update =='Save'){
          
                $rule = [
                    'name' => 'required',
                    'email' => 'required|email',
                    'password' => 'required',
                    'mobile'        => 'required',
                    'domain_name'        => 'required|unique:domains',
                    'company_name'        => 'required',
                    'user_role_id'        => 'required',
                    'reseller_role_id'        => 'required',
                ];
		        $messages = [
		            'name.required' => 'user Name is required',
		            'domain_name.unique' => 'domain Name Already Exist',
		            'email.unique' => 'email is required',
		            'password.required' => 'password is required',
                    'mobile.required' => 'mobile is required',
                    'company_name.required' => 'company name is required',
                    'user_role_id.required' => 'User role is required',
                    'reseller_role_id.required' => 'Reseller role is required',
		        ];

		        $validator = Validator::make(Input::all(), $rule, $messages);

		        if ($validator->fails()) {

		        	return redirect()->route('admin.user.reseller.create')->withInput(Input::all())->withErrors($validator);

		        }else{
		        		$domainName = Helper::getFqdn($request->domain_name);
		        		$domainDetail = Helper::getDomainDetail($domainName);
		        		$domainValid = Domain::where('domain_name',$domainName)->get();
		        		if($domainValid->count()==0){
		        			
		        			//user creation
		        			$insertUser = User::create([
	                            'name' => $request->name,
	                            'email' => $request->email,
	                            'password' => Hash::make($request->password),
	                            'mobile' => $request->mobile,
	                            'reseller_id' => Auth::user()->id,
	                            'domain_id' => null,
	                            'is_status' => 1,
                        	]);
                        	$user_id = $insertUser->id;
                        	
                        	//domain creation
		        			$insertDomain = Domain::create([
		        				'domain_name' => $domainName,
		        				'company_name' => $request->company_name,
		        				'owner_id' => $user_id,
		        				'reseller_id' => Auth::user()->id,
		        				'user_role_id' => $request->user_role_id,
		        				'user_role_id' => $request->user_role_id,
		        				'reseller_role_id' => $request->reseller_role_id,
		        			]);

			        		//Role Insertion
	                        $roleInsert = RoleUser::create([
	                        	'user_id' => $user_id,
	                        	'role_id' => $request->reseller_role_id,
	                        ]);
	                        //User domain id update
	                        $updateDomain = User::find($user_id);
	                        $updateDomain->domain_id = $insertDomain->id;
	                        $updateDomain->save();
	                    
	                    if($updateDomain){
	                     	return redirect()->route('admin.user.view')->with('success_message', 'New User successfully Added');
	                     } 

		        		return redirect()->route('admin.user.reseller.create')->with('warning_message', 'Oops something went wrong');
		        	}
		        	return redirect()->route('admin.user.reseller.create')->with('warning_message', 'Email is already exist');
                       
		        }
	    	}
	    	if ($request->Cancel =='cancel') {
	    		 return redirect()->route('admin.user.view')->with('warning_message', 'Request is Rollback');
	    	}
    	} catch (\Exception $e) {
        	return redirect()->route('admin.user.view')->with('warning_message', $e->getMessage());
    	}
    }
    /**
     * Recharge Request (GET)
     * @author Ruban
    */
    public function getUserRechargeRequestView(Request $request,$id){
        $user = User::find(Crypt::decrypt($id));
        $plans = Plan::all();
        return view('admin.user.rechargeRequest', compact('user','plans'));
    }
    /**
     * Recharge Request (POST)
     * @author Ruban
    */
    public function postUserRechargeRequestView(Request $request,$id){
        
        try {

            $user_id = Crypt::decrypt($id);
            $reseller_id = Auth::user()->id;
            if($request->Update =='Save'){

                $rule = [
                    'plan_id' => 'required|numeric',
                    'credit' => 'required|numeric',
                ];
                $messages = [
                    'plan_id.required' => 'Plan is required',
                    'credit.required' => 'credit is required',
                ];
                
                $validator = Validator::make(Input::all(), $rule, $messages);

                if ($validator->fails()) {
                    return Redirect::back()
                                ->withErrors($validator)
                                ->withInput(Input::all());
                }else{
                    //request plan Insertion
                    $requestPlan = new PlanRequest();
                    $requestPlan->plan_id           = $request->get('plan_id');
                    $requestPlan->credit            = $request->get('credit');
                    $requestPlan->user_id           = $user_id;
                    $requestPlan->reseller_id       = $reseller_id;
                    $requestPlan->is_status         = 2;
                    $requestPlan->save();

                    //purchase Insertion
                    $purchaseHistInsert = new PurchaseHistory();
                    $purchaseHistInsert->plan_id = $request->get('plan_id');
                    $purchaseHistInsert->plan_request_id = $requestPlan->id;
                    $purchaseHistInsert->user_id = $user_id;
                    $purchaseHistInsert->reseller_id = $reseller_id;
                    $purchaseHistInsert->is_status = 2;
                    $purchaseHistInsert->save();
                    
                    if($purchaseHistInsert->save())
                        return redirect()->route('admin.user.view')->with('success_message', 'Plan Request successfully Added ');
                }
            }
            if ($request->Cancel =='cancel') {
                return redirect()->route('admin.user.view')->with('warning_message', 'Request is Rollback');
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.user.view')->with('warning_message', $e->getMessage());
        }
    }
}