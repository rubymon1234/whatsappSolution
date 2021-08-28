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
            $interactiveMenu->app_name = strtoupper($request->get("appName"));
            $interactiveMenu->app_value = $request->get("appValue");
            $interactiveMenu->invalid_app_name = strtoupper($request->get("invalidAppName"));
            $interactiveMenu->invalid_app_value = $request->get("invalidAppValue");
            $interactiveMenu->save();
            if($interactiveMenu->id > 0) {
                foreach(json_decode($request->get("keySet"), true) as $key) {
                    $menuInput = isset($key['id']) ? MenuInput::findOrFail($key['id']) : new MenuInput();
                    $menuInput->interactive_menu_id = $interactiveMenu->id;
                    $menuInput->input_key = $key['inputKey'];
                    $menuInput->app_name = strtoupper($key['keyAppName']);
                    $menuInput->app_value = $key['keyAppValueInInt'];
                    $menuInput->save();
                }
            }
            if($request->get("id")) {
                return redirect()->route('user.chat.bot.menu.list')->with('success_message', 'Menu Updated Successfully!!');
            } else {
                return redirect()->route('user.chat.bot.menu.create')->with('success_message', 'Menu Added Successfully!!');
            }
        }
    }

    public function getMenuList(Request $request) {
        $menuList = InteractiveMenu::where("user_id", Auth::user()->id)->where("reseller_id", Auth::user()->reseller_id);
        if($request->get("name")) {
           $menuList = $menuList->where("name", $request->get("name"));
        }
        $menuList = $menuList->paginate(10);
        return view('user.menu.menuLinkList', ["menuList"=>$menuList]);
    }

    public function editMenuList(Request $request, $id) {
        $id = \Crypt::decrypt($id);
        $interaction;
        if($id) {
            $interaction = InteractiveMenu::findOrFail($id);
            $menuInput = MenuInput::where("interactive_menu_id", $id)->select("input_key as inputKey", "app_name as keyAppName", "app_value as keyAppValueInInt", DB::raw("'null' as keyAppValue"), "id")->get();

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
