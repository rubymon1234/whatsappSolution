<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\Instance;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class InstanceController extends Controller
{
    /**
     * Create Instance and view (GET)
     * @author Ruban
    */
    public function getInstanceView(){
    	$instanceDetail = Instance::where('user_id',Auth::user()->id)->orderBy('updated_at','DESC')->paginate(10);
    	return view('user.instance.createInstance', compact('instanceDetail'));
    }
    /**
     * Create Instance and view (POST)
     * @author Ruban
    */
    public function postInstanceCreate(Request $request){

   		if($request->save =='save'){

            $rule = [
                'instance_name' => 'required',
            ];
            $messages = [
                'instance_name.required' => 'Role Name is required',
            ];

            $validator = Validator::make(Input::all(), $rule, $messages);

            if ($validator->fails()) {
                return redirect()->route('user.instance.view')->withInput(Input::all())->withErrors($validator);
            }else{
            	//get token
            	$token = Helper::generateToken();
            	$user_id = Auth::user()->id;
            	$userDetail = User::find($user_id);

                $newInstance = new Instance();
                $newInstance->instance_name         = $request->get('instance_name');
                $newInstance->token  = $token;
                $newInstance->user_id  = $user_id;
                $newInstance->reseller_id  = $userDetail->reseller_id;
                $newInstance->is_status = 0;
                if($newInstance->save())
                    return redirect()->route('user.instance.view')->with('success_message', 'New Instance successfully Added ');
            }
        }
        return redirect()->route('user.instance.view')->with('warning_message', 'Something went wrong.');
    }
}
