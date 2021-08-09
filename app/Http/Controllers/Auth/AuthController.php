<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Domain;
use App\Models\CampaignsOutbound;
use App\Helpers\Helper;
use URL;
use Redirect;
use DateTime;
use \Carbon\Carbon;

class AuthController extends Controller
{
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function index(){

    	return view('auth.login');
    }

    public function login(Request $request){

        try {
           
        	$validator = Validator::make($request->all(),[
                'email'     => 'required',
                'password'  => 'required|min:6'
            ]);

            if ($validator->fails())
            {   
                return Redirect::back()->withErrors($validator)->withInput();
                
            }else{
                //get domain detail
                $domain = Helper::getFqdn($_SERVER['SERVER_NAME']);
                $domainDetail = Domain::where('domain_name',$domain)->first();

                if(isset($domainDetail)){
                    /* Check user table if the user is exist */
                    if (Auth::attempt(['email' => $request->email, 'password' => $request->password,'domain_id' => $domainDetail->id,'is_status' => 1])) {

                        //get user role details
                        $user      = Auth::user(); 
                        $roleSlug   = $user->roles->first()->slug;
                        return redirect()->route($roleSlug)->with('login_success_message', 'Login Successfully');
                    }else{
                       return Redirect::back()
                                ->withErrors(['error'=>'Wrong credentials or account is not active'])
                                ->withInput();
                    }
                }
                return Redirect::back()
                                ->withErrors(['error'=>'domain name does not Matched our records'])
                                ->withInput();
            }
 
            } catch (\Exception $e) {

                return Redirect::back()->with('error_message', $e->getMessage());
            }
    }
    public function homelanding(Request $request)
    {  
        $user_id = Auth::user()->id;
        $current_date_time = new DateTime("now");
        $today = $current_date_time->format("Y-m-d");
        $dashboardCount = array();
        $yesterday = Carbon::today();
        $yesterday_date = $yesterday->subDay(1);
        $yesterday_date = $yesterday_date->format("Y-m-d");

        //Today
        $dashboardToday = CampaignsOutbound::whereDate('created_at', '=', Carbon::today()->toDateString())->count();
        //yesterday
        $dashboardYesterday= CampaignsOutbound::whereDate('created_at', '=', $yesterday_date)->count();
        
        //this week
         $dashboardthisWeek= CampaignsOutbound::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        //last week
        $lastWeekStart = $yesterday->subDays($yesterday->dayOfWeek)->subWeek();
        $lastWeekEnd = $yesterday->subDays($yesterday->dayOfWeek + 1); 
        $lastWeek = CampaignsOutbound::whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])->count();
        //this month
        $dashboardthisMonth= CampaignsOutbound::whereMonth('created_at', date('m'))->count();
        //last month
        $dashboardlstMonth= CampaignsOutbound::whereMonth('created_at', '=', Carbon::now()->subMonth()->month)->count();

        //dates
        $dashboardCount['today_count'] = $dashboardToday;
        $dashboardCount['yesterday_count'] = $dashboardYesterday;
        $dashboardCount['this_week_count'] = $dashboardthisWeek;
        $dashboardCount['last_week_count'] = $lastWeek;
        $dashboardCount['this_month'] = $dashboardthisMonth;
        $dashboardCount['last_month'] = $dashboardlstMonth;

        return view('admin.dashboard', compact('dashboardCount'));
    }
    public function userlanding(Request $request){

        $user_id = Auth::user()->id;
        $current_date_time = new DateTime("now");
        $today = $current_date_time->format("Y-m-d");
        $dashboardCount = array();
        $yesterday = Carbon::today();
        $yesterday_date = $yesterday->subDay(1);
        $yesterday_date = $yesterday_date->format("Y-m-d");
        //Today
        $dashboardToday = CampaignsOutbound::where('user_id',$user_id)->whereDate('created_at', '=', Carbon::today()->toDateString())->count();
        //yesterday
        $dashboardYesterday= CampaignsOutbound::where('user_id',$user_id)->whereDate('created_at', '=', $yesterday_date)->count();
        
        //this week
         $dashboardthisWeek= CampaignsOutbound::where('user_id',$user_id)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
         //last week
        $lastWeekStart = $yesterday->subDays($yesterday->dayOfWeek)->subWeek();

        $lastWeekEnd = $lastWeekStart->addDays(7); 

        $lastWeek = CampaignsOutbound::where('user_id',$user_id)->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])->count();
        //this month
        $dashboardthisMonth= CampaignsOutbound::where('user_id',$user_id)->whereMonth('created_at', date('m'))->count();
        //last month
        $dashboardlstMonth= CampaignsOutbound::where('user_id',$user_id)->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)->count();

        //dates
        $dashboardCount['today_count'] = $dashboardToday;
        $dashboardCount['yesterday_count'] = $dashboardYesterday;
        $dashboardCount['this_week_count'] = $dashboardthisWeek;
        $dashboardCount['last_week_count'] = $lastWeek;
        $dashboardCount['this_month'] = $dashboardthisMonth;
        $dashboardCount['last_month'] = $dashboardlstMonth;

        return view('user.dashboard', compact('dashboardCount'));
    }
    public function resellerlanding(Request $request){
        $dashboardCount = array();
        $totalUser = User::where('reseller_id',Auth::user()->id)->where('domain_id',Auth::user()->domain_id)->count();
        $totalreseller = User::where('reseller_id',Auth::user()->id)->count();
        $resellerCount = $totalreseller -$totalUser;
        $dashboardCount['total_users'] = $totalUser;
        $dashboardCount['total_reseller'] = $resellerCount;
        return view('reseller.dashboard',compact('dashboardCount'));
    }
    public function defaultlanding(Request $request){
        return view('default.dashboard');
    }
}
