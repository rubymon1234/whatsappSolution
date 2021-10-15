<?php

namespace App\Http\Controllers\User;

use DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\CurrentPlan;
use App\Models\Campaign;
use App\Models\Instance;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class ComposeController extends Controller
{
    /**
     * Compose View
     * @author Ruban
    */
    public function getComposeView(){

    	$instanceDetail = Instance::where('user_id',Auth::user()->id)
    					->whereIn('is_status',[1,3])
    					->orderBy('updated_at','DESC')
    					->get();

    	return view('user.compose.sentMessage',compact('instanceDetail'));
    }

    public function postComposeView(Request $request){


    	if($request->ajax()) {

    		$rule = [
                'campaign' => 'required',
                'instance' => 'required',
                'message_type' => 'required',
                'mobile' => 'required',
                //'message' => 'required',
            ];
            $messages = [
                'campaign.required' => 'campaign name is required',
                'instance.required' => 'instance name is required',
                'message_type.required' => 'combination is required',
                'mobile.required' => 'mobile is required',
                //'message.required' => 'message is required',
            ];

            //validation error
            $validator = Validator::make(Input::all(), $rule, $messages);

            if ($validator->fails()) {

                return response()->json([
		                'success' => false,
		                'message' =>'success',
		                'validator' => true,
		                'response' => $validator
		            ]);
             }else{

              	//user data
	    		$user = Auth::user();
	    		$user_id = $user->id;

	    		//form data
	    		$campaign 		= $request->campaign; // campaign name
	    		$instance_id 	= $request->instance; // instance id
	    		$message_type 	= $request->message_type; // message type
	    		$mobile 		= $request->mobile; // mobile
	    		$message 		= $request->message; // message

	    		$validResponse = $this->messageTypeValidation($request->message_type,$request);
	    		$extensionValidation = $this->typeValidation($request->message_type,$request);
	    		//campaign start time
	    		$campaign_start_date = date('Y-m-d');
	    		$campaign_start_time = date('H:i:s');
	    		if($request->is_scheduled==1){
	            	if(isset($request->sch_date) && isset($request->sch_time)){
	            		$campaign_start_date = $request->sch_date;
	            		$campaign_start_time = $request->sch_time;
	            	}else{
	            		return response()->json([
					                'success' => false,
					                'message' =>'success',
					                'validator' => false,
					                'response' => 'Schedule date and time is in-valid',
					            ]);
	            	}
	            }
	    		//extension and file size checking
	    		if($validResponse['status'] ==true && $extensionValidation['status'] ==true){
	    			//current plan
	    			$currentPlan 	= CurrentPlan::where('is_status',1)->where('user_id',$user_id)->first();
	    			//current plan not active
	    			if($currentPlan){
	    				$daily_count 	= $currentPlan->daily_count;
	    				$plan_validity 	= $currentPlan->plan_validity;

	    				$campaignFetch = Campaign::where('user_id',$user_id)
	    										->where('current_plan_id',$currentPlan->plan_id)
	    										->select( DB::raw('sum(count) as total'))
	    										->whereIn('is_status',[1,2,0])
	    										->whereDate('start_at', '=', Carbon::today()->toDateString())->get()->toArray();
	    			//create csv
		            $num_count =0;
		            $csv_name ='';
		            if(isset($request->mobile)){
		            	$csvDetail = $this->createCsv($request->mobile);
    					$num_count = $csvDetail['num_count'];
    					$csv_name = $csvDetail['csv_name'];
		            }

		            if(strlen($request->message) >=1000){
		            		return response()->json([
						                'success' => false,
						                'message' =>'success',
						                'validator' => false,
						                'response' => 'Message count is more than 1000.',
						            ]);
		            }
    				//upload file
					if($request->message_type =='image'){
						$uploadfilename = $this->uploadFile($request,'photo');
					}elseif($request->message_type =='video'){
						$uploadfilename = $this->uploadFile($request,'video_file');
					}elseif ($request->message_type =='audio') {
						$uploadfilename = $this->uploadFile($request,'audio_file');
					}elseif($request->message_type =='document'){
						$uploadfilename = $this->uploadFile($request,'doc_file');
					}else{
						$uploadfilename = NULL;
					}
	    			if(isset($campaignFetch[0]['total'])){ $total = $campaignFetch[0]['total']; }else{ $total = 0; }
	    				$total = $total + $num_count;
	    				if($daily_count >=$total){
	    					if($request->is_scheduled==1){
	    						$today_date = $campaign_start_date;
	    					}else{
	    						$today_date = date('Y-m-d');
	    					}

    					if($plan_validity >= $today_date){

    						//current instance
    						$getInstance = Instance::where('id',$instance_id)->where('is_status',1)->first();
    						if($getInstance){

    							//Campaign Creation
	    						$campaignInsert = new Campaign();
	    						$campaignInsert->campaign_name = $campaign;
	    						$campaignInsert->user_id = $user_id;
	    						$campaignInsert->reseller_id = $user->reseller_id;
	    						$campaignInsert->current_plan_id = $currentPlan->plan_id;
	    						$campaignInsert->leads_file = $csv_name;//csv
	    						$campaignInsert->instance_token = $getInstance->token;
	    						$campaignInsert->instance_name = $getInstance->instance_name;
	    						$campaignInsert->type = $validResponse['slug'];
	    						$campaignInsert->message = rawurlencode($message);
	    						$campaignInsert->media_file_name = $uploadfilename;
	    						$campaignInsert->count = $num_count;
	    						if($num_count <=10){
	    							$campaignInsert->is_status = 2;
	    						}else{
	    							$campaignInsert->is_status = 0;
	    						}
	    						if($request->optOut =='on'){
	    							$campaignInsert->opt_out = 1;
	    						}else{
	    							$campaignInsert->opt_out = 0;
	    						}
	    						$campaignInsert->start_at = $campaign_start_date.' '.$campaign_start_time;
	    						if($request->is_scheduled==1){
	    							$campaignInsert->is_status = 0; // scheduled
	    						}
	    						$campaignInsert->save();
	    						$last_inserted_id = $campaignInsert->id;
	    						if($num_count <=10){
	    							shell_exec('/usr/bin/php /var/www/html/whatsappSolution/cronjob/cronJobNumberPriority.php '.$last_inserted_id.' 2> /dev/null > /dev/null  &');
	    						}
	    						if($campaignInsert){

	    							return response()->json([
							                'success' => true,
							                'message' =>'success',
							                'validator' => false,
							                'response' => 'Campaign created successfully',
							            ]);
	    						}
	    						return response()->json([
							                'success' => false,
							                'message' =>'success',
							                'validator' => false,
							                'response' => 'Oops, something went wrong',
							            ]);

    						}else{
    							return response()->json([
						                'success' => false,
						                'message' =>'success',
						                'validator' => false,
						                'response' => 'Campaign is running, choose another instance',
						            ]);
    						}

    					}else{

    						return response()->json([
						                'success' => false,
						                'message' =>'success',
						                'validator' => false,
						                'response' => 'Validity expired',
						            ]);
    					}

    				}else{
	    					return response()->json([
							                'success' => false,
							                'message' =>'success',
							                'validator' => false,
							                'response' => 'Daily limit exceeded',
							            ]);
	    				}
	    			}else{
	    				return response()->json([
							                'success' => false,
							                'message' =>'success',
							                'validator' => false,
							                'response' => 'Plan is not active',
							            ]);
	    			}

	    		}else{

	    			return response()->json([
		                'success' => false,
		                'message' =>'success',
		                'validator' => false,
		                'response' => $validResponse['message'].' '.$extensionValidation['message']
		            ]);
	    		}

              }

    	}
//catch
    }
    public function createCsv($contacts){
    	$csvDetail = array();
    	$token = Helper::generateUniqueId();
    	$leadPath = public_path('/uploads/csv/');
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
    public function uploadFile($request,$type){
        $ext = strtolower($request->file($type)->getClientOriginalExtension());
        $token = Helper::generateUniqueId();
        $microtime = str_replace(" ","",$token);
        $filename = Auth::user()->id .'_Media_'. $type . $microtime . '.' . $ext;
		$request->file($type)->move(public_path('uploads/media/'), $filename);
		return $filename;
    }
	public function messageTypeValidation($message_type,$request){

		$errorMessage = '';
		$arrayMessage['status'] = '';
		$arrayMessage['message'] = '';

		if($message_type =='text'){ //only text
			if(isset($request->message)){
				$arrayMessage['slug'] = 'text';
				$arrayMessage['status'] = true;
			}else{
				$arrayMessage['status'] = false;
				$arrayMessage['message'] = 'Message field is mandatary';
			}
		}elseif ($message_type =='image') { //text + photo
			if(isset($request->photo) && isset($request->message)){
				$arrayMessage['slug'] = 'media-text';
				$arrayMessage['status'] = true;
			}else{
				$arrayMessage['status'] = false;
				$arrayMessage['message'] = 'Image and message fields are mandatary';
			}
		}elseif($message_type =='video'){ //text + video_file

			if(isset($request->video_file) && isset($request->message)){
				$arrayMessage['slug'] = 'media-text';
				$arrayMessage['status'] = true;
			}else{
				$arrayMessage['status'] = false;
				$arrayMessage['message'] = 'Video and message fields are mandatary';
			}
		}elseif($message_type =='audio'){ //audio_file only
			if(isset($request->audio_file)){
				$arrayMessage['slug'] = 'media';
				$arrayMessage['status'] = true;
			}else{
				$arrayMessage['status'] = false;
				$arrayMessage['message'] = 'Audio field is mandatary';
			}
		}elseif ($message_type =='document') { // doc_file only
			if(isset($request->doc_file)){
				$arrayMessage['slug'] = 'media';
				$arrayMessage['status'] = true;
			}else{
				$arrayMessage['status'] = false;
			$arrayMessage['message'] = 'Document field is mandatary';
			}
		}
		return $arrayMessage;
	}
	public function typeValidation($message_type,$request){
		//$arrayMessage = array();
		$arrayMessage['status'] = '';
		$arrayMessage['message'] = '';
		if($message_type =='text'){ //only text
			if(isset($request->message)){
				$arrayMessage['status'] = true;
			}else{
				$arrayMessage['status'] = false;
				$arrayMessage['message'] = 'Message field is mandatary';
			}
		}elseif ($message_type =='image') { //text +photo
			if(isset($request->photo)){
				$extension = ['jpeg','png','jpg'];
				$ext = strtolower($request->file('photo')->getClientOriginalExtension());
				$fileSize = $request->file('photo')->getSize();
				if($fileSize <=4000000){
					if(in_array($ext, $extension)){
						$arrayMessage['status'] = true;
					}else{
						$arrayMessage['status'] = false;
						$arrayMessage['message'] = 'Image accepted format are jpg, png, jpeg';
					}
				}else{
					$arrayMessage['status'] = false;
					$arrayMessage['message'] = 'Image maximum file size is 4MB';
				}
			}
		}elseif($message_type =='video') {
			if(isset($request->video_file)){
				$extension = ['mp4','3gpp'];
				$ext = strtolower($request->file('video_file')->getClientOriginalExtension());
				$fileSize = $request->file('video_file')->getSize();
				if($fileSize <=4000000){

					if(in_array($ext, $extension)){
						$arrayMessage['status'] = true;
					}else{
						$arrayMessage['status'] = false;
						$arrayMessage['message'] = 'Video accepted format are mp4, 3gpp';
					}
				}else{
					$arrayMessage['status'] = false;
					$arrayMessage['message'] = 'Video maximum file size is 4MB';
				}
			}
		}elseif($message_type =='audio') {
			if(isset($request->audio_file)) {
				$extension = ['aac','mp3','amr'];
				$ext = strtolower($request->file('audio_file')->getClientOriginalExtension());
				$fileSize = $request->file('audio_file')->getSize();
				if($fileSize <=4000000){

					if(in_array($ext, $extension)){
						$arrayMessage['status'] = true;
					}else{
						$arrayMessage['status'] = false;
						$arrayMessage['message'] = 'Accepted audio format is aac, mp3, amr';
					}
				}else{
					$arrayMessage['status'] = false;
					$arrayMessage['message'] = 'Audio maximum file size is 4MB';
				}
			}
		}elseif ($message_type =='document') {
			$extension = ['doc', 'xls' , 'ppt', 'docx', 'xlsx', 'pptx', 'pdf', 'txt'];
			$ext = strtolower($request->file('doc_file')->getClientOriginalExtension());
			$fileSize = $request->file('doc_file')->getSize();
			if($fileSize <=4000000){

				if(in_array($ext, $extension)){ //&& $fileSize <=4000000
					$arrayMessage['status'] = true;
				}else{
					$arrayMessage['status'] = false;
					$arrayMessage['message'] = 'Accepted document format are doc, xls, ppt, docx, xlsx, pptx, pdf, txt';
				}
			}else{
				$arrayMessage['status'] = false;
				$arrayMessage['message'] = 'Document maximum file size is 4MB';
			}
		}
		return $arrayMessage;
	}
}
