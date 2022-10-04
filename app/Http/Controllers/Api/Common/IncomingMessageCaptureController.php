<?php

namespace App\Http\Controllers\Api\Common;

use Crypt;
use App\Models\Role;
use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\Api;
use App\Models\Campaign;
use App\Models\InboundMessage;
use App\Models\Accounts;
use App\Models\Instance;
use App\Models\Blacklist;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Models\CampaignsOutbound;
use App\Models\IncomingLog;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class IncomingMessageCaptureController extends Controller
{
    public function IncomingMessageCaptureRequest(Request $request){
        $request = json_decode(Request::createFromGlobals()->getContent());
        if($request){
            $incomingLog = new IncomingLog();
            $incomingLog->instance_token = $request->token;
            $incomingLog->method = $request->method;
            $incomingLog->response_request = json_encode($request);
            $incomingLog->save();
            $incoming_log_last_updated_id = $incomingLog->id;
        }
        if($request->method =='inbound'){ // Incomming Message
            $response = $this->InsertInboundRequest($request,$incoming_log_last_updated_id);
        }else if($request->method =='token'){
            $response = $this->qRScanAuthRequest($request);
        }else if($request->method =='message-ack'){
            $response = $this->reportACKupdate($request);
        }else{
            $response['status'] = false;
            $response['message'] = 'FAILED';
            $response['response'] = 'Method not found';
        }

        if(isset($request->token)){

            $instanceToken = Instance::where('token', $request->token)->where('is_status',1)->first();
            if($request->method !='inbound'){
                if(isset($instanceToken->web_hook_url)){
                    $date_end_time = date('Y-m-d H:m:s');
                    $sentWebHookURLAllResponse = $this->sentWebHookURLAll($request,$instanceToken->web_hook_url,'POST',$incoming_log_last_updated_id);
                    $incomingLog_Update = IncomingLog::find($incoming_log_last_updated_id);
                    $incomingLog_Update->web_hook_url_response_code = $sentWebHookURLAllResponse['errorCode'];
                    $incomingLog_Update->web_hook_url_response = $sentWebHookURLAllResponse['error_response'];
                    $incomingLog_Update->sent_webhook_url = 1;
                    $incomingLog_Update->web_hook_url_start_time = $date_end_time;
                    $incomingLog_Update->web_hook_url_end_time = $date_end_time;
                    $incomingLog_Update->save();
                }
            }
        }
        return response()->json($response);
    }
    public function reportACKupdate($request){

        if($request){
            $msg_id = $request->message_id;
            $CampaignsOutbound = CampaignsOutbound::where('msg_id',$msg_id)->where('instance_token',$request->token)->first();
            if($CampaignsOutbound){
                $CampaignsOutbound->status_message = $request->message->body;
                $CampaignsOutbound->save();
                    $response['status'] = true;
                    $response['message'] = 'SUCCESS';
                    $response['response'] = 'ACK update Successfully';
            }else{
               $response['status'] = false;
                $response['message'] = 'Failed';
                $response['response'] = 'Payload Missmatch, Something went wrong.';
            }
        }else{
            $response['status'] = false;
            $response['message'] = 'Failed';
            $response['response'] = 'Oops , Something went wrong.';
        }
        return $response;
    }
    public function qRScanAuthRequest($request){
        if($request->token){
            $instanceToken = Instance::where('token', $request->token)->first();
            if($request->message->code=='500'){
                $instanceToken->is_status = 0;
                $instanceToken->state = $request->message->body;
            }else if($request->message->code=='200'){
                $instanceToken->is_status = 1;
                $instanceToken->state = $request->message->body;
            }else{
                $instanceToken->is_status = 0;
                $instanceToken->state = $request->message->body;
            }
            $instanceToken->save();
            $response['status'] = true;
            $response['message'] = 'SUCCESS';
            $response['response'] = 'QA Authenticate Successfully';

            return $response;
        }
        $response['status'] = false;
        $response['message'] = 'Failed';
        $response['response'] = 'Token Mismatch';
        return $response;
    }
    public function InsertInboundRequest($request,$incoming_log_last_updated_id=null){

        $response = array();
        $InsertInboundMessage = new InboundMessage();
        $InsertInboundMessage->instance_token = $request->token;
        //get token
        $instanceToken = Instance::where('token', $request->token)->first();

        $InsertInboundMessage->user_id = $instanceToken->user_id;
        $InsertInboundMessage->reseller_id = $instanceToken->reseller_id;
        $InsertInboundMessage->number = explode("@",$request->from)[0];
        $InsertInboundMessage->message = $request->text->body; //message json encode value
        $InsertInboundMessage->messaging_product = $request->messaging_product;
        $InsertInboundMessage->json_data = json_encode($request);
        $InsertInboundMessage->save();
        // if(isset($request->text->body) && ($request->text->body ==='UNSUBSCRIBE' || $request->text->body ==='REPORT' || $request->text->body ==='BLOCK')){

        //     $blacklistDetail = Blacklist::where('number')->first();
        //     if(empty($blacklistDetail)){ $blacklistDetail = new Blacklist(); }
        //         $blacklistDetail = new Blacklist();
        //         $blacklistDetail->number = explode("@",$request->from)[0];
        //         $blacklistDetail->token = $request->token;
        //         $blacklistDetail->keyword = $request->text->body;
        //         $blacklistDetail->is_status = 1;
        //         $blacklistDetail->save();
        //         $this->blockedMessageNoticationInitCall($request,explode("@",$request->from)[0],$method);
        // }
        $last_inserted_id = $InsertInboundMessage->id;
        //sent with web_hook url
        if($instanceToken->web_hook_url){
            $webHookUpdate = InboundMessage::find($last_inserted_id);
            $date_start_time = date('Y-m-d H:m:s');
            $sentWebHookURLResponse = $this->sentWebHookURL($request,$instanceToken->web_hook_url,'POST');
            if($sentWebHookURLResponse){
                $date_end_time = date('Y-m-d H:m:s');
                $webHookUpdate->web_hook_url_response_code = $sentWebHookURLResponse['errorCode'];
                $webHookUpdate->web_hook_url_response = $sentWebHookURLResponse['error_response'];
                $webHookUpdate->web_hook_url_start_time = $date_start_time;
                $webHookUpdate->web_hook_url_end_time = $date_end_time;
                $webHookUpdate->save();
                if(isset($incoming_log_last_updated_id)){
                    $incomingLog_Update = IncomingLog::find($incoming_log_last_updated_id);
                    $incomingLog_Update->web_hook_url_response_code = $sentWebHookURLResponse['errorCode'];
                    $incomingLog_Update->web_hook_url_response = $sentWebHookURLResponse['error_response'];
                    $incomingLog_Update->sent_webhook_url = 1;
                    $incomingLog_Update->web_hook_url_start_time = $date_end_time;
                    $incomingLog_Update->web_hook_url_end_time = $date_end_time;
                    $incomingLog_Update->save();
                }
            }
        }
        $response['status'] = true;
        $response['message'] = 'SUCCESS';
        $response['message'] = 'Inbound Message Added Successfully';

        return $response;
    }
    public function sentWebHookURL($request,$endpoint,$method){

        $returnArray = array();
        $client = new \GuzzleHttp\Client();
        try
        {
            $response = $client->request($method, $endpoint, ['query' => [
                'data' => $request,
            ]]);
            $returnArray['errorCode'] = $response->getStatusCode();
            $returnArray['error_response'] = "success";
            $returnArray['error'] = false;

            return $returnArray;

        }catch( \Exception $e ) {
             $returnArray['errorCode'] = $e->getCode();
             $returnArray['error_response'] = "failed";
             $returnArray['error'] = true;
            return $returnArray;
        }
    }
    public function sentWebHookURLAll($request,$endpoint,$method){


        $returnArray = array();
        $client = new \GuzzleHttp\Client();
        try
        {
            $response = $client->request($method, $endpoint, ['query' => [
                'data' => $request,
            ]]);
            $returnArray['errorCode'] = $response->getStatusCode();
            $returnArray['error_response'] = "success";
            $returnArray['error'] = false;

            return $returnArray;

        }catch( \Exception $e ) {
             $returnArray['errorCode'] = $e->getCode();
             $returnArray['error_response'] = "failed";
             $returnArray['error'] = true;
            return $returnArray;
        }
    }
    public function blockedMessageNoticationInitCall($token, $number,$body){
        $dataPost = array(
            'id' => $instance,
            'number' => $data['0'],
            'message' => "You have been successfully ".$body,
            'type' => 'text',
            'file' => '',
            'opt' => '',
            'optmessage' => '',
            'option' => ''
        );
        $payload = json_encode($dataPost);
        // Prepare new cURL resource
        $ch = curl_init('https://api.textnator.com:9000/send-message');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        // Set HTTP Header for POST request
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload),
            'X-Authentication-Key:3ec5d070a0b165c00c5c06673fdb59a2')
        );
        $result = json_decode(curl_exec($ch), true);
        curl_close($ch);
        return true;
    }
}
