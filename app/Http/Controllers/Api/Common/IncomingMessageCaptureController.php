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
        $incomingLog = new IncomingLog();
        $incomingLog->instance_token = $request->token;
        $incomingLog->method = $request->method;
        $incomingLog->response_request = $request;
        $incomingLog->save();
        if($request->method =='inbound'){ // Incomming Message
            $response = $this->InsertInboundRequest($request);
        }if($request->method =='token'){
            $response = $this->qRScanAuthRequest($request);
        }if($request->method =='message-ack'){
            $response = $this->reportACKupdate($request);
        }else{
            $response['status'] = false;
            $response['message'] = 'FAILED';
            $response['response'] = 'Method not found';
        }
        return response()->json($response);
    }
    public function reportACKupdate($request){ 

        if($request){
            $msg_id = $request->message_id;
            $CampaignsOutbound = CampaignsOutbound::where('msg_id',$msg_id)->where('instance_token',$request->token)->first();
            if($CampaignsOutbound){
                $CampaignsOutbound->message_status = $request->message->msgStatus;
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
    public function InsertInboundRequest($request){

        $response = array();
        $InsertInboundMessage = new InboundMessage();
        $InsertInboundMessage->instance_token = $request->token;
        //get token 
        $instanceToken = Instance::where('token', $request->token)->first();

        $InsertInboundMessage->user_id = $instanceToken->user_id;
        $InsertInboundMessage->reseller_id = $instanceToken->reseller_id;
        $InsertInboundMessage->number = explode("@",$request->from)[0];
        //sent with web_hook url
        if($instanceToken->web_hook_url){
            $date_start_time = date('Y-m-d H:m:s');
            $sentWebHookURLResponse = $this->sentWebHookURL($request,$instanceToken->web_hook_url,'POST');
            if($sentWebHookURLResponse){
                $date_end_time = date('Y-m-d H:m:s');
                $InsertInboundMessage->web_hook_url_response_code = $response->getStatusCode();
                $InsertInboundMessage->web_hook_url_response = $response;
                $InsertInboundMessage->web_hook_url_start_time = $date_start_time;
                $InsertInboundMessage->web_hook_url_end_time = $date_end_time;
            }
        }
        $InsertInboundMessage->message = $request->text->body; //message json encode value
        $InsertInboundMessage->messaging_product = $request->messaging_product;
        $InsertInboundMessage->json_data = json_encode($request);
        $InsertInboundMessage->save();

        $response['status'] = true;
        $response['message'] = 'SUCCESS';
        $response['message'] = 'Inbound Message Added Successfully';

        return $response;
    }
    public function sentWebHookURL($request,$endpoint,$method){

        $client = new \GuzzleHttp\Client();

        $response = $client->request($method, $endpoint, ['query' => [
            'data' => $request,
        ]]);

        $statusCode = $response->getStatusCode();
        $content = $response->getBody();
        //response
        if ($response->failed()) {
           return $response;
        } else {
          return $response;
        }
    }
}
