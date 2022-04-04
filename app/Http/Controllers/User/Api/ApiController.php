<?php

namespace App\Http\Controllers\User\Api;

use App\Models\User;
use App\Models\Instance;
use App\Models\Api;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    /**
     * Create Instance and view (GET)
     * @author Ruban
    */
    public function getApiView(){
        $apiList = Api::where('user_id',Auth::user()->id)->paginate(10);
    	return view('user.api.apiView', compact('apiList'));
    }
    public function getApiCreate(){

        $instanceDetail = Instance::where('user_id',Auth::user()->id)
                        ->whereIn('is_status',[1,3])
                        ->orderBy('updated_at','DESC')
                        ->get();
        $api_key = $this->apiUnique();

        return view('user.api.apiCreate', compact('api_key','instanceDetail'));
    }
    public function postApiCreate(Request $request){
        
        $rule = [
            'api_name' => 'required',
            'api_key' => 'required',
            'instance'        => 'required',
        ];
        $messages = [
            'api_name.required' => 'Api name required',
            'api_key.required' => 'Api Key is required',
            'instance.required' => 'Instance is required',
        ];
        $validator = Validator::make(Input::all(), $rule, $messages);
        if ($validator->fails()) {
             return redirect()->route('api.key.view')->with('error_message', "Oops, Something went wrong.");  

        }else{

            $apiInsert = new Api();
            $apiInsert->user_id = Auth::user()->id;
            $apiInsert->api_name = $request->api_name;
            $apiInsert->api_key = $request->api_key;
            $apiInsert->reseller_id =  Auth::user()->reseller_id;
            $apiInsert->instance_token = $request->instance;
            $apiInsert->is_status = 1;
            $apiInsert->save();
            $apiList = Api::where('user_id',Auth::user()->id)->paginate(10);
            return view('user.api.apiView', compact('apiList'));
        }
    }
    public function apiUnique(){
        $api_key = $this->generateApiKey();
        $unique = Api::where('api_key',$api_key)->where('is_status',1)->get();
        if($unique->count() !=0){
            return $this->apiUnique();
        }
        return $api_key;
    }
    public function generateApiKey(){
        return $api_key = Helper::generateUniqueId().Helper::generateUniqueId().Helper::generateUniqueId();
    }
    public function postBlockApi(Request $request){

        try{
           /* $userUpdate = Api::find(Crypt::decryptString($request->api));
            $userUpdate->is_status = $request->status; // block & unblock
            if($userUpdate->save()){

                return response()->json([
                        'success' => true,
                        'message' =>'success',
                        'response' => 'User Details Updated Successfully '
                    ]);
            }*/
            
        }catch(\Exception $e){

            return response()->json([
                'success' => false,
                'message' => 'Oops, Something Went Wrong',
            ]);
        }
   }
}


