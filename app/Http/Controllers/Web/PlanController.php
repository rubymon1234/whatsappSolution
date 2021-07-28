<?php

namespace App\Http\Controllers\Web;

use Crypt;
use App\Models\Plan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class PlanController extends Controller
{
    /**
     * ViewPlan 
     * @author Ruban
    */
   	public function getPlanView(Request $request)
    {
    	$key = $request->qa;
    	$plans = Plan::orwhere('plan_name', 'LIKE', '%' . $key . '%')
        				->orderBy('updated_at', ' DESC')->paginate(10);
		$plans->appends(['qa' => $key]);

        return view('web.global.planView',compact('plans','key'));
    }
    /**
     * CreatePlan 
     * @author Ruban
    */
   	public function getPlanCreate(Request $request)
    {
    	return view('web.global.planCreate');
    }
    /**
     * ViewPlan (POST)
     * @author Ruban
    */
    public function postPlanCreate(Request $request)
    {
    	if($request->Update =='Save'){

            $rule = [
                'plan_name' => 'required|unique:plans',
                'daily_count' => 'required|numeric',
                'plan_validity' => 'required|numeric',
            ];
            $messages = [
                'plan_name.required' => 'Plan Name is required',
                'plan_name.unique' => 'Plan Name Already Exist',
                'daily_count.required' => 'Daily count is required',
                'plan_validity.required' => 'Plan validity is required',
            ];

            $validator = Validator::make(Input::all(), $rule, $messages);

            if ($validator->fails()) {
                return redirect()->route('global.plan.create')->withInput(Input::all())->withErrors($validator);
            }else{

                $newPlan = new Plan();
                $newPlan->plan_name         = $request->get('plan_name');
                $newPlan->daily_count  = $request->get('daily_count');
                $newPlan->plan_validity  = $request->get('plan_validity');
                if($newPlan->save())
                    return redirect()->route('global.plan.view')->with('success_message', 'New Plan successfully Added ');
            }
        }
        if ($request->Cancel =='cancel') {
            return redirect()->route('global.plan.view')->with('warning_message', 'Request is Rollback');
        }
    }
    /**
     * Update Plan 
     * @author Ruban
    */
   	public function getPlanUpdate($id =null)
    {
    	$plan = Plan::find($id);
    	return view('web.global.planUpdate', compact('plan','id'));
    }
    /**
     * UpdatePlan (POST)
     * @author Ruban
    */
    public function postPlanUpdate(Request $request,$id)
    {
    	if($request->Update =='Save'){

            $rule = [
                'plan_name' => 'required|unique:plans',
            ];
            $messages = [
                'plan_name.required' => 'Plan is required',
            ];
            $plan_id = Crypt::decrypt($id);
            $validator = Validator::make(Input::all(), $rule, $messages);

            if ($validator->fails()) {
                return redirect()->route('global.plan.update')->withInput(Input::all())->withErrors($validator);
            }else{

                $updatePlan = Plan::find($plan_id);
                $updatePlan->plan_name         = $request->get('plan_name');
                if($updatePlan->save())
                    return redirect()->route('global.plan.view')->with('success_message', 'New Plan successfully Added ');
            }
        }
        if ($request->Cancel =='cancel') {
            return redirect()->route('global.plan.view')->with('warning_message', 'Request is Rollback');
        }
    }
}
