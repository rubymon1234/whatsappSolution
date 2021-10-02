<?php

namespace App\Http\Controllers\User;

use DB;
use Response;
use DateTime;
use Crypt;
use Carbon\Carbon;
use App\Helpers\Helper;
use App\Models\LogSession;
use App\Models\ChatInstance;
use App\Models\Campaign;
use App\Models\Instance;
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
          			->orderBy('campaigns_outbounds.id', 'DESC')->paginate(10);
		$sentList->appends(['number' => $number]);
		$sentList->appends(['campaign_id' => $campaign_id]);

        // download csv
        if($request->download  =='download'){

            $sentListDetail =\DB::table('campaigns_outbounds')
                    ->join('campaigns', 'campaigns.id', '=', 'campaigns_outbounds.campaign_id')
                    ->whereRaw("wc_campaigns_outbounds.`user_id` = '".$user_id. "' $condtion")
                    ->select('campaigns_outbounds.*','campaigns.campaign_name','campaigns.instance_name')
                    ->orderBy('campaigns_outbounds.id', 'DESC')->get();

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
    /**
     * Report View 
     * @author Ruban
    */
    public function getChatBotReport(Request $request)
    {
        $user_id = Auth::user()->id;
        $combinationList = $this->getDefaultCombinationList();
        $chatInstanceList = Instance::where('user_id',$user_id)->get();
        //variable declaration
        $condtion       =''; 
        $startDate      = new Carbon('now');
        $endDate        = new Carbon('now');
        $combination    = $request->combination;
        $daterange      = $request->daterange;
        $instance_id    = $request->instance_id;
        if($request->instance_id==''){
            $chatTokens = Instance::where('user_id',$user_id)->pluck('token')->unique();
            //$chatTokens =  ChatInstance::where('user_id',$user_id)->whereIn('instance_id',$chatTokensUnique)->pluck('instance_token')->unique();
        }else{
            $chatTokens = array($request->instance_id);
        }
        if($request->daterange !=''){
            $date_range = explode(" - ",$request->daterange);
            $startDate = new Carbon($date_range[0]);
            $endDate = new Carbon($date_range[1]);
        }
        //get log sesstions
        $logSessionList = DB::table('log_sessions')
            ->join('instances', 'instances.token', '=', 'log_sessions.instance_token')
            ->whereIn('instance_token',$chatTokens);
            if($request->combination !=''){
                $logSessionList->where('app_name',$request->combination);
            }
            if($request->daterange !=''){
                $logSessionList->whereBetween('log_sessions.created_at', [Carbon::parse($startDate)->toDateString(), Carbon::parse($endDate)->toDateString()]);
            }
            $logSessionList = $logSessionList->paginate(10);

        // download csv
        if($request->download  =='download'){

            $logSessionListDownload = DB::table('log_sessions')
            ->join('instances', 'instances.token', '=', 'log_sessions.instance_token')
            ->whereIn('instance_token',$chatTokens);
            if($request->combination !=''){
                $logSessionListDownload->where('app_name',$request->combination);
            }
            if($request->daterange !=''){
                $logSessionListDownload->whereBetween('log_sessions.created_at', [Carbon::parse($startDate)->toDateString(), Carbon::parse($endDate)->toDateString()]);
            }
            $logSessionListDownload = $logSessionListDownload->get();

            $uniqueId  = hexdec(uniqid());

            $headers = [
                'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0'
                ,   'Content-type'        => 'text/csv'
                ,   'Content-Disposition' => 'attachment; filename=Chat_Report_'.$uniqueId.'.csv'
                ,   'Expires'             => '0'
                ,   'Pragma'              => 'public'
            ];

            $callback = function() use ($logSessionListDownload,$user_id) 
            {
                $fp1 = fopen('php://output', 'w');
                $data1 = Array('Sl_No','Instance Name','Number','User Input','App Name','App Value','Sent time');
                fputcsv($fp1, $data1);
                $i = 1;
                foreach ($logSessionListDownload as $log) { 
                    $sent_time = date('Y-m-d h:m:s', strtotime($log->created_at));
                    $appValue = Helper::getNextAppNameView(strtolower($log->app_name),Crypt::encryptString($log->app_value));
                    $data1 = Array(
                        $i++,
                        $log->instance_name,
                        explode("@",$log->number)[0],
                        rawurldecode($log->user_input),
                        $log->app_name,
                        $appValue,
                        $sent_time,
                    );
                    fputcsv($fp1, $data1);
                }
                fclose($fp1);
            };
            return Response::stream($callback, 200, $headers);
        }  
            
        return view('user.report.chatBotReport',compact('logSessionList','chatInstanceList','combinationList','combination','daterange','instance_id'));
    }
    /**
     * Report View 
     * @author Ruban
    */
    public function getMenuInputReport(Request $request)
    {
        $user_id = Auth::user()->id;
        $combinationList = $this->getDefaultCombinationList();
        $chatInstanceList = Instance::where('user_id',$user_id)->get();
        //variable declaration
        $condtion       =''; 
        $startDate      = new Carbon('now');
        $endDate        = new Carbon('now');
        $daterange      = $request->daterange;
        $instance_id    = $request->instance_id;
        if($request->instance_id==''){
            $chatTokens = Instance::where('user_id',$user_id)->pluck('token')->unique();
            //$chatTokens =  ChatInstance::where('user_id',$user_id)->whereIn('instance_id',$chatTokensUnique)->pluck('instance_token')->unique();
        }else{
            $chatTokens = array($request->instance_id);
        }
        if($request->daterange !=''){
            $date_range = explode(" - ",$request->daterange);
            $startDate = new Carbon($date_range[0]);
            $endDate = new Carbon($date_range[1]);
        }
        //get log sesstions
        $logSessionList = DB::table('data_capture_logs')
            ->join('instances', 'instances.token', '=', 'data_capture_logs.instance_token')
            ->whereIn('instance_token',$chatTokens);
            if($request->daterange !=''){
                $logSessionList->whereBetween('data_capture_logs.created_at', [Carbon::parse($startDate)->toDateString(), Carbon::parse($endDate)->toDateString()]);
            }
            $logSessionList = $logSessionList->paginate(10);

        // download csv
        if($request->download  =='download'){

            $logSessionListDownload = DB::table('data_capture_logs')
            ->join('instances', 'instances.token', '=', 'data_capture_logs.instance_token')
            ->whereIn('instance_token',$chatTokens);
            if($request->combination !=''){
                $logSessionListDownload->where('app_name',$request->combination);
            }
            if($request->daterange !=''){
                $logSessionListDownload->whereBetween('data_capture_logs.created_at', [Carbon::parse($startDate)->toDateString(), Carbon::parse($endDate)->toDateString()]);
            }
            $logSessionListDownload = $logSessionListDownload->get();

            $uniqueId  = hexdec(uniqid());

            $headers = [
                'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0'
                ,   'Content-type'        => 'text/csv'
                ,   'Content-Disposition' => 'attachment; filename=User_Input_Report_'.$uniqueId.'.csv'
                ,   'Expires'             => '0'
                ,   'Pragma'              => 'public'
            ];

            $callback = function() use ($logSessionListDownload,$user_id) 
            {
                $fp1 = fopen('php://output', 'w');
                $data1 = Array('Sl_No','Instance Name','Number','User Input','Sent time');
                fputcsv($fp1, $data1);
                $i = 1;
                foreach ($logSessionListDownload as $log) { 
                    $sent_time = date('Y-m-d h:m:s', strtotime($log->created_at));
                    $data1 = Array(
                        $i++,
                        $log->instance_name,
                        explode("@",$log->number)[0],
                        rawurldecode($log->user_input),
                        $sent_time,
                    );
                    fputcsv($fp1, $data1);
                }
                fclose($fp1);
            };
            return Response::stream($callback, 200, $headers);
        }  
            
        return view('user.report.chatUserInput',compact('logSessionList','chatInstanceList','combinationList','daterange','instance_id'));
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
            "timeCondition" => "TIME CONDITION",
            "menu" => "MENU"
        );
    }
}
