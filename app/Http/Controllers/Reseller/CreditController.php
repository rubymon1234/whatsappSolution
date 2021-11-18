<?php

namespace App\Http\Controllers\Reseller;

use Crypt;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use App\Models\RoleUser;
use App\Models\Accounts;
use App\Models\AccountsLog;
use App\Models\PermissionRole;
use App\Models\Domain;
use App\Models\Plan;
use App\Models\PlanRequest;
use App\Models\PurchaseHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Helper;
use DB;

class CreditController extends Controller
{
    /**
     * Credit Management 
     * @author Ruban
    */
   	public function getAddCredit(Request $request,$id)
    {
        try {
            //check if it user is reseller
            $user = Auth::user();
            if($user->hasRole('reseller')){
                $user = User::find(Crypt::decrypt($id));
                return view('reseller.credit.addCredit', compact('user'));
            }
            
            return redirect()->route('reseller.user.view')->with('error_message', "Oops, Something went wrong.");  
        } catch (\Exception $e) {
            return redirect()->route('reseller.user.view')->with('warning_message', $e->getMessage());
        }
    }
    /**
     * Credit Management 
     * @author Ruban
    */
    public function postAddCredit(Request $request,$id)
    {
        /*try {*/
            DB::beginTransaction();
            //check if it user is reseller
            $user = Auth::user();
            if($user->hasRole('reseller')){

                if($request->Update =='Save'){
                    //request
                    $user       = User::find(Crypt::decrypt($id));
                    $amount     = (int)trim($request->amount);
                    $type       = $request->payMethod;
                    $tranfer_to = $user->id;
                    $reseller_id = Auth::user()->id;
                    $account    = $request->account;
                    //reseller check
                    $this->resellerCheck($user);

                    //credit checking
                    if($type =='debit'){
                        $balanceCheck = Accounts::where('user_id',$user->id)->first();
                        //create log
                        $accountsLog = new AccountsLog;
                        $accountsLog->user_id = $tranfer_to;


                        $accountsLog->reseller_id = $reseller_id;

                        $accountsLog->amount = $amount;
                        $accountsLog->type = $type;
                        

                        if($request->account =='credits'){
                            $accountsLog->prev_amount = $balanceCheck[$account];
                            $accountsLog->curr_amount = $balanceCheck[$account] - $amount;
                        }
                        if($request->account =='api_credits'){
                            $accountsLog->prev_amount = $balanceCheck[$account];
                            $accountsLog->curr_amount = $balanceCheck[$account] - $amount;
                        }
                          
                        $accountsLog->created_by = Auth::user()->id; 
                        $accountsLog->save();

                        //Account
                        $creditaccounts = Accounts::where('user_id',$reseller_id)->where('reseller_id', Auth::user()->reseller_id)->first();
                        //debit acc
                        $debitaccounts = Accounts::where('user_id',$user->id)->where('reseller_id', $user->reseller_id)->first();

                        if($request->account =='credits'){
                            $creditaccounts->credits = $creditaccounts->credits + $amount;
                        }
                        if($request->account =='api_credits'){
                            $creditaccounts->api_credits =  $creditaccounts->api_credits + $amount;
                        }

                        $creditaccounts->save();
                        if($request->account =='credits'){
                            $debitaccounts->credits = $debitaccounts->credits - $amount;
                        }

                        if($request->account =='api_credits'){
                            $debitaccounts->api_credits =  $debitaccounts->api_credits - $amount;
                        }
                        $debitaccounts->save();
                    }
                    if($type =='credit'){
                    $balanceCheck = Accounts::where('user_id',$reseller_id)->first();
                    if($balanceCheck[$account] >=$amount){
                        //create log
                        $accountsLog = new AccountsLog;
                        $accountsLog->user_id = $tranfer_to;


                        $accountsLog->reseller_id = $reseller_id;

                        $accountsLog->amount = $amount;
                        $accountsLog->type = $type;
                        

                        if($request->account =='credits'){
                            $accountsLog->prev_amount = $balanceCheck[$account];
                            $accountsLog->curr_amount = $balanceCheck[$account] + $amount;
                        }
                        if($request->account =='api_credits'){
                            $accountsLog->prev_amount = $balanceCheck[$account];
                            $accountsLog->curr_amount = $balanceCheck[$account] + $amount;
                        }
                          
                        $accountsLog->created_by = Auth::user()->id; 
                        $accountsLog->save();

                      
                        //Account
                        $creditaccounts = Accounts::where('user_id',$tranfer_to)->where('reseller_id', Auth::user()->id)->first();
                        //debit acc
                        $debitaccounts = Accounts::where('user_id',Auth::user()->id)->where('reseller_id', Auth::user()->reseller_id)->first();
                        
                        if($request->account =='credits'){
                            $creditaccounts->credits = $creditaccounts->credits + $amount;
                        }
                        if($request->account =='api_credits'){
                            $creditaccounts->api_credits =  $creditaccounts->api_credits + $amount;
                        }
                        $creditaccounts->save();
                        if($request->account =='credits'){
                            $debitaccounts->credits = $debitaccounts->credits - $amount;
                        }
                        if($request->account =='api_credits'){
                            $debitaccounts->api_credits =  $debitaccounts->api_credits - $amount;
                        }
                        $debitaccounts->save();
                        
                    }else{
                        return Redirect::back()
                                ->withErrors(['error'=>'insufficient balance in your account.'])
                                ->withInput();
                    }
                    }
                    
                }
                DB::commit();
                return redirect()->route('reseller.user.view')->with('success_message', "Amount updated successfully");
            }
            DB::rollback();
            return redirect()->route('reseller.user.view')->with('error_message', "Oops, Something went wrong.");  
        /*} catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('reseller.user.view')->with('warning_message', $e->getMessage());
        }*/
    }
    public function resellerCheck($user){

        $reseller_id = Auth::user()->id;
        $transfer_id = $user->reseller_id;
        if($transfer_id != $reseller_id){
            return redirect()->route('reseller.user.view')->with('error_message', "Oops, Something went wrong.");
        }
        return true;
    }
}