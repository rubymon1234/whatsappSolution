<?php

namespace App\Http\Controllers\Web;

use Crypt;
use App\Models\Instance;
use App\Models\PlanRequest;
use App\Models\PurchaseHistory;
use Illuminate\Http\Request;
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
	    		if($planApproveHistory->save()){

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

    	$instance_id = $request->get('instance_id');

    	//get Instance
    	$Instance = Instance::find(Crypt::decryptString($instance_id));

    	$token 	= $Instance->token;
    	$scan_url 	= 'http://127.0.0.1:8000/?id='.$token;

    		return response()->json([
	                'success' => true,
	                'message' =>'success',
	                'scan_url' => $scan_url,
	                'response' => 'QA Successfully Generated'
	            ]);
    }
}
