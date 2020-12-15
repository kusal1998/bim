<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AgDivisions;
use App\Models\Districts;
use App\Models\UserRolePermissions;
use Auth;
Use Alert;
use DataTables;
use Validator;
use App\Traits\Permissions;

class AGDivisionsController extends Controller
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

            return view('pages.agdivisions.index');
        }

    public function list()
    {

        $AGDivisions = AgDivisions::where('is_active',1)->get();
        return DataTables::of($AGDivisions)
        ->addIndexColumn()
        ->addColumn('action', function ($AGDivisions) {
            $edit = '<a href="/'.request()->segment(1).'/update/'.$AGDivisions->id.'" class="btn btn-icon btn-primary"><i class="fas fa-edit"></i></a>';
            $view = '<a href="/'.request()->segment(1).'/view/'.$AGDivisions->id.'" class="btn btn-icon btn-info"><i class="fas fa-eye"></i></a>';
            $delete = '<a href="javascript:;" data-toggle="modal" onclick="deleteData('.$AGDivisions->id.')"
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
            $Districts = Districts::get();
            view()->share('Districts', $Districts);
            return view('pages.agdivisions.form');
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
                    'ag_name' => 'required',
                //    'md_code' => 'required|alpha_dash',
                ],
                [
                    'ag_name.required' => 'Name is required!',
                 //   'md_code.required' => 'Module Code is required!!'
                ]);
                AgDivisions::create($request->all());
                return redirect()->route('ag-divisions-all-list')->with('success', 'Created Successfully!');

                } catch (Exception $e) {
                    return redirect()->route('ag-divisions-create')->with('error', $e);
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
            $AGDivisions= AgDivisions::find($id);
            view()->share('element', $AGDivisions);

            $Districts = Districts::get();
            view()->share('Districts', $Districts);

            return view('pages.agdivisions.form');
        }

        /**
         * Show the form for editing the specified resource.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function edit($id)
        {
            $AGDivisions= AgDivisions::find($id);
            view()->share('element', $AGDivisions);

            $Districts = Districts::get();
            view()->share('Districts', $Districts);

            return view('pages.agdivisions.form');
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
                    'ag_name' => 'required',
                //    'md_code' => 'required|alpha_dash',
                ],
                [
                    'ag_name.required' => 'Name is required!',
                 //   'md_code.required' => 'Module Code is required!!'
                ]);

                AgDivisions::find($id)->update($request->all());
                return redirect()->route('ag-divisions-all-list')->with('success', 'Updated Successfully!');

            } catch (Exception $e) {

                return redirect()->route('ag-divisions-edit',$id)->with('error', 'Error!');

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
            $AGDivisions = AgDivisions::find($id);
            $AGDivisions->delete();

            return redirect()->route('ag-divisions-all-list')->with('success', 'Deleted Successfully!');
        }

        public function getAGsByDistrict($district){
            $ags=AgDivisions::where('district_id',$district)->where('is_active',1)->get();
            if($ags||sizeof($ags)<1){
                return $ags;
            }else{
                return ['message'=>'AG Divisions not found..'];
            }
        }
}
