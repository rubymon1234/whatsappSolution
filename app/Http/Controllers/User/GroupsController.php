<?php

namespace App\Http\Controllers\User;

use DB;
use Carbon\Carbon;
use App\Models\CurrentPlan;
use App\Models\Group;
use App\Models\GroupContact;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;


class GroupsController extends Controller
{
    /**
     * Groups View
     * @author Ruban
    */
   	public function getListGroup()
    {
        $groupList = Group::where('user_id',Auth::user()->id)
                            ->orderBy('updated_at','DESC')->paginate(10);
        return view('user.groups.groupsView',compact('groupList'));
    }
    public function postDeleteContacts($id){
        $contct_id = Crypt::decrypt($id);
        $groupContactList = GroupContact::find($contct_id);
        $groupContactList->delete();
        return redirect()->route('user.group.view')->with('success_message', 'Contacts deleted successfully.');
    }
    /**
     * Groups Update
     * @author Ruban
    */
    public function getUpdateGroup($id){
        $group_id = Crypt::decrypt($id);
        $groupContactList = GroupContact::where('group_id',$group_id)
                            ->orderBy('updated_at','DESC')->paginate(10);
        return view('user.groups.groupContactUpdate',compact('groupContactList','group_id'));
    }
    /**
     * Delete Group
     * @author Ruban
    */
    public function postDeleteGroup(Request $request,$id){
        echo $group_id = Crypt::decrypt($id);
        $groupContactList = GroupContact::where('group_id',$group_id)->get();
        if($groupContactList){
            foreach($groupContactList as $gContact){
                $contactDetails = GroupContact::find($gContact->id);
                $contactDetails->delete();
            }
        }
        $groupList = Group::find($group_id);
        $groupList->delete();
        return redirect()->route('user.group.view')->with('success_message', 'Group deleted successfully.');
    }
    /**
     * Create Group
     * @author Ruban
    */
    public function getCreateGroup(Request $request)
    {
        try {
            $rule = [
                'group_name' => 'required',
                'csv_import' => 'required|mimes:csv,txt',
            ];
            $messages = [
                'group_name.required' => 'Group name is required',
                'csv_import.required' => 'csv file is required',
            ];

            $validator = Validator::make(Input::all(), $rule, $messages);
            if ($validator->fails()) {

                return redirect()->route('user.group.view')->withInput(Input::all())->withErrors($validator);

            }else{

                // Get uploaded CSV file
                $file = $request->file('csv_import');
                $group_name = $request->group_name;

                // Create list name
                $name = time().'_'.$file->getClientOriginalName();
                $path = public_path('/uploads/chat-bot/').$name;
                // Moves file to folder on server
                $request->csv_import->move(public_path('/uploads/chat-bot'), $name);
                $this->importCsv($path,$group_name);
                return redirect()->route('user.group.view')->with('success_message', 'New Group created successfully.');
            }
        } catch (\Exception $e) {
             return redirect()->route('user.group.view')->with('error_message', $e->getMessage());
        }
    }
    public function postUpdateCsvGroup(Request $request,$id)
    {
        try {
            $rule = [
                'csv_import' => 'required|mimes:csv,txt',
            ];
            $messages = [
                'csv_import.required' => 'csv file is required',
            ];

            $validator = Validator::make(Input::all(), $rule, $messages);
            if ($validator->fails()) {

                return redirect()->route('user.group.view')->withInput(Input::all())->withErrors($validator);

            }else{

                // Get uploaded CSV file
                $file = $request->file('csv_import');
                $group_name = $request->group_name;

                // Create list name
                $name = time().'_'.$file->getClientOriginalName();
                $path = public_path('/uploads/chat-bot/').$name;
                // Moves file to folder on server
                $request->csv_import->move(public_path('/uploads/chat-bot'), $name);
                $this->importCsvUpdate($path,$group_name,'update',$id);
                return redirect()->route('user.group.view')->with('success_message', 'New Group created successfully.');
            }
        } catch (\Exception $e) {
             return redirect()->route('user.group.view')->with('error_message', $e->getMessage());
        }
    }
    public function importCsvUpdate($csv_path = null, $group_name = null,$senario=null,$id =null)
    {
        $customerArr = $this->csvToArray($csv_path);
        $newInsertion = [];
        //create group
        if($id){
            $last_inserted_id = $id;
            for ($i = 0; $i < count($customerArr); $i++)
            {
                $new['contact_number'] = isset($customerArr[$i][0]) ? $customerArr[$i][0]:'';
                $new['contact_name'] = isset($customerArr[$i][1])? $customerArr[$i][1]:'';
                $new['contact_email'] = isset($customerArr[$i][2])? $customerArr[$i][2]:'';
                $new['group_id'] = $last_inserted_id;
                $newInsertion[] = $new;
            }
            GroupContact::insert($newInsertion);
        }
        return true;
    }
    public function importCsv($csv_path = null, $group_name = null)
    {
        $customerArr = $this->csvToArray($csv_path);
        $newInsertion = [];
        //create group
        $groupInsertion = new Group();
        $groupInsertion->group_name = $group_name;
        $groupInsertion->user_id = Auth::user()->id;
        $groupInsertion->reseller_id = Auth::user()->reseller_id;
        $groupInsertion->is_status = 1;
        $groupInsertion->save();

        if($groupInsertion){
            $last_inserted_id =  $groupInsertion->id;
            for ($i = 0; $i < count($customerArr); $i++)
            {
                $new['contact_number'] = isset($customerArr[$i][0]) ? $customerArr[$i][0]:'';
                $new['contact_name'] = isset($customerArr[$i][1])? $customerArr[$i][1]:'';
                $new['contact_email'] = isset($customerArr[$i][2])? $customerArr[$i][2]:'';
                $new['group_id'] = $last_inserted_id;
                $newInsertion[] = $new;
            }
            GroupContact::insert($newInsertion);
        }
        return true;
    }
    function csvToArray($filename = '', $delimiter = ',')
    {

        if (!file_exists($filename) || !is_readable($filename)){
            return false;
        }

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000,  ",")) !== false)
            {
                if (!$header)
                    $header = $row;
                else
                    $data[] = $row;
            }
            fclose($handle);
        }

        return $data;
    }
}
