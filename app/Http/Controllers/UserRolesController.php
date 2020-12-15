<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserRoles;
use App\Models\Modules;
use App\Models\UserRolePermissions;
Use Alert;
use DB;
use DataTables;
use App\Traits\Permissions;
class UserRolesController extends Controller
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
            $UserRoles = UserRoles::get();


            view()->share('elements', $UserRoles);
            return view('pages.user_roles.index');
        }

        public function list()
        {
            //return response()->json(Branches::where('is_active',1)->get());
            $UserRoles = UserRoles::get();
            return DataTables::of($UserRoles)
            ->addIndexColumn()
            ->addColumn('status', function ($UserRoles) {
                if($UserRoles->is_active==1){
                    return 'Active';
                }else{
                    return 'In-Active';
                }

            })
            ->addColumn('action', function ($UserRoles) {
                $edit = '<a href="/'.request()->segment(1).'/update/'.$UserRoles->id.'" class="btn btn-icon btn-primary"><i class="fas fa-edit"></i></a>';
                $view = '<a href="/'.request()->segment(1).'/view/'.$UserRoles->id.'" class="btn btn-icon btn-info"><i class="fas fa-eye"></i></a>';
                $delete = '<a href="javascript:;" data-toggle="modal" onclick="deleteData('.$UserRoles->id.')"
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
            ->rawColumns(['action','status'])->make(true);
        }
        /**
         * Show the form for creating a new resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function create()
        {
            $Modules = Modules::where('md_code','<>','modules')->orderBy('order_menu')->orderBy('id','asc')->get();
            view()->share('elements', $Modules);
            return view('pages.user_roles.form');
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
                  //  'code' => 'required',
                ],
                [
                    'name.required' => 'User Role name is required!',
                  //  'code.required' => 'User Role code is required!!',

                ]);


                $code = substr($request->name, 0, 2);
                $UserRoles = UserRoles::create([
                    'name' => $request->name,
                    'code' => strtoupper($code).time(),
                    'is_active' => $request->is_active,


                ]);
                foreach ($request->element as $key=>$value)
                {
                    $module_code=0;
                    $is_enable=0;
                    $can_create=0;
                    $can_read=0;
                    $can_update=0;
                    $can_delete=0;
                   // $can_process=0;
                    $can_approve=0;
                    $can_reject=0;
                    $can_proof_read=0;
                    $can_close=0;
                    $can_certificate=0;
                    $can_gazzete=0;
                    $can_press=0;
                    $can_recheck=0;
                    $can_verify=0;
                    $can_publication_verify=0;
                    $can_asst_comm=0;
                    $can_bimsaviya_comm=0;
                    $can_comm_general=0;
                    $can_forward_to_proof=0;
                    $can_forward_to_translate=0;
                    $can_translate_proof=0;
                    $md_group=0;
                    foreach($value as $index=>$element){

                        if(!empty($value[0])){
                            $module_code=$value[0][0];
                        }
                        if(!empty($value[1])){
                            $is_enable=1;
                        }
                        if(!empty($value[2])){
                            $can_create=1;
                        }
                        if(!empty($value[3])){
                            $can_read=1;
                        }
                        if(!empty($value[4])){
                            $can_update=1;
                        }
                        if(!empty($value[5])){
                            $can_delete=1;
                        }
                        if(!empty($value[6])){
                            $md_group=$value[6][0];
                        }
                        if(!empty($value[7])){
                            $can_approve=1;
                        }
                        if(!empty($value[8])){
                            $can_reject=1;
                        }
                        if(!empty($value[9])){
                            $can_proof_read=1;
                        }
                        if(!empty($value[10])){
                            $can_close=1;
                        }
                        if(!empty($value[11])){
                            //$md_group=$value[11][0];
                        }

                        //md_group

                    }

                    $data[] = [
                        'role_code' =>$UserRoles->code,
                        'module_code' =>$module_code,
                        'md_group' =>$md_group,
                        'is_enable' => $is_enable,
                        'can_create' =>$can_create,
                        'can_read' => $can_read,
                        'can_update' =>$can_update,
                       // 'can_process' => $can_process,
                        'can_approve' =>$can_approve,
                        'can_reject' => $can_reject,
                        'can_proof_read' =>$can_proof_read,
                        'can_close' => $can_close
                    ];
                }
    foreach ($request->element as $key=>$value)
                {
                    $module_code=0;
                    $is_enable=0;
                    $can_create=0;
                    $can_read=0;
                    $can_update=0;
                    $can_delete=0;
                  //  $can_process=0;
                    $can_approve=0;
                    $can_reject=0;
                    $can_proof_read=0;
                    $can_close=0;
                    $can_certificate=0;
                    $can_gazzete=0;
                    $can_press=0;
                    $can_recheck=0;
                    $can_verify=0;

                    $can_asst_comm=0;
                    $can_bimsaviya_comm=0;
                    $can_comm_general=0;
                    $can_forward_to_proof=0;
                    $can_forward_to_translate=0;
                    $can_translate_proof=0;
                    $can_publication_verify=0;
                    $md_group=0;
                    foreach($value as $index=>$element){

                        if(!empty($value[0])){
                            $module_code=$value[0][0];
                        }
                        if(!empty($value[1])){
                            $is_enable=1;
                        }
                        if(!empty($value[2])){
                            $can_create=1;
                        }
                        if(!empty($value[3])){
                            $can_read=1;
                        }
                        if(!empty($value[4])){
                            $can_update=1;
                        }
                        if(!empty($value[5])){
                            $can_delete=1;
                        }
                        if(!empty($value[6])){
                            $md_group=$value[6][0];
                        }
                        if(!empty($value[7])){
                            $can_approve=1;
                        }
                        if(!empty($value[8])){
                            $can_reject=1;
                        }
                        if(!empty($value[9])){
                            $can_proof_read=1;
                        }
                        if(!empty($value[10])){
                            $can_close=1;
                        }
                        if(!empty($value[11])){
                            $can_certificate=1;
                        }
                        if(!empty($value[12])){
                            $can_gazzete=1;
                        }
                        if(!empty($value[13])){
                            $can_press=1;
                        }
                        if(!empty($value[14])){
                            $can_recheck=1;
                        }
                        if(!empty($value[15])){
                            $can_verify=1;
                        }
                        if(!empty($value[16])){
                            $can_asst_comm=1;
                        }

                        if(!empty($value[17])){
                            $can_bimsaviya_comm=1;
                        }

                        if(!empty($value[18])){
                            $can_comm_general=1;
                        }
                        if(!empty($value[19])){
                            $can_forward_to_proof=1;
                        }
                        if(!empty($value[20])){
                            $can_forward_to_translate=1;
                        }
                        if(!empty($value[21])){
                            $can_translate_proof=1;
                        }
                        if(!empty($value[22])){
                            $can_publication_verify=1;
                        }




                    }

                    $data[] = [
                        'role_code' =>$UserRoles->code,
                        'module_code' =>$module_code,
                        'md_group' =>$md_group,
                        'is_enable' => $is_enable,
                        'can_create' =>$can_create,
                        'can_read' => $can_read,
                        'can_update' =>$can_update,
                        'can_approve' =>$can_approve,
                        'can_reject' => $can_reject,
                        'can_proof_read' =>$can_proof_read,
                        'can_close' => $can_close,
                        'can_certificate' => $can_certificate,
                        'can_gazzete' =>$can_gazzete,
                        'can_press' =>$can_press,
                        'can_recheck' =>$can_recheck,
                        'can_verify' => $can_verify,
                        'can_asst_comm' => $can_asst_comm,
                        'can_bimsaviya_comm' => $can_bimsaviya_comm,
                        'can_comm_general' => $can_comm_general,
                        'can_forward_to_proof' => $can_forward_to_proof,
                        'can_forward_to_translate' => $can_forward_to_translate,
                        'can_translate_proof' =>  $can_translate_proof,
                        'can_publication_verify' => $can_publication_verify,
                    ];
                }
                DB::table('user_role_permissions')->insert($data);
                return redirect()->route('user-roles-all-list')->with('success', 'Created Successfully!');

                }
                catch (Exception $e)
                {
                    return redirect()->route('user-roles-create')->with('error', 'Error!');
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
            $UserRoles= UserRoles::find($id);
            view()->share('elementur', $UserRoles);

            $Modules = Modules::where('md_code','<>','modules')->orderBy('order_menu')->orderBy('id','asc')->get();
            view()->share('elements', $Modules);

            return view('pages.user_roles.form');
        }

        /**
         * Show the form for editing the specified resource.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function edit($id)
        {
            $UserRoles= UserRoles::find($id);
            view()->share('elementur', $UserRoles);

            $Modules = Modules::where('md_code','<>','modules')->orderBy('order_menu')->orderBy('id','asc')->get();
            view()->share('elements', $Modules);



            return view('pages.user_roles.form');
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
                    'name' => 'required|regex:/^[\pL\s\-]+$/u',
                   // 'code' => 'required',
                ],
                [
                    'name.required' => 'User Role name is required!',
                   // 'code.required' => 'User Role code is required!!',
                ]);
                $UserRoles=UserRoles::where('id',$id)->first();
                $UserRoles->name=$request->name;
                $UserRoles->is_active=$request->is_active;
                $UserRoles->save();

                DB::table('user_role_permissions')->where('role_code',$UserRoles->code)->where('module_code','<>','modules')->delete();
                foreach ($request->element as $key=>$value)
                {
                    $module_code=0;
                    $is_enable=0;
                    $can_create=0;
                    $can_read=0;
                    $can_update=0;
                    $can_delete=0;
                  //  $can_process=0;
                    $can_approve=0;
                    $can_reject=0;
                    $can_proof_read=0;
                    $can_close=0;
                    $can_certificate=0;
                    $can_gazzete=0;
                    $can_press=0;
                    $can_recheck=0;
                    $can_verify=0;

                    $can_asst_comm=0;
                    $can_bimsaviya_comm=0;
                    $can_comm_general=0;
                    $can_forward_to_proof=0;
                    $can_forward_to_translate=0;
                    $can_translate_proof=0;
                    $can_publication_verify=0;
                    $md_group=0;
                    foreach($value as $index=>$element){

                        if(!empty($value[0])){
                            $module_code=$value[0][0];
                        }
                        if(!empty($value[1])){
                            $is_enable=1;
                        }
                        if(!empty($value[2])){
                            $can_create=1;
                        }
                        if(!empty($value[3])){
                            $can_read=1;
                        }
                        if(!empty($value[4])){
                            $can_update=1;
                        }
                        if(!empty($value[5])){
                            $can_delete=1;
                        }
                        if(!empty($value[6])){
                            $md_group=$value[6][0];
                        }
                        if(!empty($value[7])){
                            $can_approve=1;
                        }
                        if(!empty($value[8])){
                            $can_reject=1;
                        }
                        if(!empty($value[9])){
                            $can_proof_read=1;
                        }
                        if(!empty($value[10])){
                            $can_close=1;
                        }
                        if(!empty($value[11])){
                            $can_certificate=1;
                        }
                        if(!empty($value[12])){
                            $can_gazzete=1;
                        }
                        if(!empty($value[13])){
                            $can_press=1;
                        }
                        if(!empty($value[14])){
                            $can_recheck=1;
                        }
                        if(!empty($value[15])){
                            $can_verify=1;
                        }
                        if(!empty($value[16])){
                            $can_asst_comm=1;
                        }

                        if(!empty($value[17])){
                            $can_bimsaviya_comm=1;
                        }

                        if(!empty($value[18])){
                            $can_comm_general=1;
                        }
                        if(!empty($value[19])){
                            $can_forward_to_proof=1;
                        }
                        if(!empty($value[20])){
                            $can_forward_to_translate=1;
                        }
                        if(!empty($value[21])){
                            $can_translate_proof=1;
                        }
                        if(!empty($value[22])){
                            $can_publication_verify=1;
                        }


                        //md_group

                    }

                    $data[] = [
                        'role_code' =>$UserRoles->code,
                        'module_code' =>$module_code,
                        'md_group' =>$md_group,
                        'is_enable' => $is_enable,
                        'can_create' =>$can_create,
                        'can_read' => $can_read,
                        'can_update' =>$can_update,
                        'can_approve' =>$can_approve,
                        'can_reject' => $can_reject,
                        'can_proof_read' =>$can_proof_read,
                        'can_close' => $can_close,
                        'can_certificate' => $can_certificate,
                        'can_gazzete' =>$can_gazzete,
                        'can_press' =>$can_press,
                        'can_recheck' =>$can_recheck,
                        'can_verify' => $can_verify,
                        'can_asst_comm' => $can_asst_comm,
                        'can_bimsaviya_comm' => $can_bimsaviya_comm,
                        'can_comm_general' => $can_comm_general,
                        'can_forward_to_proof' => $can_forward_to_proof,
                        'can_forward_to_translate' => $can_forward_to_translate,
                        'can_translate_proof' =>  $can_translate_proof,
                        'can_publication_verify' => $can_publication_verify,
                    ];
                }
                //dd($data);
                DB::table('user_role_permissions')->insert($data);
                return redirect()->route('user-roles-all-list')->with('success', 'Updated Successfully!');
            } catch (Exception $e) {
                return redirect()->route('user-roles-edit',$id)->with('error', 'Error!');
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
            $UserRoles = UserRoles::find($id);
            if($UserRoles->code=='CL1560481255'){
                return redirect()->route('user-roles-all-list')->with('error', 'Restricted! You can not delete that user role!');
            }
            elseif($UserRoles->code=='AD001'){

                return redirect()->route('user-roles-all-list')->with('error', 'Restricted! You can not delete that user role!');
            }
            else{
                DB::table('user_role_permissions')->where('role_code',$UserRoles->code)->delete();
                $UserRoles->delete();
                return redirect()->route('user-roles-all-list')->with('success', 'Deleted Successfully!');
            }

        }
        public function getPermissions($role_code){
            $permissions=UserRolePermissions::where('role_code',$role_code)->whereIn('module_code',['12th-sentence','14th-sentence','55th-sentence','amendments'])->where('is_enable',1)->get();
            $result='false';
            foreach($permissions as $permission){
                if($permission->can_create==1||$permission->can_verify==1||$permission->can_approve==1||$permission->can_recheck==1){
                    $result='true';
                    break;
                }
            }
            return $result;
        }
}
