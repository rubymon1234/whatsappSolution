<?php

namespace App\Http\Controllers\User;

use DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\CurrentPlan;
use App\Models\Campaign;
use App\Models\Instance;
use App\Models\Scrub;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class ScrubController extends Controller
{
	/**
     * Compose View
     * @author Ruban
    */
    public function getScrubView(){
    	$scrubDetail = Scrub::where('user_id',Auth::user()->id)
    					->orderBy('updated_at','DESC')->paginate(10);
    	return view('user.scrub.scrubView',compact('scrubDetail'));
    }

    public function getScrubCreate() {
    	$instanceDetail = Instance::where('user_id',Auth::user()->id)
    					->where('is_status',1)
    					->orderBy('updated_at','DESC')
    					->get();
    	return view('user.scrub.scrubCreate', compact('instanceDetail'));
    }

    public function postScrubCreate(Request $request) {

    	$rule = [
            'scrub_name' => 'required',
            'instance' => 'required',
            'mobile' => 'required',
        ];
        $messages = [
            'scrub_name.required' => 'scrub name is required',
            'instance.required' => 'instance name is required',
            'mobile.required' => 'mobile is required',
        ];

        //validation error
        $validator = Validator::make(Input::all(), $rule, $messages);

        if ($validator->fails()) {

            return redirect()->route('user.compose.scrub.create')->withInput(Input::all())->withErrors($validator);

        }else{

        	//user data
    		$user = Auth::user();
    		$user_id = $user->id;

    		//form data
    		$scrub_name 		= $request->scrub_name; // campaign name
    		$instance_id 	= $request->instance; // instance id
    		$mobile 		= $request->mobile; // mobile

    		//current plan
	    	$currentPlan 	= CurrentPlan::where('is_status',1)->where('user_id',$user_id)->first();
	    	$today_date = date('Y-m-d');

	    	//current plan not active
			if($currentPlan){
				$scrub_count 	= $currentPlan->scrub_count;
				$plan_validity 	= $currentPlan->plan_validity;
                $plan_subsciption   = $currentPlan->plan_subscription; // monthy or daily
                if($plan_subsciption ===1){ // monthy
                    $end_date = Carbon::parse($currentPlan->plan_validity)->format('Y-m-d');
                    $operator = '<=';
                }else{ // daily
                    $end_date = Carbon::today()->toDateString();
                    $operator = '=';
                }
				$scrubcampaignFetch = Scrub::where('user_id',$user_id)
									->where('current_plan_id',$currentPlan->plan_id)
									->select( DB::raw('sum(count) as total'))
									->whereDate('created_at', $operator, $end_date)->get()->toArray();
	    		$num_count = 0; $csv_name = '';
	    		if(isset($request->mobile)){
	            	$csvDetail = $this->createCsv($request->mobile);
					$num_count = $csvDetail['num_count'];
					$csv_name = $csvDetail['csv_name'];
	            }
	    		if(isset($scrubcampaignFetch[0]['total'])){ $total = $scrubcampaignFetch[0]['total']; }else{ $total = 0; }
	    				$total = $total + $num_count;

    				if($scrub_count >=$total){

    					if($plan_validity >= $today_date){

    						//current instance
    						$getInstance = Instance::find($instance_id);
    						
    						//scrub Insertion
    						$scrubInsert = new Scrub();
    						$scrubInsert->scrub_name = $scrub_name;
    						$scrubInsert->user_id = $user_id;
    						$scrubInsert->reseller_id = $user->reseller_id;
    						$scrubInsert->current_plan_id = $currentPlan->plan_id;
    						$scrubInsert->leads_file = $csv_name;
    						$scrubInsert->instance_name = $getInstance->instance_name;
    						$scrubInsert->instance_token = $getInstance->token;
    						$scrubInsert->count = $num_count;
    						$scrubInsert->is_status = 0;
    						if($scrubInsert->save()){
    							shell_exec('/usr/bin/php /var/www/html/whatsappSolution/cronjob/cronJobNumberScrub.php '.$scrubInsert->id.' 2> /dev/null > /dev/null  &');

    							return redirect()->route('user.compose.scrub.create')->with('success_message', 'New scrub campaign created successfully.');
    						}
    					}else{
    						return redirect()->route('user.compose.scrub.create')->with('error_message', 'Plan Expired.');
    					}
    				}else{
    					return redirect()->route('user.compose.scrub.create')->with('error_message', 'Srub count limit exceed.');
    				}
			}else{
				return redirect()->route('user.compose.scrub.create')->with('error_message', 'Plan is not active.');
			}
        }
    }
    public function createCsv($contacts){
    	$csvDetail = array();
    	$token = Helper::generateUniqueId();
    	$leadPath = public_path('/uploads/scrubCsv/');
    	$csvName = str_replace(" ","",$token).'.csv';
		$file = $leadPath.$csvName;

		if ($contacts) {
			$contacts = explode("\r\n",trim($contacts));
			$csvDetail['num_count'] = count($contacts);
			$fp = fopen("$file", 'w');
			foreach($contacts as $line){
				$val = explode(",",$line);
				fputcsv($fp, $val);
			}
			fclose($fp);
			$csvDetail['csv_name'] = $csvName;
			return $csvDetail;
		}
		return false;
    }
}