<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\Models\Form12;
use App\Models\Form12File;
use Auth;
use App\Traits\Permissions;
use App\Models\UserRolePermissions;
use App\Models\ProofRead;
use App\Models\GnDivisions;
use App\Models\Village;
use App\User;
use DataTables;
use ZipArchive;
class Form12FileController extends Controller
{
    use Permissions;
    public function create_file(Request $request){
        try{
            DB::beginTransaction();
            if(isset($request->file)){
                $form12_info=Form12::find($request->existing_id);
                $form12_info->ref_no2=$request->file;
                $form12_info->save();
            }else{
                $code=$this->generate_referencing_code();
                $file_info=['code'=>$code,'created_by'=>Auth::user()->id,'current_stage'=>'Publication verify'];
                $form12_file=new Form12File($file_info);
                $form12_file->save();

                $form12_info=Form12::find($request->new_id);
                $form12_info->ref_no2=$form12_file->id;
                $form12_info->save();
            }
            DB::commit();
            return redirect()->back()->with('success', 'Created Successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->back()->with('error', $e);
        }
    }

    public function generate_referencing_code(){
        $ref2_no=0;
        $now = Carbon::now();
        $current_year=$now->year;
        $year_code=null;
        $sentence_code=null;
        $max_reference=DB::select("select form_12_file.code from form_12 inner join form_12_file on form_12.ref_no2=form_12_file.id where form_12_file.code like '%".substr($current_year,2,2)."%' ORDER BY RIGHT(form_12_file.code, 5) DESC");

        if(sizeof($max_reference)==0)
        {
            $ref2_no=0;
        }
        else
        {
            $last_ref_no=$max_reference[0]->code;
            $last_ref_no=Form12File::where('code',$last_ref_no)->first();
            list($year_code,$sentence_code,$ref2_no) = explode('/', $last_ref_no->code);
        }
        $ref2_no=substr($current_year,2,2).'/12/'.sprintf('%05d', intval($ref2_no) + 1);
        return $ref2_no;
    }

    public function get_new_requests(){
        return view('pages.form12.form12_file_index');
    }

    public function newlist(){
        $pending=collect([]);
            $create=collect([]);$reg_verify=collect([]);$reg_approve=collect([]);$pub_verify=collect([]);$asst_comm=collect([]);$bim_comm=collect([]);
            $comm_gen=collect([]);$computer=collect([]);$proof_sinhala=collect([]);$computer_sinhala=collect([]);$proof_translate=collect([]);$computer_translate=collect([]);
            $pub_without=collect([]);$press_without=collect([]);

        if($this->getAccessPubVerify(request()->segment(1))=='Yes'){
            $pub_verify=Form12File::where('current_stage','Publication verify')->where('is_archived',0)->get();
        }
        if($this->getAccessAsstComm(request()->segment(1))=='Yes'){
            $asst_comm=Form12File::where('current_stage','Assistant commisioner')->where('is_archived',0)->get();
        }
        if($this->getAccessBimsaviyaComm(request()->segment(1))=='Yes'){
            $bim_comm=Form12File::where('current_stage','Bimsaviya commisioner')->where('is_archived',0)->get();
        }
        if($this->getAccessCommGen(request()->segment(1))=='Yes'){
            $comm_gen=Form12File::where('current_stage','Commissioner general')->where('is_archived',0)->get();
        }
        if($this->getAccessForwardProof(request()->segment(1))=='Yes'){
            $computer=Form12File::where('computer_branch_officer',Auth::user()->id)->where('is_archived',0)->where(function($q){
                $q->where('current_stage','Computer branch')->orWhere('current_stage','Proof read(Sinhala)-Computer')->orWhere('current_stage','Proof read(Translation)-Computer')->orWhere('current_stage','Proof read complete')->orWhere('current_stage','Gazette with G');
            })->get();
        }
        if($this->getAccessProof(request()->segment(1))=='Yes'){
            $proof_sinhala=Form12File::where('current_stage','Proof read(Sinhala)')->where('is_archived',0)->get();
        }
        if($this->getAccessForwardTransProof(request()->segment(1))=='Yes'){
            $computer_sinhala=Form12File::where('computer_branch_officer',Auth::user()->id)->where('current_stage','Proof read(Translation)-Computer')->where('is_archived',0)->get();
        }
        if($this->getAccessTransProof(request()->segment(1))=='Yes'){
            $proof_translate=Form12File::where('current_stage','Proof read(Translates)')->where('is_archived',0)->get();
        }
        if($this->getAccessForwardPublication(request()->segment(1))=='Yes'){
            $computer_translate=Form12File::where('computer_branch_officer',Auth::user()->id)->Where('current_stage','Gazette with G')->where('is_archived',0)->get();
        }
        if($this->getAccessForwardPress(request()->segment(1))=='Yes'){
            $pub_without=Form12File::where('current_stage','Publication without G')->orWhere('current_stage','Publication with G')->orWhere('current_stage','Gov press with G')->where('is_archived',0)->get();
        }
        if($this->getAccessGazette(request()->segment(1))=='Yes'){
            $press_without=Form12File::where('current_stage','Gov Press without G')->where('is_archived',0)->get();
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
        $form12file=Form12File::find($id);
        view()->share('form12',$form12file);
        $form12=Form12::where('ref_no2',$id)->where('rejected',0)->get();
        view()->share('form12_records',$form12);
        $computer_officers=User::whereIn('role_code',UserRolePermissions::select('role_code')->where('is_enable',1)->where('module_code',request()->segment(1))->where('can_forward_to_proof',1)->distinct('module_code')->get())->get();
        view()->share('computer_officers',$computer_officers);
        return view('pages.form12.form12_file_data');
    }

    public function update(Request $request,$id){
        try{
            $form12file=Form12File::find($id);
            $form12=Form12::where('ref_no2',$id)->where('rejected',0)->get();
            if($request->button=='archive'){
                $form12file->is_archived=1;
                $form12file->save();
                DB::commit();

                return redirect('12th-sentence-file/new-requests')->with('success','Updated Successfully!!');
            }else{
                switch($request->button){
                    case 'forward_asst_comm':
                        $form12file->current_stage='Assistant commisioner';
                        $form12file->publication_branch=1;
                        $form12file->publication_branch_date=Carbon::now();
                        $form12file->publication_checked_by=Auth::user()->id;
                        $form12file->save();
                        foreach($form12 as $item){
                            $item->current_stage='Assistant commisioner';
                            $item->publication_branch=1;
                            $item->publication_branch_date=Carbon::now();
                            $item->publication_checked_by=Auth::user()->id;
                            $item->comment=$request->comment;
                            $item->save();
                        }
                        DB::commit();
                        return redirect('12th-sentence-file/new-requests')->with('success','Updated Successfully!!');
                        break;
                    case 'forward_bim_comm':
                        $form12file->current_stage='Bimsaviya commisioner';
                        $form12file->computer_branch_officer=$request->computer_officer;
                        $form12file->asst_commissioner_approval=Auth::user()->id;
                        $form12file->asst_commissioner_approval_date=Carbon::now();
                        $form12file->save();
                        foreach($form12 as $item){
                            $item->current_stage='Bimsaviya commisioner';
                            $item->computer_branch_officer=$request->computer_officer;
                            $item->asst_comm_approval=Auth::user()->id;
                            $item->asst_comm_approval_date=Carbon::now();
                            $item->comment=$request->comment;
                            $item->save();
                        }
                        DB::commit();
                        return redirect('12th-sentence-file/new-requests')->with('success','Updated Successfully!!');
                        break;
                    case 'forward_comm_general':
                        $form12file->current_stage='Commissioner general';
                        $form12file->bim_approval=Auth::user()->id;
                        $form12file->bim_approval_date=Carbon::now();
                        $form12file->save();
                        foreach($form12 as $item){
                            $item->current_stage='Commissioner general';
                            $item->bimsaviya_approval=Auth::user()->id;
                            $item->bimsaviya_approval_date=Carbon::now();
                            $item->comment=$request->comment;
                            $item->save();
                        }
                        DB::commit();
                        return redirect('12th-sentence-file/new-requests')->with('success','Updated Successfully!!');
                        break;
                    case 'comm_general_approval':
                        $form12file->current_stage='Computer branch';
                        $form12file->comm_gen_approval=Auth::user()->id;
                        $form12file->comm_gen_approval_date=Carbon::now();
                        $form12file->save();
                        foreach($form12 as $item){
                            $item->current_stage='Computer branch';
                            $item->comm_gen_approval=Auth::user()->id;
                            $item->comm_gen_approal_date=Carbon::now();
                            $item->comment=$request->comment;
                            $item->save();
                        }
                        DB::commit();
                        return redirect('12th-sentence-file/new-requests')->with('success','Updated Successfully!!');
                        break;
                    case 'forward_to_proof_read':
                        $form12file->current_stage='Proof read(Sinhala)';
                        $form12file->computer_branch=1;
                        $form12file->computer_branch_date=Carbon::now();
                        $form12file->save();
                        foreach($form12 as $item){
                            $item->current_stage='Proof read(Sinhala)';
                            $item->computer_branch=1;
                            $item->computer_branch_date=Carbon::now();
                            $item->computer_checked_by=Auth::user()->id;
                            $item->comment=$request->comment;
                            $item->save();
                        }
                        DB::commit();
                        return redirect('12th-sentence-file/new-requests')->with('success','Updated Successfully!!');
                        break;
                    case 'proof_read_sinhala':
                        $form12file->current_stage='Proof read(Sinhala)-Computer';
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
                        return redirect('12th-sentence-file/new-requests')->with('success','Updated Successfully!!');
                        break;
                    case 'forward_to_proof_read_translation':
                        $form12file->current_stage='Proof read(Translates)';
                        $form12file->save();
                        foreach($form12 as $item){
                            $item->current_stage='Proof read(Translates)';
                            $item->comment=$request->comment;
                            $item->save();
                        }
                        DB::commit();
                        return redirect('12th-sentence-file/new-requests')->with('success','Updated Successfully!!');
                        break;
                    case 'proof_read_translation':
                        $form12file->current_stage='Proof read(Translation)-Computer';
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
                        return redirect('12th-sentence-file/new-requests')->with('success','Updated Successfully!!');
                        break;
                    case 'proof_read_complete':
                        $form12file->current_stage='Proof read complete';
                        $form12file->proof_read_sinhala=Auth::user()->id;
                        $form12file->proof_read_sinhala_date=Carbon::now();
                        $form12file->save();
                        foreach($form12 as $item){
                            $item->current_stage='Proof read complete';
                            $item->first_proof_read=1;
                            $item->first_proof_read_by=Auth::user()->id;
                            $item->first_proof_read_date=Carbon::now();
                            $item->comment=$request->comment;
                            $item->save();
                        }
                        DB::commit();
                        return redirect('12th-sentence-file/new-requests')->with('success','Updated Successfully!!');
                        break;
                    case 'publication_without_G':
                        $form12file->current_stage='Publication without G';
                        $form12file->publication_without_gazette=Auth::user()->id;
                        $form12file->publication_without_gazzete_date=Carbon::now();
                        $form12file->save();
                        foreach($form12 as $item){
                            $item->current_stage='Publication without G';
                            $item->second_proof_read=1;
                            $item->second_proof_read_by=Auth::user()->id;
                            $item->second_proof_read_date=Carbon::now();
                            $item->comment=$request->comment;
                            $item->save();
                        }
                        DB::commit();
                        return redirect('12th-sentence-file/new-requests')->with('success','Updated Successfully!!');
                        break;
                    case 'press_without_G':
                        $form12file->current_stage='Gov Press without G';
                        $form12file->gov_press_without_gazzette=Auth::user()->id;
                        $form12file->gov_press_without_gazzette_date=Carbon::now();
                        $form12file->save();
                        foreach($form12 as $item){
                            $item->current_stage='Gov Press without G';
                            $item->first_proof_english=1;
                            $item->first_proof_english_by=Auth::user()->id;
                            $item->first_proof_english_date=Carbon::now();
                            $item->comment=$request->comment;
                            $item->save();
                        }
                        DB::commit();
                        return redirect('12th-sentence-file/new-requests')->with('success','Updated Successfully!!');
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
                            $item->gazette_no=$request->gazzette_no;
                            $item->gazette_date=$request->gazzette_date;
                            $item->second_proof_english=1;
                            $item->second_proof_english_by=Auth::user()->id;
                            $item->second_proof_english_date=Carbon::now();
                            $item->comment=$request->comment;
                            $item->save();
                        }
                        DB::commit();
                        return redirect('12th-sentence-file/new-requests')->with('success','Updated Successfully!!');
                        break;
                    case 'publication_with_G':
                        $form12file->current_stage='Publication with G';
                        $form12file->publication_g=Auth::user()->id;
                        $form12file->publication_g_date=Carbon::now();
                        $form12file->save();
                        foreach($form12 as $item){
                            $item->current_stage='Publication with G';
                            $item->first_proof_read_tamil=1;
                            $item->first_proof_read_tamil_by=Auth::user()->id;
                            $item->first_proof_read_tamil_date=Carbon::now();
                            $item->comment=$request->comment;
                            $item->save();
                        }
                        DB::commit();
                        return redirect('12th-sentence-file/new-requests')->with('success','Updated Successfully!!');
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
                        return redirect('12th-sentence-file/new-requests')->with('success','Updated Successfully!!');
                        break;
                    case 'online':
                        $form12file->current_stage='Online Publish';
                        $form12file->save();
                        foreach($form12 as $item){
                            $item->current_stage='Online Publish';
                            $item->comment=$request->comment;
                            $item->save();
                        }
                        DB::commit();
                        return redirect('12th-sentence-file/new-requests')->with('success','Updated Successfully!!');
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
            // $zip->open(base_path()."/upload/sentence12.zip", ZipArchive::CREATE);
            $getAllFile=Form12::where('computer_branch_officer',Auth::user()->id)->where('ref_no2',$id)->where('rejected',0)->orderBy('map_no')->orderBy('block_no')->get();
            $file ="12thsentence".time().".txt";
            //$myfile = fopen(public_path()."/upload/".$file, "a") or die("Unable to open file!");
			$myfile = fopen(public_path()."/upload/".$file, "a") or die("Unable to open file!");
            foreach($getAllFile as $key=>$attibute){
                
                $form_12=DB::table('form_12')
                    ->leftjoin('provinces','form_12.province_id','=','provinces.id')
                    ->leftjoin('districts','form_12.district_id','=','districts.id')
                    ->leftjoin('ag_divisions','form_12.gn_division','=','ag_divisions.id')
                    ->select('form_12.*','provinces.province_name','districts.districts_name','ag_divisions.ag_name','districts.sinhala_name as dissinhala','ag_divisions.sinhala_name as agsinhala','provinces.sinhala_name as prosinhala')
                    ->where('form_12.id',$attibute->id)
                    ->first();
                $gn_name_engish=null;
                $gn_name_sinhala=null;
                foreach(explode(',',$form_12->gn_division) as $str){
                    if($str!=""){
                        $gn=GnDivisions::where('id',$str)->first();
                        $gn_name_engish=$gn_name_engish.','.$gn->gn_name;
                        $gn_name_sinhala=$gn_name_sinhala.','.$gn->sinhala_name;
                    }
                }

                $village_english=null;
                $village_sinhala=null;
                foreach(explode(',',$form_12->village) as $str){
                    if($str!=""){
                        $gn=Village::where('id',$str)->first();
                        $village_english=$village_english.','.$gn->village;
                        $village_sinhala=$village_sinhala.','.$gn->sinhala_name;
                    }
                }

                if(trans('sentence.lang')=='EN'){
                    $txt="NOTICE CALLING FOR CLAIMS TO LAND PARCELS.
                    \n
                    Registration  of  Title Act, No.  21 of  1998\n
                    (Section 12)\n
                    \n
                    NOTICE No : ".$form_12->file_no." ".$form_12->districts_name." District
                    \n
                    It is hereby notified that any person having a claim to the owner-ship or possession or interest to one or more of the parcels of lands reflected in the Cadastral Map No.".$form_12->map_no." made by the Surveyor-General under Section 11 of the Registration of Title Act, No. 21 of 1998 relating to the village of ". $village_english ."or any part thereof, situated within the Grama Niladhari Division of No.".$gn_name_engish. " the Divisional Secretariat Division of".$form_12->districts_name. " in the District of ".$form_12->districts_name." in the Province of ".$form_12->province_name ." submit his claim to the undersigned before XXth XXXXX,XXXX In the event of failure  to submit any claim before the above-mentioned date, action will be taken to hold an exparte inquiry to determine title to the land and accordingly publish such determination in the Government Gazette in terms of Section 14 of the above Act.
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
                    xxth xxxx, xxxx \n\n";
                }else{
                    $txt="ඉඩම් කැබලි වලට හිමිකම්පෑම් ඉදිරිපත් කරන ලෙස දැනුම්දීම.
                    \n
                    1998 අංක 21 දරන හිමිකම් ලියාපදිංචි කිරීමේ පනත\n
                    (12 වැනි වගන්තිය)
                    \n
                    දැන්වීම් අංක :". $form_12->ref_no." ".$form_12->dissinhala ." දිස්ත්‍රික්කය
                    \n
                    ".$form_12->prosinhala." පළාතේ ".$form_12->dissinhala ." දිස්ත්‍රික්කයේ ".$form_12->agsinhala." ප්‍රාදේශීය ලේකම් කොට්ඨාශයේ".$gn_name_sinhala." ග්‍රාම නිලධාරි කොට්ඨාශය තුළ පිහිටි ".$village_sinhala. " ගමට හෝ ඉන් කොටසකට හෝ කළාප අංක ".$form_12->block_no. " ට අදාලව 1998 අංක 21 දරන හිමිකම් ලියාපදිංචි කිරීමේ පනතේ 11 වැනි වගන්තිය යටතේ මිනුම්පතිවරයා විසින් සාදන ලද අංක ".$form_12->map_no." දරන කැඩැස්තර සිතියමේ දක්වා ඇති ඉඩම් කොටස් එකක හෝ ඊට වැඩි ගණනක හෝ අයිතියට නැතහොත් සන්තකයට හෝ සම්බන්ධතාවයකට හිමිකම් පාන කවර වුවද තැනැත්තෙකු විසින් තම හිමිකම්පෑම xxxx xxxxx xx වැනි දිනට පෙර, පහත අත්සන් කරන අය වෙත ඉදිරිපත් කළ යුතුය. එකී දිනට පෙර යම් හිමිකම් පෑමක් ඉදිරිපත් කිරීම පැහැර හරිනු ලැබුවහොත්, ඒ ඉඩමේ අයිතිය සම්බන්ධයෙන් ඒක පාක්ෂික පරීක්ෂණයක් පවත්වනු ලබන අතර, එහි දී ගනු ලබන තීරණය ඉහත සඳහන් පනතේ 14 වැනි වගන්තිය ප්‍රකාර ගැසට් පත්‍රයේ පළ කරනු ලැබේ‍.
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
                    ඉඩම් හිමිකම් නිරවුල් කිරීමේ දෙපාර්තමේන්තුවේ දී ය.\n\n";
                }
                fwrite($myfile, $txt);
                
                // $txtfiles = glob(public_path('upload/'.$file));
                //$zip->addFile(public_path()."/upload/".$file);
                // $zip->addFile(base_path()."/upload/".$file);
                // \Zipper::make(public_path()."/upload/form12.zip")->add($txtfiles)->close();
                // // $txtfiles = glob(base_path('upload/'.$file));
                // // \Zipper::make(base_path()."/upload/form12.zip")->add($txtfiles)->close();
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
