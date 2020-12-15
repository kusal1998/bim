<?php

namespace App\Http\Controllers;

use App\Models\UserRoles;
use App\Models\AgDivisions;
use App\Models\RegionalOffices;
use App\User;


use Illuminate\Http\Request;
use DataTables;
use App\Traits\Permissions;
use Illuminate\Support\Facades\Hash;
class UsersController extends Controller
{

    use Permissions;

    public function index()
    {
        return view('pages.users.index');
    }

    public function list()
    {

        $Users = User::get();
        return DataTables::of($Users)
        ->addIndexColumn()
        ->addColumn('action', function ($Users) {
            $edit = '<a href="/'.request()->segment(1).'/update/'.$Users->id.'" class="btn btn-icon btn-primary"><i class="far fa-edit"></i></a>';
            $view = '<a href="/'.request()->segment(1).'/view/'.$Users->id.'" class="btn btn-icon btn-info"><i class="fas fa-eye"></i></a>';
            $delete = '<a href="javascript:;" data-toggle="modal" onclick="deleteData('.$Users->id.')"
            data-target="#DeleteModal" class="btn btn-icon btn-danger"><i class="fas fa-trash"></i></a>';

            $actions='';
            if(($this->getAccessView(request()->segment(1))=="Yes")){
            $actions .= ' '.$view;
            }
            if(($this->getAccessUpdate(request()->segment(1))=="Yes")){
                $actions .= ' '.$edit;
            }
            if(($this->getAccessDelete(request()->segment(1))=="Yes")){
                $actions .= ' '.$delete;
            }
           return $actions;

        })
        /* ->addColumn('branch_id', function ($Users) {

          $name =   $this->getBranchName($Users->branch_id);

          return $name;

        }) */
        ->rawColumns(['action'])->make(true);
    }


    public function show($id)
    {
        $Users= User::find($id);
        view()->share('element', $Users);
        $UserRoles = UserRoles::where('is_active',1)->get();
        view()->share('UserRoles', $UserRoles);

        $AgDivisions = AgDivisions::where('is_active',1)->get();
        view()->share('AgDivisions', $AgDivisions);

        $RegionalOffice=RegionalOffices::where('is_active',1)->get();
        view()->share('RegionalOffice',$RegionalOffice);


        return view('pages.users.form');
    }

    public function create()
    {
        $UserRoles = UserRoles::where('is_active',1)->get();
        view()->share('UserRoles', $UserRoles);

        $AgDivisions = AgDivisions::where('is_active',1)->get();
        view()->share('AgDivisions', $AgDivisions);

        $RegionalOffice=RegionalOffices::where('is_active',1)->get();
        view()->share('RegionalOffice',$RegionalOffice);

        return view('pages.users.form');
    }

    public function store(Request $request)
    {


        try {

            $request->validate([
                'name' => 'required',
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'role_code' => 'required',
                'password' => ['required', 'string', 'min:8'],
            ],
            [
                'name.required' => 'User Name is required!',

            ]);

            $User = User::create([
                'name' => $request->name,
                'role_code' => $request->role_code,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'contact_no' => $request->contact_no,
                'is_active' => $request->is_active,
                'branch_id' => $request->branch_id,
                'password' => Hash::make($request->password),
            ]);

            return redirect()->route('users-all-list')->with('success', 'Created Successfully!', 201);

        } catch (Exception $e) {

            return redirect()->route('users-create')->with('error', $e);

        }

    }

    public function edit($id)
    {
        $Users= User::find($id);
        view()->share('element', $Users);
        $UserRoles = UserRoles::where('is_active',1)->get();
        view()->share('UserRoles', $UserRoles);

        $AgDivisions = AgDivisions::where('is_active',1)->get();
        view()->share('AgDivisions', $AgDivisions);

        $RegionalOffice=RegionalOffices::where('is_active',1)->get();
        view()->share('RegionalOffice',$RegionalOffice);
        return view('pages.users.form');
    }

    public function update($id, Request $request)
    {
        try {

            $request->validate([
                'name' => 'required',
                'email' => ['required', 'string', 'email', 'max:255'],
                'role_code' => 'required',
                'password' => ['required', 'string', 'min:8'],
            ],
            [
                'name.required' => 'User Name is required!',

            ]);


            $User=User::where('id',$id)->first();
            $User->name=$request->name;
            $User->role_code = $request->role_code;
            $User->last_name = $request->last_name;
            $User->email = $request->email;
            $User->contact_no = $request->contact_no;
            $User->branch_id = $request->branch_id;
            $User->password  = Hash::make($request->password);
            $User->is_active = (isset($request->is_active))?$request->is_active:$User->is_active;
            $User->save();

            return redirect()->route('users-all-list')->with('success', 'Updated Successfully!', 201);

        } catch (Exception $e) {

            return redirect()->route('users-edit',$id)->with('error', 'Error!');

        }
    }

    public function destroy($id)
    {
        $Users = User::find($id);
        $Users->delete();

        return redirect()->route('users-all-list')->with('success', 'Deleted Successfully!');
    }
}

