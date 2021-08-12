<?php

namespace App\Http\Controllers\User;

use Crypt;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use App\Models\RoleUser;
use App\Models\PermissionRole;
use App\Models\Domain;
use App\Models\Plan;
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
     * @author Ruban
    */
    public function getProfileDetail(){
    	return view('web.global.myProfileView');
    }
    public function postProfileDetail(Request $request){
    	$tab = $request->tab;
    	if($request->update =='update'){

    		$rule = [
                'name' => 'required',
                'mobile' => 'required',
            ];
            $messages = [
                'name.required' => 'User Name is required',
                'mobile.required' => 'Mobile Number is required',
            ];

            $validator = Validator::make(Input::all(), $rule, $messages);

            if ($validator->fails()) {
                return back()->withInput(Input::all())->withErrors($validator);
            }else{

            	$updateUser = User::find(Auth::user()->id);
                $updateUser->name = $request->name;
            	$updateUser->mobile = $request->mobile;
            	if($updateUser->save()){
                    return Redirect::back()->with('success_message', 'User Detail updated successfully')->withInput(['tab'=>$tab]);
            	}
            }
    	}elseif($request->update =='change'){
    		
    		if (!(Hash::check($request->get('current_password'), Auth::user()->password))) {
	            // The passwords matches
	            return redirect()->back()->with("error_message","Your current password does not matches with the password you provided. Please try again.")->withInput(['tab'=>$tab]);
	        }


	        if(strcmp($request->get('current_password'), $request->get('new_password')) == 0){
	            //Current password and new password are same
	            return redirect()->back()->with("error_message","New Password cannot be same as your current password. Please choose a different password.")->withInput(['tab'=>$tab]);
	        }
	        if(strcmp($request->get('new_password'), $request->get('confirm_password')) != 0){
	            //Current password and new password are same
	            return redirect()->back()->with("error_message","New password & confirm password does not match")->withInput(['tab'=>$tab]);
	        }
	        $currentUser = User::find(Auth::user()->id);
	        if($currentUser)
	        {
	            $currentUser->update([
	                'password' => Hash::make($request->get('new_password'))
	            ]);
	        }
	        else
	        {
	            return redirect()->back()->with("error_message","Something went wrong")->withInput(['tab'=>$tab]);
	        }
	        
	        return redirect()->back()->with("success_message","Password changed successfully")->withInput(['tab'=>$tab]);
    	}elseif($request->Cancel =='cancel'){
            return Redirect::back()->with('warning_message', 'User Request is Rollback')->withInput(['tab'=>'']);
        }
    }
}	