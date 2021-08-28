<?php

namespace App\Http\Controllers\Web;

use Crypt;
use Carbon\Carbon;
use App\Models\Instance;
use App\Models\PlanRequest;
use App\Models\PurchaseHistory;
use App\Models\Plan;
use App\Models\User;
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
	    	$scan_url 	= 'http://95.216.214.103:8000/?id='.$token;

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
					
			   	default:
				   # code...
				   break;
		   }
		   $selected ='';
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
		   if(count($nameList) == 0) {
				$response .= "<option value=''></option>";
		   }
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
}
