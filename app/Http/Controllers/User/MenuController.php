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
            return redirect()->route('user.chat.bot.menu.create')->withInput(Input::all())->withErrors($validator);
        } else {
            $interactiveMenu = new InteractiveMenu();
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
                    $menuInput = new MenuInput();
                    $menuInput->interactive_menu_id = $interactiveMenu->id;
                    $menuInput->input_key = $key['inputKey'];
                    $menuInput->app_name = strtoupper($key['keyAppName']);
                    $menuInput->app_value = $key['keyAppValueInInt'];
                    $menuInput->save();
                }
            }
            return redirect()->route('user.chat.bot.menu.create')->with('success_message', 'Menu Added Successfully!!');
        }
    }
}
