<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Provinces;
use App\Models\UserRolePermissions;
use Auth;
Use Alert;
use DataTables;
use Validator;
use App\Traits\Permissions;

class ProvincesController extends Controller
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
            $Provinces = Provinces::get();
            view()->share('elements', $Provinces);
            return view('pages.provinces.index');
        }

    public function list()
    {
        
        $Provinces = Provinces::where('is_active',1)->get();
        return DataTables::of($Provinces)
        ->addIndexColumn()
        ->addColumn('action', function ($Provinces) {
            $edit = '<a href="/provinces/update/'.$Provinces->id.'" class="btn btn-icon btn-primary"><i class="far fa-edit"></i></a>';
            $view = '<a href="/provinces/view/'.$Provinces->id.'" class="btn btn-icon btn-info"><i class="fas fa-eye"></i></a>';
            $delete = '<a href="javascript:;" data-toggle="modal" onclick="deleteData('.$Provinces->id.')" 
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
            return view('pages.provinces.form');
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

                //
                $request->validate([
                    'province_name' => 'required',
                    'provincial_code' => 'required',
                ],
                [
                    'province_name.required' => 'name is required!',
                    'provincial_code.required' => 'Code is required!!'
                ]);


                Provinces::create($request->all());
                return redirect()->route('provinces-all-list')->with('success', 'Created Successfully!');
                    
                } catch (Exception $e) {
                    return redirect()->route('provinces-create')->with('error', $e);
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
            $Provinces= Provinces::find($id);
            view()->share('element', $Provinces);
            return view('pages.provinces.form');
        }

        /**
         * Show the form for editing the specified resource.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function edit($id)
        {
            $Provinces= Provinces::find($id);
            view()->share('element', $Provinces);
            
            return view('pages.provinces.form');
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
                    'province_name' => 'required',
                    'provincial_code' => 'required',
                ],
                [
                    'province_name.required' => 'name is required!',
                    'provincial_code.required' => 'Code is required!!'
                ]);

                Provinces::find($id)->update($request->all());
                return redirect()->route('provinces-all-list')->with('success', 'Updated Successfully!');
    
            } catch (Exception $e) {
    
                return redirect()->route('provinces-edit',$id)->with('error', 'Error!');
    
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
            $Provinces = Provinces::find($id);
            $Provinces->delete();

            return redirect()->route('provinces-all-list')->with('success', 'Deleted Successfully!');
        }
}
