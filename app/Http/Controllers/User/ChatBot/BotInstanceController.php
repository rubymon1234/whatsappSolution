<?php

namespace App\Http\Controllers\User\ChatBot;

use DB;
use Carbon\Carbon;
use App\Models\CurrentPlan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use App\Models\ApiApplication;
use App\Models\TextApplication;
use App\Models\CaptureApplication;
use App\Models\ImageApplication;
use App\Models\LocationApplication;
use App\Models\VideoApplication;
use App\Models\TimeConditionApplication;
use App\Models\ChatInstance;
use App\Models\Instance;

class BotInstanceController extends Controller
{
	/**
     * Purchase View 
     * @author Ruban
    */
   	public function getInstanceList()
    {

        $chatInstanceList = ChatInstance::where('user_id',Auth::user()->id)->orderBy('updated_at','DESC')->paginate(10);

        return view('user.chatbot.instance.botInstanceList',compact('chatInstanceList'));
    }

    public function getInstanceCreate(){

    	$instanceDetail = Instance::where('user_id',Auth::user()->id)->whereIn('is_status',[1])->orderBy('updated_at','DESC')->get();

    	return view('user.chatbot.instance.botInstanceCreate',["combinationList" => $this->getDefaultCombinationList(),"instanceDetail" => $instanceDetail]);
    }
    public function postInstanceCreate(Request $request){

    	$rule = [
            'bot_instance_name' => 'required',
            'instance' => 'required',
            'combination' => 'required',
        ];
        $messages = [
            'bot_instance_name.required' => 'campaign name is required',
            'instance.required' => 'instance name is required',
            'combination.required' => 'combination is required',
        ];

        //validation error
        $validator = Validator::make(Input::all(), $rule, $messages);
        
        if ($validator->fails()) {

            return redirect()->route('user.chat.bot.instance.create')->withInput(Input::all())->withErrors($validator);
         }else{

         	$user = Auth::user();
         	$valid_result = $this->__appValidation($request);
         	//current plan
    		$currentPlan 	= CurrentPlan::where('is_status',1)->where('user_id',$user->id)->first();
    		if(isset($currentPlan->bot_instance_count)){ $bot_instance_count = $currentPlan->bot_instance_count; }else{
    			$bot_instance_count = 0;
    		}
    		$instanceCount = ChatInstance::where('user_id',$user->id)->where('is_status',1)->count();
    		
    		if($bot_instance_count > $instanceCount){
    			if($valid_result['status'] ==true){
         		
         		//get instance
         		$intanceDetail = Instance::find($request->instance)->first();

         		$chatInstance = new ChatInstance(); 
         		$chatInstance->user_id = $user->id;  
         		$chatInstance->reseller_id = $user->reseller_id;  
         		$chatInstance->name = $request->bot_instance_name;  
         		$chatInstance->instance_token = $intanceDetail->token;  
         		$chatInstance->app_name = strtoupper($request->text_app_name);  
         		$chatInstance->app_value = $request->text_app_name1;  

         		if($chatInstance->save()){
         			return redirect()->route('user.chat.bot.instance.list')->with('success_message', 'Bot Instance Assign Successfully');
         		}

         		return redirect()->route('user.compose.scrub.create')->with('success_message', 'Oops , Something went wrong');
         	}
    	
    	}else{
    		
         	return redirect()->route('user.chat.bot.instance.create')->with('error_message', 'Instance count is exceeded');
        }

         	return redirect()->back()->with("error_message",$valid_result['message'])->withInput(['tab'=>0]);
         }
    }
    public function __appValidation($request){
    	$array = array();
    	if($request->text_app_name =='' && $request->text_app_name1 ==''){
    		$array['status']= true;
    		$array['message']= 'Success';
    	}else if($request->text_app_name !='' && $request->text_app_name1 ==''){
    		$array['status']= false;
    		$array['message']= 'App Value is not null';
    	}else if($request->text_app_name =='' && $request->text_app_name1 !=''){
    		$array['status']= false;
    		$array['message']= 'App Name is not null';
    	}else if($request->text_app_name !='' && $request->text_app_name1 !=''){
    		$array['status']= true;
    		$array['message']= 'Success';
    	}

    	return $array;
    }
    protected function getDefaultCombinationList(): array
    {
        return array(
            'text' => "Text Only",
            'image' => "Image",
            'video' => "Video",
            'capture' => "Capture",
            'api' => "Api",
            "location" => "Location",
            "timeCondition" => "Time Condition"
        );
    }
}