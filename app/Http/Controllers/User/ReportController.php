<?php

namespace App\Http\Controllers\User;

use DB;
use Response;
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
        $campaignList = Campaign::where('user_id',$user_id)->orderBy('updated_at','DESC')->get();

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
          			->select('campaigns_outbounds.*','campaigns.campaign_name','campaigns.instance_name')
          			->orderBy('campaigns_outbounds.updated_at', 'DESC')->paginate(10);
		$sentList->appends(['number' => $number]);
		$sentList->appends(['campaign_id' => $campaign_id]);

        // download csv
        if($request->download  =='download'){

            $sentListDetail =\DB::table('campaigns_outbounds')
                    ->join('campaigns', 'campaigns.id', '=', 'campaigns_outbounds.campaign_id')
                    ->whereRaw("wc_campaigns_outbounds.`user_id` = '".$user_id. "' $condtion")
                    ->select('campaigns_outbounds.*','campaigns.campaign_name','campaigns.instance_name')
                    ->orderBy('campaigns_outbounds.updated_at', 'DESC')->get();

            $uniqueId  = hexdec(uniqid());

            $headers = [
                'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0'
                ,   'Content-type'        => 'text/csv'
                ,   'Content-Disposition' => 'attachment; filename=Consolidated_Report_'.$uniqueId.'.csv'
                ,   'Expires'             => '0'
                ,   'Pragma'              => 'public'
            ];

            $callback4 = function() use ($sentListDetail) 
            {
              $fp = fopen('php://output', 'w');
              $data = Array('Sl_No','Campaign Name','Number','Instance Name','Type','Message','Sent time','Status');
              fputcsv($fp, $data);
              $i = 1;
              foreach ($sentListDetail as $sent) { 
                $sent_time = date('Y-m-d h:m:s', $sent->sent_time);
                $data = Array(
                    $i++,
                    $sent->campaign_name,
                    $sent->number,
                    $sent->instance_name,
                    $sent->type,
                    rawurldecode($sent->message),
                    $sent_time,
                    $sent->status_message,
                );
                fputcsv($fp, $data);
              }
              fclose($fp);
            };
            return Response::stream($callback4, 200, $headers);
        }

        return view('user.report.consolidatedReport',compact('sentList','number','campaignList','campaign_id'));
    }
}
