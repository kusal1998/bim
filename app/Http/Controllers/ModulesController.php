<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Modules;
use App\Models\UserRolePermissions;
use Auth;
Use Alert;
use DataTables;
use Validator;
use App\Traits\Permissions;

class ModulesController extends Controller
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
            $Modules = Modules::get();
            view()->share('elements', $Modules);
            return view('pages.modules.index');
        }

    public function list()
    {
        
        $Modules = Modules::where('active',1)->orderBy('md_group')->get();
        return DataTables::of($Modules)
        ->addIndexColumn()
        ->addColumn('md_group', function ($Modules) {
            $md_group = '';
            if($Modules->md_group==1){
            $md_group .= 'Master Files';
            }
            if($Modules->md_group==2){
                $md_group .= 'Main Configurations';
            }
            if($Modules->md_group==3){
                $md_group .= '12th Sentence';
                }
            if($Modules->md_group==5){
                $md_group .= '14th Sentence';
                }
             if($Modules->md_group==6){
                $md_group .= '55th Sentence';
                }
               if($Modules->md_group==7){
                $md_group .= 'Amendements';
                }
            if($Modules->md_group==4){
                $md_group .= 'Reports';
                }
         
          return $md_group;
            
        })
        ->addColumn('action', function ($Modules) {
            $edit = '<a href="/modules/update/'.$Modules->id.'" class="btn btn-icon btn-primary"><i class="far fa-edit"></i></a>';
            $view = '<a href="/modules/view/'.$Modules->id.'" class="btn btn-icon btn-info"><i class="fas fa-eye"></i></a>';
            $delete = '<a href="javascript:;" data-toggle="modal" onclick="deleteData('.$Modules->id.')" 
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
        ->rawColumns(['action','md_group'])->make(true);
    }

        /**
         * Show the form for creating a new resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function create()
        {
            $order_menu = Modules::max('order_menu');
            view()->share('order_menu', $order_menu);

            return view('pages.modules.form');
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
                    'md_name' => 'required',
                //    'md_code' => 'required|alpha_dash',
                ],
                [
                    'md_name.required' => 'Module name is required!',
                 //   'md_code.required' => 'Module Code is required!!'
                ]);


                Modules::create($request->all());
                return redirect()->route('modules-all-list')->with('success', 'Created Successfully!');
                    
                } catch (Exception $e) {
                    return redirect()->route('modules-create')->with('error', $e);
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
            $Modules= Modules::find($id);
            view()->share('element', $Modules);
            return view('pages.modules.form');
        }

        /**
         * Show the form for editing the specified resource.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function edit($id)
        {
            $Modules= Modules::find($id);
            view()->share('element', $Modules);

            $order_menu = Modules::max('order_menu');
            view()->share('order_menu', $order_menu);
            
            return view('pages.modules.form');
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
                   // 'md_name' => 'required|alpha',
                   // 'md_code' => 'required|alpha_dash',
                ],
                [
                 //   'md_name.required' => 'Module name is required!',
                 //   'md_code.required' => 'Module Code is required!!'
                ]);

                Modules::find($id)->update($request->all());
                return redirect()->route('modules-all-list')->with('success', 'Updated Successfully!');
    
            } catch (Exception $e) {
    
                return redirect()->route('modules-edit',$id)->with('error', 'Error!');
    
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
            $Modules = Modules::find($id);
            $Modules->delete();

            return redirect()->route('modules-all-list')->with('success', 'Deleted Successfully!');
        }
}
