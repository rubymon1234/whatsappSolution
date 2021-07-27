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
use App\Helpers\Helper;
use URL;
use Redirect;


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
        return view('admin.dashboard');
    }
    public function userlanding(Request $request){
        return view('user.dashboard');
    }
    public function resellerlanding(Request $request){
        return view('reseller.dashboard');
    }
    public function defaultlanding(Request $request){
        return view('default.dashboard');
    }
}
