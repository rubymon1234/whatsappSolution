<?php

namespace App\Http\Controllers\User\ChatBot;

use DB;
use Carbon\Carbon;
use App\Models\CurrentPlan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use App\Models\ApiApplication;
use App\Models\TextApplication;
use App\Models\CaptureApplication;
use App\Models\ImageApplication;
use App\Models\LocationApplication;
use App\Models\VideoApplication;
use App\Models\TimeConditionApplication;
use App\Models\ChatInstance;
use App\Models\Instance;

class BotInstanceController extends Controller
{
	/**
     * Purchase View 
     * @author Ruban
    */
   	public function getInstanceList()
    {

        $chatInstanceList = ChatInstance::where('user_id',Auth::user()->id)->orderBy('updated_at','DESC')->paginate(10);

        return view('user.chatbot.instance.botInstanceList',compact('chatInstanceList'));
    }

    public function getInstanceCreate(){

    	$instanceDetail = Instance::where('user_id',Auth::user()->id)->whereIn('is_status',[1])->orderBy('updated_at','DESC')->get();

    	return view('user.chatbot.instance.botInstanceCreate',["combinationList" => $this->getDefaultCombinationList(),"instanceDetail" => $instanceDetail]);
    }
    protected function getDefaultCombinationList(): array
    {
        return array(
            'text' => "Text Only",
            'image' => "Image",
            'video' => "Video",
            'capture' => "Capture",
            'api' => "Api",
            "location" => "Location",
            "timeCondition" => "Time Condition"
        );
    }
}