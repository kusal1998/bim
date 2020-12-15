<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Form14Header;
use App\Models\Form14Details;
use App\Models\Form14File;
use DB;
use Auth;
use App\Traits\Permissions;
use App\Models\UserRolePermissions;
use App\Models\ProofRead;
use App\Models\GnDivisions;
use App\Models\Village;
use App\User;
use DataTables;
use ZipArchive;

class Form14FileController extends Controller
{
    use Permissions;
    public function create_file(Request $request){
        try{
            DB::beginTransaction();
            if(isset($request->file)){
                $form12_info=Form14Header::where('ag_division_id',$request->ag_division)->where('ref_no',null)->get();
                foreach($form12_info as $info){
                    $info->ref_no=$request->file;
                    $info->save();
                }

            }else{
                $code=$this->generate_referencing_code($request->ag_division);
                $file_info=['code'=>$code,'created_by'=>Auth::user()->id,'current_stage'=>'Publication verify','ag_division'=>$request->ag_division];
                $form12_file=new Form14File($file_info);
                $form12_file->save();

                $form12_info=Form14Header::where('ag_division_id',$request->ag_division)->where('ref_no',null)->get();
                foreach($form12_info as $info){
                    $info->ref_no=$form12_file->id;
                    $info->save();
                }
            }
            DB::commit();
            return redirect()->back()->with('success', 'Created Successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->back()->with('error', $e);
        }
    }

    public function generate_referencing_code($ag_division){
        $ref_no=0;
        $now = Carbon::now();
        $current_year=$now->year;
        $year_code=null;
        $sentence_code=null;
        $ag_code=$ag_division;
        $max_reference=DB::select("select form_14_file.code from form_14_header inner join form_14_file on form_14_header.ref_no=form_14_file.id where form_14_file.code like '%".substr($current_year,2,2)."%' ORDER BY RIGHT(form_14_file.code, 5) DESC");

        if(sizeof($max_reference)==0)
        {
            $ref_no=0;
        }
        else
        {
            $last_ref_no=$max_reference[0]->code;
            $last_ref_no=Form14File::where('code',$last_ref_no)->first();
            list($year_code,$sentence_code,$ag_code,$ref_no) = explode('/', $last_ref_no->code);
        }
        $ref_no=substr($current_year,2,2).'/14/'.$ag_code.'/'.sprintf('%05d', intval($ref_no) + 1);
        return $ref_no;
    }

    public function get_new_requests(){
        return view('pages.form14.form14_file_index');
    }

    public function newlist(){
        $pending=collect([]);
            $create=collect([]);$reg_verify=collect([]);$reg_approve=collect([]);$pub_verify=collect([]);$asst_comm=collect([]);$bim_comm=collect([]);
            $comm_gen=collect([]);$computer=collect([]);$proof_sinhala=collect([]);$computer_sinhala=collect([]);$proof_translate=collect([]);$computer_translate=collect([]);
            $pub_without=collect([]);$press_without=collect([]);


        if($this->getAccessPubVerify(request()->segment(1))=='Yes'){
            $pub_verify=Form14File::where('current_stage','Publication verify')->where('is_archived',0)->get();
        }
        if($this->getAccessAsstComm(request()->segment(1))=='Yes'){
            $asst_comm=Form14File::where('current_stage','Assistant commisioner')->where('is_archived',0)->get();
        }
        if($this->getAccessBimsaviyaComm(request()->segment(1))=='Yes'){
            $bim_comm=Form14File::where('current_stage','Bimsaviya commisioner')->where('is_archived',0)->get();
        }
        if($this->getAccessCommGen(request()->segment(1))=='Yes'){
            $comm_gen=Form14File::where('current_stage','Commissioner general')->where('is_archived',0)->get();
        }
        if($this->getAccessForwardProof(request()->segment(1))=='Yes'){
            $computer=Form14File::where('computer_branch_officer',Auth::user()->id)->where('is_archived',0)->where(function($q){
                $q->where('current_stage','Computer branch')->orWhere('current_stage','Proof read(Sinhala)-Computer');
            })->get();
        }
        if($this->getAccessProof(request()->segment(1))=='Yes'){
            $proof_sinhala=Form14File::where('current_stage','Proof read(Sinhala)')->where('is_archived',0)->get();
        }
        if($this->getAccessForwardTransProof(request()->segment(1))=='Yes'){
            $computer_sinhala=Form14File::where('computer_branch_officer',Auth::user()->id)->where('current_stage','Proof read(Translation)-Computer')->where('is_archived',0)->get();
        }
        if($this->getAccessTransProof(request()->segment(1))=='Yes'){
            $proof_translate=Form14File::where('current_stage','Proof read(Translates)')->where('is_archived',0)->get();
        }
        if($this->getAccessForwardPublication(request()->segment(1))=='Yes'){
            $computer_translate=Form14File::where('computer_branch_officer',Auth::user()->id)->where('current_stage','Proof read complete')->orWhere('current_stage','Gazette with G')->where('is_archived',0)->get();
        }
        if($this->getAccessForwardPress(request()->segment(1))=='Yes'){
            $pub_without=Form14File::where('current_stage','Publication without G')->orWhere('current_stage','Publication with G')->orWhere('current_stage','Gov press with G')->where('is_archived',0)->get();
        }
        if($this->getAccessGazette(request()->segment(1))=='Yes'){
            $press_without=Form14File::where('current_stage','Gov Press without G')->where('is_archived',0)->get();
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

                ->addColumn('computer_officer',function($pending){
                   return (isset($pending->computer_branch_officer))?$this->getUserName($pending->computer_branch_officer):'-';
                })
                ->addColumn('gazette_no',function($pending){
                    return (isset($pending->gazette_no))?$pending->gazette_no:'-';
                })
                ->addColumn('gazette_date',function($pending){
                    return (isset($pending->gazette_date))?$pending->gazette_date:'-';
                })
                ->addColumn('action', function ($pending) {
                    $edit = '<a href="/'.request()->segment(1).'/update/'.$pending->id.'" class="btn btn-icon btn-primary"><i class="far fa-edit"></i></a>';
                    $actions='';

                    if(($this->getAccessUpdate(request()->segment(1))=="Yes")){
                        $actions .= ' '.$edit;
                    }
                    if(($this->getAccessDelete(request()->segment(1))=="Yes")){
                        $actions .= ' '.$delete;
                    }
                    return $actions;
                })->rawColumns(['gazette_no','gazette_date','computer_officer','action'])->make(true);
    }

    public function view($id){
        $form12file=Form14File::find($id);
        view()->share('form12',$form12file);
        $form12=Form14Header::where('ref_no',$id)->where('rejected',0)->get();
        view()->share('form14_records',$form12);
        $computer_officers=User::whereIn('role_code',UserRolePermissions::select('role_code')->where('is_enable',1)->where('module_code',request()->segment(1))->where('can_forward_to_proof',1)->distinct('module_code')->get())->get();
        view()->share('computer_officers',$computer_officers);
        return view('pages.form14.form14_file_data');
    }

    public function block_details($header){
        $form12=Form14Header::find($header);
        $details=Form14Details::where('form_14_Header_id',$header)->where('rejected',0)->get();
        view()->share('details',$details);
        view()->share('form12',$form12);
        return view('pages.form14.form14_file_details');
    }

    public function update(Request $request,$id){
        try{
            $form12file=Form14File::find($id);
            $form12=Form14Header::where('ref_no',$id)->where('rejected',0)->get();
            if($request->button=='archive'){
                $form12file->is_archived=1;
                $form12file->save();
                DB::commit();

                return redirect('14th-sentence-file/new-requests')->with('success','Updated Successfully!!');
            }else{ 
                switch($request->button){
                    case 'forward_asst_comm':
                        $form12file->current_stage='Assistant commisioner';
                        $form12file->publication_branch=Auth::user()->id;
                        $form12file->publication_branch_date=Carbon::now();
                        $form12file->save();
                        foreach($form12 as $item){
                            $item->current_stage='Assistant commisioner';
                            $item->publication_checked=1;
                            $item->publication_checked_date=Carbon::now();
                            $item->publication_checked_by=Auth::user()->id;
                            $item->comment=$request->comment;
                            $item->save();
                        }
                        DB::commit();
                        return redirect('14th-sentence-file/new-requests')->with('success','Updated Successfully!!');
                        break;
                    case 'forward_bim_comm':
                        $form12file->current_stage='Bimsaviya commisioner';
                        $form12file->computer_branch_officer=$request->computer_officer;
                        $form12file->asst_commisioner_approval=Auth::user()->id;
                        $form12file->asst_commisioner_date=Carbon::now();
                        $form12file->save();
                        foreach($form12 as $item){
                            $item->current_stage='Bimsaviya commisioner';
                            $item->computer_branch_officer=$request->computer_officer;
                            $item->asst_commissioner_approval=Auth::user()->id;
                            $item->asst_commissioner_approved_date=Carbon::now();
                            $item->comment=$request->comment;
                            $item->save();
                        }
                        DB::commit();
                        return redirect('14th-sentence-file/new-requests')->with('success','Updated Successfully!!');
                        break;
                    case 'forward_comm_general':
                        $form12file->current_stage='Commissioner general';
                        $form12file->bim_approval=Auth::user()->id;
                        $form12file->bim_approval_date=Carbon::now();
                        $form12file->save();
                        foreach($form12 as $item){
                            $item->current_stage='Commissioner general';
                            $item->bimsaviya_commissioner_approval=Auth::user()->id;
                            $item->bimsaviya_commissioner_approved_date=Carbon::now();
                            $item->comment=$request->comment;
                            $item->save();
                        }
                        DB::commit();
                        return redirect('14th-sentence-file/new-requests')->with('success','Updated Successfully!!');
                        break;
                    case 'comm_general_approval':
                        $form12file->current_stage='Computer branch';
                        $form12file->comm_gen_approval=Auth::user()->id;
                        $form12file->comm_gen_approval_date=Carbon::now();
                        $form12file->save();
                        foreach($form12 as $item){
                            $item->current_stage='Computer branch';
                            $item->comm_gen_approval=Auth::user()->id;
                            $item->comm_gen_approval_date=Carbon::now();
                            $item->comment=$request->comment;
                            $item->save();
                        }
                        DB::commit();
                        return redirect('14th-sentence-file/new-requests')->with('success','Updated Successfully!!');
                        break;
                    case 'forward_to_proof_read':
                        $form12file->current_stage='Proof read(Sinhala)';
                        $form12file->computer_branch=1;
                        $form12file->computer_branch_date=Carbon::now();
                        $form12file->save();
                        foreach($form12 as $item){
                            $item->current_stage='Proof read(Sinhala)';
                            $item->computer_branch=Auth::user()->id;
                            $item->computer_branch_date=Carbon::now();
                            $item->comment=$request->comment;
                            $item->save();
                        }
                        DB::commit();
                        return redirect('14th-sentence-file/new-requests')->with('success','Updated Successfully!!');
                        break;
                    case 'proof_read_sinhala':
                        $form12file->current_stage='Proof read(Sinhala)-Computer';
                        $form12file->proof_read_sinhala=Auth::user()->id;
                        $form12file->proof_read_sinhala_date=Carbon::now();
                        $form12file->save();
                        foreach($form12 as $item){
                            $item->current_stage='Proof read(Sinhala)-Computer';
                            $item->comment=$request->comment;
                            $item->save();
                            $proof_details=[
                                'form_name'=>'form12',
                                'language'=>'sinhala',
                                'ref_number'=>$item->id,
                                'proof_read_by'=>Auth::user()->id,
                                'proof_read_date'=>Carbon::now(),
                            ];
                            $proof_read=new ProofRead($proof_details);
                            $proof_read->save();
                        }
                        DB::commit();
                        return redirect('14th-sentence-file/new-requests')->with('success','Updated Successfully!!');
                        break;
                    case 'forward_to_proof_read_translation':
                        $form12file->current_stage='Proof read(Translates)';
                        $form12file->save();
                        foreach($form12 as $item){
                            $item->sinhala_amended=1;
                            $item->sinhala_amended_by=Auth::user()->id;
                            $item->sinhala_amended_date=Carbon::now();
                            $item->current_stage='Proof read(Translates)';
                            $item->comment=$request->comment;
                            $item->save();
                        }
                        DB::commit();
                        return redirect('14th-sentence-file/new-requests')->with('success','Updated Successfully!!');
                        break;
                    case 'proof_read_translation':
                        $form12file->current_stage='Proof read(Translation)-Computer';
                        $form12file->proof_read_english=Auth::user()->id;
                        $form12file->proof_read_english_date=Carbon::now();
                        $form12file->save();
                        foreach($form12 as $item){
                            $item->current_stage='Proof read(Translation)-Computer';
                            $item->comment=$request->comment;
                            $item->save();
                            $proof_details=[
                                'form_name'=>'form12',
                                'language'=>'translate',
                                'ref_number'=>$item->id,
                                'proof_read_by'=>Auth::user()->id,
                                'proof_read_date'=>Carbon::now(),
                            ];
                            $proof_read=new ProofRead($proof_details);
                            $proof_read->save();
                        }
                        DB::commit();
                        return redirect('14th-sentence-file/new-requests')->with('success','Updated Successfully!!');
                        break;
                    case 'proof_read_complete':
                        $form12file->current_stage='Proof read complete';
                        $form12file->proof_read_english=Auth::user()->id;
                        $form12file->proof_read_english_date=Carbon::now();
                        $form12file->save();
                        foreach($form12 as $item){
                            $item->current_stage='Proof read complete';
                            $item->proof_read_complete=Auth::user()->id;
                            $item->proof_read_complete_date=Carbon::now();
                            $item->comment=$request->comment;
                            $item->save();
                        }
                        DB::commit();
                        return redirect('14th-sentence-file/new-requests')->with('success','Updated Successfully!!');
                        break;
                    case 'publication_without_G':
                        $form12file->current_stage='Publication without G';
                        $form12file->publication_without_gazzette=Auth::user()->id;
                        $form12file->publication_without_gazzette_date=Carbon::now();
                        $form12file->save();
                        foreach($form12 as $item){
                            $item->current_stage='Publication without G';
                            $item->gazette_without=1;
                            $item->gazette_without_by=Auth::user()->id;
                            $item->gazette_without_date=Carbon::now();
                            $item->comment=$request->comment;
                            $item->save();
                        }
                        DB::commit();
                        return redirect('14th-sentence-file/new-requests')->with('success','Updated Successfully!!');
                        break;
                    case 'press_without_G':
                        $form12file->current_stage='Gov Press without G';
                        $form12file->gov_press_without_gazette=Auth::user()->id;
                        $form12file->gov_press_without_gazette_date=Carbon::now();
                        $form12file->save();
                        foreach($form12 as $item){
                            $item->current_stage='Gov Press without G';
                            $item->press_without=1;
                            $item->press_without_by=Auth::user()->id;
                            $item->press_without_date=Carbon::now();
                            $item->comment=$request->comment;
                            $item->save();
                        }
                        DB::commit();
                        return redirect('14th-sentence-file/new-requests')->with('success','Updated Successfully!!');
                        break;
                    case 'computer_with_G':
                        $form12file->current_stage='Gazette with G';
                        $form12file->gazette_no=$request->gazzette_no;
                        $form12file->gazette_date=$request->gazzette_date;
                        $form12file->computer_branch_g=Auth::user()->id;
                        $form12file->computer_branch_g_date=Carbon::now();
                        $form12file->save();
                        foreach($form12 as $item){
                            $item->current_stage='Gazette with G';
                            $item->gazetted_no=$request->gazzette_no;
                            $item->gazetted_date=$request->gazzette_date;
                            $item->computer_with=1;
                            $item->computer_with_by=Auth::user()->id;
                            $item->computer_with_date=Carbon::now();
                            $item->comment=$request->comment;
                            $item->save();
                        }
                        DB::commit();
                        return redirect('14th-sentence-file/new-requests')->with('success','Updated Successfully!!');
                        break;
                    case 'publication_with_G':
                        $form12file->current_stage='Publication with G';
                        $form12file->publication_g=Auth::user()->id;
                        $form12file->publication_g_date=Carbon::now();
                        $form12file->save();
                        foreach($form12 as $item){
                            $item->current_stage='Publication with G';
                            $item->gazette_with=1;
                            $item->gazette_with_by=Auth::user()->id;
                            $item->gazette_with_date=Carbon::now();
                            $item->comment=$request->comment;
                            $item->save();
                        }
                        DB::commit();
                        return redirect('14th-sentence-file/new-requests')->with('success','Updated Successfully!!');
                        break;
                    case 'press_with_G':
                        $form12file->current_stage='Gov press with G';
                        $form12file->gov_press_g=Auth::user()->id;
                        $form12file->gov_press_g_date=Carbon::now();
                        $form12file->save();
                        foreach($form12 as $item){
                            $item->current_stage='Gov press with G';
                            $item->sent_gov_press=Auth::user()->id;
                            $item->sent_gov_press_date=Carbon::now();
                            $item->comment=$request->comment;
                            $item->save();
                        }
                        DB::commit();
                        return redirect('14th-sentence-file/new-requests')->with('success','Updated Successfully!!');
                        break;
                    case 'online':
                        $form12file->current_stage='Online Publish';
                        $form12file->gazetted=1;
                        $form12file->gazetted_date=Carbon::now();
                        $form12file->save();
                        foreach($form12 as $item){
                            $item->current_stage='Online Publish';
                            $item->comment=$request->comment;
                            $item->save();
                        }
                        DB::commit();
                        return redirect('14th-sentence-file/new-requests')->with('success','Updated Successfully!!');
                        break;
                }
            }
        }catch(Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->back()->with('error', 'Error!');
        }
    }

    public function downloadNotice($id)
    {
        try
        {
            // $zip = new ZipArchive;
            //$zip->open(public_path()."/upload/sentence12.zip", ZipArchive::CREATE);
            // $zip->open(base_path()."/upload/sentence14.zip", ZipArchive::CREATE);
            $getAllFile=Form14Header::where('computer_branch_officer',Auth::user()->id)->where('ref_no',$id)->where('rejected',0)->orderBy('map_no')->orderBy('block_no')->get();
            $file ="14thsentence".time().".txt";
            //$myfile = fopen(public_path()."/upload/".$file, "a") or die("Unable to open file!");
            $myfile = fopen(public_path()."/upload/".$file, "a") or die("Unable to open file!");
            foreach($getAllFile as $key=>$attibute){

                $form_14Header=DB::table('form_14_header')
                ->leftjoin('gn_divisions','form_14_header.ag_division_id','=','gn_divisions.id')
                ->leftjoin('provinces','form_14_header.province_id','=','provinces.id')
                ->leftjoin('districts','form_14_header.district_id','=','districts.id')
                ->leftjoin('ag_divisions','form_14_header.ag_division_id','=','ag_divisions.id')
                ->select('form_14_header.*','provinces.province_name','provinces.sinhala_name as pvsinhala','districts.districts_name','districts.sinhala_name as drsinhala','gn_divisions.gn_name','gn_divisions.sinhala_name as gnsinhala','ag_divisions.ag_name','ag_divisions.sinhala_name as agsinhala')
                ->where('form_14_header.id',$attibute->id)
                ->first();
                $gn_name_engish=null;
                $gn_name_sinhala=null;
                foreach(explode(',',$form_14Header->gn_division_id) as $str)
                {
                    if($str!="")
                    {
                    $gn=GnDivisions::where('id',$str)->first();
                    $gn_name_engish=$gn_name_engish.','.$gn->gn_name;
                    $gn_name_sinhala=$gn_name_sinhala.','.$gn->sinhala_name;
                    }
                }
                $village_english=null;
                $village_sinhala=null;
                foreach(explode(',',$form_14Header->village_name) as $str){
                    if($str!="")
                    {
                    $gn=Village::where('id',$str)->first();
                    $village_english=$village_english.','.$gn->village;
                    $village_sinhala=$village_sinhala.','.$gn->sinhala_name;
                    }
                }
                $txt1='';
                $string_lotNo='';
                $txt2='';
                if(trans('sentence.lang')=='EN')
                {
                    $form_14Details=Form14Details::Where('form_14_Header_id',$form_14Header->id)->get();
                    foreach($form_14Details as $key=> $element)
                    {
                        if($element->lot_no)
                        {
                            $string_lotNo=$string_lotNo.','.$element->lot_no;
                        }
                    }
                    $string_lotNo=substr($string_lotNo,1);
                    $txt1="REGISTRATION OF TITLE ACT No. 21 OF 1998\r\n\r\nDeclaration of Determination of the Commissioner of Title Settlement under Section 14\r\n\r\nBY virtue of the powers vested in me under Section 14 of the Registration of Title Act, No.21 of 1998, I, the undersigned, hereby declare my determination as set out in the Schedule appended hereto in regard to the  title to parcel of Land No.".$string_lotNo." of Block ".$form_14Header->block_no.", contained in the Cadastral Map No. ".$form_14Header->map_no.", situated in the Village of ".$village_english." within the Grama Niladhari Division of No. ".$gn_name_engish." in the Divisional Secretary's Division of ".$form_14Header->ag_name.", in the District of ".$form_14Header->districts_name.", in the Province of ".$form_14Header->province_name.", referred to in Notice No. ".$form_14Header->file_no." calling for claims to land parcels which was duly published in the Gazette No. ".$form_14Header->gazetted_no."  of ".$form_14Header->gazetted_date." in terms of Section 12 of the Registration of Title Act, No.21 of 1998.\n
K.A.K. Ranjith Dharmapala,\r
Commissioner of Title Settlement.\r
Land Title Settlement Departmenr\r
No 1200/6, Mihikatha Medura,\r
Rajamalwatta Road,\r
Battaramulla\r
xxth xxxx, xxxx\r\n";
fwrite($myfile, $txt1);
                    foreach($form_14Details as $key=> $element)
                    {
                        $nic_array=[];
                        $name_str=str_replace("\r\n","|",$element->name);
                        $nic_str=str_replace("\r\n","|",$element->nic_number);
                        $name_array=explode('|',$name_str);
                        $nic_array=explode('|',$nic_str);
                        $address_string=str_replace("\r\n","|",$element->addres);
                        $address_array=explode('|',$address_string);

                        $compact_name="";
                        $record_count=sizeof($name_array);
                        $current_address=$address_array[0];
                        for($j=0;$j<$record_count;$j++){
                            $compact_name=$compact_name.$name_array[$j]."\r\n";
                            $temp_address=$current_address;
                            if(($j+1)<$record_count){
                                if(strlen(substr($address_array[$j+1],2))>2){
                                    $compact_name=$compact_name."".substr($temp_address,2)."\r\n";
                                    $current_address=$address_array[$j+1];
                                }
                            }else{

                                if(sizeof($address_array)>1){
                                    $compact_name=$compact_name."".substr($temp_address,2)."\r\n";
                                }else
                                    $compact_name=$compact_name."".$temp_address."\r\n";
                            }
                        }
                        $compact_name_arr=explode(" ",$compact_name);
                        $str_max_length=0;
                        foreach($compact_name_arr as $arr_index=>$value){
                                if(strlen($value)>$str_max_length){
                                    $str_max_length=strlen($value);
                                }
                        }
                        $str_max_length=$str_max_length*2;
                        $name_array=[];
                        $name='';$nic_count=0;
                        foreach($compact_name_arr as $l=>$value){
                            if(strstr($value, PHP_EOL)) {
                                $str_val=str_replace("\r\n","|",$value);
                                $str=explode('|',$str_val);
                                $pushed=0;

                                $name=$name.$str[0]." ";

                                array_push($name_array,$name);
                                $name="";
                                $name=$name.$str[1]." ";

                                // if(strlen($name." ".$str[0])<=$str_max_length){
                                //     $name=$name.$str[0]." ";
                                //     $spaces=$str_max_length-strlen($name." ");
                                //     for($i=1;$i<$spaces;$i++){
                                //         $name=$name." ";
                                //     }
                                //     array_push($name_array,$name);
                                //     $name="";

                                // }else{
                                //     array_push($name_array,$name);
                                //     $name="";
                                //     for($i=0;$i<sizeof($str);$i++){
                                //         if(strlen($name.' '.$str[$i])<=$str_max_length){
                                //             $spaces=$str_max_length-strlen($name.' '.$str[$i]);
                                //             $name=$name.$str[$i]." ";
                                //             for($j=0;$j<$spaces;$j++){
                                //                 $name=$name." ";
                                //             }
                                //             array_push($name_array,$name);
                                //             $name="";
                                //         }else{
                                //             array_push($name_array,$name);
                                //             $name="";
                                //             $name=$name.$str[$i]." ";
                                //             if($l==sizeof($compact_name_arr)-1){
                                //                 array_push($name_array,$name);$name="";
                                //             }
                                //         }
                                //     }
                                // }

                            }else{

                                if(strlen($name.' '.$value)>$str_max_length){

                                    array_push($name_array,$name);
                                    $name=$value." ";
                                    if($l==sizeof($compact_name_arr)-1){
                                        array_push($name_array,$name);
                                    }
                                }else{
                                    $name=$name.$value." ";
                                    if($l==sizeof($compact_name_arr)-1){
                                        array_push($name_array,$name);
                                    }
                                }
                            }
                        }
                            if(sizeof(explode('|',$name_str))==1){
                                $name_array[0]=$name_array[0]."\t\t ".$nic_array[0];
                                $nic_count=$nic_count+1;
                            }else{
                                if($nic_count<sizeof($nic_array)){

                                    foreach($name_array as $seq=>$name){
                                        if(preg_match("/^\d*[.]/",$name)==1){
                                            if(substr($name,0,strpos($name,"."))==$nic_count+1){
                                                $name_array[$seq]=$name_array[$seq]."\t\t  ".$nic_array[$nic_count];
                                                $nic_count=$nic_count+1;
                                            }
                                        }

                                    }
                                }

                            }

                        $compact_mortgage_array=explode(" ",$element->mortgages);
                        $mortgage_max_length=0;
                        foreach($compact_mortgage_array as $arr_index=>$value){
                            $str_val=str_replace("\r\n","|",$value);
                            $str=explode('|',$str_val);
                            foreach($str as $string){
                                if(strlen($string)>$mortgage_max_length){
                                    $mortgage_max_length=strlen($string);
                                }
                            }
                        }
                        $mortgage_max_length=$mortgage_max_length*2;
                        $mortgage_array=[];
                        $mortgage='';
                        foreach($compact_mortgage_array as $value){
                            if(strstr($value, PHP_EOL)) {
                                $str_val=str_replace("\r\n","|",$value);
                                $str=explode('|',$str_val);
                                foreach($str as $string){
                                    if(strlen($string)>2){
                                        if(strlen($mortgage.' '.$string)>$mortgage_max_length){

                                            array_push($mortgage_array,$mortgage);
                                            $mortgage=$string." ";
                                        }else{
                                            $mortgage=$mortgage.$string." ";
                                        }
                                    }
                                }
                            }else{
                                if(strlen($mortgage.' '.$value)>$mortgage_max_length){

                                    array_push($mortgage_array,$mortgage);
                                    $mortgage=$value." ";
                                }else{
                                    $mortgage=$mortgage.$value." ";
                                }
                            }
                        }

                        $compact_bondage_array=explode(" ",$element->other_boudages);
                        $bondage_max_length=0;
                        foreach($compact_bondage_array as $arr_index=>$value){
                            $str_val=str_replace("\r\n","|",$value);
                            $str=explode('|',$str_val);
                            foreach($str as $string){
                                if(strlen($string)>$bondage_max_length){
                                    $bondage_max_length=strlen($string);
                                }
                            }
                        }
                        $bondage_max_length=$bondage_max_length*2;
                        $bondage_array=[];
                        $bondage='';
                        foreach($compact_bondage_array as $value){
                            if(strstr($value, PHP_EOL)) {
                                $str_val=str_replace("\r\n","|",$value);
                                $str=explode('|',$str_val);
                                foreach($str as $string){
                                    if(strlen($string)>2){
                                        if(strlen($bondage.' '.$string)>$bondage_max_length){
                                            array_push($bondage_array,$bondage);
                                            $bondage=$string." ";
                                        }else{
                                            $bondage=$bondage.$string." ";
                                        }
                                    }
                                }
                            }else{
                                if(strlen($bondage.' '.$value)>$bondage_max_length){

                                    array_push($bondage_array,$bondage);
                                    $bondage=$value." ";
                                }else{
                                    $bondage=$bondage.$value." ";
                                }
                            }
                        }
                        $arrays=[sizeof($name_array),sizeof($mortgage_array),sizeof($bondage_array)];
                        $max_array=0;
                        $maximum=0;
                        foreach($arrays as $k=>$el){
                            if($el>$maximum){
                                $maximum=$el;
                                $max_array=$k;
                            }
                        }
                        if($max_array==0){
                            $name_str='';
                            // dd($name_array);
                            foreach($name_array as $name_index=>$str){

                                if(sizeof($mortgage_array)>$name_index){
                                    $mort_str=$mortgage_array[$name_index];
                                }else{
                                    $mort_str="\t";
                                }
                                if(sizeof($bondage_array)>$name_index){
                                    $bond_str=$bondage_array[$name_index];
                                }else{
                                    $bond_str="\t";
                                }
                                if($name_str==''){
                                    $name_str=$str."\t    ".$element->ownership_type."   ".$element->class."    ".(($mort_str!="\t")?$mort_str:"-".$mort_str)."\t\t\t    ".(($bond_str!="\t")?$bond_str:"-".$bond_str)."\r\n";
                                }else{
                                    $name_str=$name_str."\t\t\t          ".$str."\t\t\t\t\t\t     ".$mort_str."\t\t\t    ".$bond_str."\r\n";
                                }
                            }
                        }
                        if($max_array==1){
                            $name_str='';
                            foreach($mortgage_array as $name_index=>$str){
                                if(sizeof($nic_array)>$name_index){
                                    $nic_str=$nic_array[$name_index];
                                }else{
                                    $nic_str="\t";
                                }
                                if(sizeof($name_array)>$name_index){
                                    $mort_str=$name_array[$name_index];
                                }else{
                                    $mort_str="\t\t\t     ";
                                }
                                if(sizeof($bondage_array)>$name_index){
                                    $bond_str=$bondage_array[$name_index];
                                }else{
                                    $bond_str="\t";
                                }
                                if($name_str==''){
                                    $name_str=$mort_str."\t    ".$element->ownership_type."   ".$element->class."    ".$str."\t\t\t    ".(($bond_str!="\t")?$bond_str:"-".$bond_str)."\r\n";
                                }else{
                                    $name_str=$name_str."\t\t\t          ".$mort_str."\t\t\t\t\t\t     ".$str."\t\t\t    ".$bond_str."\r\n";
                                }
                            }
                        }

                        if($max_array==2){
                            $name_str='';
                            foreach($bondage_array as $name_index=>$str){
                                if(sizeof($nic_array)>$name_index){
                                    $nic_str=$nic_array[$name_index];
                                }else{
                                    $nic_str="\t";
                                }
                                if(sizeof($mortgage_array)>$name_index){
                                    $mort_str=$mortgage_array[$name_index];
                                }else{
                                    $mort_str="\t";
                                }
                                if(sizeof($name_array)>$name_index){
                                    $bond_str=$name_array[$name_index];
                                }else{
                                    $bond_str="\t\t\t     ";
                                }
                                if($name_str==''){
                                    $name_str=$bond_str."\t     ".$element->ownership_type."	".$element->class."	   ".(($mort_str!="\t")?$mort_str:"-".$mort_str)."\t\t\t    ".$str."\r\n";
                                }else{
                                    $name_str=$name_str."\t\t\t          ".$bond_str."\t\t\t\t\t\t     ".$nic_str."\t\t\t    ".$mort_str."\t\t\t    ".$str."\r\n";
                                }
                            }
                        }




                        // $address_string='';
                        // foreach($address_array as $str){
                        //     if($address_string==""){
                        //         $address_string=$str;
                        //     }else{
                        //         $address_string=$address_string."\r\n\t\t\t".$str;
                        //     }
                        // }
                        // $name_str=$name_str."\t\t\t".$address_string;

                        $txt2="\r\n"
                        ."\r\n\t".$element->lot_no." \t".$element->size."     ".$name_str;
                        fwrite($myfile, $txt2);
                    }
                }
                else
                {
                    $form_14Details=Form14Details::Where('form_14_Header_id',$form_14Header->id)->get();
                    foreach($form_14Details as $key=> $element)
                    {
                        if($element->lot_no)
                        {
                            $string_lotNo=$string_lotNo.','.$element->lot_no;
                        }
                    }
                    $string_lotNo=substr($string_lotNo,1);
                    $txt1="1998 අංක 21 දරන හිමිකම් ලියාපදිංචි කිරීමේ පනත\r\n\r\n14 වැනි වගන්තිය යටතේ හිමිකම් නිරවුල් කිරීමේ කොමසාරිස්ගේ තීරණ ප්‍රකාශය\r\n\r\n".$form_14Header->pvsinhala." පළාතේ ".$form_14Header->drsinhala." දිස්ත්‍රික්කයේ ".$form_14Header->agsinhala." ප්‍රාදේශීය ලේකම් කොට්ඨාසයේ ".$gn_name_sinhala." ග්‍රාම නිලධාරි කොට්ඨාසය තුළ ".$village_sinhala."  නමැති ගමේ පිහිටියා වූ ද, අංක ".$form_14Header->map_no." දරන කැඩැස්තර සිතියමේ කලාප අංක ".$form_14Header->block_no." හි කැබලි අංක ".$string_lotNo." දරන ඉඩම් කොටස ලෙස පෙන්නුම් කොට ඇත්තා වූ ද, හිමිකම් පෑම් ඉදිරිපත් කරන ලෙස දැනුම් දෙමින් 1998 අංක 21 දරන හිමිකම් ලියාපදිංචි කිරීමේ පනතේ 12 වැනි වගන්තිය ප්‍රකාර XXXX.XX.XX වැනි දින අංක ".$form_14Header->gazetted_no." දරන ගැසට් පත්‍රයේ යථා පරිදි පළකරන ලද අංක ".$form_14Header->file_no." දරන දැන්වීමේ සඳහන් කොට ඇත්තා වූ ද, ඉඩම් කොටස්වල අයිතිය සම්බන්ධයෙන් මෙහි උපලේඛනයේ දැක්වෙන මාගේ තීරණ 1998 අංක 21 දරන හිමිකම් ලියාපදිංචි කිරීමේ පනතේ 14 වැනි වගන්තියෙන් පහත අත්සන් කරන මා වෙත පවරා ඇති බලතල ප්‍රකාර, මම මෙයින් ප්‍රකාශ කරම\r\n
කේ.ඒ.කේ. රංජිත් ධර්මපාල,\r
හිමිකම් නිරවුල් කිරීමේ කොමසාරිස්.\r
2018 ඔක්තෝබර් මස 15 වැනි දින,\r
මිහිකත මැදුර, අංක 1200/6,\r
රජමල්වත්ත පාර,\r
බත්තරමුල්ල\r
ඉඩම් හිමිකම් නිරවුල් කිරීමේ දෙපාර්තමේන්තුවේ දී ය.\r\n\r\n";

fwrite($myfile, $txt1);
                    foreach($form_14Details as $key=> $element)
                    {

                        $ownership='-';
                        $land_class='-';
                        if($element->ownership_type=='full'){
                            $ownership='සම්පූර්ණ අයිතිය';
                        }elseif($element->ownership_type=='Equal'){
                            $ownership='සමාන අයිතිය';
                        }else{
                            $ownership='විෂම අයිතිය';
                        }
                        if($element->class=='1st_Class'){
                            $land_class='පළමු වන පන්තිය';
                        }else{
                            $land_class='දෙවෙනි පන්තිය';
                        }

                        $nic_array=[];
                        $name_str=str_replace("\r\n","|",$element->name);
                        $nic_str=str_replace("\r\n","|",$element->nic_number);
                        $name_array=explode('|',$name_str);
                        $nic_array=explode('|',$nic_str);
                        $address_string=str_replace("\r\n","|",$element->addres);
                        $address_array=explode('|',$address_string);

                        $compact_name="";
                        $record_count=sizeof($name_array);
                        $current_address=$address_array[0];
                        for($j=0;$j<$record_count;$j++){
                            $compact_name=$compact_name.$name_array[$j]."\r\n";
                            $temp_address=$current_address;
                            if(($j+1)<$record_count){
                                if(strlen(substr($address_array[$j+1],2))>2){
                                    $compact_name=$compact_name."".substr($temp_address,2)."\r\n";
                                    $current_address=$address_array[$j+1];
                                }
                            }else{

                                if(sizeof($address_array)>1){
                                    $compact_name=$compact_name."".substr($temp_address,2)."\r\n";
                                }else
                                    $compact_name=$compact_name."".$temp_address."\r\n";
                            }
                        }
                        $compact_name_arr=explode(" ",$compact_name);
                        $str_max_length=0;
                        foreach($compact_name_arr as $arr_index=>$value){
                                if(strlen($value)>$str_max_length){
                                    $str_max_length=strlen($value);
                                }
                        }
                        $str_max_length=$str_max_length*2;
                        $name_array=[];
                        $name='';$nic_count=0;
                        foreach($compact_name_arr as $l=>$value){
                            if(strstr($value, PHP_EOL)) {
                                $str_val=str_replace("\r\n","|",$value);
                                $str=explode('|',$str_val);
                                $pushed=0;

                                $name=$name.$str[0]." ";

                                array_push($name_array,$name);
                                $name="";
                                $name=$name.$str[1]." ";

                                // if(strlen($name." ".$str[0])<=$str_max_length){
                                //     $name=$name.$str[0]." ";
                                //     $spaces=$str_max_length-strlen($name." ");
                                //     for($i=1;$i<$spaces;$i++){
                                //         $name=$name." ";
                                //     }
                                //     array_push($name_array,$name);
                                //     $name="";

                                // }else{
                                //     array_push($name_array,$name);
                                //     $name="";
                                //     for($i=0;$i<sizeof($str);$i++){
                                //         if(strlen($name.' '.$str[$i])<=$str_max_length){
                                //             $spaces=$str_max_length-strlen($name.' '.$str[$i]);
                                //             $name=$name.$str[$i]." ";
                                //             for($j=0;$j<$spaces;$j++){
                                //                 $name=$name." ";
                                //             }
                                //             array_push($name_array,$name);
                                //             $name="";
                                //         }else{
                                //             array_push($name_array,$name);
                                //             $name="";
                                //             $name=$name.$str[$i]." ";
                                //             if($l==sizeof($compact_name_arr)-1){
                                //                 array_push($name_array,$name);$name="";
                                //             }
                                //         }
                                //     }
                                // }

                            }else{

                                if(strlen($name.' '.$value)>$str_max_length){

                                    array_push($name_array,$name);
                                    $name=$value." ";
                                    if($l==sizeof($compact_name_arr)-1){
                                        array_push($name_array,$name);
                                    }
                                }else{
                                    $name=$name.$value." ";
                                    if($l==sizeof($compact_name_arr)-1){
                                        array_push($name_array,$name);
                                    }
                                }
                            }
                        }
                            if(sizeof(explode('|',$name_str))==1){
                                $name_array[0]=$name_array[0]."\t\t ".$nic_array[0];
                                $nic_count=$nic_count+1;
                            }else{
                                if($nic_count<sizeof($nic_array)){

                                    foreach($name_array as $seq=>$name){
                                        if(preg_match("/^\d*[.]/",$name)==1){
                                            if(substr($name,0,strpos($name,"."))==$nic_count+1){
                                                $name_array[$seq]=$name_array[$seq]."\t\t  ".$nic_array[$nic_count];
                                                $nic_count=$nic_count+1;
                                            }
                                        }

                                    }
                                }

                            }

                        $compact_mortgage_array=explode(" ",$element->mortgages);
                        $mortgage_max_length=0;
                        foreach($compact_mortgage_array as $arr_index=>$value){
                            $str_val=str_replace("\r\n","|",$value);
                            $str=explode('|',$str_val);
                            foreach($str as $string){
                                if(strlen($string)>$mortgage_max_length){
                                    $mortgage_max_length=strlen($string);
                                }
                            }
                        }
                        $mortgage_max_length=$mortgage_max_length*2;
                        $mortgage_array=[];
                        $mortgage='';
                        foreach($compact_mortgage_array as $value){
                            if(strstr($value, PHP_EOL)) {
                                $str_val=str_replace("\r\n","|",$value);
                                $str=explode('|',$str_val);
                                foreach($str as $string){
                                    if(strlen($string)>2){
                                        if(strlen($mortgage.' '.$string)>$mortgage_max_length){

                                            array_push($mortgage_array,$mortgage);
                                            $mortgage=$string." ";
                                        }else{
                                            $mortgage=$mortgage.$string." ";
                                        }
                                    }
                                }
                            }else{
                                if(strlen($mortgage.' '.$value)>$mortgage_max_length){

                                    array_push($mortgage_array,$mortgage);
                                    $mortgage=$value." ";
                                }else{
                                    $mortgage=$mortgage.$value." ";
                                }
                            }
                        }

                        $compact_bondage_array=explode(" ",$element->other_boudages);
                        $bondage_max_length=0;
                        foreach($compact_bondage_array as $arr_index=>$value){
                            $str_val=str_replace("\r\n","|",$value);
                            $str=explode('|',$str_val);
                            foreach($str as $string){
                                if(strlen($string)>$bondage_max_length){
                                    $bondage_max_length=strlen($string);
                                }
                            }
                        }
                        $bondage_max_length=$bondage_max_length*2;
                        $bondage_array=[];
                        $bondage='';
                        foreach($compact_bondage_array as $value){
                            if(strstr($value, PHP_EOL)) {
                                $str_val=str_replace("\r\n","|",$value);
                                $str=explode('|',$str_val);
                                foreach($str as $string){
                                    if(strlen($string)>2){
                                        if(strlen($bondage.' '.$string)>$bondage_max_length){
                                            array_push($bondage_array,$bondage);
                                            $bondage=$string." ";
                                        }else{
                                            $bondage=$bondage.$string." ";
                                        }
                                    }
                                }
                            }else{
                                if(strlen($bondage.' '.$value)>$bondage_max_length){

                                    array_push($bondage_array,$bondage);
                                    $bondage=$value." ";
                                }else{
                                    $bondage=$bondage.$value." ";
                                }
                            }
                        }
                        $arrays=[sizeof($name_array),sizeof($mortgage_array),sizeof($bondage_array)];
                        $max_array=0;
                        $maximum=0;
                        foreach($arrays as $k=>$el){
                            if($el>$maximum){
                                $maximum=$el;
                                $max_array=$k;
                            }
                        }
                        if($max_array==0){
                            $name_str='';
                            // dd($name_array);
                            foreach($name_array as $name_index=>$str){

                                if(sizeof($mortgage_array)>$name_index){
                                    $mort_str=$mortgage_array[$name_index];
                                }else{
                                    $mort_str="\t";
                                }
                                if(sizeof($bondage_array)>$name_index){
                                    $bond_str=$bondage_array[$name_index];
                                }else{
                                    $bond_str="\t";
                                }
                                if($name_str==''){
                                    $name_str=$str."\t    ".$ownership."   ".$land_class."    ".(($mort_str!="\t")?$mort_str:"-".$mort_str)."\t\t\t    ".(($bond_str!="\t")?$bond_str:"-".$bond_str)."\r\n";
                                }else{
                                    $name_str=$name_str."\t\t\t          ".$str."\t\t\t\t\t\t     ".$mort_str."\t\t\t    ".$bond_str."\r\n";
                                }
                            }
                        }
                        if($max_array==1){
                            $name_str='';
                            foreach($mortgage_array as $name_index=>$str){
                                if(sizeof($nic_array)>$name_index){
                                    $nic_str=$nic_array[$name_index];
                                }else{
                                    $nic_str="\t";
                                }
                                if(sizeof($name_array)>$name_index){
                                    $mort_str=$name_array[$name_index];
                                }else{
                                    $mort_str="\t\t\t     ";
                                }
                                if(sizeof($bondage_array)>$name_index){
                                    $bond_str=$bondage_array[$name_index];
                                }else{
                                    $bond_str="\t";
                                }
                                if($name_str==''){
                                    $name_str=$mort_str."\t    ".$ownership."   ".$land_class."    ".$str."\t\t\t    ".(($bond_str!="\t")?$bond_str:"-".$bond_str)."\r\n";
                                }else{
                                    $name_str=$name_str."\t\t\t          ".$mort_str."\t\t\t\t\t\t     ".$str."\t\t\t    ".$bond_str."\r\n";
                                }
                            }
                        }

                        if($max_array==2){
                            $name_str='';
                            foreach($bondage_array as $name_index=>$str){
                                if(sizeof($nic_array)>$name_index){
                                    $nic_str=$nic_array[$name_index];
                                }else{
                                    $nic_str="\t";
                                }
                                if(sizeof($mortgage_array)>$name_index){
                                    $mort_str=$mortgage_array[$name_index];
                                }else{
                                    $mort_str="\t";
                                }
                                if(sizeof($name_array)>$name_index){
                                    $bond_str=$name_array[$name_index];
                                }else{
                                    $bond_str="\t\t\t     ";
                                }
                                if($name_str==''){
                                    $name_str=$bond_str."\t     ".$ownership."	".$land_class."	   ".(($mort_str!="\t")?$mort_str:"-".$mort_str)."\t\t\t    ".$str."\r\n";
                                }else{
                                    $name_str=$name_str."\t\t\t          ".$bond_str."\t\t\t\t\t\t     ".$nic_str."\t\t\t    ".$mort_str."\t\t\t    ".$str."\r\n";
                                }
                            }
                        }

                        $txt2="\r\n"
                        ."\r\n\t".$element->lot_no." \t".$element->size."  ".$name_str;
                        fwrite($myfile, $txt2);
                    }
                }
                fwrite($myfile, "\r\n\r\n\r\n".$form_14Header->file_no."\r\n\r\n\r\n");

                }

                fclose($myfile);

            //return response()->download(public_path("/upload/sentence12.zip"));
            // fclose($myfile);
            return response()->download(public_path("/upload/".$file));
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
