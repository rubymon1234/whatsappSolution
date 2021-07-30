<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ComposeController extends Controller
{
    /**
     * Compose View
     * @author Ruban
    */
    public function getComposeView(){
    	return view('user.compose.sentMessage');
    }
}
