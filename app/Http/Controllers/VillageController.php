<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Village;
use App\Models\GnDivisions;
use App\Models\UserRolePermissions;
use Auth;
Use Alert;
use DataTables;
use Validator;
use App\Traits\Permissions;

class VillageController extends Controller
{
    use Permissions;

    public function index(){

        return view('pages.villages.index');
    }

    public function list(){
        $villages=Village::get();
        return DataTables::of($villages)
        ->addIndexColumn()
        ->addColumn('action', function ($villages) {
            $edit = '<a href="/'.request()->segment(1).'/update/'.$villages->id.'" class="btn btn-icon btn-primary"><i class="far fa-edit"></i></a>';
            $view = '<a href="/'.request()->segment(1).'/view/'.$villages->id.'" class="btn btn-icon btn-info"><i class="fas fa-eye"></i></a>';
            $delete = '<a href="javascript:;" data-toggle="modal" onclick="deleteData('.$villages->id.')"
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
        ->addColumn('gn_division', function ($villages) {
          $gn_division =   GnDivisions::find($villages->gn_division);
          return $gn_division->gn_name;

        })
        ->rawColumns(['gn_division','action'])->make(true);
    }

    public function create(){
        $gn_division = GnDivisions::get();
        view()->share('gn_divisions', $gn_division);
        return view('pages.villages.form');
    }

    public function store(Request $request){
        try{
            $request->validate(
            //     [
            //     'village' => ['required','unique:villages']
            // ],
            [
                'village.required' => 'name is required!',
            ]);
            Village::create($request->all());
            return redirect()->route('villages-all-list')->with('success', 'Created Successfully!');

        } catch (Exception $e) {
            return redirect()->route('villages-create')->with('error', $e);
        }
    }

    public function show($id){
        $GNDivisions= Village::find($id);
        view()->share('element', $GNDivisions);
        $gn_division = GnDivisions::get();
        view()->share('gn_divisions', $gn_division);
        return view('pages.villages.form');
    }

    public function update(Request $request, $id){
        try {

            $request->validate([
                'village' => 'required', 'unique:villages'
            ],
            [
                'village.required' => 'name is required!',
            ]);

            Village::find($id)->update($request->all());
            return redirect()->route('villages-all-list')->with('success', 'Updated Successfully!');

        } catch (Exception $e) {
            return redirect()->route('villages-edit',$id)->with('error', 'Error!');
        }
    }

    public function getVillagesByGn($gn){
       
        $ids = explode(',',$gn);
        $x = count($ids);
// dd($ids[$x-1]);
        $ags = Village::where('gn_division',$ids[$x-1])->get();


        //$ags=Village::all();
        if($ags||sizeof($ags)<1){
            return $ags;
        }else{
            return ['message'=>'Villages not found..'];
        }
    }


}
