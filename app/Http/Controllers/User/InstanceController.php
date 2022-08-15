<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\Instance;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Models\CurrentPlan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;

class InstanceController extends Controller
{
    /**
     * Create Instance and view (GET)
     * @author Ruban
    */
    public function getInstanceView(){
        //current plan
        $currentPlan    = CurrentPlan::where('is_status',1)->where('user_id',Auth::user()->id)->first();
    	$instanceDetail = Instance::where('user_id',Auth::user()->id)->whereIn('is_status',[0,1,3])->orderBy('updated_at','DESC')->paginate(10);
    	return view('user.instance.createInstance', compact('instanceDetail','currentPlan'));
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
                'instance_name.required' => 'Instance Name is required',
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
                $newInstance->web_hook_url  = $request->web_hook_url;
                $newInstance->reseller_id  = $userDetail->reseller_id;
                $newInstance->is_status = 0;
                if($newInstance->save())
                    return redirect()->route('user.instance.view')->with('success_message', 'New Instance successfully Added ');
            }
        }
        return redirect()->route('user.instance.view')->with('warning_message', 'Something went wrong.');
    }

     /**
     * Update Instance and view (GET)
     * @author Ruban
    */
    public function getInstanceUpdate($id){
        $id_t = Crypt::decrypt($id);
        $instanceDetail = Instance::find($id_t);
        return view('user.instance.updateInstance', compact('instanceDetail','id'));
    }
    /**
     * Create Instance and view (POST)
     * @author Ruban
    */
    public function postInstanceUpdate(Request $request,$id){

        if($request->save =='update'){

            $rule = [
                'instance_name' => 'required',
            ];
            $messages = [
                'instance_name.required' => 'Instance Name is required',
            ];

            $validator = Validator::make(Input::all(), $rule, $messages);

            if ($validator->fails()) {
                return redirect()->route('user.instance.update')->withInput(Input::all())->withErrors($validator);
            }else{
                $newInstance = Instance::find(Crypt::decrypt($id));
                $newInstance->instance_name = $request->get('instance_name');
                $newInstance->web_hook_url  = $request->web_hook_url;
                if($newInstance->save())
                    return redirect()->route('user.instance.view')->with('success_message', 'Instance  successfully update');
            }
        }
        return redirect()->route('user.instance.view')->with('warning_message', 'Something went wrong.');
    }
}
