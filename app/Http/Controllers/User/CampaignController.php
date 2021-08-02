<?php

namespace App\Http\Controllers\User;

use DB;
use Carbon\Carbon;
use App\Models\Campaign;
use App\Models\CurrentPlan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CampaignController extends Controller
{
    /**
     * Purchase View 
     * @author Ruban
    */
   	public function getCampaignList()
    {

        $campaignList = Campaign::where('user_id',Auth::user()->id)->orderBy('updated_at','DESC')->paginate(10);

        return view('user.campaign.campaignList',compact('campaignList'));
    }
}
