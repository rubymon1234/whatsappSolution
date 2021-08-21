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
            'message' => ($this->isMessageRequired($request->get("combination")) ? 'required' : ''),
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
                    $textEntry = new TextApplication();
                    $textEntry->user_id = Auth::user()->id;
                    $textEntry->reseller_id = Auth::user()->reseller_id;
                    $textEntry->name = $request->get("scrub_name");
                    $textEntry->message = rawurlencode($request->get("message"));
                    $textEntry->next_app_value = $this->getAppName($request->get("text_app_name1"), $request->get("text_app_name1"));
                    $textEntry->next_app_name = $this->getAppName($request->get("text_app_name1"), $request->get("text_app_name"));
                    $textEntry->save();
                    break;

                case 'image':
                    $textEntry = new ImageApplication();
                    $textEntry->user_id = Auth::user()->id;
                    $textEntry->reseller_id = Auth::user()->reseller_id;
                    $textEntry->name = $request->get("scrub_name");
                    $textEntry->message = rawurlencode($request->get("message"));
                    $textEntry->app_value = $this->getAppName($request->get("image_app_name1"), $request->get("image_app_name1"));
                    $textEntry->app_name = $this->getAppName($request->get("image_app_name1"), $request->get("image_app_name"));
                    if ($request->file()) {
                        $fileName = time() . '_' . $request->image_photo->getClientOriginalName();
                        $filePath = $request->file('image_photo')->storeAs('uploads/chat-bot', $fileName, 'public');
                        $textEntry->file_name = 'storage/app/' . $filePath;
                    }
                    $textEntry->save();
                    break;

                case 'video':
                    $textEntry = new VideoApplication();
                    $textEntry->user_id = Auth::user()->id;
                    $textEntry->reseller_id = Auth::user()->reseller_id;
                    $textEntry->name = $request->get("scrub_name");
                    $textEntry->message = rawurlencode($request->get("message"));
                    $textEntry->next_app_value = $this->getAppName($request->get("video_app_name1"), $request->get("video_app_name1"));
                    $textEntry->next_app_name = $this->getAppName($request->get("video_app_name1"), $request->get("video_app_name"));
                    if ($request->file()) {
                        $fileName = time() . '_' . $request->video->getClientOriginalName();
                        $filePath = $request->file('video')->storeAs('uploads/chat-bot', $fileName, 'public');
                        $textEntry->file_name = 'storage/app/' . $filePath;
                    }
                    $textEntry->save();
                    break;

                case 'capture':
                    $textEntry = new CaptureApplication();
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
                    $textEntry = new ApiApplication();
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
                    $textEntry = new LocationApplication();
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
                    $textEntry = new TimeConditionApplication();
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
        return redirect()->route('user.chat.bot.message.create', [])->with('success_message', 'New Message Response Added ');
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
                $data = TextApplication::select("name", "message", "next_app_name as app_name", "created_at", DB::raw("'Text' as type"));
                break;

            case 'image':
                $data = ImageApplication::select("name", "message", "app_name as app_name", "created_at", DB::raw("'Image' as type"));
                break;

            case 'video':
                $data = VideoApplication::select("name", "message", "next_app_name as app_name", "created_at", DB::raw("'Video' as type"));
                break;

            case 'capture':
                $data = CaptureApplication::select("name", "app_name as app_name", "created_at", DB::raw("'Capture' as type"));
                break;

            case 'api':
                $data = ApiApplication::select("name", "app_name as app_name", "created_at", DB::raw("'Api' as type"));
                break;

            case 'location':
                $data = LocationApplication::select("name", "message", "next_app_name as app_name", "created_at", DB::raw("'Location' as type"));
                break;

            case 'timeCondition':
                $data = TimeConditionApplication::select("name", "created_at", DB::raw("'Time Condition' as type"));
                break;

            default:
                $text = DB::table('text_applications')->select("name", "message", "next_app_name as app_name", "created_at", DB::raw("'Text' as type"))->where("user_id", Auth::user()->id);
                $image = DB::table('image_applications')->select("name", "message", "app_name as app_name", "created_at", DB::raw("'Image' as type"))->where("user_id", Auth::user()->id);
                $capture = DB::table('capture_applications')->select("name", DB::raw("'' as message"), "app_name as app_name", "created_at", DB::raw("'Capture' as type"))->where("user_id", Auth::user()->id);
                $video = DB::table('video_applications')->select("name", "message", "next_app_name as app_name", "created_at", DB::raw("'Video' as type"))->where("user_id", Auth::user()->id);
                $api = DB::table('api_applications')->select("name", DB::raw("'' as message"), "app_name as app_name", "created_at", DB::raw("'Api' as type"))->where("user_id", Auth::user()->id);
                $location = DB::table('location_applications')->select("name", "message", "next_app_name as app_name", "created_at", DB::raw("'Location' as type"))->where("user_id", Auth::user()->id);
                $timeCondition = DB::table('time_condition_applications')->select("name", DB::raw("'' as message"), DB::raw("'' as app_name"), "created_at", DB::raw("'Time Condition' as type"))->where("user_id", Auth::user()->id);
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
        $data = $data->paginate(10);
        return view('user.chatbot.messageResponseList', ["messageList" => $data, "combinationList" => $this->getDefaultCombinationList()]);
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
