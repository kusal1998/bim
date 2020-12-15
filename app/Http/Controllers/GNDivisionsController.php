<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AgDivisions;
use App\Models\GnDivisions;
use App\Models\Districts;
use App\Models\UserRolePermissions;
use Auth;
Use Alert;
use DataTables;
use Validator;
use App\Traits\Permissions;

class GNDivisionsController extends Controller
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

            return view('pages.gndivisions.index');
        }

    public function list()
    {

        $GNDivisions = GnDivisions::where('is_active',1)->get();
        return DataTables::of($GNDivisions)
        ->addIndexColumn()
        ->addColumn('action', function ($GNDivisions) {
            $edit = '<a href="/'.request()->segment(1).'/update/'.$GNDivisions->id.'" class="btn btn-icon btn-primary"><i class="fas fa-edit"></i></a>';
            $view = '<a href="/'.request()->segment(1).'/view/'.$GNDivisions->id.'" class="btn btn-icon btn-info"><i class="fas fa-eye"></i></a>';
            $delete = '<a href="javascript:;" data-toggle="modal" onclick="deleteData('.$GNDivisions->id.')"
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
            $AgDivisions = AgDivisions::get();
            view()->share('AgDivisions', $AgDivisions);
            return view('pages.gndivisions.form');
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
                    'gn_name' => 'required',
                //    'md_code' => 'required|alpha_dash',
                ],
                [
                    'gn_name.required' => 'name is required!',
                 //   'md_code.required' => 'Module Code is required!!'
                ]);


                GnDivisions::create($request->all());
                return redirect()->route('gn-divisions-all-list')->with('success', 'Created Successfully!');

                } catch (Exception $e) {
                    return redirect()->route('gn-divisions-create')->with('error', $e);
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
            $GNDivisions= GnDivisions::find($id);
            view()->share('element', $GNDivisions);
            $AgDivisions = AgDivisions::get();
            view()->share('AgDivisions', $AgDivisions);
            return view('pages.gndivisions.form');
        }

        /**
         * Show the form for editing the specified resource.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function edit($id)
        {
            $GNDivisions= GnDivisions::find($id);
            view()->share('element', $GNDivisions);
            $AgDivisions = AgDivisions::get();
            view()->share('AgDivisions', $AgDivisions);
            return view('pages.gndivisions.form');
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
                    'gn_name' => 'required',
                //    'md_code' => 'required|alpha_dash',
                ],
                [
                    'gn_name.required' => 'name is required!',
                 //   'md_code.required' => 'Module Code is required!!'
                ]);

                GnDivisions::find($id)->update($request->all());
                return redirect()->route('gn-divisions-all-list')->with('success', 'Updated Successfully!');

            } catch (Exception $e) {

                return redirect()->route('gn-divisions-edit',$id)->with('error', 'Error!');

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
            $GNDivisions = GnDivisions::find($id);
            $GNDivisions->delete();

            return redirect()->route('gn-divisions-all-list')->with('success', 'Deleted Successfully!');
        }

        public function getGnsByAG($ag){
            $ags=GnDivisions::where('ag_id',$ag)->where('is_active',1)->get();
            if($ags||sizeof($ags)<1){
                return $ags;
            }else{
                return ['message'=>'GN Divisions not found..'];
            }
        }
}
