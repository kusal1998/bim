<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Form14Header;
use App\Models\Form14Details;
use App\Models\Form14File;
use App\Models\AmendmentsFile;
use App\Models\AmendmentsHeader;
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

class AmendmentFileController extends Controller
{
    use Permissions;
    public function create_file(Request $request){
        error_log($request->new_id);
        try{
            DB::beginTransaction();
            if(isset($request->file)){
                $form12_info=AmendmentsHeader::find($request->existing_id);
                $form12_info->ref_no=$request->file;
                $form12_info->save();
            }else{
                $code=$this->generate_referencing_code();
                $file_info=['code'=>$code,'created_by'=>Auth::user()->id,'current_stage'=>'Publication verify'];
                $form12_file=new AmendmentsFile($file_info);
                $form12_file->save();

                $form12_info=AmendmentsHeader::find($request->new_id);
                $form12_info->ref_no=$form12_file->id;
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
        $ref_no=0;
        $now = Carbon::now();
        $current_year=$now->year;
        $year_code=null;
        $sentence_code=null;
        $max_reference=DB::select("select amendments_file.code from amendments_header inner join amendments_file on amendments_header.ref_no=amendments_file.id where amendments_file.code like '%".substr($current_year,2,2)."%' ORDER BY RIGHT(amendments_file.code, 5) DESC");

        if(sizeof($max_reference)==0)
        {
            $ref_no=0;
        }
        else
        {
            $last_ref_no=$max_reference[0]->code;
            $last_ref_no=AmendmentsFile::where('code',$last_ref_no)->first();
            list($year_code,$sentence_code,$ref_no) = explode('/', $last_ref_no->code);
        }
        $ref_no=substr($current_year,2,2).'/AM/'.sprintf('%05d', intval($ref_no) + 1);
        return $ref_no;
    }

    public function get_new_requests(){
        return view('pages.amendments.amendment_file_index');
    }

    public function newlist(){
        $pending=collect([]);
            $create=collect([]);$reg_verify=collect([]);$reg_approve=collect([]);$pub_verify=collect([]);$asst_comm=collect([]);$bim_comm=collect([]);
            $comm_gen=collect([]);$computer=collect([]);$proof_sinhala=collect([]);$computer_sinhala=collect([]);$proof_translate=collect([]);$computer_translate=collect([]);
            $pub_without=collect([]);$press_without=collect([]);


        if($this->getAccessPubVerify(request()->segment(1))=='Yes'){
            $pub_verify=AmendmentsFile::where('current_stage','Publication verify')->where('is_archived',0)->get();
        }
        if($this->getAccessAsstComm(request()->segment(1))=='Yes'){
            $asst_comm=AmendmentsFile::where('current_stage','Assistant commisioner')->where('is_archived',0)->get();
        }
        if($this->getAccessBimsaviyaComm(request()->segment(1))=='Yes'){
            $bim_comm=AmendmentsFile::where('current_stage','Bimsaviya commisioner')->where('is_archived',0)->get();
        }
        if($this->getAccessCommGen(request()->segment(1))=='Yes'){
            $comm_gen=AmendmentsFile::where('current_stage','Commissioner general')->where('is_archived',0)->get();
        }
        if($this->getAccessForwardProof(request()->segment(1))=='Yes'){
            $computer=AmendmentsFile::where('computer_branch_officer',Auth::user()->id)->where('is_archived',0)->where(function($q){
                $q->where('current_stage','Computer branch')->orWhere('current_stage','Proof read(Sinhala)-Computer');
            })->get();
        }
        if($this->getAccessProof(request()->segment(1))=='Yes'){
            $proof_sinhala=AmendmentsFile::where('current_stage','Proof read(Sinhala)')->where('is_archived',0)->get();
        }
        if($this->getAccessForwardTransProof(request()->segment(1))=='Yes'){
            $computer_sinhala=AmendmentsFile::where('computer_branch_officer',Auth::user()->id)->where('current_stage','Proof read(Translation)-Computer')->where('is_archived',0)->get();
        }
        if($this->getAccessTransProof(request()->segment(1))=='Yes'){
            $proof_translate=AmendmentsFile::where('current_stage','Proof read(Translates)')->where('is_archived',0)->get();
        }
        if($this->getAccessForwardPublication(request()->segment(1))=='Yes'){
            $computer_translate=AmendmentsFile::where('computer_branch_officer',Auth::user()->id)->where('current_stage','Proof read complete')->orWhere('current_stage','Gazette with G')->where('is_archived',0)->get();
        }
        if($this->getAccessForwardPress(request()->segment(1))=='Yes'){
            $pub_without=AmendmentsFile::where('current_stage','Publication without G')->orWhere('current_stage','Publication with G')->orWhere('current_stage','Gov press with G')->where('is_archived',0)->get();
        }
        if($this->getAccessGazette(request()->segment(1))=='Yes'){
            $press_without=AmendmentsFile::where('current_stage','Gov Press without G')->where('is_archived',0)->get();
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
        error_log($id);
        $form12file=AmendmentsFile::find($id);
        view()->share('form12',$form12file);
        $form12=AmendmentsHeader::where('ref_no',$id)->where('rejected',0)->get();
        view()->share('form14_records',$form12);
        $computer_officers=User::whereIn('role_code',UserRolePermissions::select('role_code')->where('is_enable',1)->where('module_code',request()->segment(1))->where('can_forward_to_proof',1)->distinct('module_code')->get())->get();
        view()->share('computer_officers',$computer_officers);
        error_log($computer_officers);
        return view('pages.amendments.amendment_file_data');
    }

    public function block_details($header){
        $form12=AmendmentsHeader::find($header);
        $details=AmendmentsDetails::where('form_14_Header_id',$header)->where('rejected',0)->get();
        view()->share('details',$details);
        view()->share('form12',$form12);
        return view('pages.amendments.amendment_file_details');
    }

    public function update(Request $request,$id){

        try{
            $form12file=AmendmentsFile::find($id);
            $form12=AmendmentsHeader::where('ref_no',$id)->where('rejected',0)->get();
            if($request->button=='archive'){
                $form12file->is_archived=1;
                $form12file->save();
                DB::commit();

                return redirect('amendment-sentence-file/new-requests')->with('success','Updated Successfully!!');
            }else{ 
                switch($request->button){
                    case 'forward_asst_comm':
                        $form12file->current_stage='Assistant commisioner';
                        $form12file->publication_branch=Auth::user()->id;
                        $form12file->publication_branch_date=Carbon::now();
                        $form12file->save();
                        foreach($form12 as $item){
                            $item->current_stage='Assistant commisioner';
                            $item->publication_verify=1;
                            $item->publication_verify_date=Carbon::now();
                            $item->publication_verify_by=Auth::user()->id;
                            $item->comment=$request->comment;
                            $item->save();
                        }
                        DB::commit();
                        return redirect('amendment-sentence-file/new-requests')->with('success','Updated Successfully!!');
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
                            $item->asst_com_approval=Auth::user()->id;
                            $item->asst_com_approval_date=Carbon::now();
                            $item->comment=$request->comment;
                            $item->save();
                        }
                        DB::commit();
                        return redirect('amendment-sentence-file/new-requests')->with('success','Updated Successfully!!');
                        break;
                    case 'forward_comm_general':
                        $form12file->current_stage='Commissioner general';
                        $form12file->bim_approval=Auth::user()->id;
                        $form12file->bim_approval_date=Carbon::now();
                        $form12file->save();
                        foreach($form12 as $item){
                            $item->current_stage='Commissioner general';
                            $item->bimsaviya_com_approval=Auth::user()->id;
                            $item->bimsaviya_com_approval_date=Carbon::now();
                            $item->comment=$request->comment;
                            $item->save();
                        }
                        DB::commit();
                        return redirect('amendment-sentence-file/new-requests')->with('success','Updated Successfully!!');
                        break;
                    case 'comm_general_approval':
                        $form12file->current_stage='Computer branch';
                        $form12file->comm_gen_approval=Auth::user()->id;
                        $form12file->comm_gen_approval_date=Carbon::now();
                        $form12file->save();
                        foreach($form12 as $item){
                            $item->current_stage='Computer branch';
                            $item->commissioner_general_apprival=Auth::user()->id;
                            $item->commissioner_general_apprival_date=Carbon::now();
                            $item->comment=$request->comment;
                            $item->save();
                        }
                        DB::commit();
                        return redirect('amendment-sentence-file/new-requests')->with('success','Updated Successfully!!');
                        break;
                    case 'forward_to_proof_read':
                        $form12file->current_stage='Proof read(Sinhala)';
                        $form12file->computer_branch=1;
                        $form12file->computer_branch_date=Carbon::now();
                        $form12file->save();
                        foreach($form12 as $item){
                            $item->current_stage='Proof read(Sinhala)';
                            $item->computer_checked=1;
                            $item->computer_checked_by=Auth::user()->id;
                             $item->computer_checked_date=Carbon::now();
                            $item->comment=$request->comment;
                            $item->save();
                        }
                        DB::commit();
                        return redirect('amendment-sentence-file/new-requests')->with('success','Updated Successfully!!');
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
                        return redirect('amendment-sentence-file/new-requests')->with('success','Updated Successfully!!');
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
                        return redirect('amendment-sentence-file/new-requests')->with('success','Updated Successfully!!');
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
                        return redirect('amendment-sentence-file/new-requests')->with('success','Updated Successfully!!');
                        break;
                    case 'proof_read_complete':
                        $form12file->current_stage='Proof read complete';
                        $form12file->proof_read_english=Auth::user()->id;
                        $form12file->proof_read_english_date=Carbon::now();
                        $form12file->save();
                        foreach($form12 as $item){
                            $item->current_stage='Proof read complete';
                            $item->proof_read_complete_by=Auth::user()->id;
                            $item->proof_read_complete_date=Carbon::now();
                            $item->comment=$request->comment;
                            $item->save();
                        }
                        DB::commit();
                        return redirect('amendment-sentence-file/new-requests')->with('success','Updated Successfully!!');
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
                        return redirect('amendment-sentence-file/new-requests')->with('success','Updated Successfully!!');
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
                        return redirect('amendment-sentence-file/new-requests')->with('success','Updated Successfully!!');
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
                            $item->computer_with=1;
                            $item->computer_with_by=Auth::user()->id;
                            $item->computer_with_date=Carbon::now();
                            $item->comment=$request->comment;
                            $item->save();
                        }
                        DB::commit();
                        return redirect('amendment-sentence-file/new-requests')->with('success','Updated Successfully!!');
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
                        return redirect('amendment-sentence-file/new-requests')->with('success','Updated Successfully!!');
                        break;
                    case 'press_with_G':
                        $form12file->current_stage='Gov press with G';
                        $form12file->sent_to_gov_press=Auth::user()->id;
                        $form12file->sent_to_gov_press_date=Carbon::now();
                        $form12file->save();
                        foreach($form12 as $item){
                            $item->current_stage='Gov press with G';
                            $item->sent_gov_press=Auth::user()->id;
                            $item->sent_gov_press_date=Carbon::now();
                            $item->comment=$request->comment;
                            $item->save();
                        }
                        DB::commit();
                        return redirect('amendment-sentence-file/new-requests')->with('success','Updated Successfully!!');
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
                        return redirect('amendment-sentence-file/new-requests')->with('success','Updated Successfully!!');
                        break;
                }
            }
        }catch(Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->back()->with('error', 'Error!');
        }
    }

    }
