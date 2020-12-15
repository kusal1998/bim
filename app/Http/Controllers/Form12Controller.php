<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Models\Modules;
use App\User;
use App\Models\Provinces;
use App\Models\Districts;
use App\Models\AgDivisions;
use App\Models\GnDivisions;
use App\Models\Form12;
use App\Models\RegionalOffices;
use App\Models\UserRolePermissions;
use App\Models\ProofRead;
use App\Models\Village;
use Auth;
Use Alert;
use App\Models\Recheck;
use DataTables;
use Validator;
use Log;
use Exception;
use DB;
use Carbon\Carbon;
use App\Traits\Permissions;
use ZipArchive;

class Form12Controller extends Controller
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
        public function new()
        {
            return view('pages.form12.new_requests');
        }
        public function approved()
        {
            return view('pages.form12.approved');
        }
        public function pending()
        {
            return view('pages.form12.pending');
        }
        public function rejected()
        {
            return view('pages.form12.rejected');
        }
        public function reference()
        {
            return view('pages.form12.referenceNumberIndex');
        }
        public function recheck()
        {
            return view('pages.form12.recheck');
        }
        public function gazetted()
        {
            return view('pages.form12.gazetted');
        }

    public function pendinglist()
    {
        $pending=collect([]);
            $create=collect([]);$reg_verify=collect([]);$reg_approve=collect([]);$pub_verify=collect([]);$asst_comm=collect([]);$bim_comm=collect([]);
            $comm_gen=collect([]);$computer=collect([]);$proof_sinhala=collect([]);$computer_sinhala=collect([]);$proof_translate=collect([]);$computer_translate=collect([]);
            $pub_without=collect([]);$press_without=collect([]);
        if($this->getAccessCreate(request()->segment(1))=='Yes'){
            $create=Form12::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('current_stage','Regional officer')->where('rejected',0)->get();
        }
        if($this->getAccessRegVerify(request()->segment(1))=='Yes'){
            $reg_verify=Form12::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('current_stage','Regional commissioner')->where('rejected',0)->get();
        }
        if($this->getAccessRegApprove(request()->segment(1))=='Yes'){
            $reg_approve=Form12::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('current_stage','Publication verify')->where('rejected',0)->get();
        }
        if($this->getAccessPubVerify(request()->segment(1))=='Yes'){
            $pub_verify=Form12::where('current_stage','Assistant commisioner')->where('rejected',0)->get();
        }
        if($this->getAccessAsstComm(request()->segment(1))=='Yes'){
            $asst_comm=Form12::where('current_stage','Bimsaviya commisioner')->where('rejected',0)->get();
        }
        if($this->getAccessBimsaviyaComm(request()->segment(1))=='Yes'){
            $bim_comm=Form12::where('current_stage','Commissioner general')->where('rejected',0)->get();
        }
        if($this->getAccessCommGen(request()->segment(1))=='Yes'){
            $comm_gen=Form12::where('current_stage','Computer branch')->where('rejected',0)->get();
        }
        if($this->getAccessForwardProof(request()->segment(1))=='Yes'){
            $computer=Form12::where('current_stage','Proof read(Sinhala)')->where('computer_branch_officer',Auth::user()->id)->where('rejected',0)->get();
        }
        if($this->getAccessProof(request()->segment(1))=='Yes'){
            $proof_sinhala=Form12::where('current_stage','Proof read(Sinhala)-Computer')->where('rejected',0)->get();
        }
        if($this->getAccessForwardTransProof(request()->segment(1))=='Yes'){
            $computer_sinhala=Form12::where('current_stage','Proof read(Translates)')->where('computer_branch_officer',Auth::user()->id)->where('rejected',0)->get();
        }
        if($this->getAccessTransProof(request()->segment(1))=='Yes'){
            $proof_translate=Form12::where('current_stage','Proof read(Translation)-Computer')->where('rejected',0)->get();
        }
        if($this->getAccessForwardPublication(request()->segment(1))=='Yes'){
            $computer_translate=Form12::where('current_stage','Proof read complete')->orWhere('current_stage','Publication without G')
                ->orWhere('current_stage','Publication with G')->where('computer_branch_officer',Auth::user()->id)->where('rejected',0)->get();
        }
        if($this->getAccessForwardPress(request()->segment(1))=='Yes'){
            $pub_without=Form12::where('current_stage','Gov Press without G')->orWhere('current_stage','Gov press with G')->where('rejected',0)->get();
        }
        if($this->getAccessGazette(request()->segment(1))=='Yes'){
            $press_without=Form12::where('current_stage','Gazette with G')->where('rejected',0)->get();
        }
        $pending=$reg_verify->merge($reg_approve);
        $pending=$pending->merge($create);
        $pending=$pending->merge($pub_verify);
        $pending=$pending->merge($asst_comm);
        $pending=$pending->merge($bim_comm);
        $pending=$pending->merge($comm_gen);
        $pending=$pending->merge($computer);
        $pending=$pending->merge($proof_sinhala);
        $pending=$pending->merge($computer_sinhala);
        $pending=$pending->merge($proof_translate);
        $pending=$pending->merge($computer_translate);
        $pending=$pending->merge($pub_without);
        $pending=$pending->merge($press_without);
        $pending=$pending->unique('id');
        return DataTables::of($pending)
                ->addIndexColumn()
                ->addColumn('action', function ($pending) {
                    // $edit = '<a href="/'.request()->segment(1).'/update/'.$pending->id.'" class="btn btn-icon btn-primary"><i class="far fa-edit"></i></a>';
                    $view = '<a href="/'.request()->segment(1).'/view/'.$pending->id.'" class="btn btn-icon btn-info"><i class="fas fa-eye"></i></a>';
                    $delete = '<a href="javascript:;" data-toggle="modal" onclick="deleteData('.$pending->id.')"
                    data-target="#DeleteModal" class="btn btn-icon btn-danger"><i class="fas fa-trash"></i></a>';
                    $actions='';
                    if(($this->getAccessView(request()->segment(1))=="Yes")){
                        $actions .= ' '.$view;
                    }
                    // if(($this->getAccessUpdate(request()->segment(1))=="Yes")){
                    //     $actions .= ' '.$edit;
                    // }
                    if(($this->getAccessDelete(request()->segment(1))=="Yes")){
                        $actions .= ' '.$delete;
                    }
                    return $actions;
                })->rawColumns(['action'])->make(true);
    }
    public function newlist(){
        $pending=collect([]);
            $create=collect([]);$reg_verify=collect([]);$reg_approve=collect([]);$pub_verify=collect([]);$asst_comm=collect([]);$bim_comm=collect([]);
            $comm_gen=collect([]);$computer=collect([]);$proof_sinhala=collect([]);$computer_sinhala=collect([]);$proof_translate=collect([]);$computer_translate=collect([]);
            $pub_without=collect([]);$press_without=collect([]);
        if($this->getAccessRegVerify(request()->segment(1))=='Yes'){
            $reg_verify=Form12::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('current_stage','Regional officer')->where('rejected',0)->get();
        }
        if($this->getAccessRegApprove(request()->segment(1))=='Yes'){
            $reg_approve=Form12::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('current_stage','Regional commissioner')->where('rejected',0)->get();
        }
        if($this->getAccessPubVerify(request()->segment(1))=='Yes'){
            $pub_verify=Form12::where('current_stage','Publication verify')->where('ref_no2',null)->where('rejected',0)->get();
        }
        if($this->getAccessAsstComm(request()->segment(1))=='Yes'){
            $asst_comm=Form12::where('current_stage','Assistant commisioner')->where('rejected',0)->get();
        }
        if($this->getAccessBimsaviyaComm(request()->segment(1))=='Yes'){
            $bim_comm=Form12::where('current_stage','Bimsaviya commisioner')->where('rejected',0)->get();
        }
        if($this->getAccessCommGen(request()->segment(1))=='Yes'){
            $comm_gen=Form12::where('current_stage','Commissioner general')->where('rejected',0)->get();
        }
        if($this->getAccessForwardProof(request()->segment(1))=='Yes'){
            $computer=Form12::where('computer_branch_officer',Auth::user()->id)->where('rejected',0)->where(function($q){
                $q->where('current_stage','Computer branch')->orWhere('current_stage','Proof read(Sinhala)-Computer');
            })->get();
        }
        if($this->getAccessProof(request()->segment(1))=='Yes'){
            $proof_sinhala=Form12::where('current_stage','Proof read(Sinhala)')->where('rejected',0)->get();
        }
        if($this->getAccessForwardTransProof(request()->segment(1))=='Yes'){
            $computer_sinhala=Form12::where('computer_branch_officer',Auth::user()->id)->where('current_stage','Proof read(Translation)-Computer')->where('rejected',0)->get();
        }
        if($this->getAccessTransProof(request()->segment(1))=='Yes'){
            $proof_translate=Form12::where('current_stage','Proof read(Translates)')->where('rejected',0)->get();
        }
        if($this->getAccessForwardPublication(request()->segment(1))=='Yes'){
            $computer_translate=Form12::where('computer_branch_officer',Auth::user()->id)->where('current_stage','Proof read complete')->orWhere('current_stage','Gazette with G')->where('rejected',0)->get();
        }
        if($this->getAccessForwardPress(request()->segment(1))=='Yes'){
            $pub_without=Form12::where('current_stage','Publication without G')->orWhere('current_stage','Publication with G')->orWhere('current_stage','Gov press with G')->where('rejected',0)->get();
        }
        if($this->getAccessGazette(request()->segment(1))=='Yes'){
            $press_without=Form12::where('current_stage','Gov Press without G')->where('rejected',0)->get();
        }
        $pending=$reg_verify->merge($reg_approve);
        $pending=$pending->merge($pub_verify);
        $pending=$pending->merge($asst_comm);
        $pending=$pending->merge($bim_comm);
        $pending=$pending->merge($comm_gen);
        $pending=$pending->merge($computer);
        $pending=$pending->merge($proof_sinhala);
        $pending=$pending->merge($computer_sinhala);
        $pending=$pending->merge($proof_translate);
        $pending=$pending->merge($computer_translate);
        $pending=$pending->merge($pub_without);
        $pending=$pending->merge($press_without);
        $pending=$pending->unique('id');

        return DataTables::of($pending)
                ->addIndexColumn()
                ->addColumn('action', function ($pending) {
                    $edit = '<a href="/'.request()->segment(1).'/update/'.$pending->id.'" class="btn btn-icon btn-primary"><i class="far fa-edit"></i></a>';
                    $view = '<a href="/'.request()->segment(1).'/view/'.$pending->id.'" class="btn btn-icon btn-info"><i class="fas fa-eye"></i></a>';
                    $delete = '<a href="javascript:;" data-toggle="modal" onclick="deleteData('.$pending->id.')"
                    data-target="#DeleteModal" class="btn btn-icon btn-danger"><i class="fas fa-trash"></i></a>';
                    $new_file = '<a href="javascript:;" data-toggle="modal" onclick="setID('.$pending->id.')"
                    data-target="#NewFileModal" class="btn btn-icon btn-success"><i class="fas fa-folder-open"></i></a>';
                    $existing_file = '<a href="javascript:;" data-toggle="modal" onclick="setID('.$pending->id.')"
                    data-target="#ExistingFileModal" class="btn btn-icon btn-warning"><i class="fas fa-folder"></i></a>';
                    $actions='';
                    if(($this->getAccessView(request()->segment(1))=="Yes")){
                        $actions .= ' '.$view;
                    }
                    if(($this->getAccessUpdate(request()->segment(1))=="Yes")){
                        $actions .= ' '.$edit;
                        if($pending->current_stage=='Publication verify'){
                            $actions .= ' '.$new_file;
                            $actions .= ' '.$existing_file;
                        }
                    }
                    if(($this->getAccessDelete(request()->segment(1))=="Yes")){
                        $actions .= ' '.$delete;
                    }
                    return $actions;
                })->rawColumns(['action'])->make(true);
    }


    public function currentlist(){
        $current=[];
        $text=[];
        if($this->getAccessCreate(request()->segment(1))=='Yes' || $this->getAccessRegVerify(request()->segment(1))=='Yes' || $this->getAccessRegApprove(request()->segment(1))=='Yes'){
             $current=DB::table('form_12')
                    //->leftjoin('form_12_file','form_12.ref_no2','=','form_12_file.id')
                    ->whereIn('form_12.prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('form_12.current_stage','<>','Online Publish')->where('form_12.rejected',0)->where('form_12.recheck',0)->get();
        }else{
            $current=DB::table('form_12')
          //  ->leftjoin('form_12_file','form_12.ref_no2','=','form_12_file.id')
            -> where('form_12.rejected',0)->where('form_12.recheck',0)->where('form_12.current_stage','!=','Online Publish')->Where('form_12.current_stage','!=','Regional commissioner')->Where('form_12.current_stage','!=','Regional officer')->Where('form_12.current_stage','!=','Regional data entry')->get();
        }

        return DataTables::of($current)
                ->addIndexColumn()
            //     ->addColumn('ag_division', function ($newdata) {
            //         $AGName  =  $this->getAGDName($newdata->ag_division);
            //         return $AGName;

            //       })
            //   ->addColumn('gn_division', function ($newdata) {
            //         $GNName  =  $this->getGNDName($newdata->gn_division);
            //         return $GNName;

            //       })
                ->addColumn('action', function ($current) {
                    $edit = '<a href="/'.request()->segment(1).'/update/'.$current->id.'" class="btn btn-icon btn-primary"><i class="far fa-edit"></i></a>';
                    $view = '<a href="/'.request()->segment(1).'/view/'.$current->id.'" class="btn btn-icon btn-info"><i class="fas fa-eye"></i></a>';
                    $delete = '<a href="javascript:;" data-toggle="modal" onclick="deleteData('.$current->id.')"
                    data-target="#DeleteModal" class="btn btn-icon btn-danger"><i class="fas fa-trash"></i></a>';
                    $actions='';
                    if(($this->getAccessView(request()->segment(1))=="Yes")){
                        $actions .= ' '.$view;
                    }
                    if(($this->getAccessCreate(request()->segment(1))=="Yes") && $current->current_stage=='Regional data entry'){
                        $actions .= ' '.$edit;
                    }
                    if(($this->getAccessDelete(request()->segment(1))=="Yes")){
                        $actions .= ' '.$delete;
                    }
                    return $actions;
                })->rawColumns(['action'])->make(true);
    }

    public function getrecheck()
    {
        $rejected=[];
        if($this->getAccessCreate(request()->segment(1))=='Yes' || $this->getAccessRegVerify(request()->segment(1))=='Yes' || $this->getAccessRegApprove(request()->segment(1))=='Yes'){
            $rejected=Form12::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('rejected',0)->where('recheck',1)->get();
        }
        else{
            $rejected=Form12::where('rejected',0)->where('recheck',1)->get();
        }
        return DataTables::of($rejected)
                ->addIndexColumn()
                ->addColumn('ag_division', function ($newdata) {
                    $AGName  =  $this->getAGDName($newdata->ag_division);
                    return $AGName;

                  })
              ->addColumn('gn_division', function ($newdata) {
                    $GNName  =  $this->getGNDName($newdata->gn_division);
                    return $GNName;

                  })
                ->addColumn('action', function ($rejected) {
                    $edit = '<a href="/'.request()->segment(1).'/update/'.$rejected->id.'" class="btn btn-icon btn-primary"><i class="far fa-edit"></i></a>';
                    $view = '<a href="/'.request()->segment(1).'/view/'.$rejected->id.'" class="btn btn-icon btn-info"><i class="fas fa-eye"></i></a>';
                    $delete = '<a href="javascript:;" data-toggle="modal" onclick="deleteData('.$rejected->id.')"
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
                })->rawColumns(['action'])->make(true);

    }


    public function getrejected(){
        $rejected=[];
        if($this->getAccessCreate(request()->segment(1))=='Yes' || $this->getAccessRegVerify(request()->segment(1))=='Yes' || $this->getAccessRegApprove(request()->segment(1))=='Yes'){
            $rejected=Form12::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('rejected',1)->get();
        }
        else{
            $rejected=Form12::where('rejected',1)->get();
        }
        return DataTables::of($rejected)
                ->addIndexColumn()
                ->addColumn('ag_division', function ($newdata) {
                    $AGName  =  $this->getAGDName($newdata->ag_division);
                    return $AGName;

                  })
              ->addColumn('gn_division', function ($newdata) {
                    $GNName  =  $this->getGNDName($newdata->gn_division);
                    return $GNName;

                  })
                ->addColumn('action', function ($rejected) {
                    $edit = '<a href="/'.request()->segment(1).'/update/'.$rejected->id.'" class="btn btn-icon btn-primary"><i class="far fa-edit"></i></a>';
                    $view = '<a href="/'.request()->segment(1).'/view/'.$rejected->id.'" class="btn btn-icon btn-info"><i class="fas fa-eye"></i></a>';
                    $delete = '<a href="javascript:;" data-toggle="modal" onclick="deleteData('.$rejected->id.')"
                    data-target="#DeleteModal" class="btn btn-icon btn-danger"><i class="fas fa-trash"></i></a>';
                    $actions='';
                    if(($this->getAccessView(request()->segment(1))=="Yes")){
                        $actions .= ' '.$view;
                    }
                    // if(($this->getAccessUpdate(request()->segment(1))=="Yes")){
                    //     $actions .= ' '.$edit;
                    // }
                    if(($this->getAccessDelete(request()->segment(1))=="Yes")){
                        $actions .= ' '.$delete;
                    }
                    return $actions;
                })->rawColumns(['action'])->make(true);
    }

    public function getReference()
    {
        $reference=[];
        if($this->getAccessForwardProof(request()->segment(1))=='Yes'){
            $reference=Form12::where('computer_branch_officer',Auth::user()->id)->where('current_stage','Computer branch')->where('rejected',0)->distinct()->get('ref_no2');
        }
        return DataTables::of($reference)
                ->addIndexColumn()
                ->addColumn('ref_no', function ($reference) {
                    $refNum  =  $reference->ref_no2;
                    return $refNum;

                  })
               ->addColumn('action', function ($reference) {
                   // $view = '<a href="/'.request()->segment(1).'/view/'.$reference->id.'" class="btn btn-icon btn-info"><i class="fas fa-eye"></i></a>';
                    $download = '<a id="download" href="/'.request()->segment(1).'/download?ref='.$reference->ref_no2.'" class="btn btn-icon btn-primary"><i class="fas fa-download"></i></a>';
                    $actions='';
                    if(($this->getAccessView(request()->segment(1))=="Yes")){
                       // $actions .= ' '.$view;
                        $actions .= ' '.$download;
                    }
                    return $actions;
                })->rawColumns(['action'])->make(true);

    }


    public function getgazetted(){
        $gazetted=[];
        if($this->getAccessCreate(request()->segment(1))=='Yes' || $this->getAccessRegVerify(request()->segment(1))=='Yes' || $this->getAccessRegApprove(request()->segment(1))=='Yes'){
            $gazetted=Form12::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('current_stage','Online Publish')->where('rejected',0)->orderBy('id', 'DESC')->get();
        }else{
            $gazetted=Form12::where('current_stage','Online Publish')->where('rejected',0)->orderBy('id', 'DESC')->get();
        }
        return DataTables::of($gazetted)
                ->addIndexColumn()
            //     ->addColumn('ag_division', function ($newdata) {
            //         $AGName  =  $this->getAGDName($newdata->ag_division);
            //         return $AGName;

            //       })
            //   ->addColumn('gn_division', function ($newdata) {
            //         $GNName  =  $this->getGNDName($newdata->gn_division);
            //         return $GNName;

            //       })
                ->addColumn('action', function ($gazetted) {
                    $edit = '<a href="/'.request()->segment(1).'/update/'.$gazetted->id.'" class="btn btn-icon btn-primary"><i class="far fa-edit"></i></a>';
                    $view = '<a href="/'.request()->segment(1).'/view/'.$gazetted->id.'" class="btn btn-icon btn-info"><i class="fas fa-eye"></i></a>';
                    $delete = '<a href="javascript:;" data-toggle="modal" onclick="deleteData('.$gazetted->id.')"
                    data-target="#DeleteModal" class="btn btn-icon btn-danger"><i class="fas fa-trash"></i></a>';
                    $actions='';
                    if(($this->getAccessView(request()->segment(1))=="Yes")){
                        $actions .= ' '.$view;
                    }
                    // if(($this->getAccessUpdate(request()->segment(1))=="Yes")){
                    //     $actions .= ' '.$edit;
                    // }
                    if(($this->getAccessDelete(request()->segment(1))=="Yes")){
                        $actions .= ' '.$delete;
                    }
                    return $actions;
                })->rawColumns(['action'])->make(true);
    }

    public function downloadNotice()
    {
        try
        {
        if(Input::get('ref')!=null || Input::get('ref')!="")
        {
        $getAllFile=Form12::where('computer_branch_officer',Auth::user()->id)->where('ref_no2',Input::get('ref'))->where('current_stage','Computer branch')->where('rejected',0)->get();
        foreach($getAllFile as $key=>$attibute)
        {
        $form_12=DB::table('form_12')
        ->leftjoin('provinces','form_12.province_id','=','provinces.id')
        ->leftjoin('districts','form_12.district_id','=','districts.id')
        ->leftjoin('ag_divisions','form_12.gn_division','=','ag_divisions.id')
        ->select('form_12.*','provinces.province_name','districts.districts_name','ag_divisions.ag_name','districts.sinhala_name as dissinhala','ag_divisions.sinhala_name as agsinhala','provinces.sinhala_name as prosinhala')
        ->where('form_12.id',$attibute->id)
        ->first();
        $gn_name_engish=null;
        $gn_name_sinhala=null;
        foreach(explode(',',$form_12->gn_division) as $str)
        {
            if($str!="")
            {
            $gn=GnDivisions::where('id',$str)->first();
            $gn_name_engish=$gn_name_engish.','.$gn->gn_name;
            $gn_name_sinhala=$gn_name_sinhala.','.$gn->sinhala_name;
            }
        }
       $file =$attibute->id.".txt";
      // $myfile = fopen(public_path()."/upload/".$file, "w") or die("Unable to open file!");
       $myfile = fopen(base_path()."/upload/".$file, "w") or die("Unable to open file!");
        if(trans('sentence.lang')=='EN')
        {
            $txt="NOTICE CALLING FOR CLAIMS TO LAND PARCELS.
            \n
Registration  of  Title Act, No.  21 of  1998\n
              (Section 12)\n
            \n
NOTICE No : ".$form_12->ref_no." ".$form_12->districts_name." District
            \n
It is hereby notified that any person having a claim to the owner-ship or possession or interest to one or more of the parcels of lands reflected in the Cadastral Map No.".$form_12->map_no." made by the Surveyor-General under Section 11 of the Registration of Title Act, No. 21 of 1998 relating to the village of ". $form_12->village ."or any part thereof, situated within the Grama Niladhari Division of No.".$gn_name_engish. " the Divisional Secretariat Division of".$form_12->districts_name. " in the District of ".$form_12->districts_name." in the Province of ".$form_12->province_name ." submit his claim to the undersigned before XXth XXXXX,XXXX In the event of failure  to submit any claim before the above-mentioned date, action will be taken to hold an exparte inquiry to determine title to the land and accordingly publish such determination in the Government Gazette in terms of Section 14 of the above Act.
\n
The Cadastral Map No.".$form_12->map_no." referred to above may be perused at the relevant Grama Niladhari Office, Divisional Secretariat, District Survey Office, Office of the Commissioner of Title Settlement, or the Surveyor - General’s office.
\n
Further information may be obtained from the Grama Niladhari,the Divisional Secretary, or the Commissioner of Title Settlement.
\n
    K.A.K. RANJITH DHARMAPALA,\n
    Commissioner of Title Settlement\n
    \n
Land Title Settlement Department,\n
No.1200/6, “Mihikatha Medura”,\n
Rajamalwatta Road,\n
Battaramulla\n
xxth xxxx, xxxx ";
        }
        else
        {
            $txt="ඉඩම් කැබලි වලට හිමිකම්පෑම් ඉදිරිපත් කරන ලෙස දැනුම්දීම.
\n
1998 අංක 21 දරන හිමිකම් ලියාපදිංචි කිරීමේ පනත\n
    (12 වැනි වගන්තිය)
            \n
දැන්වීම් අංක :". $form_12->ref_no." ".$form_12->dissinhala ." දිස්ත්‍රික්කය
            \n
".$form_12->prosinhala." පළාතේ ".$form_12->dissinhala ." දිස්ත්‍රික්කයේ ".$form_12->agsinhala." ප්‍රාදේශීය ලේකම් කොට්ඨාශයේ".$gn_name_sinhala." ග්‍රාම නිලධාරි කොට්ඨාශය තුළ පිහිටි ".$form_12->village. " ගමට හෝ ඉන් කොටසකට හෝ කළාප අංක ".$form_12->block_no. " ට අදාලව 1998 අංක 21 දරන හිමිකම් ලියාපදිංචි කිරීමේ පනතේ 11 වැනි වගන්තිය යටතේ මිනුම්පතිවරයා විසින් සාදන ලද අංක ".$form_12->map_no." දරන කැඩැස්තර සිතියමේ දක්වා ඇති ඉඩම් කොටස් එකක හෝ ඊට වැඩි ගණනක හෝ අයිතියට නැතහොත් සන්තකයට හෝ සම්බන්ධතාවයකට හිමිකම් පාන කවර වුවද තැනැත්තෙකු විසින් තම හිමිකම්පෑම xxxx xxxxx xx වැනි දිනට පෙර, පහත අත්සන් කරන අය වෙත ඉදිරිපත් කළ යුතුය. එකී දිනට පෙර යම් හිමිකම් පෑමක් ඉදිරිපත් කිරීම පැහැර හරිනු ලැබුවහොත්, ඒ ඉඩමේ අයිතිය සම්බන්ධයෙන් ඒක පාක්ෂික පරීක්ෂණයක් පවත්වනු ලබන අතර, එහි දී ගනු ලබන තීරණය ඉහත සඳහන් පනතේ 14 වැනි වගන්තිය ප්‍රකාර ගැසට් පත්‍රයේ පළ කරනු ලැබේ‍.
             \n
ඉහත සඳහන් අංක ".$form_12->map_no." දරන කැඩැස්තර සිතියම, අදාළ ග්‍රාම නිලධාරී කාර්යාලයේ දී, ප්‍රාදේශීය මහ ලේකම් කාර්යාලයේ දී, දිස්ත්‍රික් මිනුම් කාර්යාලයේ දී, හිමිකම් නිරවුල් කිරීමේ කොමසාරිස්වරයාගේ කාර්යාලයේ දී, හෝ මිනුම්පති වරයාගේ කාර්යාලයේ දී පරීක්ෂා කරනු ලැබිය හැකිය.
            \n
වැඩි විස්තර, ග්‍රාම නිලධාරිගෙන්, ප්‍රාදේශීය ලේකම්වරයාගෙන්, හෝ හිමිකම් නිරවුල් කිරීමේ කොමසාරිස්වරයාගෙන් ලබා ගත හැකිය.
            \n
                පී.එම්.එච්. ප්‍රියදර්ශනී,\n
                හිමිකම් නිරවුල් කිරීමේ කොමසාරිස් (රා.ආ.)\n
                \n
2019 ජූලි මස 10 වැනි දින,\n
බත්තරමුල්ල‍,\n
රජමල්වත්ත පාර, “මිහිකත මැඳුර”,\n
අංක 1200/6,\n
ඉඩම් හිමිකම් නිරවුල් කිරීමේ දෙපාර්තමේන්තුවේ දී ය.";
        }
        fwrite($myfile, $txt);
        fclose($myfile);
       //  $txtfiles = glob(public_path('upload/'.$file));
        // \Zipper::make(public_path()."/upload/form12.zip")->add($txtfiles)->close();
          $txtfiles = glob(base_path('upload/'.$file));
         \Zipper::make(base_path()."/upload/form12.zip")->add($txtfiles)->close();
    }
   // return response()->download(public_path()."/upload/form12.zip");
    return response()->download(base_path()."/upload/form12.zip");
}else
{
    return redirect()->back()->with('error', 'Reference Number is Null');
}
} catch (\Exception $e) {
    dd($e);
}


    }








        /**
         * Show the form for creating a new resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function create()
        {
            $file12_no=$this->Generate_File_Number();
            $province=Provinces::where('is_active',1)->get();
            $district=Districts::where('is_active',1)->get();
            $agDivision=AgDivisions::where('is_active',1)->get();
            $gnDivision=GnDivisions::where('is_active',1)->get();
            $villages=Village::get();
            $computer_officers=User::whereIn('role_code',UserRolePermissions::select('role_code')->where('is_enable',1)->where('module_code',request()->segment(1))->where('can_forward_to_proof',1)->distinct('module_code')->get())->get();
            view()->share('computer_officers',$computer_officers);
            $form12_id=null;

            view()->share('villages',$villages);
            view()->share('recheckHistory',[]);
            view()->share('file_no',$file12_no);
            view()->share('form12_id',$form12_id);
            view()->share('gnDivision',$gnDivision);
            view()->share('agDivision',$agDivision);
            view()->share('district',$district);
            view()->share('province',$province);
            view()->share('form12',null);
            return view('pages.form12.form');
        }


        /**
         * Store a newly created resource in storage.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */


        public Function Generate_File_Number()
        {
            $regionalOffice=AgDivisions::where('id',Auth::User()->branch_id)->where('is_active',1)->first();

            if(isset($regionalOffice))
            {
            $max_12File_no=DB::select("select file_no from form_12 where file_no like '%".$regionalOffice->ag_code."%' ORDER BY RIGHT(file_no, 10) DESC");
            $Regi=null;
            if(sizeof($max_12File_no)==0)
            {
                $file12_no=0;
            }
            else
            {
                $last_file_no=$max_12File_no[0]->file_no;
                $last_file_no=Form12::where('file_no',$last_file_no)->first();
                list($Regi,$file12_no) = explode('/', $last_file_no->file_no);
            }
            $file12_no=$regionalOffice->ag_code.'/'.sprintf('%010d', intval($file12_no) + 1);
            return $file12_no;
        }
        else
        {
            return redirect()->route('home')->with('error', 'Please Update User AG Office');
        }
        }
        public function store(Request $request)
        {
            try{
                $count12=Form12::where('map_no',$request->map_no)->where('block_no',$request->block_no)->where('rejected',0)->get();
                if(sizeof($count12)==0){
                    DB::beginTransaction();
                    $string_gn_division=null;
                    $gn_division=$request->get('gn_div_id');
                    if($gn_division){
                        foreach($gn_division as $element){
                            $string_gn_division=$string_gn_division.','.$element;
                        }
                    }
                    $string_village=null;
                    $villages=$request->get('village_id');
                    if($villages){
                        foreach($villages as $element){
                            $string_village=$string_village.','.$element;
                        }
                    }

                    Form12::updateOrCreate(['id' =>$request->form12_id] ,
                    [
                        'ag_division' => $request->ag_div_id,
                        'map_no'=>$request->map_no,
                        'block_no'=>$request->block_no,
                        'province_id'=>$request->province_id,
                        'district_id'=>$request->district_id,
                        'gn_division'=>$string_gn_division,
                        'village'=>$string_village,
                        'name'=>null,
                        'government_lands'=>$request->gov_lands,
                        'private_lands'=>$request->pri_lands,
                        'total_lands'=>$request->tot_lands,
                        'file_no'=>$this->Generate_File_Number(),
                        'ref_no'=>$request->gazzette_ref,
                        'publication_branch'=>0,
                        'publication_branch_date'=>null,
                        'computer_branch'=>0,
                        'computer_branch_date'=>null,
                        'prepared_by'=>Auth::User()->id,
                        'prepared_date'=>Carbon::now(),
                        'regional_approved'=>0,
                        'current_stage'=>'Regional commissioner',
                        'rejected'=>0,
                        'recheck'=>0,
                        'comment'=>$request->comment,
                    ]);
                DB::commit();
                return redirect()->route('form12-create')->with('success', 'Created Successfully!');
                }else{
                    return redirect()->route('form12-create')->with('error','Entered block no with map number already processed!!!');
                }

               } catch (Exception $e) {
                   DB::rollBack();
                   Log::error($e);
                   return redirect()->route('form12-create')->with('error', $e);
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
            $province=Provinces::where('is_active',1)->get();
            $district=Districts::where('is_active',1)->get();
            $agDivision=AgDivisions::where('is_active',1)->get();
            $gnDivision=GnDivisions::where('is_active',1)->get();
            $villages=Village::get();
            view()->share('villages',$villages);
            view()->share('gnDivision',$gnDivision);
            view()->share('agDivision',$agDivision);
            view()->share('district',$district);
            view()->share('province',$province);
            $form12=Form12::find($id);
            view()->share('form12',$form12);
            $computer_officers=User::whereIn('role_code',UserRolePermissions::select('role_code')->where('is_enable',1)->where('module_code',request()->segment(1))->where('can_forward_to_proof',1)->distinct('module_code')->get())->get();
            view()->share('computer_officers',$computer_officers);
            $proof_reads=ProofRead::where('form_name','form12')->where('language','sinhala')->where('ref_number',$form12->id)->get();
            $proof_reads_translate=ProofRead::where('form_name','form12')->where('language','translate')->where('ref_number',$form12->id)->get();
            $recheckHistory=Recheck::where('ref_form_id',$id)->where('form_name',request()->segment(1))->get();
           view()->share('recheckHistory',$recheckHistory);
            view()->share('proof_reads',$proof_reads);
            view()->share('proof_reads_translate',$proof_reads_translate);
            return view('pages.form12.form');
        }

        /**
         * Show the form for editing the specified resource.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function edit($id)
        {
            $province=Provinces::where('is_active',1)->get();
            $district=Districts::where('is_active',1)->get();
            $agDivision=AgDivisions::where('is_active',1)->get();
            $gnDivision=GnDivisions::where('is_active',1)->get();
            $villages=Village::get();
            view()->share('villages',$villages);
            view()->share('gnDivision',$gnDivision);
            view()->share('agDivision',$agDivision);
            view()->share('district',$district);
            view()->share('province',$province);
            $form12=Form12::find($id);
            $computer_officers=User::whereIn('role_code',UserRolePermissions::select('role_code')->where('is_enable',1)->where('module_code',request()->segment(1))->where('can_forward_to_proof',1)->distinct('module_code')->get())->get();
            view()->share('computer_officers',$computer_officers);
            $proof_reads=ProofRead::where('form_name','form12')->where('language','sinhala')->where('ref_number',$form12->id)->get();
            $proof_reads_translate=ProofRead::where('form_name','form12')->where('language','translate')->where('ref_number',$form12->id)->get();
           $recheckHistory=Recheck::where('ref_form_id',$id)->where('form_name',request()->segment(1))->get();
           view()->share('recheckHistory',$recheckHistory);
            view()->share('form12',$form12);
            view()->share('proof_reads',$proof_reads);
            view()->share('proof_reads_translate',$proof_reads_translate);
            return view('pages.form12.form');
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
                DB::beginTransaction();
                $form12=Form12::find($id);
                switch($request->button){
                    case 'forward_regional_commisioner':
                        $string_gn_division=null;
                        $gn_division=$request->get('gn_div_id');
                        if($gn_division){
                            foreach($gn_division as $element){
                                $string_gn_division=$string_gn_division.','.$element;
                            }
                        }
                        $string_village=null;
                        $villages=$request->get('village_id');
                        if($villages){
                            foreach($villages as $element){
                                $string_village=$string_village.','.$element;
                            }
                        }
                        $form12->current_stage='Regional commissioner';
                        $form12->map_no=$request->map_no;
                        $form12->block_no=$request->block_no;
                        $form12->gn_division=$string_gn_division;
                        $form12->village=$string_village;
                        $form12->government_lands=$request->gov_lands;
                        $form12->private_lands=$request->pri_lands;
                        $form12->total_lands=$request->tot_lands;
                        $form12->recheck=0;
                        $form12->regional_checked=1;
                        $form12->regional_checked_by=Auth::user()->id;
                        $form12->regional_checked_date=Carbon::now();
                        $form12->comment=$request->comment;
                        $form12->save();
                        DB::commit();
                        return redirect()->route('form12-new-requests')->with('success', 'Updated Successfully!');
                        break;
                    case 'regional_commisioner_approval':
                        $string_gn_division=null;
                        $gn_division=$request->get('gn_div_id');
                        if($gn_division){
                            foreach($gn_division as $element){
                                $string_gn_division=$string_gn_division.','.$element;
                            }
                        }
                        $string_village=null;
                        $villages=$request->get('village_id');
                        if($villages){
                            foreach($villages as $element){
                                $string_village=$string_village.','.$element;
                            }
                        }
                        $form12->current_stage='Publication verify';
                        $form12->map_no=$request->map_no;
                        $form12->block_no=$request->block_no;
                        $form12->gn_division=$string_gn_division;
                        $form12->village=$string_village;
                        $form12->government_lands=$request->gov_lands;
                        $form12->private_lands=$request->pri_lands;
                        $form12->total_lands=$request->tot_lands;
                        $form12->recheck=0;
                        $form12->regional_approved=1;
                        $form12->regional_approved_by=Auth::user()->id;
                        $form12->regional_approved_date=Carbon::now();
                        $form12->comment=$request->comment;
                        $form12->save();
                        DB::commit();
                        return redirect()->route('form12-new-requests')->with('success', 'Updated Successfully!');
                        break;
                    case 'forward_asst_comm':
                        alert($request->comment);
                        $count_ref=Form12::where('ref_no',$request->ref_no)->count();
                        if($count_ref>0)
                        {
                            return redirect()->back()->with('error','Dupplicate File Ref Number!!!' );
                        }
                        else
                        {
                        $form12->current_stage='Assistant commisioner';
                        $form12->ref_no=$request->ref_no;
                        $form12->ref_no2=$request->ref_no2;
                        $form12->publication_branch=1;
                        $form12->publication_branch_date=Carbon::now();
                        $form12->publication_checked_by=Auth::user()->id;
                        $form12->comment=$request->comment;
                        $form12->save();
                        DB::commit();
                        return redirect()->route('form12-new-requests')->with('success', 'Updated Successfully!');
                        }
                        break;
                    case 'forward_bim_comm':
                        $form12->current_stage='Bimsaviya commisioner';
                        $form12->computer_branch_officer=$request->computer_officer;
                        $form12->asst_comm_approval=Auth::user()->id;
                        $form12->asst_comm_approval_date=Carbon::now();
                        $form12->comment=$request->comment;
                        $form12->save();
                        DB::commit();
                        return redirect()->route('form12-new-requests')->with('success', 'Updated Successfully!');
                        break;
                    case 'forward_comm_general':
                        $form12->current_stage='Commissioner general';
                        $form12->bimsaviya_approval=Auth::user()->id;
                        $form12->bimsaviya_approval_date=Carbon::now();
                        $form12->comment=$request->comment;
                        $form12->save();
                        DB::commit();
                        return redirect()->route('form12-new-requests')->with('success', 'Updated Successfully!');
                        break;
                    case 'comm_general_approval':
                        $form12->current_stage='Computer branch';
                        $form12->comm_gen_approval=Auth::user()->id;
                        $form12->comm_gen_approal_date=Carbon::now();
                        $form12->comment=$request->comment;
                        $form12->save();
                        DB::commit();
                        return redirect()->route('form12-new-requests')->with('success', 'Updated Successfully!');
                        break;
                    case 'forward_to_proof_read':
                        $form12->current_stage='Proof read(Sinhala)';
                        $form12->computer_branch=1;
                        $form12->computer_branch_date=Carbon::now();
                        $form12->computer_checked_by=Auth::user()->id;
                        $form12->comment=$request->comment;
                        $form12->save();
                        DB::commit();
                        return redirect()->route('form12-new-requests')->with('success', 'Updated Successfully!');
                        break;
                    case 'proof_read_sinhala':
                        $form12->current_stage='Proof read(Sinhala)-Computer';
                        $form12->comment=$request->comment;
                        $form12->save();
                        $proof_details=[
                            'form_name'=>'form12',
                            'language'=>'sinhala',
                            'ref_number'=>$form12->id,
                            'proof_read_by'=>Auth::user()->id,
                            'proof_read_date'=>Carbon::now(),
                        ];
                        $proof_read=new ProofRead($proof_details);
                        $proof_read->save();
                        DB::commit();
                        return redirect()->route('form12-new-requests')->with('success', 'Updated Successfully!');
                        break;
                    case 'forward_to_proof_read_translation':
                        $form12->current_stage='Proof read(Translates)';
                        $form12->comment=$request->comment;
                        $form12->save();
                        DB::commit();
                        return redirect()->route('form12-new-requests')->with('success', 'Updated Successfully!');
                        break;
                    case 'proof_read_translation':
                        $form12->current_stage='Proof read(Translation)-Computer';
                        $form12->comment=$request->comment;
                        $form12->save();
                        $proof_details=[
                            'form_name'=>'form12',
                            'language'=>'translate',
                            'ref_number'=>$form12->id,
                            'proof_read_by'=>Auth::user()->id,
                            'proof_read_date'=>Carbon::now(),
                        ];
                        $proof_read=new ProofRead($proof_details);
                        $proof_read->save();
                        DB::commit();
                        return redirect()->route('form12-new-requests')->with('success', 'Updated Successfully!');
                        break;
                    case 'proof_read_complete':
                        $form12->current_stage='Proof read complete';
                        $form12->first_proof_read=1;
                        $form12->first_proof_read_by=Auth::user()->id;
                        $form12->first_proof_read_date=Carbon::now();
                        $form12->comment=$request->comment;
                        $form12->save();
                        DB::commit();
                        return redirect()->route('form12-new-requests')->with('success', 'Updated Successfully!');
                        break;
                    case 'publication_without_G':
                        $form12->current_stage='Publication without G';
                        $form12->second_proof_read=1;
                        $form12->second_proof_read_by=Auth::user()->id;
                        $form12->second_proof_read_date=Carbon::now();
                        $form12->comment=$request->comment;
                        $form12->save();
                        DB::commit();
                        return redirect()->route('form12-new-requests')->with('success', 'Updated Successfully!');
                        break;
                    case 'press_without_G':
                        $form12->current_stage='Gov Press without G';
                        $form12->first_proof_english=1;
                        $form12->first_proof_english_by=Auth::user()->id;
                        $form12->first_proof_english_date=Carbon::now();
                        $form12->comment=$request->comment;
                        $form12->save();
                        DB::commit();
                        return redirect()->route('form12-new-requests')->with('success', 'Updated Successfully!');
                        break;
                    case 'computer_with_G':
                        $form12->current_stage='Gazette with G';
                        $form12->gazette_no=$request->gazzette_no;
                        $form12->gazette_date=$request->gazzette_date;
                        $form12->second_proof_english=1;
                        $form12->second_proof_english_by=Auth::user()->id;
                        $form12->second_proof_english_date=Carbon::now();
                        $form12->comment=$request->comment;
                        $form12->save();
                        DB::commit();
                        return redirect()->route('form12-new-requests')->with('success', 'Updated Successfully!');
                        break;
                    case 'publication_with_G':
                        $form12->current_stage='Publication with G';
                        $form12->first_proof_read_tamil=1;
                        $form12->first_proof_read_tamil_by=Auth::user()->id;
                        $form12->first_proof_read_tamil_date=Carbon::now();
                        $form12->comment=$request->comment;
                        $form12->save();
                        DB::commit();
                        return redirect()->route('form12-new-requests')->with('success', 'Updated Successfully!');
                        break;
                    case 'press_with_G':
                        $form12->current_stage='Gov press with G';
                        $form12->sent_gov_press=Auth::user()->id;
                        $form12->sent_gov_press_date=Carbon::now();
                        $form12->comment=$request->comment;
                        $form12->save();
                        DB::commit();
                        return redirect()->route('form12-new-requests')->with('success', 'Updated Successfully!');
                        break;
                    case 'reject':
                        $form12->rejected=1;
                        $form12->rejected_date=Carbon::now();
                        $form12->rejected_reason=$request->reason;
                        $form12->save();
                        DB::commit();
                        if(isset($request->from)){
                            return redirect()->back()->with('success', 'Updated Successfully!');
                        }else{
                            return redirect()->route('form12-new-requests')->with('success', 'Updated Successfully!');
                        }
                        break;
                    case 'recheck':
                        $recheck_data=[
                            'ref_form_id'=>$form12->id,
                            'recheck_by'=>Auth::user()->id,
                            'recheck_stage'=>$form12->current_stage,
                            'recheck_reason'=>$request->recheck_reason,
                            'form_name'=>request()->segment(1),
                        ];
                        $recheck=new Recheck($recheck_data);
                        $recheck->save();
                        if($form12->current_stage=='Regional commissioner'){
                            $form12->current_stage='Regional data entry';
                        }else{
                            $form12->current_stage='Regional commissioner';
                        }
                        $form12->recheck=1;
                        $form12->recheck_reason=$request->recheck_reason;
                        $form12->recheck_date=Carbon::now();
                        $form12->save();
                        DB::commit();
                        return redirect()->route('form12-new-requests')->with('success', 'Updated Successfully!');
                        break;
                    case 'online':
                        $form12->current_stage='Online Publish';
                        $form12->comment=$request->comment;
                        $form12->save();
                        DB::commit();
                        return redirect()->route('form12-new-requests')->with('success', 'Created Successfully!');
                        break;

                }
            } catch (Exception $e) {
                DB::rollBack();
                Log::error($e);
                return redirect()->route('form12-new-requests')->with('error', 'Error!');

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

        public function getForm12($map,$block){
            if($this->getAccessCreate('14th-sentence')=='Yes')
            {
            $form12=Form12::where('map_no',$map)->where('block_no',$block)->where('rejected',0)->where('current_stage','Online Publish')->where('ag_division',Auth::user()->branch_id)->first();
            }
            else
            {
             $form12=Form12::where('map_no',$map)->where('block_no',$block)->first();
            }
            if($form12){
                return $form12;
            }else{
                return ['message'=>'Form 12 not found..'];
            }
        }
        public function getForm12Map($map)
        {
            if($this->getAccessCreate('14th-sentence')=='Yes')
            {
            $from12=Form12::where('map_no',$map)->where('rejected',0)->where('current_stage','Online Publish')->where('ag_division',Auth::user()->branch_id)->distinct()->get('block_no');
            }
            else
            {
            $from12=Form12::where('map_no',$map)->distinct()->get('block_no');
            }
            if($from12)
            {
                return $from12;
            }
            else
            {
                return ['message'=>'Map Numbers Not found..'];
            }
        }
        public function checkMapNumber($map)
        {
            $from12=Form12::where('map_no',$map)->where('rejected',0)->distinct()->get('map_no');
            if(sizeof($from12)>0)
            {
                return ['message'=>1];
            }
            else
            {
                return ['message'=>0];
            }
        }
        public function checkBlockNumber($map,$block)
        {
            $from12=Form12::where('map_no',$map)->where('block_no',$block)->where('rejected',0)->distinct()->get('block_no');
            if(sizeof($from12)>0)
            {
                return ['message'=>1];
            }
            else
            {
                return ['message'=>0];
            }
        }

}
