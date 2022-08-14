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
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class IncomingMessageCaptureController extends Controller
{
    public function IncomingMessageCaptureRequest(Request $request){ 
        $request = json_decode(Request::createFromGlobals()->getContent());
        if($request->method =='inbound'){ // Incomming Message
            $this->InsertInboundRequest($request);
        }
    }

    public function InsertInboundRequest($request){

        $InsertInboundMessage = new InboundMessage();
        $InsertInboundMessage->instance_token = $request->token;
        //get token 
        $instanceToken = Instance::where('token', $request->token)->first();

        $InsertInboundMessage->user_id = $instanceToken->user_id;
        $InsertInboundMessage->reseller_id = $instanceToken->reseller_id;
        $InsertInboundMessage->number = explode("@",$request->from)[0];
        //sent with web_hook url
        if($instanceToken->web_hook_url){
            $sentWebHookURLResponse = $this->sentWebHookURL($request,$instanceToken->web_hook_url,'POST');
            if($sentWebHookURLResponse){
                $InsertInboundMessage->web_hook_url_response_code = $response->getStatusCode();
                $InsertInboundMessage->web_hook_url_response = $response;
            }
        }

        $InsertInboundMessage->message = $request->text->body; //message json encode value
        $InsertInboundMessage->messaging_product = $request->messaging_product;
        $InsertInboundMessage->json_data = json_encode($request);
        $InsertInboundMessage->save();
        return true;
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
