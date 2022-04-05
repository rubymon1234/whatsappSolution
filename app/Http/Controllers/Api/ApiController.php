<?php

namespace App\Http\Controllers\Api;

use Crypt;
use App\Models\Role;
use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\Api;
use App\Models\Campaign;
use App\Models\Accounts;
use App\Models\Instance;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
  public function send(Request $request){

    //$api_key 		= $request->key; // campaign name
    $api_key = Helper::getBearerToken($request);
    $apiKey 	= Api::where('is_status',1)->where('api_key',$api_key)->first();
    if ($apiKey){
      $campaign 		= $request->campaign; // campaign name
      $instance_token 	=  $apiKey->instance_token;// instance id from database
      $user_id 	=  $apiKey->user_id;// user id from database
      $reseller_id 	=  $apiKey->reseller_id;// reseller id from database
      $message_type 	= $request->type; // message type
      $mobile 		= $request->mobile; // mobile
      $message 		= rawurldecode($request->message); // message
      $uploadfilename = NULL;

      $validResponse = $this->messageTypeValidation($request->type,$request);
      $extensionValidation = $this->typeValidation($request->type,$request);

      if ($validResponse['status'] ==true && $extensionValidation['status'] ==true){
        if($message_type !='text'){
          $uploadfilename = $this->uploadFile($request,'file');
        }
        $account 	= Accounts::where('user_id',$user_id)->first();
        $credit = $account->credits;
        $csvDetail = $this->createCsv($mobile);

        if ($this->checkCredit($csvDetail['num_count'], $credit, $account)){
          $num_count = $csvDetail['num_count'];
          $csv_name = $csvDetail['csv_name'];

          $campaign_start_date = date('Y-m-d');
          $campaign_start_time = date('H:i:s');
          if($request->scheduled==1){
                if(isset($request->date) && isset($request->time)){
                  $campaign_start_date = $request->date;
                  $campaign_start_time = $request->time;
                }else{
                  //Invalid scheduled date and time
                }
          }
          if(strlen($request->message) >1000){
            return response()->json([
                      'status' => 'FAILED',
                      'data' => [
                        'status' => 'failed',
                        'message' => 'Message is more than 1000 characters'
                      ]
                  ]);
          }

          $getInstance = Instance::where('token',$instance_token)->first();
          if($getInstance){
            //Campaign Creation
            $campaignInsert = new Campaign();
            $campaignInsert->campaign_name = $campaign;
            $campaignInsert->user_id = $user_id;
            $campaignInsert->reseller_id = $reseller_id;
            $campaignInsert->current_plan_id = NULL;
            $campaignInsert->leads_file = $csv_name;//csv
            $campaignInsert->instance_token = $instance_token;
            $campaignInsert->instance_name = $getInstance->instance_name;
            $campaignInsert->type = $validResponse['slug'];
            $campaignInsert->message = rawurlencode($message);
            $campaignInsert->media_file_name = $uploadfilename;
            $campaignInsert->count = $num_count;

            if($request->optout =='1'){
              $campaignInsert->opt_out = 1;
            }else{
              $campaignInsert->opt_out = 0;
            }
            $campaignInsert->start_at = $campaign_start_date.' '.$campaign_start_time;
            if($request->scheduled==1){
              $campaignInsert->is_status = 0; // scheduled
            }else{
              $campaignInsert->is_status = 2;
            }
            $campaignInsert->save();
            $last_inserted_id = $campaignInsert->id;
            //shell_exec('/usr/bin/php /var/www/html/whatsappSolution/cronjob/cronJobNumberPriority.php '.$last_inserted_id.' 2> /dev/null > /dev/null  &');

            if($campaignInsert){

              return response()->json([
                        'status' => 'OK',
                        'data' => [
                          'status' => 'success',
                          'campid' => $last_inserted_id,
                          'message' => 'Campaign created successfully'
                        ]
                    ]);
            }
          }else{
            //Invalid instance token
            return response()->json([
                      'status' => 'FAILED',
                      'data' => [
                        'status' => 'failed',
                        'message' => 'Invalid instance token'
                      ]
                  ]);
          }

        }else{
          // No credits
          return response()->json([
                    'status' => 'FAILED',
                    'data' => [
                      'status' => 'failed',
                      'message' => 'Insufficient credits'
                    ]
                ]);
        }
      }else{
        //Message validation failed
        if ($extensionValidation['message']){
          $error = $extensionValidation['message'];
        }else{
          $error = $validResponse['message'];
        }

        return response()->json([
                  'status' => 'FAILED',
                  'data' => [
                    'status' => 'failed',
                    'message' => $error
                  ]
              ]);
      }



    }else{
      //Invalid API Key
      return response()->json([
                'status' => 'FAILED',
                'data' => [
                  'status' => 'failed',
                  'message' => 'Invalid API key'
                ]
            ]);
    }

  }

  public function checkCredit($count, $credit, $account){
    if ($credit >= $count){
      //update credit
      $updateAccount = Accounts::find($account->id);
      $updateAccount->credits = $credit-$count;
      $updateAccount->save();

      return true;
    }
    return false;
  }

  public function createCsv($contacts){
    $csvDetail = array();
    $token = Helper::generateUniqueId();
    $leadPath = public_path('/uploads/csv/');
    $csvName = str_replace(" ","",$token).'.csv';
    $file = $leadPath.$csvName;

  if ($contacts) {
    $contacts = explode(",",trim($contacts));
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
      $filename = '_Media_'. $type . $microtime . '.' . $ext;
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
      $arrayMessage['message'] = 'Message is mandatary';
    }
  }elseif ($message_type =='image') { //text + photo
    if(isset($request->file) && isset($request->message)){
      $arrayMessage['slug'] = 'media-text';
      $arrayMessage['status'] = true;
    }else{
      $arrayMessage['status'] = false;
      $arrayMessage['message'] = 'Image and message are mandatary';
    }
  }elseif($message_type =='video'){ //text + video_file

    if(isset($request->file) && isset($request->message)){
      $arrayMessage['slug'] = 'media-text';
      $arrayMessage['status'] = true;
    }else{
      $arrayMessage['status'] = false;
      $arrayMessage['message'] = 'Video and message are mandatary';
    }
  }elseif($message_type =='audio'){ //audio_file only
    if(isset($request->file)){
      $arrayMessage['slug'] = 'media';
      $arrayMessage['status'] = true;
    }else{
      $arrayMessage['status'] = false;
      $arrayMessage['message'] = 'Audio is mandatary';
    }
  }elseif ($message_type =='document') { // doc_file only
    if(isset($request->file)){
      $arrayMessage['slug'] = 'media';
      $arrayMessage['status'] = true;
    }else{
      $arrayMessage['status'] = false;
      $arrayMessage['message'] = 'Document is mandatary';
    }
  }else{
    $arrayMessage['status'] = false;
    $arrayMessage['message'] = 'Invalid message type';
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
      $arrayMessage['message'] = 'Message is mandatary';
    }
  }elseif ($message_type =='image') { //text +photo
    if(isset($request->file)){
      $extension = ['jpeg','png','jpg'];
      $ext = strtolower($request->file('file')->getClientOriginalExtension());
      $fileSize = $request->file('file')->getSize();
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
    if(isset($request->file)){
      $extension = ['mp4','3gp'];
      $ext = strtolower($request->file('file')->getClientOriginalExtension());
      $fileSize = $request->file('file')->getSize();
      if($fileSize <=4000000){

        if(in_array($ext, $extension)){
          $arrayMessage['status'] = true;
        }else{
          $arrayMessage['status'] = false;
          $arrayMessage['message'] = 'Video accepted format are mp4, 3gp';
        }
      }else{
        $arrayMessage['status'] = false;
        $arrayMessage['message'] = 'Video maximum file size is 4MB';
      }
    }
  }elseif($message_type =='audio') {
    if(isset($request->file)) {
      $extension = ['aac','mp3','amr'];
      $ext = strtolower($request->file('file')->getClientOriginalExtension());
      $fileSize = $request->file('file')->getSize();
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
    $ext = strtolower($request->file('file')->getClientOriginalExtension());
    $fileSize = $request->file('file')->getSize();
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
  }else{
    $arrayMessage['status'] = false;
    $arrayMessage['message'] = 'Invalid message type';
  }
  return $arrayMessage;
}
}
