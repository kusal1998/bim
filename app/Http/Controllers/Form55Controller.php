<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Modules;
use App\Models\UserRolePermissions;
use App\Models\Form14Header;
use App\Models\Form55Header;
use App\Models\Form55Details;
use App\Models\RegionalOffices;
use App\Models\Provinces;
use App\Models\Districts;
use App\Models\AgDivisions;
use App\Models\GnDivisions;
use App\Models\ProofRead;
use App\User;
use Auth;
Use Alert;
use DataTables;
use Validator;
use Log;
use Exception;
use DB;
use Carbon\Carbon;
use App\Traits\Permissions;

class Form55Controller extends Controller
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
            return view('pages.form55.new_requests');
        }
        public function approved()
        {
            return view('pages.form55.approved');
        }
        public function pending()
        {
            return view('pages.form55.pending');
        }
        public function rejected()
        {
            return view('pages.form55.rejected');
        }
        public function recheck()
        {
            return view('pages.form55.recheck');
        }
        public function gazetted()
        {
            return view('pages.form55.gazetted');
        }
        /**
         * Show the form for creating a new resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function getrecheck()
        {
            $rejected=[];
            if($this->getAccessCreate(request()->segment(1))=='Yes' || $this->getAccessRegVerify(request()->segment(1))=='Yes' || $this->getAccessRegApprove(request()->segment(1))=='Yes'){
                $rejected=Form55Header::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                        ->where('rejected',0)->where('recheck',1)->get();
            }
            else{
                $rejected=Form55Header::where('rejected',0)->where('recheck',1)->get();
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
        public function create()
        {
            $province=Provinces::where('is_active',1)->get();
            $district=Districts::where('is_active',1)->get();
            $agDivision=AgDivisions::where('is_active',1)->get();
            $gnDivision=GnDivisions::where('is_active',1)->get();
            $reOffice=RegionalOffices::where('is_active',1)->get();
            $map_number=Form14Header::where('rejected',0)->where('current_stage','Certificate issued')->where('ag_division_id',Auth::user()->branch_id)->distinct()->get('map_no');
            $computer_officers=User::whereIn('role_code',UserRolePermissions::select('role_code')->where('is_enable',1)->where('module_code',request()->segment(1))->where('can_forward_to_proof',1)->distinct('module_code')->get())->get();
            view()->share('computer_officers',$computer_officers);
            $reginal_officers=User::whereIn('role_code',UserRolePermissions::select('role_code')->where('is_enable',1)->where('module_code',request()->segment(1))->where('can_verify',1)->distinct('module_code')->get())->where('branch_id',Auth::User()->branch_id)->get();
            view()->share('map_numbers',$map_number);
            view()->share('lot_numbers',[]);
            view()->share('block_numbers',[]);
            view()->share('ReginalOfficers',$reginal_officers);
            view()->share('elementDetails',[]);
            view()->share('reOffice',$reOffice);
            view()->share('gnDivision',$gnDivision);
            view()->share('agDivision',$agDivision);
            view()->share('district',$district);
            view()->share('province',$province);
            view()->share('form12',null);
            return view('pages.form55.form');
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
                $string_gn_division=null;
                    $gn_division=$request->get('gn_division_id');
                    if($gn_division){
                        foreach($gn_division as $element){
                            $string_gn_division=$string_gn_division.','.$element;
                        }
                    }
                $form55header= Form55Header::Create([
                'province_id' => $request->province_id,
                'district_id'=>$request->district_id,
                'ag_division_id'=>$request->ag_division_id,
                'gn_division_id'=>$string_gn_division,
                'map_no'=>$request->header_map_no,
                'block_no'=>$request->header_block_no,
                'lot_no'=>$request->header_lot_no,
                'village'=>$request->village,
                'name_of_the_deceased'=>$request->name_of_the_deceased,
                'date_of_notice'=>$request->date_of_notice,
                'date_of_last_notice'=>$request->date_of_last_notice,
                'regional_office'=>Auth::user()->branch_id,
                'regional_officer'=>$request->regional_officer,
                'office_of_registration'=>$request->office_of_registration,
                'current_stage'=>'Regional data entry',
                'rejected'=>0,
                'prepared_by'=>Auth::user()->id,
                'prepared_date'=>Carbon::now(),
            ]);
                DB::commit();
                return redirect()->route('form55-edit',$form55header->id)->with('success', 'Created Successfully!');

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
            $element=Form55Header::where('id',$id)->first();
            $elementDetails=Form55Details::where('form_55_header_id',$element->id)->get();
            $province=Provinces::where('is_active',1)->get();
            $district=Districts::where('is_active',1)->get();
            $agDivision=AgDivisions::where('is_active',1)->get();
            $gnDivision=GnDivisions::where('is_active',1)->get();
            $reOffice=RegionalOffices::where('is_active',1)->get();
            if($element->current_stage=='Regional data entry' || $element->current_stage=='Regional officer' || $element->current_stage=='Regional commissioner' )
            {
            $map_number=Form14Header::where('rejected',0)->where('current_stage','Certificate issued')->where('ag_division_id',Auth::user()->branch_id)->distinct()->get('map_no');
            $block_number=Form14Header::where('rejected',0)->where('current_stage','Certificate issued')->where('ag_division_id',Auth::user()->branch_id)->distinct()->get('block_no');
            $lot_number=DB::table('form_14_header')
            ->join('form_14_detail','form_14_header.id','=','form_14_detail.form_14_Header_id')
            ->where('form_14_header.map_no',$element->map_no)->where('form_14_header.block_no',$element->block_no)
            ->where('form_14_header.rejected',0)->where('form_14_header.current_stage','Certificate issued')
            ->where('form_14_header.ag_division_id',Auth::user()->branch_id)
            ->get();
            }
            else
            {
                $map_number=Form14Header::distinct()->get('map_no');
                $block_number=Form14Header::distinct()->get('block_no');
                $lot_number=DB::table('form_14_header')
                ->join('form_14_detail','form_14_header.id','=','form_14_detail.form_14_Header_id')
                ->where('form_14_header.rejected',0)->where('form_14_header.current_stage','Certificate issued')
                ->get();
            }
            view()->share('block_numbers',$block_number);
            view()->share('lot_numbers',$lot_number);
            view()->share('map_numbers',$map_number);
            $computer_officers=User::whereIn('role_code',UserRolePermissions::select('role_code')->where('is_enable',1)->where('module_code',request()->segment(1))->where('can_forward_to_proof',1)->distinct('module_code')->get())->get();
            view()->share('computer_officers',$computer_officers);
            $reginal_officers=User::whereIn('role_code',UserRolePermissions::select('role_code')->where('is_enable',1)->where('module_code',request()->segment(1))->where('can_verify',1)->distinct('module_code')->get())->where('branch_id',Auth::User()->branch_id)->get();
            view()->share('ReginalOfficers',$reginal_officers);
            view()->share('elementDetails',$elementDetails);
            view()->share('element',$element);
            view()->share('reOffice',$reOffice);
            view()->share('gnDivision',$gnDivision);
            view()->share('agDivision',$agDivision);
            view()->share('district',$district);
            view()->share('province',$province);
            view()->share('form12',$element);
            $proof_reads=ProofRead::where('form_name','form55')->where('language','sinhala')->where('ref_number',$element->id)->get();
            $proof_reads_translate=ProofRead::where('form_name','form55')->where('language','translate')->where('ref_number',$element->id)->get();
            view()->share('proof_reads',$proof_reads);
            view()->share('proof_reads_translate',$proof_reads_translate);
            return view('pages.form55.form');
        }

        /**
         * Show the form for editing the specified resource.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function getDetails($id)
        {
            $elementDetails=Form55Details::where('form_55_header_id',$id)->where('rejected',0)->get();
            return response()->json([
                'message'   => $elementDetails,
                'class_name'  => 'alert-success',
                'status'=>200
               ]);

        }
        public function edit($id)
        {
            $element=Form55Header::where('id',$id)->first();
            $elementDetails=Form55Details::where('form_55_header_id',$element->id)->get();
            $province=Provinces::where('is_active',1)->get();
            $district=Districts::where('is_active',1)->get();
            $agDivision=AgDivisions::where('is_active',1)->get();
            $gnDivision=GnDivisions::where('is_active',1)->get();
            $reOffice=RegionalOffices::where('is_active',1)->get();
            $computer_officers=User::whereIn('role_code',UserRolePermissions::select('role_code')->where('is_enable',1)->where('module_code',request()->segment(1))->where('can_forward_to_proof',1)->distinct('module_code')->get())->get();
            if($element->current_stage=='Regional data entry' || $element->current_stage=='Regional officer' || $element->current_stage=='Regional commissioner' )
            {
            $map_number=Form14Header::where('rejected',0)->where('current_stage','Certificate issued')->where('ag_division_id',Auth::user()->branch_id)->distinct()->get('map_no');
            $block_number=Form14Header::where('rejected',0)->where('current_stage','Certificate issued')->where('ag_division_id',Auth::user()->branch_id)->distinct()->get('block_no');
            $lot_number=DB::table('form_14_header')
            ->join('form_14_detail','form_14_header.id','=','form_14_detail.form_14_Header_id')
            ->where('form_14_header.map_no',$element->map_no)->where('form_14_header.block_no',$element->block_no)
            ->where('form_14_header.rejected',0)->where('form_14_header.current_stage','Certificate issued')
            ->where('form_14_header.ag_division_id',Auth::user()->branch_id)
            ->get();
            }
            else
            {
                $map_number=Form14Header::distinct()->get('map_no');
                $block_number=Form14Header::distinct()->get('block_no');
                $lot_number=DB::table('form_14_header')
                ->join('form_14_detail','form_14_header.id','=','form_14_detail.form_14_Header_id')
                ->where('form_14_header.rejected',0)->where('form_14_header.current_stage','Certificate issued')
                ->get();
            }
            view()->share('block_numbers',$block_number);
            view()->share('lot_numbers',$lot_number);
            view()->share('map_numbers',$map_number);
            view()->share('computer_officers',$computer_officers);
            $reginal_officers=User::whereIn('role_code',UserRolePermissions::select('role_code')->where('is_enable',1)->where('module_code',request()->segment(1))->where('can_verify',1)->distinct('module_code')->get())->where('branch_id',Auth::User()->branch_id)->get();
            view()->share('ReginalOfficers',$reginal_officers);
            view()->share('elementDetails',$elementDetails);
            view()->share('element',$element);
            view()->share('reOffice',$reOffice);
            view()->share('gnDivision',$gnDivision);
            view()->share('agDivision',$agDivision);
            view()->share('district',$district);
            view()->share('province',$province);
            view()->share('form12',$element);
            $proof_reads=ProofRead::where('form_name','form55')->where('language','sinhala')->where('ref_number',$element->id)->get();
            $proof_reads_translate=ProofRead::where('form_name','form55')->where('language','translate')->where('ref_number',$element->id)->get();
            view()->share('proof_reads',$proof_reads);
            view()->share('proof_reads_translate',$proof_reads_translate);
            return view('pages.form55.form');
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
            $form55Details=Form55Details::where('id',$request->detail_id)->first();
            if($form55Details)
            {
                $form55Details->rejected_reason=$request->reason;
                $form55Details->rejected=1;
                $form55Details->save();
            }
            return redirect()->back()->with('success', 'Successfully!');
        }
        public function update(Request $request, $id)
        {
         try {
                $form55=Form55Header::find($id);
                DB::beginTransaction();
                switch($request->button){
                    case 'save':
                        if($request->form_name=='header'){
                            $string_gn_division=null;
                            $gn_division=$request->get('gn_division_id');
                            if($gn_division){
                                foreach($gn_division as $element){
                                    $string_gn_division=$string_gn_division.','.$element;
                                }
                            }
                            Form55Header::updateOrCreate(['id' =>$id] ,
                            [
                                'gn_division_id'=>$string_gn_division,
                                'map_no'=>$request->header_map_no,
                                'block_no'=>$request->header_block_no,
                                'lot_no'=>$request->header_lot_no,
                                'name_of_the_deceased'=>$request->name_of_the_deceased,
                                'date_of_notice'=>$request->date_of_notice,
                                'date_of_last_notice'=>$request->date_of_last_notice,
                                'gazette_number'=>$request->gazette_number,
                                'gazette_date'=>$request->gazette_date,
                                'office_of_registration'=>$request->office_of_registration,
                                'ref_no2'=>$request->ref_no2,
                            ]);
                            DB::commit();
                            return redirect()->route('form55-edit',$id)->with('success', 'Created Successfully!');
                        }else if($request->form_name=='details'){
                            $form55Header=Form55Header::where('id',$id)->first();
                            $value=$request->form55Details;
                            $from55details=Form55Details::where('form_55_header_id',$form55Header->id)->delete();
                            foreach($request->form55Details as $key=>$service){
                                $from55details=new Form55Details();
                                $from55details->form_55_header_id=$form55Header->id;
                                $from55details->map_no=$value[$key]['map_no'];
                                $from55details->block_no=$value[$key]['block_no'];
                                $from55details->lot_no=$value[$key]['lot_no'];
                                $from55details->size=$value[$key]['size'];
                                $from55details->certificate_number=$value[$key]['certificate_number'];
                                $from55details->village=$value[$key]['village'];
                                //$from55details->registerd_office=$value[$key]['registerd_office'];
                                $from55details->rejected=0;
                                $from55details->save();
                            }
                            DB::commit();

                            return redirect()->route('form55-profile',$id)->with('success', 'Updated Successfully!');
                        }
                        break;
                    case 'forward_regional_officer':
                        $form55->current_stage='Regional officer';
                        $form55->save();
                        DB::commit();
                        return redirect()->route('form55-new-requests')->with('success', 'Created Successfully!');
                        break;
                    case 'forward_regional_commisioner':
                        $form55->current_stage='Regional commissioner';
                        $form55->regional_checked=1;
                        $form55->regional_checked_by=Auth::user()->id;
                        $form55->regional_checked_date=Carbon::now();
                        $form55->save();
                        $form55Header=Form55Header::find($id);
                        $value=$request->form55Details;
                        $from55details=Form55Details::where('form_55_header_id',$form55Header->id)->delete();
                        foreach($request->form55Details as $key=>$service){
                            $from55details=new Form55Details();
                            $from55details->form_55_header_id=$form55Header->id;
                            $from55details->map_no=$value[$key]['map_no'];
                            $from55details->block_no=$value[$key]['block_no'];
                            $from55details->lot_no=$value[$key]['lot_no'];
                            $from55details->size=$value[$key]['size'];
                            $from55details->certificate_number=$value[$key]['certificate_number'];
                            $from55details->village=$value[$key]['village'];
                            // $from55details->registerd_office=$value[$key]['registerd_office'];
                            $from55details->rejected=0;
                            $from55details->save();
                        }
                        DB::commit();
                        return redirect()->route('form55-new-requests')->with('success', 'Created Successfully!');
                        break;
                    case 'regional_commisioner_approval':
                        $form55->current_stage='Publication verify';
                        $form55->regional_officer_approval=1;
                        $form55->regional_officer_approval_date=Carbon::now();
                        $form55->save();
                        $form55Header=Form55Header::find($id);
                        $value=$request->form55Details;
                        $from55details=Form55Details::where('form_55_header_id',$form55Header->id)->delete();
                        foreach($request->form55Details as $key=>$service){
                            $from55details=new Form55Details();
                            $from55details->form_55_header_id=$form55Header->id;
                            $from55details->map_no=$value[$key]['map_no'];
                            $from55details->block_no=$value[$key]['block_no'];
                            $from55details->lot_no=$value[$key]['lot_no'];
                            $from55details->size=$value[$key]['size'];
                            $from55details->certificate_number=$value[$key]['certificate_number'];
                            $from55details->village=$value[$key]['village'];
                            // $from55details->registerd_office=$value[$key]['registerd_office'];
                            $from55details->rejected=0;
                            $from55details->save();
                        }
                        DB::commit();
                        return redirect()->route('form55-new-requests')->with('success', 'Created Successfully!');
                        break;
                    case 'forward_asst_comm':
                        $form55->current_stage='Assistant commisioner';
                        $form55->publication_checked=Auth::user()->id;
                        $form55->publication_checked_date=Carbon::now();
                        $form55->save();
                        $form55Header=Form55Header::find($id);
                        $value=$request->form55Details;
                        $from55details=Form55Details::where('form_55_header_id',$form55Header->id)->delete();
                        foreach($request->form55Details as $key=>$service){
                            $from55details=new Form55Details();
                            $from55details->form_55_header_id=$form55Header->id;
                            $from55details->map_no=$value[$key]['map_no'];
                            $from55details->block_no=$value[$key]['block_no'];
                            $from55details->lot_no=$value[$key]['lot_no'];
                            $from55details->size=$value[$key]['size'];
                            $from55details->certificate_number=$value[$key]['certificate_number'];
                            $from55details->village=$value[$key]['village'];
                            // $from55details->registerd_office=$value[$key]['registerd_office'];
                            $from55details->rejected=0;
                            $from55details->save();
                        }
                        DB::commit();
                        return redirect()->route('form55-new-requests')->with('success', 'Created Successfully!');
                        break;
                    case 'forward_bim_comm':
                        $form55->current_stage='Bimsaviya commisioner';
                        $form55->computer_branch_officer=$request->computer_officer;
                        $form55->asst_com_approval=Auth::user()->id;
                        $form55->asst_com_approval_date=Carbon::now();
                        $form55->save();
                        DB::commit();
                        return redirect()->route('form55-new-requests')->with('success', 'Created Successfully!');
                        break;
                    case 'forward_comm_general':
                        $form55->current_stage='Commissioner general';
                        $form55->bimsaviya_com_approval=Auth::user()->id;
                        $form55->bimsaviya_com_approval_date=Carbon::now();
                        $form55->save();
                        DB::commit();
                        return redirect()->route('form55-new-requests')->with('success', 'Created Successfully!');
                        break;
                    case 'comm_general_approval':
                        $form55->current_stage='Computer branch';
                        $form55->commisioner_genaral_approval=Auth::user()->id;
                        $form55->commisioner_genaral_approval_date=Carbon::now();
                        $form55->save();
                        DB::commit();
                        return redirect()->route('form55-new-requests')->with('success', 'Created Successfully!');
                        break;
                    case 'forward_to_proof_read':
                        $form55->current_stage='Proof read(Sinhala)';
                        $form55->computer_checked=1;
                        $form55->computer_checked_by=Auth::user()->id;
                        $form55->computer_checked_date=Carbon::now();
                        $form55->save();
                        DB::commit();
                        return redirect()->route('form55-new-requests')->with('success', 'Created Successfully!');
                        break;
                    case 'proof_read_sinhala':
                        $form55->current_stage='Proof read(Sinhala)-Computer';
                        $form55->save();
                        $proof_details=[
                            'form_name'=>'form55',
                            'language'=>'sinhala',
                            'ref_number'=>$form55->id,
                            'proof_read_by'=>Auth::user()->id,
                            'proof_read_date'=>Carbon::now(),
                        ];
                        $proof_read=new ProofRead($proof_details);
                        $proof_read->save();
                        DB::commit();
                        return redirect()->route('form55-new-requests')->with('success', 'Created Successfully!');
                        break;
                    case 'forward_to_proof_read_translation':
                        $form55->current_stage='Proof read(Translates)';
                        $form55->sinhala_amended=1;
                        $form55->sinhala_amended_by=Auth::user()->id;
                        $form55->sinhala_amended_date=Carbon::now();
                        $form55->save();
                        DB::commit();
                        return redirect()->route('form55-new-requests')->with('success', 'Created Successfully!');
                        break;
                    case 'proof_read_translation':
                        $form55->current_stage='Proof read(Translation)-Computer';
                        $proof_details=[
                            'form_name'=>'form55',
                            'language'=>'translate',
                            'ref_number'=>$form55->id,
                            'proof_read_by'=>Auth::user()->id,
                            'proof_read_date'=>Carbon::now(),
                        ];
                        $proof_read=new ProofRead($proof_details);
                        $proof_read->save();
                        $form55->save();
                        DB::commit();
                        return redirect()->route('form55-new-requests')->with('success', 'Created Successfully!');
                        break;
                    case 'proof_read_complete':
                        $form55->current_stage='Proof read complete';
                        $form55->proof_read_complete=Auth::user()->id;
                        $form55->proof_read_complete_date=Carbon::now();
                        $form55->save();
                        DB::commit();
                        return redirect()->route('form55-new-requests')->with('success', 'Created Successfully!');
                        break;
                    case 'publication_without_G':
                        $form55->current_stage='Publication without G';
                        $form55->gazette_without=1;
                        $form55->gazette_without_by=Auth::user()->id;
                        $form55->gazette_without_date=Carbon::now();
                        $form55->save();
                        DB::commit();
                        return redirect()->route('form55-new-requests')->with('success', 'Created Successfully!');
                        break;
                    case 'press_without_G':
                        $form55->current_stage='Gov Press without G';
                        $form55->press_without=1;
                        $form55->press_without_by=Auth::user()->id;
                        $form55->press_without_date=Carbon::now();
                        $form55->save();
                        DB::commit();
                        return redirect()->route('form55-new-requests')->with('success', 'Created Successfully!');
                        break;
                    case 'computer_with_G':
                        $form55->current_stage='Gazette with G';
                        $form55->computer_with=1;
                        $form55->computer_with_by=Auth::user()->id;
                        $form55->computer_with_date=Carbon::now();
                        $form55->save();
                        DB::commit();
                        return redirect()->route('form55-new-requests')->with('success', 'Created Successfully!');
                        break;
                    case 'publication_with_G':
                        $form55->current_stage='Publication with G';
                        $form55->gazette_with=1;
                        $form55->gazette_with_by=Auth::user()->id;
                        $form55->gazette_with_date=Carbon::now();
                        $form55->save();
                        DB::commit();
                        return redirect()->route('form55-new-requests')->with('success', 'Created Successfully!');
                        break;
                    case 'press_with_G':
                        $form55->current_stage='Gov press with G';
                        $form55->sent_to_press=Auth::user()->id;
                        $form55->sent_to_press_date=Carbon::now();
                        $form55->save();
                        DB::commit();
                        return redirect()->route('form55-new-requests')->with('success', 'Created Successfully!');
                        break;
                    case 'reject':
                        $form55->rejected=1;
                        $form55->rejected_date=Carbon::now();
                        $form55->rejected_reason=$request->reason;
                        $form55->save();
                        DB::commit();
                        return redirect()->route('form55-new-requests')->with('success', 'Created Successfully!');
                        break;
                    case 'recheck':
                        $recheck_data=[
                            'ref_form_id'=>$form55->id,
                            'recheck_by'=>Auth::user()->id,
                            'recheck_stage'=>$form55->current_stage,
                            'recheck_reason'=>$request->recheck_reason,
                            'form_name'=>request()->segment(1),
                        ];
                        $recheck=new Recheck($recheck_data);
                        $recheck->save();
                        if($form55->current_stage=='Regional commissioner'){
                            $form55->current_stage='Regional data entry';
                        }else{
                            $form55->current_stage='Regional commissioner';
                        }
                        $form55->recheck=1;
                        $form55->recheck_reason=$request->recheck_reason;
                        $form55->recheck_date=Carbon::now();
                        $form55->save();
                        DB::commit();
                        return redirect()->route('form55-new-requests')->with('success', 'Created Successfully!');
                        break;
                    case 'online':
                        $form55->current_stage='Online Publish';
                        $form55->save();
                        DB::commit();
                        return redirect()->route('form55-new-requests')->with('success', 'Created Successfully!');
                        break;
                }
            } catch (Exception $e) {
                DB::rollBack();
                Log::error($e);
                return redirect()->back()->with('error', $e);
            }
        }


        public function newlist(){
            $pending=collect([]);
            $create=collect([]);$reg_verify=collect([]);$reg_approve=collect([]);$pub_verify=collect([]);$asst_comm=collect([]);$bim_comm=collect([]);
            $comm_gen=collect([]);$computer=collect([]);$proof_sinhala=collect([]);$computer_sinhala=collect([]);$proof_translate=collect([]);$computer_translate=collect([]);
            $pub_without=collect([]);$press_without=collect([]);
            if($this->getAccessRegVerify(request()->segment(1))=='Yes'){
                $reg_verify=Form55Header::where('regional_officer',Auth::user()->id)->whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('current_stage','Regional officer')->where('rejected',0)->get();
            }
            if($this->getAccessRegApprove(request()->segment(1))=='Yes'){
                $reg_approve=Form55Header::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('current_stage','Regional commissioner')->where('rejected',0)->get();
            }
            if($this->getAccessPubVerify(request()->segment(1))=='Yes'){
                $pub_verify=Form55Header::where('current_stage','Publication verify')->where('rejected',0)->get();
            }
            if($this->getAccessAsstComm(request()->segment(1))=='Yes'){
                $asst_comm=Form55Header::where('current_stage','Assistant commisioner')->where('rejected',0)->get();
            }
            if($this->getAccessBimsaviyaComm(request()->segment(1))=='Yes'){
                $bim_comm=Form55Header::where('current_stage','Bimsaviya commisioner')->where('rejected',0)->get();
            }
            if($this->getAccessCommGen(request()->segment(1))=='Yes'){
                $comm_gen=Form55Header::where('current_stage','Commissioner general')->where('rejected',0)->get();
            }
            if($this->getAccessForwardProof(request()->segment(1))=='Yes'){
                $computer=Form55Header::where('computer_branch_officer',Auth::user()->id)->where('current_stage','Computer branch')->orWhere('current_stage','Proof read(Sinhala)-Computer')->where('rejected',0)->get();
            }
            if($this->getAccessProof(request()->segment(1))=='Yes'){
                $proof_sinhala=Form55Header::where('current_stage','Proof read(Sinhala)')->where('rejected',0)->get();
            }
            if($this->getAccessForwardTransProof(request()->segment(1))=='Yes'){
                $computer_sinhala=Form55Header::where('computer_branch_officer',Auth::user()->id)->where('current_stage','Proof read(Translation)-Computer')->where('rejected',0)->get();
            }
            if($this->getAccessTransProof(request()->segment(1))=='Yes'){
                $proof_translate=Form55Header::where('current_stage','Proof read(Translates)')->where('rejected',0)->get();
            }
            if($this->getAccessForwardPublication(request()->segment(1))=='Yes'){
                $computer_translate=Form55Header::where('computer_branch_officer',Auth::user()->id)->where('current_stage','Proof read complete')->orWhere('current_stage','Gazette with G')->where('rejected',0)->get();
            }
            if($this->getAccessForwardPress(request()->segment(1))=='Yes'){
                $pub_without=Form55Header::where('current_stage','Publication without G')->orWhere('current_stage','Publication with G')->orWhere('current_stage','Gov press with G')->where('rejected',0)->get();
            }
            if($this->getAccessGazette(request()->segment(1))=='Yes'){
                $press_without=Form55Header::where('current_stage','Gov Press without G')->where('rejected',0)->get();
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
                    ->addColumn('ag_division_id', function ($newdata) {
                          $AGName  =  $this->getAGDName($newdata->ag_division_id);
                          return $AGName;

                        })
                    ->addColumn('gn_division_id', function ($newdata) {
                        $newgnname='';
                        foreach(explode(',',$newdata->gn_division_id) as $str)
                        {
                            if($str!="")
                            {
                                $gname  =  $this->getGNDName($str);
                                $newgnname=$newgnname.','.$gname;
                            }  
                        }
                        $GNName=substr($newgnname,1);
                          return $GNName;
                        })
                    ->addColumn('action', function ($newdata) {
                        $edit = '<a href="/'.request()->segment(1).'/update/'.$newdata->id.'" class="btn btn-icon btn-primary"><i class="far fa-edit"></i></a>';
                        $view = '<a href="/'.request()->segment(1).'/view/'.$newdata->id.'" class="btn btn-icon btn-info"><i class="fas fa-eye"></i></a>';
                        $delete = '<a href="javascript:;" data-toggle="modal" onclick="deleteData('.$newdata->id.')"
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

        public function getpendingdata(){
            $pending=collect([]);
            $create=collect([]);$reg_verify=collect([]);$reg_approve=collect([]);$pub_verify=collect([]);$asst_comm=collect([]);$bim_comm=collect([]);
            $comm_gen=collect([]);$computer=collect([]);$proof_sinhala=collect([]);$computer_sinhala=collect([]);$proof_translate=collect([]);$computer_translate=collect([]);
            $pub_without=collect([]);$press_without=collect([]);
            if($this->getAccessCreate(request()->segment(1))=='Yes'){
                $create=Form55Header::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('current_stage','Regional officer')->where('rejected',0)->get();
            }
            if($this->getAccessRegVerify(request()->segment(1))=='Yes'){
                $reg_verify=Form55Header::where('regional_officer',Auth::user()->id)->whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('current_stage','Regional commissioner')->where('rejected',0)->get();
            }
            if($this->getAccessRegApprove(request()->segment(1))=='Yes'){
                $reg_approve=Form55Header::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('current_stage','Publication verify')->where('rejected',0)->get();
            }
            if($this->getAccessPubVerify(request()->segment(1))=='Yes'){
                $pub_verify=Form55Header::where('current_stage','Assistant commisioner')->where('rejected',0)->get();
            }
            if($this->getAccessAsstComm(request()->segment(1))=='Yes'){
                $asst_comm=Form55Header::where('current_stage','Bimsaviya commisioner')->where('rejected',0)->get();
            }
            if($this->getAccessBimsaviyaComm(request()->segment(1))=='Yes'){
                $bim_comm=Form55Header::where('current_stage','Commissioner general')->where('rejected',0)->get();
            }
            if($this->getAccessCommGen(request()->segment(1))=='Yes'){
                $comm_gen=Form55Header::where('current_stage','Computer branch')->where('rejected',0)->get();
            }
            if($this->getAccessForwardProof(request()->segment(1))=='Yes'){
                $computer=Form55Header::where('computer_branch_officer',Auth::user()->id)->where('current_stage','Proof read(Sinhala)')->where('rejected',0)->get();
            }
            if($this->getAccessProof(request()->segment(1))=='Yes'){
                $proof_sinhala=Form55Header::where('current_stage','Proof read(Sinhala)-Computer')->where('rejected',0)->get();
            }
            if($this->getAccessForwardTransProof(request()->segment(1))=='Yes'){
                $computer_sinhala=Form55Header::where('computer_branch_officer',Auth::user()->id)->where('current_stage','Proof read(Translates)')->where('rejected',0)->get();
            }
            if($this->getAccessTransProof(request()->segment(1))=='Yes'){
                $proof_translate=Form55Header::where('current_stage','Proof read(Translation)-Computer')->where('rejected',0)->get();
            }
            if($this->getAccessForwardPublication(request()->segment(1))=='Yes'){
                $computer_translate=Form55Header::where('computer_branch_officer',Auth::user()->id)->where('current_stage','Proof read complete')->orWhere('current_stage','Publication without G')
                    ->orWhere('current_stage','Publication with G')->where('rejected',0)->get();
            }
            if($this->getAccessForwardPress(request()->segment(1))=='Yes'){
                $pub_without=Form55Header::where('current_stage','Gov Press without G')->orWhere('current_stage','Gov press with G')->where('rejected',0)->get();
            }
            if($this->getAccessGazette(request()->segment(1))=='Yes'){
                $press_without=Form55Header::where('current_stage','Gazette with G')->where('rejected',0)->get();
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
                    ->addColumn('ag_division_id', function ($newdata) {
                        $AGName  =  $this->getAGDName($newdata->ag_division_id);
                        return $AGName;

                      })
                  ->addColumn('gn_division_id', function ($newdata) {
                    $newgnname='';
                    foreach(explode(',',$newdata->gn_division_id) as $str)
                    {
                        if($str!="")
                        {
                            $gname  =  $this->getGNDName($str);
                            $newgnname=$newgnname.','.$gname;
                        }  
                    }
                    $GNName=substr($newgnname,1);
                      return $GNName;

                      })
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

        public function currentlist(){
            $current=[];
            if($this->getAccessCreate(request()->segment(1))=='Yes' || $this->getAccessRegVerify(request()->segment(1))=='Yes' || $this->getAccessRegApprove(request()->segment(1))=='Yes'){
                $current=Form55Header::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('current_stage','<>','Online Publish')->orWhere('current_stage',null)->where('rejected',0)->where('recheck',0)->get();
            }else{
                $current=Form55Header::where('current_stage','<>','Online Publish')->Where('current_stage','<>','Regional commissioner')->Where('current_stage','<>','Regional officer')->Where('current_stage','<>','Regional data entry')->where('rejected',0)->where('recheck',0)->get();
            }
            return DataTables::of($current)
                    ->addIndexColumn()
                    ->addColumn('ag_division_id', function ($newdata) {
                        $AGName  =  $this->getAGDName($newdata->ag_division_id);
                        return $AGName;

                      })
                  ->addColumn('gn_division_id', function ($newdata) {
                    $newgnname='';
                    foreach(explode(',',$newdata->gn_division_id) as $str)
                    {
                        if($str!="")
                        {
                            $gname  =  $this->getGNDName($str);
                            $newgnname=$newgnname.','.$gname;
                        }  
                    }
                    $GNName=substr($newgnname,1);
                      return $GNName;

                      })
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
        public function getrejected(){
            $rejected=[];
            if($this->getAccessCreate(request()->segment(1))=='Yes' || $this->getAccessRegVerify(request()->segment(1))=='Yes' || $this->getAccessRegApprove(request()->segment(1))=='Yes'){
                $rejected=Form55Header::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('rejected',1)->get();
            }
            else{
                $rejected=Form55Header::where('rejected',1)->get();
            }
            return DataTables::of($rejected)
                    ->addIndexColumn()
                    ->addColumn('ag_division_id', function ($newdata) {
                        $AGName  =  $this->getAGDName($newdata->ag_division_id);
                        return $AGName;

                      })
                  ->addColumn('gn_division_id', function ($newdata) {
                    $newgnname='';
                    foreach(explode(',',$newdata->gn_division_id) as $str)
                    {
                        if($str!="")
                        {
                            $gname  =  $this->getGNDName($str);
                            $newgnname=$newgnname.','.$gname;
                        }  
                    }
                    $GNName=substr($newgnname,1);
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
                $gazetted=Form55Header::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('current_stage','Online Publish')->where('rejected',0)->get();
            }else{
                $gazetted=Form55Header::where('current_stage','Online Publish')->where('rejected',0)->get();
            }
            return DataTables::of($gazetted)
                    ->addIndexColumn()
                    ->addColumn('ag_division_id', function ($newdata) {
                        $AGName  =  $this->getAGDName($newdata->ag_division_id);
                        return $AGName;

                      })
                  ->addColumn('gn_division_id', function ($newdata) {
                    $newgnname='';
                    foreach(explode(',',$newdata->gn_division_id) as $str)
                    {
                        if($str!="")
                        {
                            $gname  =  $this->getGNDName($str);
                            $newgnname=$newgnname.','.$gname;
                        }  
                    }
                    $GNName=substr($newgnname,1);
                      return $GNName;

                      })
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
