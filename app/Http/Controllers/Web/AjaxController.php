<?php

namespace App\Http\Controllers\Web;

use Crypt;
use Carbon\Carbon;
use App\Models\Instance;
use App\Models\PlanRequest;
use App\Models\PurchaseHistory;
use App\Models\Plan;
use App\Models\User;
use App\Models\ChatInstance;
use App\Models\CurrentPlan;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\ApiApplication;
use App\Models\TextApplication;
use App\Models\CaptureApplication;
use App\Models\ImageApplication;
use App\Models\LocationApplication;
use App\Models\VideoApplication;
use App\Models\TimeConditionApplication;
use App\Models\InteractiveMenu;
use App\Models\MenuInput;
use App\Models\ListApplication;
use App\Models\ListBody;
use App\Models\ButtonApplication;
use App\Models\ButtonBodies;
use App\Models\Api;
use Exception;

class AjaxController extends Controller
{
    /**
     * Request Approve
     * @author Ruban
    */
   	public function postRequestApprove(Request $request)
    {
    	try{

    		$plan_req_id 	= Crypt::decryptString($request->plan_req_id);
    		$status 		= $request->status;
    		$res 		= $request->res;
	    	if($request->ajax()) {
	    		//plan detail entry
	    		$planApprove = PlanRequest::find($plan_req_id);
	    		$planApprove->is_status = $status;
	    		$planApprove->save();

	    		//purchase history entry
	    		$getPurchaseDetail = PurchaseHistory::where('plan_request_id',$plan_req_id)->first();
	    		$planApproveHistory = PurchaseHistory::find($getPurchaseDetail->id);
	    		$planApproveHistory->is_status = $status;
	    		$planApproveHistory->save();

	    		$planApproveDetail = PurchaseHistory::find($planApproveHistory->id);
	    		$planDetail = Plan::find($planApproveDetail->plan_id);

	    		//CurrentPlan
	    		if($status==1){
	    			$currentPlanInsert = new CurrentPlan();
	                $currentPlanInsert->plan_id = $planApproveDetail->plan_id;
	                $currentPlanInsert->daily_count = $planDetail->daily_count;
	                $currentPlanInsert->scrub_count = $planDetail->scrub_count;
	                $currentPlanInsert->bot_instance_count = $planDetail->bot_instance_count;
	                $currentPlanInsert->user_id = $planApproveDetail->user_id;
	                $currentPlanInsert->reseller_id = $planApproveDetail->reseller_id;
	                $currentPlanInsert->is_status = 2;
	                $currentPlanInsert->save();
	    		}

	    		if($planApproveHistory){

	    			return response()->json([
		                'success' => true,
		                'message' =>'success',
		                'response' => 'Plan Successfully '.$res
		            ]);
	    		}
	    	}

    	}catch(\Exception $e){

    		return response()->json([
	                'success' => false,
	                'message' => 'Oops, Something Went Wrong',
	            ]);
    	}
    }
    public function postQRScan(Request $request){

    	try{

	    	$instance_id = $request->get('instance_id');

	    	//get Instance
	    	$Instance = Instance::find(Crypt::decryptString($instance_id));

	    	$token 	= $Instance->token;
	    	$scan_url 	= 'http://135.181.82.89:8000/?id='.$token;

	    		return response()->json([
		                'success' => true,
		                'message' =>'success',
		                'scan_url' => $scan_url,
		                'response' => 'QA Successfully Generated'
		            ]);
		}catch(\Exception $e){

				return response()->json([
	                'success' => false,
	                'message' => 'Oops, Something Went Wrong',
	            ]);
    	}
    }

    public function postCurrentStatus(Request $request){

    	try{

    		$curr_plan_id = $request->get('curr_plan_id');
    		$status = $request->get('status');
    		$user_id = Auth::user()->id;

    		//Active - InActive
    		$currentPlan = CurrentPlan::where('user_id',$user_id)->where('is_status',1)->first();
    		if($currentPlan){
    			$currentPlan->is_status = 0;
    			$currentPlan->save();
    		}

    		//Active current plan
    		$currentPlanUpdate = CurrentPlan::find($curr_plan_id);
    		if($currentPlanUpdate->plan_validity ==NULL){
    			$planDetail = Plan::find($currentPlanUpdate->plan_id);
    			$planDetail->plan_validity; //plan validity in days
    			//update plan
    			$start = Carbon::today();
    			$start_date = $start->addDay($planDetail->plan_validity);
    			$currentPlanUpdate->plan_validity = $start_date;
    		}
    		$currentPlanUpdate->is_status = $status;

    		if($currentPlanUpdate->save()){

    			return response()->json([
		                'success' => true,
		                'message' =>'success',
		                'response' => 'Plan Successfully Updated'
		            ]);
    		}

		}catch(\Exception $e){

			return response()->json([
                'success' => false,
                'message' => 'Oops, Something Went Wrong',
            ]);
    	}
    }

    public function getCancelCampaign(Request $request) {

    	try{
    		$campaignUpdate = Campaign::find(Crypt::decryptString($request->campaign_id));
    		$campaignUpdate->is_status = 3; // Cancel
    		if($campaignUpdate->save()){

    			return response()->json([
		                'success' => true,
		                'message' =>'success',
		                'response' => 'Campaign Successfully Canceled'
		            ]);
    		}
    	}catch(\Exception $e){

			return response()->json([
                'success' => false,
                'message' => 'Oops, Something Went Wrong',
            ]);
    	}
    }
   public function getBlockUser(Request $request){

   		try{

   			$userUpdate = User::find(Crypt::decryptString($request->user));
    		$userUpdate->is_status = $request->status; // block & unblock
    		if($userUpdate->save()){

    			return response()->json([
		                'success' => true,
		                'message' =>'success',
		                'response' => 'User Details Updated Successfully '
		            ]);
    		}
    		
		}catch(\Exception $e){

			return response()->json([
	            'success' => false,
	            'message' => 'Oops, Something Went Wrong',
	        ]);
    	}
   }
   public function getInstanceDelete(Request $request){

   		try{

   			$updateUpdate = ChatInstance::find(Crypt::decryptString($request->instance_id));
    		if($updateUpdate->delete()){ //delete

    			return response()->json([
		                'success' => true,
		                'message' =>'success',
		                'response' => 'Instance deleted Successfully'
		            ]);
    		}
		}catch(\Exception $e){

			return response()->json([
	            'success' => false,
	            'message' => 'Oops, Something Went Wrong',
	        ]);
    	}
   }
   public function getMessageResponseDelete(Request $request){

   		$nameList = [];
   		$id = Crypt::decrypt($request->id);
	    switch ($request->type) {
		   	case 'text':
				$nameList = TextApplication::where("user_id", Auth::user()->id)->where("id", $id)->delete();
			   break;

			case 'image':
				$nameList = ImageApplication::where("user_id", Auth::user()->id)->where("id", $id)->delete();
				break;

			case 'video':
				$nameList = VideoApplication::where("user_id", Auth::user()->id)->where("id", $id)->delete();
				break;

			case 'capture':
				$nameList = CaptureApplication::where("user_id", Auth::user()->id)->where("id", $id)->delete();
				break;

			case 'api':
				$nameList = ApiApplication::where("user_id", Auth::user()->id)->select("name", "id")->get();
				break;
						
			case 'location':
				$nameList = LocationApplication::where("user_id", Auth::user()->id)->where("id", $id)->delete();
				break;
				
			case 'time condition':
				$nameList = TimeConditionApplication::where("user_id", Auth::user()->id)->where("id", $id)->delete();
				break;
			case 'menu':
				$nameList = InteractiveMenu::where("user_id", Auth::user()->id)->where("id", $id)->delete();
				break;	
		   	default:
			   # code...
			   break;
	   }
		   
			return response()->json([
				'success' => true,
				'message' => 'Success',
				'response' => "Deleted Successfully"
			]);
   }
   public function getMenuResponseDelete(Request $request){

   		$id = Crypt::decrypt($request->id);
   		MenuInput::where('interactive_menu_id',$id)->delete();
   		$deleteMenu = InteractiveMenu::find($id);
   		$deleteMenu->delete();

   		return response()->json([
				'success' => true,
				'message' => 'Success',
				'response' => "Deleted Successfully"
			]);
   }
   public function getNextAppName(Request $request) {
	   $response = "";
	   try {
		   $nameList = [];
		   switch ($request->combination) {
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
		   $selected ='';
		   $response .= "<option value='null'></option>";
		   foreach($nameList as $row) {
		   	if(isset($request->app_value)){
		   		if($row->id == $request->app_value){
		   			$selected = 'selected';
		   		}else{
		   			$selected = '';
		   		}
		   	}else{
		   		$selected ='';
		   	}
			   $response .= "<option value='" . $row->id . "' " . $selected . ">" . $row->name . "</option>";
		   	
		   }
		   /*if(count($nameList) == 0) {
				
		   }*/
		   return response()->json([
			'success' => true,
			'message' => 'Success',
			'response' => $response
		]);
	   } catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'message' => 'Oops, Something Went Wrong',
				'response' => $response
			]);
	   }
   }
   
    public function postBodies(Request $request) {
	   $response1 = "";
	   try {
		   $nameList = [];
		   switch ($request->combination) {
				case 'button':
					$nameList = ButtonBodies::where("button_application_id", $request->body_id)->select("body", "id")->get();
					break;
				case 'list':
					$nameList = ListBody::where("list_application_id", $request->body_id)->select("body", "description","id")->get();
					break;
			   	default:
				   # code...
				   break;
		   }
		   $selected ='';
		   //$response1 .='<select class="form-control custom-select select2" >';
		   $response1 .= "<option value=''></option>";
		   foreach($nameList as $row) {
		   	if($request->combination =='button'){

		   	 $response1 .= "<option value='" . $row->id . "'>" . rawurldecode($row->body) . "</option>";
		   	}else if($request->combination =='list'){
		   		$response1 .= "<option value='" . $row->id . "'>" . rawurldecode($row->body). "</option>";
		   	}
		   }
		   if(count($nameList) == 0) {
				$response1 .= "<option value='null'></option>";
		   }
		   //$response1 .='</select>';
		   return response()->json([
			'success' => true,
			'message' => 'Success',
			'response' => $response1
		]);
	   } catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'message' => 'Oops, Something Went Wrong',
				'response' => $response
			]);
	   }
   	}
   	public function postBlockApi(Request $request){
        try{
            $userUpdate = Api::find(Crypt::decryptString($request->api));
            $userUpdate->is_status = $request->status; // block & unblock
            if($userUpdate->save()){

                return response()->json([
                        'success' => true,
                        'message' =>'success',
                        'response' => 'User Details Updated Successfully '
                    ]);
            }
            
        }catch(\Exception $e){

            return response()->json([
                'success' => false,
                'message' => 'Oops, Something Went Wrong',
            ]);
        }
   	}
}
