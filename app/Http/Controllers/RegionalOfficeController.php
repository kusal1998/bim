<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegionalOffices;
use App\Models\UserRolePermissions;
use Auth;
Use Alert;
use DataTables;
use Validator;
use App\Traits\Permissions;

class RegionalOfficeController extends Controller
{
        use Permissions; 
        /**
         * Create a new controller instance.
         *
         * @return void
         */
        public function __construct()
        {
            $this->middleware('auth');
        }

        /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function index()
        {
            $RegionalOffices = RegionalOffices::get();
            view()->share('elements', $RegionalOffices);
            return view('pages.regional-office.index');
        }

    public function list()
    {
        
        $RegionalOffices = RegionalOffices::where('is_active',1)->get();
        return DataTables::of($RegionalOffices)
        ->addIndexColumn()
        ->addColumn('action', function ($RegionalOffices) {
            $edit = '<a href="/'.request()->segment(1).'/update/'.$RegionalOffices->id.'" class="btn btn-icon btn-primary"><i class="fas fa-edit"></i></a>';
            $view = '<a href="/'.request()->segment(1).'/view/'.$RegionalOffices->id.'" class="btn btn-icon btn-info"><i class="fas fa-eye"></i></a>';
            $delete = '<a href="javascript:;" data-toggle="modal" onclick="deleteData('.$RegionalOffices->id.')" 
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
        ->rawColumns(['action'])->make(true);
    }

        /**
         * Show the form for creating a new resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function create()
        {
            return view('pages.regional-office.form');
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function store(Request $request)
        {
            
               try{

                
                $request->validate([
                    'name' => 'required',
                //    'md_code' => 'required|alpha_dash',
                ],
                [
                    'name.required' => 'Name is required!',
                 //   'md_code.required' => 'Module Code is required!!'
                ]);


                RegionalOffices::create($request->all());
                return redirect()->route('regional-office-all-list')->with('success', 'Created Successfully!');
                    
                } catch (Exception $e) {
                
                    return redirect()->route('regional-office-create')->with('error', $e);
                }

               
        }

        /**
         * Display the specified resource.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function show($id)
        {
            $RegionalOffices= RegionalOffices::find($id);
            view()->share('element', $RegionalOffices);
            return view('pages.regional-office.form');
        }

        /**
         * Show the form for editing the specified resource.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function edit($id)
        {
            $RegionalOffices= RegionalOffices::find($id);
            view()->share('element', $RegionalOffices);
            
            return view('pages.regional-office.form');
        }

        /**
         * Update the specified resource in storage.
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function update(Request $request, $id)
        {
            try {

                $request->validate([
                   'name' => 'required',
                   // 'md_code' => 'required|alpha_dash',
                ],
                [
                'name.required' => 'Name is required!',
                 //   'md_code.required' => 'Module Code is required!!'
                ]);

                RegionalOffices::find($id)->update($request->all());
                return redirect()->route('regional-office-all-list')->with('success', 'Updated Successfully!');
    
            } catch (Exception $e) {
    
                return redirect()->route('regional-office-edit',$id)->with('error', 'Error!');
    
            }
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function destroy($id)
        {
            $RegionalOffices = RegionalOffices::find($id);
            $RegionalOffices->delete();

            return redirect()->route('regional-office-all-list')->with('success', 'Deleted Successfully!');
        }
}
