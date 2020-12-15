<?php

namespace App\Http\Controllers;

use App\Models\Branches;
use Illuminate\Http\Request;
use DataTables;
use App\Traits\Permissions;
class AuditController extends Controller
{
 
    use Permissions;
    
    public function index()
    {
        return view('pages.audits.index');
    }

    public function list()
    {
        $audits = \OwenIt\Auditing\Models\Audit::orderBy('created_at', 'desc')->get();
        return DataTables::of($audits)
        ->addIndexColumn()
        ->addColumn('action', function ($audits) {
          
            $view = '<a href="/'.request()->segment(1).'/view/'.$audits->id.'" class="btn btn-icon btn-info"><i class="fas fa-eye"></i></a>';
            $actions='';
            if(($this->getAccessView(request()->segment(1))=="Yes")){
            $actions .= ' '.$view; 
            }
           return $actions;
            
        })
        ->addColumn('user_id', function ($audits) {
           
            $name =   $this->getUserName($audits->user_id);
             
            return $name;
              
          })
        /*   ->addColumn('model', function ($audits) {
            $string = $audits->auditable_type; 
            $str_arr = explode ("/", $string); 
            return $str_arr[2];
          }) */
        ->rawColumns(['action','user_id'])->make(true);
    }
   
    public function show($id)
    {
        $audits = \OwenIt\Auditing\Models\Audit::where('id',$id)->first();
        view()->share('audits', $audits);
        return view('pages.audits.form');
    }

    public function destroy($id)
    {
        $Branches = Branches::find($id);
        $Branches->delete();

        return redirect()->route('branches-all-list')->with('success', 'Deleted Successfully!');
    }
}