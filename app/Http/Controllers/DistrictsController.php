<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Districts;
use App\Models\Provinces;
use App\Models\UserRolePermissions;
use Auth;
Use Alert;
use DataTables;
use Validator;
use App\Traits\Permissions;

class DistrictsController extends Controller
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
            return view('pages.districts.index');
        }

    public function list()
    {

        $Districts = Districts::where('is_active',1)->get();
        return DataTables::of($Districts)
        ->addIndexColumn()
        ->addColumn('action', function ($Districts) {
            $edit = '<a href="/'.request()->segment(1).'/update/'.$Districts->id.'" class="btn btn-icon btn-primary"><i class="fas fa-edit"></i></a>';
            $view = '<a href="/'.request()->segment(1).'/view/'.$Districts->id.'" class="btn btn-icon btn-info"><i class="fas fa-eye"></i></a>';
            $delete = '<a href="javascript:;" data-toggle="modal" onclick="deleteData('.$Districts->id.')"
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

            $Provinces = Provinces::where('is_active',1)->get();
            view()->share('Provinces', $Provinces);
            return view('pages.districts.form');
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
                    'districts_name' => 'required',
                //    'md_code' => 'required|alpha_dash',
                ],
                [
                    'districts_code.required' => 'Code is required!',
                 //   'md_code.required' => 'Module Code is required!!'
                ]);


                Districts::create($request->all());
                return redirect()->route('districts-all-list')->with('success', 'Created Successfully!');

                } catch (Exception $e) {
                    return redirect()->route('districts-create')->with('error', $e);
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
            $Districts= Districts::find($id);
            view()->share('element', $Districts);
            $Provinces = Provinces::where('is_active',1)->get();
            view()->share('Provinces', $Provinces);
            return view('pages.districts.form');
        }

        /**
         * Show the form for editing the specified resource.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function edit($id)
        {
            $Districts= Districts::find($id);
            view()->share('element', $Districts);

            $Provinces = Provinces::where('is_active',1)->get();
            view()->share('Provinces', $Provinces);

            return view('pages.districts.form');
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
                    'districts_name' => 'required',
                //    'md_code' => 'required|alpha_dash',
                ],
                [
                    'districts_code.required' => 'Code is required!',
                 //   'md_code.required' => 'Module Code is required!!'
                ]);

                Districts::find($id)->update($request->all());
                return redirect()->route('districts-all-list')->with('success', 'Updated Successfully!');

            } catch (Exception $e) {

                return redirect()->route('districts-edit',$id)->with('error', 'Error!');

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
            $Districts = Districts::find($id);
            $Districts->delete();

            return redirect()->route('districts-all-list')->with('success', 'Deleted Successfully!');
        }

        public function getDistrictsByProvince($province){
            $districts=Districts::where('province_id',$province)->where('is_active',1)->get();
            if($districts||sizeof($districts)<1){
                return $districts;
            }else{
                return ['message'=>'Districts not found..'];
            }
        }
}
