<?php

namespace App\Http\Controllers\User\ChatBot;

use DB;
use App\Helpers\Helper;
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

class MessageResponseController extends Controller
{
    /**
     * Message Response
     * @author Ruban
     */
    public function getMessageResponse()
    {
        return view('user.chatbot.messageResponseCreate', ["combinationList" => $this->getDefaultCombinationList()]);
    }

    public function addMessageResponse(Request $request)
    {
        $rule = [
            'combination' => 'required',
            'scrub_name' => 'required',
            //'message' => ($this->isMessageRequired($request->get("combination")) ? 'required' : '') ,
            'location' => ($request->get("combination") == 'location' ? 'required' : ''),
            'validator' => ($request->get("combination") == 'capture' ? 'required' : '')
        ];
        $messages = [
            'scrub_name.required' => 'Name is required',
            $request->get("combination") . '_app_name1.required' => $request->get("combination")  . ' app value is required'
        ];

        $validator = Validator::make(Input::all(), $rule, $messages);

        if ($validator->fails()) {
            return redirect()->route('user.chat.bot.message.create')->withInput(Input::all())->withErrors($validator);
        } else {
            switch ($request->get("combination")) {
                case 'text':
                    $textEntry = $request->get("id") ? TextApplication::findOrFail($request->get("id")) : new TextApplication();
                    $textEntry->user_id = Auth::user()->id;
                    $textEntry->reseller_id = Auth::user()->reseller_id;
                    $textEntry->name = $request->get("scrub_name");
                    $textEntry->message = rawurlencode($request->get("messageText"));
                    $textEntry->next_app_value = $this->getAppName($request->get("text_app_name1"), $request->get("text_app_name1"));
                    $textEntry->next_app_name = $this->getAppName($request->get("text_app_name1"), $request->get("text_app_name"));
                    $textEntry->save();
                    break;

                case 'image':
                    $textEntry = $request->get("id") ? ImageApplication::findOrFail($request->get("id")) : new ImageApplication();
                    $textEntry->user_id = Auth::user()->id;
                    $textEntry->reseller_id = Auth::user()->reseller_id;
                    $textEntry->name = $request->get("scrub_name");
                    $textEntry->message = rawurlencode($request->get("messageImage"));
                    $textEntry->app_value = $this->getAppName($request->get("image_app_name1"), $request->get("image_app_name1"));
                    $textEntry->app_name = $this->getAppName($request->get("image_app_name1"), $request->get("image_app_name"));
                    if ($request->file()) {
                        $extension = ['jpeg','png','jpg'];
                        $ext = strtolower($request->file('image_photo')->getClientOriginalExtension());
                        $fileSize = $request->file('image_photo')->getSize();
                        if($fileSize <=4000000){
                            if(in_array($ext, $extension)){
                                 $token = Helper::generateUniqueId();
                                $fileName = time(). '_'.$token.'.'.$ext;
                                // $filePath = $request->file('image_photo')->storeAs('uploads/chat-bot', $fileName, 'public');
                                $request->image_photo->move(public_path('/uploads/chat-bot'), $fileName);
                                $textEntry->file_name = $fileName;
                            } else {
                                return redirect()->route('user.chat.bot.message.create')->withInput(Input::all())->withErrors($validator);
                            }
                        } else {
                            return redirect()->route('user.chat.bot.message.create')->withInput(Input::all())->withErrors($validator);
                        }
                    }
                    $textEntry->save();
                    break;

                case 'video':
                    $textEntry = $request->get("id") ? VideoApplication::findOrFail($request->get("id")) : new VideoApplication();
                    $textEntry->user_id = Auth::user()->id;
                    $textEntry->reseller_id = Auth::user()->reseller_id;
                    $textEntry->name = $request->get("scrub_name");
                    $textEntry->message = rawurlencode($request->get("messageVideo"));
                    $textEntry->next_app_value = $this->getAppName($request->get("video_app_name1"), $request->get("video_app_name1"));
                    $textEntry->next_app_name = $this->getAppName($request->get("video_app_name1"), $request->get("video_app_name"));
                    if ($request->file()) {
                        $extension = ['mp4','3gpp'];
                        $ext = strtolower($request->file('video')->getClientOriginalExtension());
                        $fileSize = $request->file('video')->getSize();
                        if($fileSize <=4000000){
                            if(in_array($ext, $extension)){
                                $token = Helper::generateUniqueId();
                                $fileName = time() . '_'.$token.'.'. $ext;
                                // $filePath = $request->file('video')->storeAs('uploads/chat-bot', $fileName, 'public');
                                $request->video->move(public_path('/uploads/chat-bot'), $fileName);
                                $textEntry->file_name = $fileName;
                            } else {
                                return redirect()->route('user.chat.bot.message.create')->withInput(Input::all())->withErrors($validator);
                            }
                        } else {
                            return redirect()->route('user.chat.bot.message.create')->withInput(Input::all())->withErrors($validator);
                        }
                    }
                    $textEntry->save();
                    break;

                case 'capture':
                    $textEntry = $request->get("id") ? CaptureApplication::findOrFail($request->get("id")) : new CaptureApplication();
                    $textEntry->user_id = Auth::user()->id;
                    $textEntry->reseller_id = Auth::user()->reseller_id;
                    $textEntry->name = $request->get("scrub_name");
                    $textEntry->validator = $request->get("validator");
                    $textEntry->app_value = $this->getAppName($request->get("capture_app_name1"), $request->get("capture_app_name1"));
                    $textEntry->app_name = $this->getAppName($request->get("capture_app_name1"), $request->get("capture_app_name"));
                    $textEntry->success_app_value = $this->getAppName($request->get("capture_success_app_value"), $request->get("capture_success_app_value"));
                    $textEntry->success_app_name = $this->getAppName($request->get("capture_success_app_value"), $request->get("capture_success_app_name"));
                    $textEntry->failed_app_name = $this->getAppName($request->get("capture_failure_app_value"), $request->get("capture_failure_app_name"));
                    $textEntry->failed_app_value = $this->getAppName($request->get("capture_failure_app_value"), $request->get("capture_failure_app_value"));
                    $textEntry->save();
                    break;

                case 'api':
                    $textEntry = $request->get("id") ? ApiApplication::findOrFail($request->get("id")) : new ApiApplication();
                    $textEntry->user_id = Auth::user()->id;
                    $textEntry->reseller_id = Auth::user()->reseller_id;
                    $textEntry->name = $request->get("scrub_name");
                    $textEntry->url = $request->get("url");
                    $textEntry->parameter_mobile = $request->get("parameter_mobile");
                    $textEntry->parameter_input = $request->get("parameter_input");
                    $textEntry->app_value = $this->getAppName($request->get("api_app_name1"), $request->get("api_app_name1"));
                    $textEntry->app_name = $this->getAppName($request->get("api_app_name1"), $request->get("api_app_name"));
                    $textEntry->success_app_value = $this->getAppName($request->get("api_success_app_value"), $request->get("api_success_app_value"));
                    $textEntry->success_app_name = $this->getAppName($request->get("api_success_app_value"), $request->get("api_success_app_name"));
                    $textEntry->failed_app_name = $this->getAppName($request->get("api_failure_app_value"), $request->get("api_failure_app_name"));
                    $textEntry->failed_app_value = $this->getAppName($request->get("api_failure_app_value"), $request->get("api_failure_app_value"));
                    $textEntry->save();
                    break;

                case 'location':
                    $textEntry = $request->get("id") ? LocationApplication::findOrFail($request->get("id")) : new LocationApplication();
                    $textEntry->user_id = Auth::user()->id;
                    $textEntry->reseller_id = Auth::user()->reseller_id;
                    $textEntry->name = $request->get("scrub_name");
                    $textEntry->lattitude = $request->get("latitude");
                    $textEntry->longitude = $request->get("longitude");
                    $textEntry->message = rawurlencode($request->get("location"));
                    $textEntry->next_app_value = $this->getAppName($request->get("location_app_name1"), $request->get("location_app_name1"));
                    $textEntry->next_app_name = $this->getAppName($request->get("location_app_name1"), $request->get("location_app_name"));
                    $textEntry->save();
                    break;

                case 'timeCondition':
                    $textEntry = $request->get("id") ? TimeConditionApplication::findOrFail($request->get("id")) : new TimeConditionApplication();
                    $textEntry->user_id = Auth::user()->id;
                    $textEntry->reseller_id = Auth::user()->reseller_id;
                    $textEntry->name = $request->get("scrub_name");
                    $textEntry->start_time = $request->get("startTime");
                    $textEntry->end_time = $request->get("endTime");
                    $textEntry->sun = (int)$request->get("sun");
                    $textEntry->mon = (int)$request->get("mon");
                    $textEntry->tue = (int)$request->get("tue");
                    $textEntry->wed = (int)$request->get("wed");
                    $textEntry->thu = (int)$request->get("thu");
                    $textEntry->fri = (int)$request->get("fri");
                    $textEntry->sat = (int)$request->get("sat");
                    $textEntry->success_app_value = $this->getAppName($request->get("timeCondition_success_app_value"), $request->get("timeCondition_success_app_value"));
                    $textEntry->success_app_name = $this->getAppName($request->get("timeCondition_success_app_value"), $request->get("timeCondition_success_app_name"));
                    $textEntry->failed_app_name = $this->getAppName($request->get("timeCondition_failure_app_value"), $request->get("timeCondition_failure_app_name"));
                    $textEntry->failed_app_value = $this->getAppName($request->get("timeCondition_failure_app_value"), $request->get("timeCondition_failure_app_value"));
                    $textEntry->save();
                    break;

                default:
                    # code...
                    break;
            }
        }
        if($request->get("id")) {
            return redirect()->route('user.chat.bot.message.list', ['combination'=>$request->get("combination")])->with('success_message', 'Message Response updated ');
        } else {
            return redirect()->route('user.chat.bot.message.create', [])->with('success_message', 'New Message Response Added ');
        }
    }

    protected function getAppName($appValue, $appName): ?String
    {
        if ($appValue === 'null' || $appValue == null) {
            return null;
        } else {
            return strtoupper($appName);
        }
    }

    protected function isMessageRequired($combination): bool
    {
        if ($combination === 'text' || $combination === 'image' || $combination === 'video') {
            return true;
        } else {
            return false;
        }
    }

    public function listMessageResponse(Request $request)
    {
        $data;
        $nameSearchKey = "%" . $request->get("name") . "%";
        switch ($request->get("combination")) {
            case 'text':
                $data = TextApplication::select("id", "name", "message", "next_app_name as app_name", "next_app_value as app_value", "created_at", DB::raw("'TEXT' as type"), DB::raw("'text' as typeValue"));
                break;

            case 'image':
                $data = ImageApplication::select("id", "name", "message", "app_name as app_name", "app_value as app_value", "created_at", DB::raw("'IMAGE' as type"), DB::raw("'image' as typeValue"));
                break;

            case 'video':
                $data = VideoApplication::select("id", "name", "message", "next_app_name as app_name", "next_app_value as app_value", "created_at", DB::raw("'VIDEO' as type"), DB::raw("'video' as typeValue"));
                break;

            case 'capture':
                $data = CaptureApplication::select("id", "name", "app_name as app_name", "app_value as app_value", "created_at", DB::raw("'CAPTURE' as type"), DB::raw("'capture' as typeValue"));
                break;

            case 'api':
                $data = ApiApplication::select("id", "name", "app_name as app_name", "app_value as app_value", "created_at", DB::raw("'API' as type"), DB::raw("'api' as typeValue"));
                break;

            case 'location':
                $data = LocationApplication::select("id", "name", "message", "next_app_name as app_name", "next_app_value as app_value", "created_at", DB::raw("'LOCATION' as type"), DB::raw("'location' as typeValue"));
                break;

            case 'timeCondition':
                $data = TimeConditionApplication::select("id", "name", "created_at", DB::raw("'TIME CONDITION' as type"), DB::raw("'timeCondition' as typeValue"));
                break;

            default:
                $text = DB::table('text_applications')->select("id", "name", "message", "next_app_name as app_name", "created_at", DB::raw("'TEXT' as type"), DB::raw("'text' as typeValue"), "next_app_value as app_value")->where("user_id", Auth::user()->id);
                $image = DB::table('image_applications')->select("id", "name", "message", "app_name as app_name", "created_at", DB::raw("'IMAGE' as type"), DB::raw("'image' as typeValue"), "app_value as app_value")->where("user_id", Auth::user()->id);
                $capture = DB::table('capture_applications')->select("id", "name", DB::raw("'' as message"), "app_name as app_name", "created_at", DB::raw("'CAPTURE' as type"), DB::raw("'capture' as typeValue"), "app_value as app_value")->where("user_id", Auth::user()->id);
                $video = DB::table('video_applications')->select("id", "name", "message", "next_app_name as app_name", "created_at", DB::raw("'VIDEO' as type"), DB::raw("'video' as typeValue"), "next_app_value as app_value")->where("user_id", Auth::user()->id);
                $api = DB::table('api_applications')->select("id", "name", DB::raw("'' as message"), "app_name as app_name", "created_at", DB::raw("'API' as type"), DB::raw("'api' as typeValue"), "app_value as app_value")->where("user_id", Auth::user()->id);
                $location = DB::table('location_applications')->select("id", "name", "message", "next_app_name as app_name", "created_at", DB::raw("'LOCATION' as type"), DB::raw("'location' as typeValue"), "next_app_value as app_value")->where("user_id", Auth::user()->id);
                $timeCondition = DB::table('time_condition_applications')->select("id", "name", DB::raw("'' as message"), DB::raw("'' as app_name"), "created_at", DB::raw("'TIME CONDITION' as type"), DB::raw("'timeCondition' as typeValue"), DB::raw("'' as app_value"))->where("user_id", Auth::user()->id);
                if ($request->get("name")) {
                    $text = $text->where("name", "LIKE", $nameSearchKey);
                    $image = $image->where("name", "LIKE", $nameSearchKey);
                    $capture = $capture->where("name", "LIKE", $nameSearchKey);
                    $video = $video->where("name", "LIKE", $nameSearchKey);
                    $api = $api->where("name", "LIKE", $nameSearchKey);
                    $location = $location->where("name", "LIKE", $nameSearchKey);
                    $timeCondition = $timeCondition->where("name", "LIKE", $nameSearchKey);
                }
                $timeCondition = $timeCondition->union($location)->union($api)->union($video)->union($capture)->union($image)->union($text);
                $data = DB::query()->fromSub($timeCondition, "");
                break;
        }
        if($request->get("combination") != "") {
            $data = $data->where("user_id", Auth::user()->id);
            if ($request->get("name")) {
                $data = $data->where("name", "LIKE", $nameSearchKey);
            }
        }
        $data = $data->orderBy("created_at", "DESC");
        $data = $data->paginate(10);
        // DB::enableQueryLog();
        $text1 = DB::table('text_applications')->select("name", DB::raw("'TEXT' as type"), "id")->where("user_id", Auth::user()->id);
        $image1 = DB::table('image_applications')->select("name", DB::raw("'IMAGE' as type"), "id")->where("user_id", Auth::user()->id);
        $capture1 = DB::table('capture_applications')->select("name", DB::raw("'CAPTURE' as type"), "id")->where("user_id", Auth::user()->id);
        $location1 = DB::table('location_applications')->select("name", DB::raw("'LOCATION' as type"), "id")->where("user_id", Auth::user()->id);
        $timeCondition1 = DB::table('time_condition_applications')->select("name", DB::raw("'TIMECONDITION' as type"), "id")->where("user_id", Auth::user()->id);
        $api1 = DB::table('api_applications')->select("name", DB::raw("'API' as type"), "id")->where("user_id", Auth::user()->id);
        $video1 = DB::table('video_applications')->select("name", DB::raw("'VIDEO' as type"), "id")->where("user_id", Auth::user()->id);
        $video1 = $video1->union($image1)->union($text1)->union($capture1)->union($location1)->union($timeCondition1)->union($api1)->get();
        // dd(DB::getQueryLog());
        return view('user.chatbot.messageResponseList', ["messageList" => $data, "combinationList" => $this->getDefaultCombinationList(), "allData" => $video1]);
    }

    protected function getDefaultCombinationList(): array
    {
        return array(
            'text' => "TEXT",
            'image' => "IMAGE",
            'video' => "VIDEO",
            'capture' => "CAPTURE",
            'api' => "API",
            "location" => "LOCATION",
            "timeCondition" => "TIME CONDITION"
        );
    }

    public function getMessageResponseDetail(Request $request, $id) {
        $id = \Crypt::decrypt($id);

        if($id > 0) {
            switch ($request->combination) {
                case 'text':
                    $nameList = TextApplication::where("user_id", Auth::user()->id)->where("id", $id)->select("*", "next_app_value as app_value", "next_app_name as app_name")->first();
                    break;

                case 'image':
                    $nameList = ImageApplication::where("user_id", Auth::user()->id)->where("id", $id)->first();
                    break;

                case 'video':
                    $nameList = VideoApplication::where("user_id", Auth::user()->id)->select("*", "next_app_value as app_value", "next_app_name as app_name")->where("id", $id)->first();
                    break;

                case 'capture':
                    $nameList = CaptureApplication::where("user_id", Auth::user()->id)->select("*", DB::raw("'' as message"))->where("id", $id)->first();
                    break;

                case 'api':
                    $nameList = ApiApplication::where("user_id", Auth::user()->id)->select("*", DB::raw("'' as message"))->where("id", $id)->first();
                    break;

                case 'location':
                    $nameList = LocationApplication::where("user_id", Auth::user()->id)->where("id", $id)->select("*", "next_app_value as app_value", "next_app_name as app_name")->first();
                    break;

                case 'timeCondition':
                    $nameList = TimeConditionApplication::where("user_id", Auth::user()->id)->select("*", DB::raw("'' as message"))->where("id", $id)->first();
                    break;

                default:
                # code...
                break;
            }
            return view('user.chatbot.messageResponseEdit', $nameList, ["combinationList" => $this->getDefaultCombinationList()]);
        } else {
            return redirect()->back()->with('error_message', 'Something went wrong!!');
        }
    }
}
