<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Models\Modules;
use App\Models\UserRolePermissions;
use App\Models\RegionalOffices;
use App\Models\Provinces;
use App\Models\Districts;
use App\Models\AgDivisions;
use App\Models\GnDivisions;
use App\Models\Village;
use App\Models\Form14Header;
use App\Models\Form12;
use App\Models\Form14Details;
use App\Models\ProofRead;
use App\Models\Recheck;
use App\User;
use PDF;
use Auth;
Use Alert;
use Log;
use Exception;
use DB;
use DataTables;
use Validator;
use Carbon\Carbon;
use App\Traits\Permissions;

class Form14Controller extends Controller
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
        // public function new()
        // {
        //     return view('pages.form14.new_requests');
        // }
        public function approved()
        {
            return view('pages.form14.approved');
        }
        public function pending()
        {
            return view('pages.form14.pending');
        }
        public function rejected()
        {
            return view('pages.form14.rejected');
        }
          public function rejlands()
        {
            return view('pages.form14.rejected_lands');
        }
        public function recheck()
        {
            return view('pages.form14.recheck');
        }
        public function gazetted()
        {
            return view('pages.form14.gazetted');
        }
        /**
         * Show the form for creating a new resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function create()
        {
            $file14_no=$this->Generate_File_Number();
            $province=Provinces::where('is_active',1)->get();
            $district=Districts::where('is_active',1)->get();
            $agDivision=AgDivisions::where('is_active',1)->get();
           // $gnDivision=GnDivisions::where('ag_id',Auth::user()->branch_id)->where('is_active',1)->get();
           $gnDivision=GnDivisions::where('is_active',1)->get();
            $villages=Village::get();
            view()->share('villages',$villages);
            $map_number=Form12::where('rejected',0)->where('current_stage','Online Publish')->where('ag_division',Auth::user()->branch_id)->distinct()->get('map_no');
            $form14_header_id=null;
            $computer_officers=User::whereIn('role_code',UserRolePermissions::select('role_code')->where('is_enable',1)->where('module_code',request()->segment(1))->where('can_forward_to_proof',1)->distinct('module_code')->get())->get();
            view()->share('computer_officers',$computer_officers);
            $reginal_officers=User::whereIn('role_code',UserRolePermissions::select('role_code')->where('is_enable',1)->where('module_code',request()->segment(1))->where('can_verify',1)->distinct('module_code')->get())->where('branch_id',Auth::User()->branch_id)->get();
            view()->share('from14GnDivision',[]);
            view()->share('map_numbers',$map_number);
            view()->share('block_numbers',[]);
            view()->share('ReginalOfficers',$reginal_officers);
            view()->share('elementDetails',[]);
            view()->share('file_no',$file14_no);
            view()->share('form14_header_id',$form14_header_id);
            view()->share('gnDivision',$gnDivision);
            view()->share('agDivision',$agDivision);
            view()->share('district',$district);
            view()->share('province',$province);
            view()->share('form12',null);
            return view('pages.form14.form');
        }

        public Function Generate_File_Number()
        {
            $regionalOffice=AgDivisions::where('id',Auth::User()->branch_id)->where('is_active',1)->first();

            if(isset($regionalOffice))
            {
            $max_14File_no=DB::select("select file_no from form_14_header where file_no like '%".$regionalOffice->ag_code."%' ORDER BY RIGHT(file_no, 10) DESC");
            $Regi=null;
            if(sizeof($max_14File_no)==0)
            {
                $file14_no=0;
            }
            else
            {
                $last_file_no=$max_14File_no[0]->file_no;
                $last_file_no=Form14Header::where('file_no',$last_file_no)->first();
                list($Regi,$file14_no) = explode('/', $last_file_no->file_no);
            }
            $file14_no=$regionalOffice->ag_code.'/'.sprintf('%010d', intval($file14_no) + 1);
            return $file14_no;
        }
        else
        {
            return redirect()->route('home')->with('error', 'Please Update User AG Office');
        }
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
                DB::beginTransaction();
                $form12=Form12::where('map_no',$request->map_no)->where('block_no',$request->block_no)->where('rejected',0)->where('current_stage','Online Publish')->where('ag_division',Auth::user()->branch_id)->first();
                if($form12){
                    $string_gn_division=null;
                            $string_village_name=null;
                            $gn_division=$request->get('gn_division_id');
                            $village_name=$request->get('village_name');
                            if($gn_division){
                                foreach($gn_division as $element){
                                    $string_gn_division=$string_gn_division.','.$element;
                                }
                            }
                            if($village_name)
                            {
                                foreach($village_name as $element){
                                    $string_village_name=$string_village_name.','.$element;
                                }
                            }
                    $fileno=$this->Generate_File_Number();
                    Form14Header::Create(
                    [
                        'province_id' => $request->province_id,
                        'district_id'=>$request->district_id,
                        'ag_division_id'=>$request->ag_division_id,
                        'map_no'=>$request->map_no,
                        'block_no'=>$request->block_no,
                        'gn_division_id'=>$string_gn_division,
                        'village_name'=>$string_village_name,
                        'governments_lands'=>$request->governments_lands,
                        'private_lands'=>$request->private_lands,
                        'total_lands'=>$request->total_lands,
                        'file_no'=>$fileno,
                        'ref_no'=>null,
                        'publication_branch'=>0,
                        'publication_branch_date'=>null,
                        'computer_branch'=>0,
                        'computer_branch_date'=>null,
                        'prepared_by'=>Auth::User()->id,
                        'prepared_date'=>Carbon::now(),
                        'regional_officer'=>$request->regional_officer,
                        'regional_approved'=>0,
                        'regional_checked'=>0,
                        'current_stage'=>'Regional data entry',
                        'rejected'=>0,
                    ]);
                    view()->share('fileNo',$fileno);
                    $form14header=Form14Header::where('file_no',$fileno)->first();
                    DB::commit();
                    return redirect()->route('form14-edit',$form14header->id)->with('success', 'Created Successfully!');
                }else{
                    return redirect()->back()->with('Error','Related Form 12 not found');
                }
            } catch (Exception $e) {
                DB::rollBack();
                Log::error($e);
                return redirect()->back()->with('error', $e);
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
            $element=Form14Header::find($id);
            $form14=$element;
            $elementDetails=Form14Details::where('form_14_Header_id',$element->id)->get();
            $province=Provinces::where('is_active',1)->get();
            $district=Districts::where('is_active',1)->get();
            $agDivision=AgDivisions::where('is_active',1)->get();
            $gnDivision=GnDivisions::where('is_active',1)->get();
            if($element->current_stage=='Regional data entry' || $element->current_stage=='Regional officer' || $element->current_stage=='Regional commissioner' )
            {
                 $map_number=Form12::where('rejected',0)->where('current_stage','Online Publish')->where('ag_division',Auth::user()->branch_id)->distinct()->get('map_no');
            }
            else
            {
                $map_number=Form12::distinct()->get('map_no');
            }
            $block_number=Form14Header::where('id',$id)->get();
            $form14_header_id=$element->id;
            $computer_officers=User::whereIn('role_code',UserRolePermissions::select('role_code')->where('is_enable',1)->where('module_code',request()->segment(1))->where('can_forward_to_proof',1)->distinct('module_code')->get())->get();
            view()->share('computer_officers',$computer_officers);
            $reginal_officers=User::whereIn('role_code',UserRolePermissions::select('role_code')->where('is_enable',1)->where('module_code',request()->segment(1))->where('can_verify',1)->distinct('module_code')->get())->where('branch_id',Auth::User()->branch_id)->get();
            $from14GnDivision=[];
            foreach(explode(',',$element->gn_division_id) as $str)
            {
                if($str!="")
                {
                   $gn_id=GnDivisions::where('id',$str)->where('is_active',1)->first();
                   array_push($from14GnDivision,(object)[
                       'id'=>$gn_id->id,
                       'gn_name'=>$gn_id->gn_name,
                       'sinhala_name'=>$gn_id->sinhala_name
                   ]);
                }
            }
            $villages=Village::get();
            view()->share('villages',$villages);
            view()->share('from14GnDivision',$from14GnDivision);
            view()->share('map_numbers',$map_number);
            view()->share('block_numbers',$block_number);
            view()->share('ReginalOfficers',$reginal_officers);
            view()->share('elementDetails',$elementDetails);
            view()->share('element',$element);
            view()->share('file_no',$element->file_no);
            view()->share('form14_header_id',$form14_header_id);
            view()->share('gnDivision',$gnDivision);
            view()->share('agDivision',$agDivision);
            view()->share('district',$district);
            view()->share('province',$province);
            view()->share('form12',$form14);
            $proof_reads=ProofRead::where('form_name','form14')->where('language','sinhala')->where('ref_number',$form14->id)->get();
            $proof_reads_translate=ProofRead::where('form_name','form14')->where('language','translate')->where('ref_number',$form14->id)->get();
            view()->share('proof_reads',$proof_reads);
            view()->share('proof_reads_translate',$proof_reads_translate);
            return view('pages.form14.form');
        }

        /**
         * Show the form for editing the specified resource.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function getrecheck()
        {
            $rejected=[];
            if($this->getAccessCreate(request()->segment(1))=='Yes' || $this->getAccessRegVerify(request()->segment(1))=='Yes' || $this->getAccessRegApprove(request()->segment(1))=='Yes'){
                $rejected=Form14Header::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                        ->where('rejected',0)->where('recheck',1)->get();
            }
            else{
                $rejected=Form14Header::where('rejected',0)->where('recheck',1)->get();
            }
            return DataTables::of($rejected)
                    ->addIndexColumn()
                    ->addColumn('ag_division', function ($newdata) {
                        $AGName  =  $this->getAGDName($newdata->ag_division_id);
                        return $AGName;
                      })
                  ->addColumn('gn_division', function ($newdata) {
                        $GNName  =  $this->getGNDName($newdata->gn_division_id);
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
        public function getDetails($id)
        {
            $elementDetails=Form14Details::where('form_14_Header_id',$id)->where('rejected',0)->get();
            return response()->json([
                'message'   => $elementDetails,
                'class_name'  => 'alert-success',
                'status'=>200
               ]);
        }
        public function certificate(Request $request)
        {
        try {
            DB::beginTransaction();
            $details=Form14Details::find($request->detail_id);
            if($details)
            {
                if($request->certificateNumber != null && $request->certificateNumber1 != null){
                $details->certificate_number="1. ".$request->certificateNumber." /  2. ".$request->certificateNumber1;
                }else{
                    $details->certificate_number=$request->certificateNumber;
                }
                $details->certificate_number_date=Carbon::now();
                $details->save();
            }
            else
            {
            return redirect()->back()->with('error', "Can't Update Certificate Number");
            }
            DB::commit();
            return redirect()->back()->with('success', 'Updated Successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->back()->with('error', $e);
        }

        }
        public function edit($id)
        {
            $element=Form14Header::find($id);
            $form14=$element;
            $elementDetails=Form14Details::where('form_14_Header_id',$element->id)->get();
            $province=Provinces::where('is_active',1)->get();
            $district=Districts::where('is_active',1)->get();
            $agDivision=AgDivisions::where('is_active',1)->get();
            $gnDivision=GnDivisions::where('is_active',1)->get();
            if($element->current_stage=='Regional data entry' || $element->current_stage=='Regional officer' || $element->current_stage=='Regional commissioner' )
            {
                 $map_number=Form12::where('rejected',0)->where('current_stage','Online Publish')->where('ag_division',Auth::user()->branch_id)->distinct()->get('map_no');
            }
            else
            {
                 $map_number=Form12::distinct()->get('map_no');
            }
            $block_number=Form14Header::where('id',$id)->get();
            $from14GnDivision=[];
            foreach(explode(',',$element->gn_division_id) as $str)
            {
                if($str!="")
                {
                   $gn_id=GnDivisions::where('id',$str)->where('is_active',1)->first();
                   array_push($from14GnDivision,(object)[
                       'id'=>$gn_id->id,
                       'gn_name'=>$gn_id->gn_name,
                       'sinhala_name'=>$gn_id->sinhala_name
                   ]);
                }
            }
            $villages=Village::get();
            view()->share('villages',$villages);
            view()->share('from14GnDivision',$from14GnDivision);
            $form14_header_id=$element->id;
            $computer_officers=User::whereIn('role_code',UserRolePermissions::select('role_code')->where('is_enable',1)->where('module_code',request()->segment(1))->where('can_forward_to_proof',1)->distinct('module_code')->get())->get();
            view()->share('computer_officers',$computer_officers);
            $reginal_officers=User::where('role_code','RE1570278230')->where('branch_id',Auth::User()->branch_id)->get();
            view()->share('map_numbers',$map_number);
            view()->share('block_numbers',$block_number);
            view()->share('ReginalOfficers',$reginal_officers);
            view()->share('elementDetails',$elementDetails);
            view()->share('element',$element);
            view()->share('file_no',$element->file_no);
            view()->share('form14_header_id',$form14_header_id);
            view()->share('gnDivision',$gnDivision);
            view()->share('agDivision',$agDivision);
            view()->share('district',$district);
            view()->share('province',$province);
            view()->share('form12',$form14);
            $proof_reads=ProofRead::where('form_name','form14')->where('language','sinhala')->where('ref_number',$form14->id)->get();
            $proof_reads_translate=ProofRead::where('form_name','form14')->where('language','translate')->where('ref_number',$form14->id)->get();
            view()->share('proof_reads',$proof_reads);
            view()->share('proof_reads_translate',$proof_reads_translate);
            return view('pages.form14.form');
        }

        /**
         * Update the specified resource in storage.
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function detailsRecheck(Request $request)
        {
            $form14Details=Form14Details::where('id',$request->detail_id)->first();
            if($form14Details)
            {
                $form14Details->rejected_reason=$request->reason;
                $form14Details->rejected=1;
                $form14Details->save();
            }
            return redirect()->back()->with('success', 'Successfully!');
        }
        public function checkDuplicateEntry(Request $request)
        {
            $detail_count=Form14Details::join('form_14_header','form_14_detail.form_14_Header_id','=','form_14_header.id')
            ->where('form_14_header.map_no',$request->map_no)->where('form_14_header.block_no',$request->block_no)->where('form_14_detail.lot_no',$request->lot_no)
            ->where('form_14_detail.rejected',0)->get();
           $noofsize=sizeof($detail_count);
            return response()->json([
                'message'   => $noofsize,
                'class_name'  => 'alert-danger',
                'status'=>300
               ]);
        }
        public function update(Request $request, $id)
        {
            try {

                $form14=Form14Header::find($id);
                DB::beginTransaction();
                switch($request->button){
                    case 'save':
                        if($request->form_name=='header'){
                            $string_gn_division=null;
                            $string_village_name=null;
                            $gn_division=$request->get('gn_division_id');
                            $village_name=$request->get('village_name');
                            if($gn_division){
                                foreach($gn_division as $element){
                                    $string_gn_division=$string_gn_division.','.$element;
                                }
                            }
                            if($village_name)
                            {
                                foreach($village_name as $element){
                                    $string_village_name=$string_village_name.','.$element;
                                }
                            }
                            Form14Header::updateOrCreate(['file_no' =>$request->file_no] ,
                            [
                                'map_no'=>$request->map_no,
                                'block_no'=>$request->block_no,
                                'gn_division_id'=>$string_gn_division,
                                'village_name'=>$string_village_name,
                                'governments_lands'=>$request->governments_lands,
                                'private_lands'=>$request->private_lands,
                                'total_lands'=>$request->total_lands,
                                'file_no'=>$request->file_no,
                                'gazetted_no'=>$request->gazetted_no,
                                'gazetted_date'=>$request->gazetted_date,
                                'certificate_isssue_gazzette'=>$request->certificate_isssue_gazzette,
                                'certificate_isssue_gazzette_date'=>$request->certificate_isssue_gazzette_date,
                            ]);
                            $form14header=Form14Header::where('file_no',$request->file_no)->first();
                            DB::commit();
                            return redirect()->route('form14-edit',$form14header->id)->with('success', 'Updated Successfully!');
                        }else if($request->form_name=='details'){

                            $form14Header=Form14Header::find($id);
                            $form14Header->ref_no=$request->ref_no;
                            $form14Header->save();
                            $value=$request->form14Details;
                            //$from14details=Form14Details::where('form_14_Header_id',$form14Header->id)->delete();

                            foreach($request->form14Details as $key=>$service)
                            {
                                $string_gn_division=null;
                                $gn_division=(isset($value[$key]['owner_details_gn_division_id']))?$value[$key]['owner_details_gn_division_id']:null;
                                if($gn_division){
                                    foreach($gn_division as $element){
                                        $string_gn_division=$string_gn_division.','.$element;
                                    }
                                }
                                $detail_count=Form14Details::where('form_14_Header_id',$form14Header->id)->where('rejected',0)->get();
                                if(sizeof($detail_count)==0){
                                    $from14details=new Form14Details();
                                    $from14details->form_14_Header_id=$form14Header->id;
                                    $from14details->lot_no=$value[$key]['lot_no'];
                                    $from14details->name=$value[$key]['name'];
                                    $from14details->addres=$value[$key]['address'];
                                    $from14details->nic_number=$value[$key]['nic'];
                                    $from14details->size=$value[$key]['size'];
                                    $from14details->ownership_type=$value[$key]['natowner'];
                                    $from14details->class=$value[$key]['class'];
                                    $from14details->mortgages=$value[$key]['mortgages'];
                                    $from14details->other_boudages=$value[$key]['other'];
                                    $from14details->type=$value[$key]['type'];
                                    $from14details->owner_details_gn_division_id=$string_gn_division;
                                    $from14details->sub_type=$value[$key]['sub_type'];
                                    $from14details->rejected=0;
                                    $from14details->save();
                                }elseif(sizeof($detail_count)==1){
                                    $from14details=Form14Details::where('form_14_Header_id',$form14Header->id)->where('rejected',0)->first();
                                    $from14details->lot_no=$value[$key]['lot_no'];
                                    $from14details->name=$value[$key]['name'];
                                    $from14details->addres=$value[$key]['address'];
                                    $from14details->nic_number=$value[$key]['nic'];
                                    $from14details->size=$value[$key]['size'];
                                    $from14details->ownership_type=$value[$key]['natowner'];
                                    $from14details->class=$value[$key]['class'];
                                    $from14details->mortgages=$value[$key]['mortgages'];
                                    $from14details->other_boudages=$value[$key]['other'];
                                    $from14details->type=$value[$key]['type'];
                                    $from14details->owner_details_gn_division_id=$string_gn_division;
                                    $from14details->sub_type=$value[$key]['sub_type'];
                                    $from14details->rejected=0;
                                    $from14details->save();

                                }else{
                                    return redirect()->route('form14-profile',$id)->with('success', 'Duplicate Entries!');
                                }

                            }
                            //dd($request->all());
                            DB::commit();

                            return redirect()->route('form14-profile',$id)->with('success', 'Updated Successfully!');

                        }
                        break;
                        case 'forward_regional_officer':
                            $form14->current_stage='Regional officer';
                            $form14->comment=$request->comment;
                            $form14->save();
                            //details
                            $form14Header=Form14Header::find($id);
                            $value=$request->form14Details;
                            $from14details=Form14Details::where('form_14_Header_id',$form14Header->id)->delete();
                            foreach($request->form14Details as $key=>$service)
                            {
                                $string_gn_division=null;
                                $gn_division=(isset($value[$key]['owner_details_gn_division_id']))?$value[$key]['owner_details_gn_division_id']:null;
                                if($gn_division){
                                    foreach($gn_division as $element){
                                        $string_gn_division=$string_gn_division.','.$element;
                                    }
                                }
                                $detail_count=Form14Details::where('form_14_Header_id',$form14Header->id)->where('lot_no',$value[$key]['lot_no'])
                                ->where('rejected',0)->get();
                                if(sizeof($detail_count)==0){
                                    $from14details=new Form14Details();
                                    $from14details->form_14_Header_id=$form14Header->id;
                                    $from14details->lot_no=$value[$key]['lot_no'];
                                    $from14details->name=$value[$key]['name'];
                                    $from14details->addres=$value[$key]['address'];
                                    $from14details->nic_number=$value[$key]['nic'];
                                    $from14details->size=$value[$key]['size'];
                                    $from14details->ownership_type=$value[$key]['natowner'];
                                    $from14details->class=$value[$key]['class'];
                                    $from14details->mortgages=$value[$key]['mortgages'];
                                    $from14details->other_boudages=$value[$key]['other'];
                                    $from14details->type=$value[$key]['type'];
                                    $from14details->owner_details_gn_division_id=$string_gn_division;
                                    $from14details->sub_type=$value[$key]['sub_type'];
                                    $from14details->rejected=0;
                                    $from14details->save();
                                }elseif(sizeof($detail_count)==1){
                                    $from14details=Form14Details::where('form_14_Header_id',$form14Header->id)->where('lot_no',$value[$key]['lot_no'])
                                        ->where('rejected',0)->first();
                                    $from14details->name=$value[$key]['name'];
                                    $from14details->addres=$value[$key]['address'];
                                    $from14details->nic_number=$value[$key]['nic'];
                                    $from14details->size=$value[$key]['size'];
                                    $from14details->ownership_type=$value[$key]['natowner'];
                                    $from14details->class=$value[$key]['class'];
                                    $from14details->mortgages=$value[$key]['mortgages'];
                                    $from14details->other_boudages=$value[$key]['other'];
                                    $from14details->type=$value[$key]['type'];
                                    $from14details->owner_details_gn_division_id=$string_gn_division;
                                    $from14details->sub_type=$value[$key]['sub_type'];
                                    $from14details->rejected=0;
                                    $from14details->save();
                                }else{
                                    return redirect()->route('form14-profile',$id)->with('success', 'Duplicate Entries!');
                                }
                            }
                            DB::commit();
                            return redirect('/14th-sentence/create')->with('success', 'Updated Successfully!');
                            break;
                        case 'forward_regional_commisioner':
                            $form14->current_stage='Regional commissioner';
                            $form14->regional_checked=1;
                            $form14->regional_checked_by=Auth::user()->id;
                            $form14->regional_checked_date=Carbon::now();
                            $form14->comment=$request->comment;
                            $form14->save();
                            //details
                            $form14Header=Form14Header::find($id);
                            $value=$request->form14Details;
                            $from14details=Form14Details::where('form_14_Header_id',$form14Header->id)->delete();
                            foreach($request->form14Details as $key=>$service)
                            {
                                $string_gn_division=null;
                                $gn_division=(isset($value[$key]['owner_details_gn_division_id']))?$value[$key]['owner_details_gn_division_id']:null;
                                if($gn_division){
                                    foreach($gn_division as $element){
                                        $string_gn_division=$string_gn_division.','.$element;
                                    }
                                }
                                $detail_count=Form14Details::where('form_14_Header_id',$form14Header->id)->where('lot_no',$value[$key]['lot_no'])
                                ->where('rejected',0)->get();
                                if(sizeof($detail_count)==0){
                                    $from14details=new Form14Details();
                                    $from14details->form_14_Header_id=$form14Header->id;
                                    $from14details->lot_no=$value[$key]['lot_no'];
                                    $from14details->name=$value[$key]['name'];
                                    $from14details->addres=$value[$key]['address'];
                                    $from14details->nic_number=$value[$key]['nic'];
                                    $from14details->size=$value[$key]['size'];
                                    $from14details->ownership_type=$value[$key]['natowner'];
                                    $from14details->class=$value[$key]['class'];
                                    $from14details->mortgages=$value[$key]['mortgages'];
                                    $from14details->other_boudages=$value[$key]['other'];
                                    $from14details->type=$value[$key]['type'];
                                    $from14details->owner_details_gn_division_id=$string_gn_division;
                                    $from14details->sub_type=$value[$key]['sub_type'];
                                    $from14details->rejected=0;
                                    $from14details->save();
                                }elseif(sizeof($detail_count)==1){
                                    $from14details=Form14Details::where('form_14_Header_id',$form14Header->id)->where('lot_no',$value[$key]['lot_no'])
                                        ->where('rejected',0)->first();
                                    $from14details->name=$value[$key]['name'];
                                    $from14details->addres=$value[$key]['address'];
                                    $from14details->nic_number=$value[$key]['nic'];
                                    $from14details->size=$value[$key]['size'];
                                    $from14details->ownership_type=$value[$key]['natowner'];
                                    $from14details->class=$value[$key]['class'];
                                    $from14details->mortgages=$value[$key]['mortgages'];
                                    $from14details->other_boudages=$value[$key]['other'];
                                    $from14details->type=$value[$key]['type'];
                                    $from14details->owner_details_gn_division_id=$string_gn_division;
                                    $from14details->sub_type=$value[$key]['sub_type'];
                                    $from14details->rejected=0;
                                    $from14details->save();
                                }else{
                                    return redirect()->route('form14-profile',$id)->with('success', 'Duplicate Entries!');
                                }
                            }
                            DB::commit();
                            return redirect()->route('form14-new-requests')->with('success', 'Updated Successfully!');
                            break;
                        case 'regional_commisioner_approval':
                            $form14->current_stage='Publication verify';
                            $form14->regional_approved=1;
                            $form14->regional_approved_by=Auth::user()->id;
                            $form14->regional_approved_date=Carbon::now();
                            $form14->comment=$request->comment;
                            $form14->save();
                            //details
                            $form14Header=Form14Header::find($id);
                            $value=$request->form14Details;
                            $from14details=Form14Details::where('form_14_Header_id',$form14Header->id)->delete();
                            foreach($request->form14Details as $key=>$service)
                            {
                                $string_gn_division=null;
                                $gn_division=(isset($value[$key]['owner_details_gn_division_id']))?$value[$key]['owner_details_gn_division_id']:null;
                                if($gn_division){
                                    foreach($gn_division as $element){
                                        $string_gn_division=$string_gn_division.','.$element;
                                    }
                                }
                                $detail_count=Form14Details::where('form_14_Header_id',$form14Header->id)->where('lot_no',$value[$key]['lot_no'])
                                ->where('rejected',0)->get();
                                if(sizeof($detail_count)==0){
                                    $from14details=new Form14Details();
                                    $from14details->form_14_Header_id=$form14Header->id;
                                    $from14details->lot_no=$value[$key]['lot_no'];
                                    $from14details->name=$value[$key]['name'];
                                    $from14details->addres=$value[$key]['address'];
                                    $from14details->nic_number=$value[$key]['nic'];
                                    $from14details->size=$value[$key]['size'];
                                    $from14details->ownership_type=$value[$key]['natowner'];
                                    $from14details->class=$value[$key]['class'];
                                    $from14details->mortgages=$value[$key]['mortgages'];
                                    $from14details->other_boudages=$value[$key]['other'];
                                    $from14details->type=$value[$key]['type'];
                                    $from14details->owner_details_gn_division_id=$string_gn_division;
                                    $from14details->sub_type=$value[$key]['sub_type'];
                                    $from14details->rejected=0;
                                    $from14details->save();
                                }elseif(sizeof($detail_count)==1){
                                    $from14details=Form14Details::where('form_14_Header_id',$form14Header->id)->where('lot_no',$value[$key]['lot_no'])
                                        ->where('rejected',0)->first();
                                    $from14details->name=$value[$key]['name'];
                                    $from14details->addres=$value[$key]['address'];
                                    $from14details->nic_number=$value[$key]['nic'];
                                    $from14details->size=$value[$key]['size'];
                                    $from14details->ownership_type=$value[$key]['natowner'];
                                    $from14details->class=$value[$key]['class'];
                                    $from14details->mortgages=$value[$key]['mortgages'];
                                    $from14details->other_boudages=$value[$key]['other'];
                                    $from14details->type=$value[$key]['type'];
                                    $from14details->owner_details_gn_division_id=$string_gn_division;
                                    $from14details->sub_type=$value[$key]['sub_type'];
                                    $from14details->rejected=0;
                                    $from14details->save();
                                }else{
                                    return redirect()->route('form14-profile',$id)->with('success', 'Duplicate Entries!');
                                }
                            }
                            DB::commit();
                            return redirect()->route('form14-new-requests')->with('success', 'Updated Successfully!');
                            break;
                        case 'forward_asst_comm':
                            $form14->current_stage='Assistant commisioner';
                            $form14->ref_no=$request->ref_no;
                            $form14->publication_checked=1;
                            $form14->publication_checked_by=Auth::user()->id;
                            $form14->publication_checked_date=Carbon::now();
                            $form14->comment=$request->comment;
                            $form14->save();
                            //details
                            $form14Header=Form14Header::find($id);
                            $value=$request->form14Details;
                            $from14details=Form14Details::where('form_14_Header_id',$form14Header->id)->delete();
                            foreach($request->form14Details as $key=>$service)
                            {
                                $string_gn_division=null;
                                $gn_division=$value[$key]['owner_details_gn_division_id'];
                                if($gn_division){
                                    foreach($gn_division as $element){
                                        $string_gn_division=$string_gn_division.','.$element;
                                    }
                                }
                                $detail_count=Form14Details::where('form_14_Header_id',$form14Header->id)->where('lot_no',$value[$key]['lot_no'])
                                ->where('rejected',0)->get();
                                if(sizeof($detail_count)==0){
                                    $from14details=new Form14Details();
                                    $from14details->form_14_Header_id=$form14Header->id;
                                    $from14details->lot_no=$value[$key]['lot_no'];
                                    $from14details->name=$value[$key]['name'];
                                    $from14details->addres=$value[$key]['address'];
                                    $from14details->nic_number=$value[$key]['nic'];
                                    $from14details->size=$value[$key]['size'];
                                    $from14details->ownership_type=$value[$key]['natowner'];
                                    $from14details->class=$value[$key]['class'];
                                    $from14details->mortgages=$value[$key]['mortgages'];
                                    $from14details->other_boudages=$value[$key]['other'];
                                    $from14details->type=$value[$key]['type'];
                                    $from14details->owner_details_gn_division_id=$string_gn_division;
                                    $from14details->sub_type=$value[$key]['sub_type'];
                                    $from14details->rejected=0;
                                    $from14details->save();
                                }elseif(sizeof($detail_count)==1){
                                    $from14details=Form14Details::where('form_14_Header_id',$form14Header->id)->where('lot_no',$value[$key]['lot_no'])
                                        ->where('rejected',0)->first();
                                    $from14details->name=$value[$key]['name'];
                                    $from14details->addres=$value[$key]['address'];
                                    $from14details->nic_number=$value[$key]['nic'];
                                    $from14details->size=$value[$key]['size'];
                                    $from14details->ownership_type=$value[$key]['natowner'];
                                    $from14details->class=$value[$key]['class'];
                                    $from14details->mortgages=$value[$key]['mortgages'];
                                    $from14details->other_boudages=$value[$key]['other'];
                                    $from14details->type=$value[$key]['type'];
                                    $from14details->owner_details_gn_division_id=$string_gn_division;
                                    $from14details->sub_type=$value[$key]['sub_type'];
                                    $from14details->rejected=0;
                                    $from14details->save();
                                }else{
                                    return redirect()->route('form14-profile',$id)->with('success', 'Duplicate Entries!');
                                }
                            }
                            DB::commit();
                            return redirect()->route('form14-new-requests')->with('success', 'Updated Successfully!');
                            break;
                        case 'forward_bim_comm':
                            $form14->current_stage='Bimsaviya commisioner';
                            $form14->computer_branch_officer=$request->computer_officer;
                            $form14->asst_commissioner_approval=Auth::user()->id;
                            $form14->asst_commissioner_approved_date=Carbon::now();
                            $form14->comment=$request->comment;
                            $form14->save();
                            DB::commit();
                            return redirect()->route('form14-new-requests')->with('success', 'Updated Successfully!');
                            break;
                        case 'forward_comm_general':
                            $form14->current_stage='Commissioner general';
                            $form14->bimsaviya_commissioner_approval=Auth::user()->id;
                            $form14->bimsaviya_commissioner_approved_date=Carbon::now();
                            $form14->comment=$request->comment;
                            $form14->save();
                            DB::commit();
                            return redirect()->route('form14-new-requests')->with('success', 'Updated Successfully!');
                            break;
                        case 'comm_general_approval':
                            $form14->current_stage='Computer branch';
                            $form14->comm_gen_approval=Auth::user()->id;
                            $form14->comm_gen_approval_date=Carbon::now();
                            $form14->comment=$request->comment;
                            $form14->save();
                            DB::commit();
                            return redirect()->route('form14-new-requests')->with('success', 'Updated Successfully!');
                            break;
                        case 'forward_to_proof_read':
                            $form14->current_stage='Proof read(Sinhala)';
                            $form14->computer_branch=Auth::user()->id;
                            $form14->computer_branch_date=Carbon::now();
                            $form14->comment=$request->comment;
                            $form14->save();
                            DB::commit();
                            return redirect()->route('form14-new-requests')->with('success', 'Updated Successfully!');
                            break;
                        case 'proof_read_sinhala':
                            $form14->current_stage='Proof read(Sinhala)-Computer';
                            $form14->comment=$request->comment;
                            $form14->save();
                            $proof_details=[
                                'form_name'=>'form14',
                                'language'=>'sinhala',
                                'ref_number'=>$form14->id,
                                'proof_read_by'=>Auth::user()->id,
                                'proof_read_date'=>Carbon::now(),
                            ];
                            $proof_read=new ProofRead($proof_details);
                            $proof_read->save();
                            DB::commit();
                            return redirect()->route('form14-new-requests')->with('success', 'Updated Successfully!');
                            break;
                        case 'forward_to_proof_read_translation':
                            $form14->current_stage='Proof read(Translates)';
                            $form14->sinhala_amended=1;
                            $form14->sinhala_amended_by=Auth::user()->id;
                            $form14->sinhala_amended_date=Carbon::now();
                            $form14->comment=$request->comment;
                            $form14->save();
                            DB::commit();
                            return redirect()->route('form14-new-requests')->with('success', 'Updated Successfully!');
                            break;
                        case 'proof_read_translation':
                            $form14->current_stage='Proof read(Translation)-Computer';
                            $form14->comment=$request->comment;
                            $form14->save();
                            $proof_details=[
                                'form_name'=>'form14',
                                'language'=>'translate',
                                'ref_number'=>$form14->id,
                                'proof_read_by'=>Auth::user()->id,
                                'proof_read_date'=>Carbon::now(),
                            ];
                            $proof_read=new ProofRead($proof_details);
                            $proof_read->save();
                            DB::commit();
                            return redirect()->route('form14-new-requests')->with('success', 'Updated Successfully!');
                            break;
                        case 'proof_read_complete':
                            $form14->current_stage='Proof read complete';
                            $form14->proof_read_complete=Auth::user()->id;
                            $form14->proof_read_complete_date=Carbon::now();
                            $form14->comment=$request->comment;
                            $form14->save();
                            DB::commit();
                            return redirect()->route('form14-new-requests')->with('success', 'Updated Successfully!');
                            break;
                        case 'publication_without_G':
                            $form14->current_stage='Publication without G';
                            $form14->gazette_without=1;
                            $form14->gazette_without_by=Auth::user()->id;
                            $form14->gazette_without_date=Carbon::now();
                            $form14->comment=$request->comment;
                            $form14->save();
                            DB::commit();
                            return redirect()->route('form14-new-requests')->with('success', 'Updated Successfully!');
                            break;
                        case 'press_without_G':
                            $form14->current_stage='Gov Press without G';
                            $form14->press_without=1;
                            $form14->press_without_by=Auth::user()->id;
                            $form14->press_without_date=Carbon::now();
                            $form14->comment=$request->comment;
                            $form14->save();
                            DB::commit();
                            return redirect()->route('form14-new-requests')->with('success', 'Updated Successfully!');
                            break;
                        case 'computer_with_G':
                            $form14->current_stage='Gazette with G';
                            $form14->computer_with=1;
                            $form14->computer_with_by=Auth::user()->id;
                            $form14->computer_with_date=Carbon::now();
                            $form14->comment=$request->comment;
                            $form14->save();
                            DB::commit();
                            return redirect()->route('form14-new-requests')->with('success', 'Updated Successfully!');
                            break;
                        case 'publication_with_G':
                            $form14->current_stage='Publication with G';
                            $form14->gazette_with=1;
                            $form14->gazette_with_by=Auth::user()->id;
                            $form14->gazette_with_date=Carbon::now();
                            $form14->comment=$request->comment;
                            $form14->save();
                            DB::commit();
                            return redirect()->route('form14-new-requests')->with('success', 'Updated Successfully!');
                            break;
                        case 'press_with_G':
                            $form14->current_stage='Gov press with G';
                            $form14->sent_gov_press=Auth::user()->id;
                            $form14->sent_gov_press_date=Carbon::now();
                            $form14->comment=$request->comment;
                            $form14->save();
                            DB::commit();
                            return redirect()->route('form14-new-requests')->with('success', 'Updated Successfully!');
                            break;
                        case 'reject':
                            if(isset($request->from)){
                                $detail=Form14Details::find($request->detail_id);
                                $detail->rejected=1;
                                $detail->rejected_reason=$request->reason;
                                $detail->save();
                            }else{
                                $form14->rejected=1;
                                $form14->rejected_date=Carbon::now();
                                $form14->rejected_reason=$request->reason;
                                $form14->save();
                            }
                            DB::commit();
                            return redirect()->route('form14-new-requests')->with('success', 'Updated Successfully!');
                            break;
                        case 'recheck':
                            $recheck_data=[
                                'ref_form_id'=>$form14->id,
                                'recheck_by'=>Auth::user()->id,
                                'recheck_stage'=>$form14->current_stage,
                                'recheck_reason'=>$request->recheck_reason,
                                'form_name'=>request()->segment(1),
                            ];
                            $recheck=new Recheck($recheck_data);
                            $recheck->save();
                            if($form14->current_stage=='Regional commissioner'){
                                $form14->current_stage='Regional data entry';
                            }elseif($form14->current_stage=='Regional officer'){
                                $form14->current_stage='Regional data entry';
                            }else{
                                $form14->current_stage='Regional commissioner';
                            }
                            $form14->recheck=1;
                            $form14->recheck_reason=$request->recheck_reason;
                            $form14->recheck_date=Carbon::now();
                            $form14->save();
                            DB::commit();
                            return redirect()->route('form14-new-requests')->with('success', 'Updated Successfully!');
                            break;
                        case 'online':
                            $form14->current_stage='Online Publish';
                            $form14->comment=$request->comment;
                            $form14->save();
                            DB::commit();
                            return redirect()->route('form14-new-requests')->with('success', 'Updated Successfully!');
                            break;
                        case 'certificates':
                            $form14->current_stage='Certificate issued';
                            $form14->comment=$request->comment;
                            $form14->save();

                            $form14Header=Form14Header::find($id);
                            $value=$request->form14Details;
                            foreach($request->form14Details as $key=>$service)
                            {
                                $from14details=Form14Details::where('form_14_Header_id',$form14Header->id)->where('lot_no',$value[$key]['lot_no'])->where('rejected',0)->first();
                                if($from14details){
                                    if($value[$key]['certificate_number']!=null && $value[$key]['certificate_number']!=''){
                                    $from14details->certificate_number=$value[$key]['certificate_number'];
                                    $from14details->save();
                                    }else{
                                        alert('test');
                                    }
                                }else{
                                    return redirect()->back()->with('success', 'No Data Found!');
                                }
                            }
                            DB::commit();
                            return redirect()->back()->with('success', 'Updated Successfully!');
                            break;
                }
           } catch (Exception $e) {
               dd($e);
               DB::rollBack();
               Log::error($e);
               return redirect()->back()->with('error', $e);
           }
        }
        public function downloadFile($id)
        {

            $fileType=Input::get('type');
            if($fileType=='14th-sentence')
            {
                $file14Header=DB::table('form_14_header')
                ->leftjoin('form_14_detail','form_14_header.id','=','form_14_detail.form_14_Header_id')
                ->leftjoin('ag_divisions','form_14_header.ag_division_id','=','ag_divisions.id')
                ->leftjoin('gn_divisions','form_14_header.gn_division_id','=','gn_divisions.id')
                ->leftjoin('provinces','form_14_header.province_id','=','provinces.id')
                ->leftjoin('districts','form_14_header.district_id','=','districts.id')
                ->select('form_14_header.*','form_14_detail.lot_no','form_14_detail.name','ag_divisions.sinhala_name as ag_name','gn_divisions.sinhala_name as gn_name','provinces.sinhala_name as province_name','districts.sinhala_name as districts_name')
                ->where('form_14_header.rejected',0)->where('form_14_detail.rejected',0)
                ->where('form_14_header.id',$id)
                ->first();
                view()->share('file14Header',$file14Header);
                $current=Form14Details::where('form_14_Header_id',$id)->where('rejected',0)->get();
                view()->share('elements',$current);
                $prepared_by=User::where('id',$file14Header->prepared_by)->first();
                $check_by=User::Where('id',$file14Header->regional_checked_by)->first();
                $approval_by=User::where('id',Auth::user()->id)->first();
                $approval_date=Carbon::now();
                view()->share('approvalDate',$approval_date);
                view()->share('prepared_by',$prepared_by);
                view()->share('check_by',$check_by);
                view()->share('approval_by',$approval_by);
                return view('pdfs.report1');
            }
        }
        public function report1list(){
            $current=[];
            if($this->getAccessCreate(request()->segment(1))=='Yes' || $this->getAccessRegVerify(request()->segment(1))=='Yes' || $this->getAccessRegApprove(request()->segment(1))=='Yes'){
                $current=Form14Details::where('rejected',0)->get();
            }else{
                $current=Form14Details::where('rejected',0)->get();
            }
            return DataTables::of($current)
                    ->addIndexColumn()
                    ->addColumn('DT_RowIndex', function ($current) {
                            $id =$current->id;
                            return $id;
                            })
                    ->addColumn('lot_no', function ($current) {
                        $lot_no =$current->lot_no;
                        return $lot_no;
                    })
                    ->addColumn('size', function ($current) {
                        $size =$current->size;
                        return $size;
                    })
                    ->addColumn('name', function ($current) {
                        $name =$current->name;
                        return $name;
                    })
                    ->addColumn('addres', function ($current) {
                        $addres =$current->addres;
                        return $addres;
                    })
                    ->addColumn('nic_number', function ($current) {
                        $nic_number =$current->nic_number;
                        return $nic_number;
                    })
                    ->addColumn('ownership_type', function ($current) {
                        $ownership_type =$current->ownership_type;
                        return $ownership_type;
                    })
                    ->addColumn('class', function ($current) {
                        $class =$current->class;
                        return $class;
                    })
                    ->addColumn('mortgages', function ($current) {
                        $mortgages =$current->mortgages;
                        return $mortgages;
                    })
                    ->addColumn('other_boudages', function ($current) {
                        $other_boudages =$current->other_boudages;
                        return $other_boudages;
                    })->rawColumns([])->make(true);
        }

    public function currentlist(){
        $current=[];

        if($this->getAccessCreate(request()->segment(1))=='Yes' || $this->getAccessRegVerify(request()->segment(1))=='Yes' || $this->getAccessRegApprove(request()->segment(1))=='Yes')
        {
            $current= $current=DB::table('form_14_header')
                   // ->leftjoin('form_14_file','form_14_header.ref_no','=','form_14_file.id')
                    ->whereIn('form_14_header.prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('form_14_header.current_stage','!=','Online Publish')->Where('form_14_header.current_stage','!=',null)->where('form_14_header.rejected',0)->where('form_14_header.recheck',0)->get();

        }else{
            $current=$current=DB::table('form_14_header')
            //->leftjoin('form_14_file','form_14_header.ref_no','=','form_14_file.id')
            ->where('form_14_header.current_stage','!=','Online Publish')->Where('form_14_header.current_stage','!=','Regional commissioner')->Where('form_14_header.current_stage','!=','Regional officer')->Where('form_14_header.current_stage','!=','Regional data entry')->where('form_14_header.rejected',0)->where('form_14_header.recheck',0)->get();
        }

        return DataTables::of($current)
                ->addIndexColumn()
            //     ->addColumn('ag_division_id', function ($current) {
            //         $AGName  =  $this->getAGDName($current->ag_division_id);
            //         return $AGName;

            //       })
            //   ->addColumn('gn_division_id', function ($current) {
            //         $GNName  =  $this->getGNDName($current->gn_division_id);
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

    public function new(){
		$ag_divisions=collect([]);
        $pending=collect([]);
            $create=collect([]);$reg_verify=collect([]);$reg_approve=collect([]);$pub_verify=collect([]);$asst_comm=collect([]);$bim_comm=collect([]);
            $comm_gen=collect([]);$computer=collect([]);$proof_sinhala=collect([]);$computer_sinhala=collect([]);$proof_translate=collect([]);$computer_translate=collect([]);
            $pub_without=collect([]);$press_without=collect([]);
        if($this->getAccessRegVerify(request()->segment(1))=='Yes'){
            $reg_verify=Form14Header::where('regional_officer',Auth::user()->id)->whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('current_stage','Regional officer')->where('rejected',0)->get();
					$ag_divisions=Form14Header::select('ag_division_id')->where('current_stage','Regional officer')->where('ag_division_id',Auth::user()->branch_id)->distinct()->get('ag_division_id');
        }
        if($this->getAccessRegApprove(request()->segment(1))=='Yes'){
            $reg_approve=Form14Header::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('current_stage','Regional commissioner')->where('rejected',0)->get();
					$ag_divisions=Form14Header::select('ag_division_id')->where('current_stage','Regional officer')->where('ag_division_id',Auth::user()->branch_id)->distinct()->get('ag_division_id');
        }
        if($this->getAccessPubVerify(request()->segment(1))=='Yes'){
            $pub_verify=Form14Header::where('current_stage','Publication verify')->where('rejected',0)->where('ref_no',null)->get();
			$ag_divisions=Form14Header::select('ag_division_id')->where('current_stage','Publication verify')->distinct()->get('ag_division_id');
        }
        if($this->getAccessAsstComm(request()->segment(1))=='Yes'){
            $asst_comm=Form14Header::where('current_stage','Assistant commisioner')->where('rejected',0)->get();
        }
        if($this->getAccessBimsaviyaComm(request()->segment(1))=='Yes'){
            $bim_comm=Form14Header::where('current_stage','Bimsaviya commisioner')->where('rejected',0)->get();
        }
        if($this->getAccessCommGen(request()->segment(1))=='Yes'){
            $comm_gen=Form14Header::where('current_stage','Commissioner general')->where('rejected',0)->get();
        }
        if($this->getAccessForwardProof(request()->segment(1))=='Yes'){
            $computer=Form14Header::where('computer_branch_officer',Auth::user()->id)->where('current_stage','Computer branch')->orWhere('current_stage','Proof read(Sinhala)-Computer')->where('rejected',0)->get();
        }
        if($this->getAccessProof(request()->segment(1))=='Yes'){
            $proof_sinhala=Form14Header::where('current_stage','Proof read(Sinhala)')->where('rejected',0)->get();
        }
        if($this->getAccessForwardTransProof(request()->segment(1))=='Yes'){
            $computer_sinhala=Form14Header::where('computer_branch_officer',Auth::user()->id)->where('current_stage','Proof read(Translation)-Computer')->where('rejected',0)->get();
        }
        if($this->getAccessTransProof(request()->segment(1))=='Yes'){
            $proof_translate=Form14Header::where('current_stage','Proof read(Translates)')->where('rejected',0)->get();
        }
        if($this->getAccessForwardPublication(request()->segment(1))=='Yes'){
            $computer_translate=Form14Header::where('computer_branch_officer',Auth::user()->id)->where('current_stage','Proof read complete')->orWhere('current_stage','Gazette with G')->where('rejected',0)->get();
        }
        if($this->getAccessForwardPress(request()->segment(1))=='Yes'){
            $pub_without=Form14Header::where('current_stage','Publication without G')->orWhere('current_stage','Publication with G')->orWhere('current_stage','Gov press with G')->where('rejected',0)->get();
        }
        if($this->getAccessGazette(request()->segment(1))=='Yes'){
            $press_without=Form14Header::where('current_stage','Gov Press without G')->where('rejected',0)->get();
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
        view()->share('new_requests',$pending);
        
        view()->share('ag_divisions',$ag_divisions);
        return view('pages.form14.new_requests');

    }


    public function getpendingdata(){
        $pending=collect([]);
            $create=collect([]);$reg_verify=collect([]);$reg_approve=collect([]);$pub_verify=collect([]);$asst_comm=collect([]);$bim_comm=collect([]);
            $comm_gen=collect([]);$computer=collect([]);$proof_sinhala=collect([]);$computer_sinhala=collect([]);$proof_translate=collect([]);$computer_translate=collect([]);
            $pub_without=collect([]);$press_without=collect([]);
        if($this->getAccessCreate(request()->segment(1))=='Yes'){
            $create=Form14Header::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('current_stage','Regional officer')->where('rejected',0)->get();
        }
        if($this->getAccessRegVerify(request()->segment(1))=='Yes'){
            $reg_verify=Form14Header::where('regional_officer',Auth::user()->id)->whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('current_stage','Regional commissioner')->where('rejected',0)->get();
        }
        if($this->getAccessRegApprove(request()->segment(1))=='Yes'){
            $reg_approve=Form14Header::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('current_stage','Publication verify')->where('rejected',0)->get();
        }
        if($this->getAccessPubVerify(request()->segment(1))=='Yes'){
            $pub_verify=Form14Header::where('current_stage','Assistant commisioner')->where('rejected',0)->get();
        }
        if($this->getAccessAsstComm(request()->segment(1))=='Yes'){
            $asst_comm=Form14Header::where('current_stage','Bimsaviya commisioner')->where('rejected',0)->get();
        }
        if($this->getAccessBimsaviyaComm(request()->segment(1))=='Yes'){
            $bim_comm=Form14Header::where('current_stage','Commissioner general')->where('rejected',0)->get();
        }
        if($this->getAccessCommGen(request()->segment(1))=='Yes'){
            $comm_gen=Form14Header::where('current_stage','Computer branch')->where('rejected',0)->get();
        }
        if($this->getAccessForwardProof(request()->segment(1))=='Yes'){
            $computer=Form14Header::where('computer_branch_officer',Auth::user()->id)->where('current_stage','Proof read(Sinhala)')->where('rejected',0)->get();
        }
        if($this->getAccessProof(request()->segment(1))=='Yes'){
            $proof_sinhala=Form14Header::where('current_stage','Proof read(Sinhala)-Computer')->where('rejected',0)->get();
        }
        if($this->getAccessForwardTransProof(request()->segment(1))=='Yes'){
            $computer_sinhala=Form14Header::where('computer_branch_officer',Auth::user()->id)->where('current_stage','Proof read(Translates)')->where('rejected',0)->get();
        }
        if($this->getAccessTransProof(request()->segment(1))=='Yes'){
            $proof_translate=Form14Header::where('current_stage','Proof read(Translation)-Computer')->where('rejected',0)->get();
        }
        if($this->getAccessForwardPublication(request()->segment(1))=='Yes'){
            $computer_translate=Form14Header::where('computer_branch_officer',Auth::user()->id)->where('current_stage','Proof read complete')->orWhere('current_stage','Publication without G')
                ->orWhere('current_stage','Publication with G')->where('rejected',0)->get();
        }
        if($this->getAccessForwardPress(request()->segment(1))=='Yes'){
            $pub_without=Form14Header::where('current_stage','Gov Press without G')->orWhere('current_stage','Gov press with G')->where('rejected',0)->get();
        }
        if($this->getAccessGazette(request()->segment(1))=='Yes'){
            $press_without=Form14Header::where('current_stage','Gazette with G')->where('rejected',0)->get();
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
            //     ->addColumn('ag_division_id', function ($newdata) {
            //         $AGName  =  $this->getAGDName($newdata->ag_division_id);
            //         return $AGName;

            //       })
            //   ->addColumn('gn_division_id', function ($newdata) {
            //         $GNName  =  $this->getGNDName($newdata->gn_division_id);
            //         return $GNName;

            //       })
                ->addColumn('action', function ($pending) {
                    $edit = '<a href="/'.request()->segment(1).'/update/'.$pending->id.'" class="btn btn-icon btn-primary"><i class="far fa-edit"></i></a>';
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

    public function getrejected(){
        $rejected=[];
        if($this->getAccessCreate(request()->segment(1))=='Yes' || $this->getAccessRegVerify(request()->segment(1))=='Yes' || $this->getAccessRegApprove(request()->segment(1))=='Yes'){
            $rejected=Form14Header::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('rejected',1)->get();
        }
        else{
            $rejected=Form14Header::where('rejected',1)->get();
        }
        return DataTables::of($rejected)
                ->addIndexColumn()
                ->addColumn('ag_division', function ($newdata) {
                    $AGName  =  $this->getAGDName($newdata->ag_division_id);
                    return $AGName;

                  })
              ->addColumn('gn_division', function ($newdata) {
                    $GNName  =  $this->getGNDName($newdata->gn_division_id);
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
    public function getgazetted(){
        $gazetted=[];
        if($this->getAccessCreate(request()->segment(1))=='Yes' || $this->getAccessRegVerify(request()->segment(1))=='Yes' || $this->getAccessRegApprove(request()->segment(1))=='Yes'){
            $gazetted=Form14Header::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
            ->where(function($q) {$q->where('current_stage', 'Online Publish')->orWhere('current_stage', 'Certificate issued');})
            ->where('rejected',0)->Orderby('id','desc')->get();
        }else{
            $gazetted=Form14Header::where('current_stage','Online Publish')->orWhere('current_stage','Certificate issued')->where('rejected',0)->Orderby('id','desc')->get();
        }
        return DataTables::of($gazetted)
                ->addIndexColumn()
            //     ->addColumn('ag_division_id', function ($newdata) {
            //         $AGName  =  $this->getAGDName($newdata->ag_division_id);
            //         return $AGName;

            //       })
            //   ->addColumn('gn_division_id', function ($newdata) {
            //         $GNName  =  $this->getGNDName($newdata->gn_division_id);
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
                    if(($this->getAccessUpdate(request()->segment(1))=="Yes") && ($gazetted->current_stage=='Online Publish')){
                        $actions .= ' '.$edit;
                    }
                    if(($this->getAccessDelete(request()->segment(1))=="Yes")){
                        $actions .= ' '.$delete;
                    }
                    return $actions;
                })
                ->rawColumns(['action'])->make(true);
    }
    public function getForm14Map($map)
    {
        if($this->getAccessCreate('55th-sentence')=='Yes')
        {
        $from14=Form14Header::where('map_no',$map)->where('rejected',0)->where('current_stage','Certificate issued')->where('ag_division_id',Auth::user()->branch_id)->distinct()->get('block_no');
        }
        else
        {
        $from14=Form14Header::where('map_no',$map)->where('rejected',0)->where('current_stage','Certificate issued')->distinct()->get('block_no');
        }
        if($from14)
        {
            return $from14;
        }
        else
        {
            return ['message'=>'Map Numbers Not found..'];
        }
    }
    public function getForm14($map,$block){
        if($this->getAccessCreate('55th-sentence')=='Yes')
        {
        $form14=DB::table('form_14_header')
        ->join('form_14_detail','form_14_header.id','=','form_14_detail.form_14_Header_id')
        ->where('form_14_header.map_no',$map)->where('form_14_header.block_no',$block)
        ->where('form_14_header.rejected',0)->where('form_14_header.current_stage','Certificate issued')
        ->where('form_14_header.ag_division_id',Auth::user()->branch_id)
        ->get();
        }
        else
        {
            $form14=DB::table('form_14_header')
            ->join('form_14_detail','form_14_header.id','=','form_14_detail.form_14_Header_id')
            ->where('form_14_header.map_no',$map)->where('form_14_header.block_no',$block)
            ->where('form_14_header.rejected',0)->where('form_14_header.current_stage','Certificate issued')
            ->get();
        }
        if($form14){
            return $form14;
        }else{
            return ['message'=>'Form 14 not found..'];
        }
    }
    public function getForm14lot($map,$block,$lotno){
        if($this->getAccessCreate('55th-sentence')=='Yes')
        {
        $form14=DB::table('form_14_header')
        ->join('form_14_detail','form_14_header.id','=','form_14_detail.form_14_Header_id')
        ->where('form_14_header.map_no',$map)->where('form_14_header.block_no',$block)->where('form_14_detail.lot_no',$lotno)
        ->where('form_14_header.rejected',0)->where('form_14_header.current_stage','Certificate issued')
        ->where('form_14_header.ag_division_id',Auth::user()->branch_id)
        ->get();
        }
        else
        {
            $form14=DB::table('form_14_header')
            ->join('form_14_detail','form_14_header.id','=','form_14_detail.form_14_Header_id')
            ->where('form_14_header.map_no',$map)->where('form_14_header.block_no',$block)->where('form_14_detail.lot_no',$lotno)
            ->get();
        }
        if($form14){
            return $form14;
        }else{
            return ['message'=>'not found..'];
        }
    }
    public function getMapNumbers()
    {
        if($this->getAccessCreate(request()->segment(1))=='Yes')
        {
        $map_number=Form14Header::where('rejected',0)->where('current_stage','Certificate issued')->where('ag_division_id',Auth::user()->branch_id)->distinct()->get('map_no');
        }
        else
        {
            $map_number=Form14Header::distinct()->get('map_no');
        }
        if($map_number){
            return $map_number;
        }else{
            return ['message'=>'not found'];
        }
    }
    public function getrefNumbers()
    {
        $ref_number=Form14Header::distinct()->get('ref_no');
        return $ref_number;
    }

    public function findForm($map,$block,$lot){
        $form14=Form14Header::join('form_14_detail','form_14_header.id','=','form_14_detail.form_14_Header_id')
                ->join('users','form_14_header.regional_officer','=','users.id')
                //->join('villages','form_14_header.village_name','=','villages.id')
                ->where('form_14_header.map_no',$map)->where('form_14_header.block_no',$block)->where('form_14_detail.lot_no',$lot)
                ->where('form_14_header.rejected',0)->where('form_14_detail.rejected',0)
                ->select('form_14_detail.*','form_14_header.gn_division_id','form_14_header.village_name','form_14_header.gazetted_date','form_14_header.gazetted_no','users.name as regional')->first();
                $element=Form14Header::find($form14->form_14_Header_id);
                $from14GnDivision=[];
                foreach(explode(',',$element->gn_division_id) as $str)
                {
                    if($str!="")
                    {
                        $gn_id=GnDivisions::where('id',$str)->where('is_active',1)->first();
                        array_push($from14GnDivision,(object)[
                            'id'=>$gn_id->id,
                            'gn_name'=>$gn_id->gn_name,
                            'sinhala_name'=>$gn_id->sinhala_name                      
                        ]);

                    }
                }
                 $from14Village=[];
                 foreach(explode(',',$form14->village_name) as $str1)
                 {
                     if($str1!="" || $str1!=null)
                     {

                         $village_id=Village::where('id',$str1)->first();
                         array_push($from14Village,(object)[
                            'id'=>$village_id->id,
                            'village'=>$village_id->village,
                            'sinhala_name'=>$village_id->sinhala_name                      
                        ]);
                     }
                 }
        if($form14){
            return ['element'=>$form14,'gns'=>$from14GnDivision,'village'=>$from14Village];
        }else{
            return ['message'=>'Form 14 not found..'];
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

        }
}
