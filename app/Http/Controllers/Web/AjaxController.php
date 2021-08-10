<?php

namespace App\Http\Controllers\Web;

use Crypt;
use Carbon\Carbon;
use App\Models\Instance;
use App\Models\PlanRequest;
use App\Models\PurchaseHistory;
use App\Models\Plan;
use App\Models\CurrentPlan;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

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
}
