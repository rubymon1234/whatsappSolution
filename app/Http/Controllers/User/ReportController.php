<?php

namespace App\Http\Controllers\User;

use DB;
use Carbon\Carbon;
use App\Models\Campaign;
use App\Models\CurrentPlan;
use App\Models\CampaignsOutbound;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    /**
     * Report View 
     * @author Ruban
    */
   	public function getConsolidatedReport(Request $request)
    {
    	$user_id = Auth::user()->id;
        $number = $request->number;
        $campaign_id = $request->campaign_id;
        //campaign list
        $campaignList = Campaign::where('user_id',$user_id)->get();

        $condtion ='';
        if($number !=''){
			$condtion.=" AND wc_campaigns_outbounds.`number`='".$number. "'";
		}
		if($campaign_id !='') {
			$condtion.=" AND wc_campaigns_outbounds.`campaign_id`='".$campaign_id. "'";
		}
    	$sentList =\DB::table('campaigns_outbounds')
    				->join('campaigns', 'campaigns.id', '=', 'campaigns_outbounds.campaign_id')
          			->whereRaw("wc_campaigns_outbounds.`user_id` = '".$user_id. "' $condtion")
          			->select('campaigns_outbounds.*','campaigns.campaign_name')
          			->orderBy('campaigns_outbounds.updated_at', ' DESC')->paginate(10);
		$sentList->appends(['number' => $number]);
		$sentList->appends(['campaign_id' => $campaign_id]);

        return view('user.report.consolidatedReport',compact('sentList','number','campaignList','campaign_id'));
    }
}
