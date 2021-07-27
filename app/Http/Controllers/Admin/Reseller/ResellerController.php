<?php

namespace App\Http\Controllers\Admin\Reseller;

use Crypt;
use App\Models\Role;
use App\Models\Permission;
use App\Models\PermissionRole;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class ResellerController extends Controller
{
    /**
     * ViewRole 
     * @author Ruban
    */
   	public function getResellerView()
    {
        $roles = Role::paginate(15);
        return view('admin.reseller.resellerView',compact('roles'));
    }

}