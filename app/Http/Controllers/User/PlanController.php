<?php

namespace App\Http\Controllers\User;

use DB;
use Carbon\Carbon;
use App\Models\CurrentPlan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PlanController extends Controller
{
    /**
     * Purchase View 
     * @author Ruban
    */
   	public function getActivePlans()
    {

        $currentPlan = DB::table('current_plans')
            ->leftJoin('users', 'users.id', '=', 'current_plans.user_id')
            ->leftJoin('plans', 'plans.id', '=', 'current_plans.plan_id')
            ->where('current_plans.user_id',Auth::user()->id) // pending for approval
            ->whereIn('current_plans.is_status',['1','2','0']) // pending for approval
            //->whereDate('current_plans.plan_validity', '>=', Carbon::today()->toDateString())
            ->orwhere('current_plans.plan_validity',NULL)
            ->select('users.name','plans.plan_name','current_plans.is_status','plans.daily_count','plans.plan_validity','current_plans.plan_validity as current_validity','current_plans.created_at','current_plans.id as id')
            ->latest('current_plans.updated_at')
            ->paginate(9);

            /*echo "<pre>";
            print_r($currentPlan);
            exit();*/
        return view('user.plans.activePlans',compact('currentPlan'));
    }
}
