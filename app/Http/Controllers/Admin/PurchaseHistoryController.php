<?php

namespace App\Http\Controllers\Admin;

use DB;
use Illuminate\Support\Facades\Auth;
use App\Models\PurchaseHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PurchaseHistoryController extends Controller
{
    /**
     * Purchase View 
     * @author Ruban
    */
   	public function getPurchaseHistory()
    {
        $purchaseHistory = DB::table('purchase_histories')
            ->leftJoin('plan_requests', 'plan_requests.id', '=', 'purchase_histories.plan_request_id')
            ->leftJoin('users', 'users.id', '=', 'purchase_histories.user_id')
            ->leftJoin('plans', 'plans.id', '=', 'purchase_histories.plan_id')
            ->where('purchase_histories.reseller_id',Auth::user()->id) //pending for approval
            ->select('users.name','plans.plan_name','purchase_histories.is_status','plans.daily_count','plans.plan_validity','plan_requests.credit','purchase_histories.created_at','plans.scrub_count','plans.bot_instance_count')
            ->latest('purchase_histories.updated_at')
            ->paginate(10);

        return view('admin.recharge.purchaseHistoryView',compact('purchaseHistory'));
    }
}
