<?php

namespace App\Http\Controllers\User\ChatBot;

use DB;
use Carbon\Carbon;
use App\Models\CurrentPlan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MessageResponseController extends Controller
{
    /**
     * Message Response 
     * @author Ruban
    */
   	public function getMessageResponse()
    {
        return view('user.chatbot.messageResponseCreate');
    }
}
