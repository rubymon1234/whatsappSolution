<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\InteractiveMenu;
use App\Models\MenuInput;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\Models\TextApplication;
use App\Models\ImageApplication;
use App\Models\VideoApplication;
use DB;

class MenuController extends Controller
{
    public function addMenuList()
    {
        return view('user.menu.menuLinkCreate', []);
    }

    public function saveUpdate(Request $request) {
       /* dd($request);
        exit();*/
        $rule = [
            'name' => 'required'
        ];
        $messages = [
            'name.required' => 'Name is required',
        ];

        $validator = Validator::make(Input::all(), $rule, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withInput(Input::all())->withErrors($validator);
        } else {
            $interactiveMenu = $request->get("id") ? InteractiveMenu::findOrFail($request->get("id")) : new InteractiveMenu();
            $interactiveMenu->user_id = Auth::user()->id;
            $interactiveMenu->reseller_id = Auth::user()->reseller_id;
            $interactiveMenu->name = $request->get("name");
            $interactiveMenu->app_name = $this->getAppValue ($request->get("appName"));
            $interactiveMenu->app_value = $this->getAppValue($request->get("appValue"));
            $interactiveMenu->invalid_app_name = $this->getAppValue($request->get("invalidAppName"));
            $interactiveMenu->invalid_app_value = $this->getAppValue($request->get("invalidAppValue"));
            $interactiveMenu->save();
            
          
            if($interactiveMenu->id > 0) {
                $this->menuBulkInsert($request,$interactiveMenu->id);
            }

            if($request->get("id")) {
                return redirect()->route('user.chat.bot.menu.list')->with('success_message', 'Menu Updated Successfully!!');
            } else {
                return redirect()->route('user.chat.bot.menu.create')->with('success_message', 'Menu Added Successfully!!');
            }
        }
    }
    private function menuBulkInsert($request,$interactive_menu_id = null){
        if(isset($request->menuRemoveRow)){
             $removeMenuRow = explode(",", $request->menuRemoveRow);
            foreach ($removeMenuRow as $remove_id) {
                MenuInput::where('id',$remove_id)->delete();
            }
        }
        if(isset($request->appNameSet)){
        //delete before
            MenuInput::where('interactive_menu_id',$interactive_menu_id)->delete();
            foreach ($request->appNameSet as $key => $keySet) {
                
                $insertMenuInput = new MenuInput();
                $insertMenuInput->interactive_menu_id = $interactive_menu_id;
                $insertMenuInput->app_name = strtoupper($keySet);

                $insertMenuInput->app_value = $request->appValueSet[$key];

                $insertMenuInput->type = $request->type[$key];
                if($request->type[$key] =='key'){
                    $insertMenuInput->input_key = rawurlencode(strtolower($request->inputKey[$key])); 
                }elseif ($request->type[$key] =='button' || $request->type[$key] =='list') {
                    $insertMenuInput->set_key_primary = $request->key1[$key];
                    $insertMenuInput->set_key_secondary = $request->key2[$key];
                }
                $insertMenuInput->save();
            }
            return true;
        }
    }

    private function getAppValue($appValue) {
        return ($appValue == 'null' || $appValue == null) ? null : strtoupper($appValue);
    }

    public function getMenuList(Request $request) {
        $menuList = InteractiveMenu::where("user_id", Auth::user()->id)->where("reseller_id", Auth::user()->reseller_id);
        if($request->get("name")) {
           $menuList = $menuList->where("name", $request->get("name"));
        }
        $menuList = $menuList->orderBy("created_at", "DESC");
        $menuList = $menuList->paginate(10);

        $text = DB::table('text_applications')->select("name", DB::raw("'TEXT' as type"), "id")->where("user_id", Auth::user()->id);
        $image = DB::table('image_applications')->select("name", DB::raw("'IMAGE' as type"), "id")->where("user_id", Auth::user()->id);
        $capture = DB::table('capture_applications')->select("name", DB::raw("'CAPTURE' as type"), "id")->where("user_id", Auth::user()->id);
        $location = DB::table('location_applications')->select("name", DB::raw("'LOCATION' as type"), "id")->where("user_id", Auth::user()->id);
        $timeCondition = DB::table('time_condition_applications')->select("name", DB::raw("'TIME CONDITION' as type"), "id")->where("user_id", Auth::user()->id);
        $video = DB::table('video_applications')->select("name", DB::raw("'VIDEO' as type"), "id")->where("user_id", Auth::user()->id);
        $video = $video->union($image)->union($text)->union($capture)->union($location)->union($timeCondition)->get();
        return view('user.menu.menuLinkList', ["menuList"=>$menuList, "allData" => $video]);
    }

    public function editMenuList(Request $request, $id) {
        $id = \Crypt::decrypt($id);
        $interaction;
        if($id) {
            $interaction = InteractiveMenu::findOrFail($id);
            $menuInput = MenuInput::where("interactive_menu_id", $id)->select("input_key as inputKey", "app_name as keyAppName", "app_value as keyAppValueInInt", DB::raw("'null' as keyAppValue"), "id","type","set_key_primary","set_key_secondary")->get();

            $text = DB::table('text_applications')->select("name", DB::raw("'TEXT' as type"), "id")->where("user_id", Auth::user()->id);
            $image = DB::table('image_applications')->select("name", DB::raw("'IMAGE' as type"), "id")->where("user_id", Auth::user()->id);
            $video = DB::table('video_applications')->select("name", DB::raw("'VIDEO' as type"), "id")->where("user_id", Auth::user()->id);
            $video = $video->union($image)->union($text)->get();
        }
        return view('user.menu.menuLinkEdit', $interaction, ["menuInput"=>$menuInput, "allMenu" =>$video]);
    }

    public function removeMenuKey(Request $request) {
        if($request->get("id")) {
            MenuInput::where("id", $request->get("id"))->delete();
            return response()->json(["message" => "success"], 200);
        }
    }
}
