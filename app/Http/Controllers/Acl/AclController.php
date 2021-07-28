<?php

namespace App\Http\Controllers\Acl;

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

class AclController extends Controller
{
    /**
     * ViewRole 
     * @author Ruban
    */
   	public function getViewRole()
    {
        $roles = Role::paginate(15);
        return view('acl.roleView',compact('roles'));
    }

    /**
     * CreateRole
     * @author Ruban
    */
    public function getCreateRole($id =null)
    {
        $roleDetail = array();
        $permissions = Permission::all(); 
        if(isset($id)){
            $id = Crypt::decrypt($id);
            $roleDetail = Role::find($id); 
        }
        return view('acl.roleCreate',compact('permissions','roleDetail','id'));
    }

    /**
     * CreateRole ( Method=POST )
     * @param name, display_name,description
     * @author Ruban
    */
    public function postCreateRole(Request $request,$id =null)
    {
    	try {
    		
	    	if($request->Update =='Save' || $request->Update =='update'){
                if($request->Update =='Save'){
                    $rule = [
                        'name' => 'required|unique:roles',
                        'display_name' => 'required',
                        'description' => 'required',
                        'slug'        => 'required',
                    ];
                }
		    	if($request->Update =='update'){
                    $rule = [
                        'display_name' => 'required',
                        'description' => 'required',
                        'slug'        => 'required',
                    ];
                }
		        $messages = [
		            //'name.required' => 'Role Name is required',
		            'display_name.unique' => 'Role Name Already Exist',
		            'description.required' => 'Description is required',
                    'slug.required' => 'Default Permission is required',
		        ];

		        $validator = Validator::make(Input::all(), $rule, $messages);

		        if ($validator->fails()) {

		        	return redirect()->route('acl.role.manage')->withInput(Input::all())->withErrors($validator);

		        }else{

                    if(isset($id)){
                        $id = Crypt::decrypt($id);
                    }
                    if($id){

                        $updateRole = Role::find($id);
                        $updateRole->display_name = $request->display_name;
                        $updateRole->slug = $request->slug;
                        $updateRole->description = $request->description;
                        $updateRole->save();
                        $message = 'Role Updated Successfully';

                    }else{

                        $updateRole = Role::create([
                            'name' => $request->name,
                            'display_name' => $request->display_name,
                            'slug' => $request->slug,
                            'description' => $request->description,
                        ]);
                        $id = $updateRole->id;
                        $message = 'New Role successfully Added';
                    }
                    if($updateRole){
                        $getPermission = Permission::where('name',$request->slug)->first();
                        $permissionExist = PermissionRole::where('permission_id',$getPermission->id)->where('role_id',$id)->get();
                        if($permissionExist->count() ==0){
                            $permissionRole = new PermissionRole();
                            $permissionRole->permission_id = $getPermission->id;
                            $permissionRole->role_id = $id;
                            $permissionRole->save();
                        }
                    }

		        return redirect()->route('acl.role.view')->with('success_message', $message);

		        }
	    	}
	    	if ($request->Cancel =='cancel') {
	    		 return redirect()->route('acl.role.view')->with('warning_message', 'Request is Rollback');
	    	}
    	} catch (\Exception $e) {
        	return redirect()->route('acl.role.view')->with('warning_message', $e->getMessage());
    	}
    }

    /**
     * ViewPermissions ( Method=GET )
     * @param no
     * @author Ruban
    */
    public function getViewPerms()
    {
        $perms = Permission::paginate(50);
        return view('acl.permsView',compact('perms'));
    }

    /**
     * CreatePermissions ( Method=GET )
     * @param no
     * @author Ruban
    */
    public function getCreatePerms()
    {
        $perm_repo  = false;
        return view('acl.permsCreate');
    }

    /**
     * CreatePermissions ( Method=POST )
     * @param name,display_name,description
     * @author Ruban
    */
    public function postCreatePerms(Request $request)
    {
        if($request->Update =='Save'){

            $rule = [
                'name' => 'required|unique:permissions',
                'display_name' => 'required',
                'description' => 'required',
            ];
            $messages = [
                'name.required' => 'Role Name is required',
                'display_name.unique' => 'Role Name Already Exist',
                'description.required' => 'Description is required',
            ];

            $validator = Validator::make(Input::all(), $rule, $messages);

            if ($validator->fails()) {
                return redirect()->route('acl.perms.manage')->withInput(Input::all())->withErrors($validator);
            }else{

                $newPermission = new Permission();
                $newPermission->name         = $request->get('name');
                $newPermission->display_name = $request->get('display_name');
                $newPermission->description  = $request->get('description');
                if($newPermission->save())
                    return redirect()->route('acl.perms.view')->with('success_message', 'New Permissoin successfully Added ');
            }
        }
        if ($request->Cancel =='cancel') {
            return redirect()->route('acl.perms.view')->with('warning_message', 'Request is Rollback');
        }
    }
    /**
     * View page - Assign permission for selected Role
     * @param  Integer $roleId role id
     * @return array         [description]
     */
    public function getPermissionAssign($roleId)
    { 
        $roleId = Crypt::decrypt($roleId);
        $role = Role::find($roleId);
        $role_permissions = $role->perms()->get();
        $permissions = Permission::get();

        return view('acl.assign-permissions', [
            'role' => $role,
            'role_permissions' => $role_permissions,
            'permissions' =>$permissions,
        ]);
    }
    /**
     * Form action -  assign permission for roles
     * @param  Request $request form contents
     * @return [type]           return with message
     */
    public function postPermissionAssign(Request $request, $roleId)
    {
        if($request->Update =='Save'){
            $roleId = Crypt::decrypt($roleId);
            $role = Role::find($roleId);
            $role->perms()->sync($request->get('permissn'));
            return redirect()->route('acl.role.view')->with('success_message', 'Role Updated Successfully ');
        }
        return redirect()->route('acl.role.view')->with('warning_message', 'Request Rollback ');
    }
    /**
     * Form action -  Edit permission for roles
     * @param  Request $request form contents
     * @return [type]           return with message
     */
    public function getEditPerms($id){
        $id = Crypt::decrypt($id);
        $perms = Permission::find($id);
        return view('acl.permsEdit',compact('perms','id'));
    }
    /**
     * Form action -  Edit permission for roles
     * @param  Request $request form contents
     * @return [type]           return with message
     */
    public function postEditPerms(Request $request,$id){

        if($id !='' && $request->name !='' && $request->display_name !=''){

            $perms = Permission::find($id);
            $perms->name = $request->name;
            $perms->display_name = $request->display_name;
            $perms->description = $request->description;
            $perms->save();
            return redirect()->route('acl.perms.view')->with('success_message', 'Permission Updated Successfully ');
        }else{
           return redirect()->route('acl.perms.view')->with('error_message', 'Error During Update'); 
        }
    }
    /**
     * DetetePermissions ( Method=GET )
     * @param array roles,permission
     * @author Ruban
    */
    public function getDelPerms($permissionId){

        try {

            $user      = Auth::user(); 
            $roleId   = $user->roles->first()->id;
            $roleArr = Role::all();
            foreach ($roleArr as $key => $roleDetail) {
                $role = Role::find($roleDetail->id);
                $role->perms()->detach($permissionId);
            }
            $perms = Permission::findOrFail($permissionId);
            $perms->delete();

            return redirect()->route('acl.perms.view')->with('success_message', 'Permission Removed Successfully ');

        } catch (\Exception $e) {
            return redirect()->route('acl.perms.view')->with('error_message', $e->getMessage());
        }
    }
}
