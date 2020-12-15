<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GnDivisions;
use App\Models\Districts;
use App\Models\UserRolePermissions;
use App\Models\RegionalOffices;
use App\Models\Provinces;
use App\Models\AgDivisions;
use App\Models\AmendmentsHeader;
use App\Models\AmendmentsDetails;
use App\Models\AmendmentsNewDetails;
use App\Models\Form14Header;
use App\Models\ProofRead;
use App\Models\Village;
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

class AmendmentsController extends Controller
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
            return view('pages.amendments.new_requests');
        }
        public function approved()
        {
            return view('pages.amendments.approved');
        }
        public function pending()
        {
            return view('pages.amendments.pending');
        }
        public function rejected()
        {
            return view('pages.amendments.rejected');
        }
        public function recheck()
        {
            return view('pages.amendments.recheck');
        }
        public function gazetted()
        {
            return view('pages.amendments.gazetted');
        }


        /**
         * Show the form for creating a new resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function create()
        {
            $province=Provinces::where('is_active',1)->get();
            $district=Districts::where('is_active',1)->get();
            $agDivision=AgDivisions::where('is_active',1)->get();
            $gnDivision=GnDivisions::where('is_active',1)->get();
            $villages=Village::where('gn_division',Auth::user()->branch_id)->get();
            $reOffice=RegionalOffices::where('is_active',1)->get();
            $computer_officers=User::whereIn('role_code',UserRolePermissions::select('role_code')->where('is_enable',1)->where('module_code',request()->segment(1))->where('can_forward_to_proof',1)->distinct('module_code')->get())->get();
            view()->share('computer_officers',$computer_officers);
            $reginal_officers=User::whereIn('role_code',UserRolePermissions::select('role_code')->where('is_enable',1)->where('module_code',request()->segment(1))->where('can_verify',1)->distinct('module_code')->get())->where('branch_id',Auth::User()->branch_id)->get();
            $map_number=Form14Header::where('rejected',0)->where('current_stage','Online Publish')->where('ag_division_id',Auth::user()->branch_id)->distinct()->get('map_no');
            view()->share('lot_numbers',[]);
            view()->share('block_numbers',[]);
            view()->share('map_numbers',$map_number);
            view()->share('newelementDetails',null);
            view()->share('element',null);
            view()->share('ReginalOfficers',$reginal_officers);
            view()->share('elementDetails',[]);
            view()->share('reOffice',$reOffice);
            view()->share('gnDivision',$gnDivision);
            view()->share('agDivision',$agDivision);
            view()->share('district',$district);
            view()->share('province',$province);
            view()->share('form12',null);
            view()->share('villages',$villages);
            
            return view('pages.amendments.form');
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function store(Request $request)
        {
            // dd($request->all());
            try{
                DB::beginTransaction();
                $string_gns=null;
                if(isset($request->gn_div_id))
                {
                $gn_divisions=$request->gn_div_id;
                if(sizeof($gn_divisions)>0){
                    foreach($gn_divisions as $element){
                        $string_gns=$string_gns.','.$element;
                    }
                    if(isset($string_gns))
                    {
                    $string_gns=substr($string_gns,1);
                    }
                }
                }

                $columString=null;
                if(isset($request->column_name))
                {
                foreach($request->column_name as $key=>$service)
                {
                    $columString=$columString.','.$service;
                }
                if(isset($columString))
                {
                    $columString=substr($columString,1);
                }
                }

                $form14=Form14Header::join('form_14_detail','form_14_header.id','=','form_14_detail.form_14_Header_id')
                ->join('users','form_14_header.regional_officer','=','users.id')
                ->where('form_14_header.map_no',$request->map_no)->where('form_14_header.block_no',$request->block_no)->where('form_14_detail.lot_no',$request->lot_no)
                ->where('form_14_header.rejected',0)->where('form_14_detail.rejected',0)
                ->select('form_14_detail.*','form_14_header.gn_division_id','form_14_header.village_name','form_14_header.gazetted_date','form_14_header.gazetted_no','users.name as regional')->first();

                $columns=$request->column_name;
                $column_value_string="";
                if(isset($request->column_name)){
                    foreach($columns as $key=>$service)
                    {
                        $column_value_string=$column_value_string.','.$form14->$service;
                    }
                    if(isset($column_value_string))
                    {
                        $column_value_string=substr($column_value_string,1);
                    }
                }
                


                $reginal_officer=User::where('name',$request->regional_officer)->first();
                $amendmentheader= AmendmentsHeader::Create([
                'province_id' => $request->province_id,
                'district_id'=>$request->district_id,
                'ag_division_id'=>$request->ag_div_id,
                'gn_division_id'=>$string_gns,
                'village'=>$request->village,
                'map_no'=>$request->map_no,
                'block_no'=>$request->block_no,
                'lot_no'=>$request->lot_no,
                '_14_gazzert'=>$form14->gazetted_no,
                '_14_gazette_date'=>$form14->gazetted_date,
                'column_name'=> $columString,
                'column_value_to_be_changed'=>$column_value_string,
                'column_new_value'=>$request->column_new_value,
                'reasons'=>$request->reasons,
                'complain_date'=>$request->complain_date,
                'current_stage'=>'Regional data entry',
                'rejected'=>0,
                'prepared_by'=>Auth::user()->id,
                'prepared_date'=>Carbon::now(),
                'regional_officer'=>$reginal_officer->id,
                
            ]);

                $string_gn_division=null;
                if(isset($request->new['owner_details_gn_division_id']))
                {
                $gn_division=$request->new['owner_details_gn_division_id'];
                if($gn_division){
                    foreach($gn_division as $element){
                        $string_gn_division=$string_gn_division.','.$element;
                    }
                    if(isset($string_gn_division))
                    {
                    $string_gn_division=substr($string_gn_division,1);
                    }
                }
                }
                $amendment_new_details= new AmendmentsNewDetails();
                $amendment_new_details->amendments_header_id=$amendmentheader->id;
                if(isset($request->new['lot_no']))
                {
                $amendment_new_details->lot_no=$request->new['lot_no'];
                }
                if(isset($request->new['name']))
                {
                $amendment_new_details->name=$request->new['name'];
                }
                if(isset($request->new['address']))
                {
                $amendment_new_details->addres=$request->new['address'];
                }
                if(isset($request->new['nic']))
                {
                $amendment_new_details->nic_number=$request->new['nic'];
                }
                if(isset($request->new['size']))
                {
                $amendment_new_details->size=$request->new['size'];
                }
                if(isset($request->new['natowner']))
                {
                $amendment_new_details->ownership_type=$request->new['natowner'];
                }
                if(isset($request->new['class']))
                {
                $amendment_new_details->class=$request->new['class'];
                }
                if(isset($request->new['mortgages']))
                {
                $amendment_new_details->mortgages=$request->new['mortgages'];
                }
                if(isset($request->new['other']))
                {
                $amendment_new_details->other_boudages=$request->new['other'];
                }
                if(isset($request->new['land_type']))
                {
                $amendment_new_details->type=$request->new['land_type'];
                }
                $amendment_new_details->owner_details_gn_division_id=$string_gn_division;
                if(isset($request->new['sub_type']))
                {
                $amendment_new_details->sub_type=$request->new['sub_type'];
                }
                $amendment_new_details->save();
                DB::commit();
                return redirect()->route('amendments-edit',$amendmentheader->id)->with('success', 'Created Successfully!');

               } catch (Exception $e) {
                   DB::rollBack();
                  Log::error($e);
                  dd($e);
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
            $element=AmendmentsHeader::where('id',$id)->first();
            $amdGnDivision=[];
            foreach(explode(',',$element->gn_division_id) as $str)
            {
                if($str!="")
                {
                   $gn_id=GnDivisions::where('id',$str)->where('is_active',1)->first();
                   array_push($amdGnDivision,(object)[
                       'id'=>$gn_id->id,
                       'gn_name'=>$gn_id->gn_name,
                       'sinhala_name'=>$gn_id->sinhala_name
                   ]);
                }
            }
            view()->share('amdGnDivision',$amdGnDivision);
            $elementDetails=AmendmentsDetails::where('amendment_header_id',$element->id)->get();
            $newelementDetails=AmendmentsNewDetails::where('amendments_header_id',$element->id)->orderBy('id','DESC')->first();
            $province=Provinces::where('is_active',1)->get();
            $district=Districts::where('is_active',1)->get();
            $agDivision=AgDivisions::where('is_active',1)->get();
            $gnDivision=GnDivisions::where('is_active',1)->get();
            $reOffice=RegionalOffices::where('is_active',1)->get();
            $computer_officers=User::whereIn('role_code',UserRolePermissions::select('role_code')->where('is_enable',1)->where('module_code',request()->segment(1))->where('can_forward_to_proof',1)->distinct('module_code')->get())->get();
            view()->share('computer_officers',$computer_officers);
            $reginal_officers=User::whereIn('role_code',UserRolePermissions::select('role_code')->where('is_enable',1)->where('module_code',request()->segment(1))->where('can_verify',1)->distinct('module_code')->get())->where('branch_id',Auth::User()->branch_id)->get();
            $amendmentRegenioalOfficer=User::Where('id',$element->regional_officer)->first();
            if($element->current_stage=='Regional data entry' || $element->current_stage=='Regional officer' || $element->current_stage=='Regional commissioner' )
            {
                $map_number=Form14Header::where('rejected',0)->where('current_stage','Online Publish')->where('ag_division_id',Auth::user()->branch_id)->distinct()->get('map_no');
                $block_number=Form14Header::where('rejected',0)->where('current_stage','Online Publish')->where('ag_division_id',Auth::user()->branch_id)->where('map_no',$element->map_no)->distinct()->get('block_no');
            }
            else
            {
                $map_number=Form14Header::distinct()->get('map_no');
                $block_number=Form14Header::where('map_no',$element->map_no)->distinct()->get('block_no');
            }
            if($element)
            {
            if($element->current_stage=='Regional data entry' || $element->current_stage=='Regional officer' || $element->current_stage=='Regional commissioner' )
            {
                $lot_number=DB::table('form_14_header')
                ->join('form_14_detail','form_14_header.id','=','form_14_detail.form_14_Header_id')
                ->where('form_14_header.map_no',$element->map_no)->where('form_14_header.block_no',$element->block_no)
                ->where('form_14_header.rejected',0)->where('form_14_header.current_stage','Online Publish')
                ->where('form_14_header.ag_division_id',Auth::user()->branch_id)
                ->get();
            }
            else
            {
                $lot_number=DB::table('form_14_header')
                ->join('form_14_detail','form_14_header.id','=','form_14_detail.form_14_Header_id')
                ->where('form_14_header.map_no',$element->map_no)->where('form_14_header.block_no',$element->block_no)
                ->get();
            }
            }
            else
            {
                $block_number=[];
                $lot_number=[];
            }
            view()->share('lot_numbers',$lot_number);
            view()->share('block_numbers',$block_number);
            view()->share('map_numbers',$map_number);
            view()->share('newelementDetails',$newelementDetails);
            view()->share('amendmentRegenioalOfficer',$amendmentRegenioalOfficer);
            view()->share('ReginalOfficers',$reginal_officers);
            view()->share('elementDetails',$elementDetails);
            view()->share('element',$element);
            view()->share('reOffice',$reOffice);
            view()->share('gnDivision',$gnDivision);
            view()->share('agDivision',$agDivision);
            view()->share('district',$district);
            view()->share('province',$province);
            view()->share('form12',$element);
            $proof_reads=ProofRead::where('form_name','amendment')->where('language','sinhala')->where('ref_number',$element->id)->get();
            $proof_reads_translate=ProofRead::where('form_name','amendment')->where('language','translate')->where('ref_number',$element->id)->get();
            view()->share('proof_reads',$proof_reads);
            view()->share('proof_reads_translate',$proof_reads_translate);
            return view('pages.amendments.form');
        }

        /**
         * Show the form for editing the specified resource.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */

         public function getDetails($id)
         {
            $elementDetails=AmendmentsDetails::where('amendment_header_id',$id)->where('rejected',0)->get();
            return response()->json([
                'message'   => $elementDetails,
                'class_name'  => 'alert-success',
                'status'=>200
               ]);
         }
        public function edit($id)
        {
            $element=AmendmentsHeader::where('id',$id)->first();
            $amdGnDivision=[];
            foreach(explode(',',$element->gn_division_id) as $str)
            {
                if($str!="")
                {
                   $gn_id=GnDivisions::where('id',$str)->where('is_active',1)->first();
                   array_push($amdGnDivision,(object)[
                       'id'=>$gn_id->id,
                       'gn_name'=>$gn_id->gn_name,
                       'sinhala_name'=>$gn_id->sinhala_name
                   ]);
                }
            }
            view()->share('amdGnDivision',$amdGnDivision);
            $elementDetails=AmendmentsDetails::where('amendment_header_id',$element->id)->get();
            $newelementDetails=AmendmentsNewDetails::where('amendments_header_id',$element->id)->orderBy('id','DESC')->first();
            $province=Provinces::where('is_active',1)->get();
            $district=Districts::where('is_active',1)->get();
            $agDivision=AgDivisions::where('is_active',1)->get();
            $gnDivision=GnDivisions::where('is_active',1)->get();
            $reOffice=RegionalOffices::where('is_active',1)->get();
            $computer_officers=User::whereIn('role_code',UserRolePermissions::select('role_code')->where('is_enable',1)->where('module_code',request()->segment(1))->where('can_forward_to_proof',1)->distinct('module_code')->get())->get();
            view()->share('computer_officers',$computer_officers);
            $reginal_officers=User::whereIn('role_code',UserRolePermissions::select('role_code')->where('is_enable',1)->where('module_code',request()->segment(1))->where('can_verify',1)->distinct('module_code')->get())->where('branch_id',Auth::User()->branch_id)->get();
            $amendmentRegenioalOfficer=User::Where('id',$element->regional_officer)->first();
            if($element->current_stage=='Regional data entry' || $element->current_stage=='Regional officer' || $element->current_stage=='Regional commissioner' )
            {
                $map_number=Form14Header::where('rejected',0)->where('current_stage','Online Publish')->where('ag_division_id',Auth::user()->branch_id)->distinct()->get('map_no');
                $block_number=Form14Header::where('rejected',0)->where('current_stage','Online Publish')->where('ag_division_id',Auth::user()->branch_id)->where('map_no',$element->map_no)->distinct()->get('block_no');
            }
            else
            {
                $map_number=Form14Header::distinct()->get('map_no');
                $block_number=Form14Header::where('map_no',$element->map_no)->distinct()->get('block_no');
            }
            if($element)
            {
            if($element->current_stage=='Regional data entry' || $element->current_stage=='Regional officer' || $element->current_stage=='Regional commissioner' )
            {
            $lot_number=DB::table('form_14_header')
            ->join('form_14_detail','form_14_header.id','=','form_14_detail.form_14_Header_id')
            ->where('form_14_header.map_no',$element->map_no)->where('form_14_header.block_no',$element->block_no)
            ->where('form_14_header.rejected',0)->where('form_14_header.current_stage','Online Publish')
            ->where('form_14_header.ag_division_id',Auth::user()->branch_id)
            ->get();
            }
            else
            {
                $lot_number=DB::table('form_14_header')
                ->join('form_14_detail','form_14_header.id','=','form_14_detail.form_14_Header_id')
                ->where('form_14_header.map_no',$element->map_no)->where('form_14_header.block_no',$element->block_no)
                ->get();
            }
            }
            else
            {
                $block_number=[];
                $lot_number=[];
            }
            view()->share('lot_numbers',$lot_number);
            view()->share('block_numbers',$block_number);
            view()->share('map_numbers',$map_number);
            view()->share('newelementDetails',$newelementDetails);
            view()->share('amendmentRegenioalOfficer',$amendmentRegenioalOfficer);
            view()->share('ReginalOfficers',$reginal_officers);
            view()->share('elementDetails',$elementDetails);
            view()->share('element',$element);
            view()->share('reOffice',$reOffice);
            view()->share('gnDivision',$gnDivision);
            view()->share('agDivision',$agDivision);
            view()->share('district',$district);
            view()->share('province',$province);
            view()->share('form12',$element);
            $proof_reads=ProofRead::where('form_name','amendment')->where('language','sinhala')->where('ref_number',$element->id)->get();
            $proof_reads_translate=ProofRead::where('form_name','amendment')->where('language','translate')->where('ref_number',$element->id)->get();
            view()->share('proof_reads',$proof_reads);
            view()->share('proof_reads_translate',$proof_reads_translate);
            return view('pages.amendments.form');
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
            $amendmentDetails=AmendmentsDetails::where('id',$request->detail_id)->first();
            if($amendmentDetails)
            {
                $amendmentDetails->rejected_reason=$request->reason;
                $amendmentDetails->rejected=1;
                $amendmentDetails->save();
            }
            return redirect()->back()->with('success', 'Successfully!');
        }
        public function update(Request $request, $id)
        {
            try {
                DB::beginTransaction();
                $amendment=AmendmentsHeader::find($id);
                switch($request->button){
                    case 'save':
                        if($request->form_name=='header'){
                            $columString=null;
                            if(isset($request->column_name))
                            {
                            foreach($request->column_name as $key=>$service)
                            {
                                $columString=$columString.','.$service;
                            }
                            if(isset($columString))
                            {
                                $columString=substr($columString,1);
                            }
                            }
                            $reginal_officer=User::where('name',$request->regional_officer)->first();
                            AmendmentsHeader::updateOrCreate(['id' =>$id] ,
                            [
                                'village'=>$request->village,
                                'map_no'=>$request->map_no,
                                'block_no'=>$request->block_no,
                                'lot_no'=>$request->lot_no,
                                '_14_gazzert'=>$request->_14_gazzert,
                                '_14_gazette_date'=>$request->_14_gazette_date,
                                'column_name'=>$columString,
                                //'column_value_to_be_changed'=>$request->column_value_to_be_changed,
                                //'column_new_value'=>$request->column_new_value,
                                'reasons'=>$request->reasons,
                                'complain_date'=>$request->complain_date,
                                'gazette_no'=>$request->gazette_no,
                                'gazette_date'=>$request->gazette_date,
                                'regional_officer'=>$reginal_officer->id,
                                'comment'=>'tst',
                            ]);
                            $string_gn_division=null;
                            if(isset($request->new['owner_details_gn_division_id']))
                            {
                            $gn_division=$request->new['owner_details_gn_division_id'];
                            if($gn_division){
                                foreach($gn_division as $element){

                                    $string_gn_division=$string_gn_division.','.$element;
                                }
                                if(isset($string_gn_division))
                                {
                                $string_gn_division=substr($string_gn_division,1);
                                }
                            }
                            }
                            if(isset($columString))
                            {
                            $amendment_new_details= new AmendmentsNewDetails();
                            $amendment_new_details->amendments_header_id=$id;
                            if(isset($request->new['lot_no']))
                            {
                            $amendment_new_details->lot_no=$request->new['lot_no'];
                            }
                            if(isset($request->new['name']))
                            {
                            $amendment_new_details->name=$request->new['name'];
                            }
                            if(isset($request->new['address']))
                            {
                            $amendment_new_details->addres=$request->new['address'];
                            }
                            if(isset($request->new['nic']))
                            {
                            $amendment_new_details->nic_number=$request->new['nic'];
                            }
                            if(isset($request->new['size']))
                            {
                            $amendment_new_details->size=$request->new['size'];
                            }
                            if(isset($request->new['natowner']))
                            {
                            $amendment_new_details->ownership_type=$request->new['natowner'];
                            }
                            if(isset($request->new['class']))
                            {
                            $amendment_new_details->class=$request->new['class'];
                            }
                            if(isset($request->new['mortgages']))
                            {
                            $amendment_new_details->mortgages=$request->new['mortgages'];
                            }
                            if(isset($request->new['other']))
                            {
                            $amendment_new_details->other_boudages=$request->new['other'];
                            }
                            if(isset($request->new['land_type']))
                            {
                            $amendment_new_details->type=$request->new['land_type'];
                            }
                            $amendment_new_details->owner_details_gn_division_id=$string_gn_division;
                            if(isset($request->new['sub_type']))
                            {
                            $amendment_new_details->sub_type=$request->new['sub_type'];
                            }
                            $amendment_new_details->save();
                            }
                            DB::commit();
                            return redirect()->route('amendments-edit',$id)->with('success', 'Created Successfully!');
                            }else if($request->form_name=='details'){
                            $amendmentHeader=AmendmentsHeader::find($id);
                            $value=$request->amendmentDetails;
                            $amendmentdetails=AmendmentsDetails::where('amendment_header_id',$amendmentHeader->id)->delete();
                            foreach($request->amendmentDetails as $key=>$service){
                                $amendmentdetails=new AmendmentsDetails();
                                $amendmentdetails->amendment_header_id=$amendmentHeader->id;
                                $amendmentdetails->nature_if_identification=$value[$key]['nature_if_identification'];
                                $amendmentdetails->document_evidence=$value[$key]['document_evidence'];
                                $amendmentdetails->parties_noticed=$value[$key]['parties_noticed'];
                                $amendmentdetails->conclution=$value[$key]['conclution'];
                                $amendmentdetails->name_of_the_officer=$value[$key]['name_of_the_officer'];
                                $amendmentdetails->rejected=0;
                                $amendmentdetails->save();
                            }
                            DB::commit();
                          //  return redirect()->back()->with('success', 'Created Successfully!');
                            return redirect()->route('amendments-profile',$id)->with('success', 'Updated Successfully!');
                        }
                    case 'forward_regional_officer':
                        $amendment->current_stage='Regional officer';
                        $amendment->comment=$request->comment;
                        $amendment->save();
                        $amendmentHeader=AmendmentsHeader::find($id);
                        $value=$request->amendmentDetails;
                        $amendmentdetails=AmendmentsDetails::where('amendment_header_id',$amendmentHeader->id)->delete();
                        foreach($request->amendmentDetails as $key=>$service){
                            $amendmentdetails=new AmendmentsDetails();
                            $amendmentdetails->amendment_header_id=$amendmentHeader->id;
                            $amendmentdetails->nature_if_identification=$value[$key]['nature_if_identification'];
                            $amendmentdetails->document_evidence=$value[$key]['document_evidence'];
                            $amendmentdetails->parties_noticed=$value[$key]['parties_noticed'];
                            $amendmentdetails->conclution=$value[$key]['conclution'];
                            $amendmentdetails->name_of_the_officer=$value[$key]['name_of_the_officer'];
                            $amendmentdetails->rejected=0;
                            $amendmentdetails->save();
                        }
                        DB::commit();
                        return redirect()->route('amendments-new-requests')->with('success', 'Created Successfully!');
                        break;
                    case 'forward_regional_commisioner':
                        $amendment->current_stage='Regional commissioner';
                        $amendment->regional_checked=1;
                        $amendment->regional_checked_by=Auth::user()->id;
                        $amendment->regional_checked_date=Carbon::now();
                        $amendment->comment=$request->comment;
                        $amendment->save();
                        $amendmentHeader=AmendmentsHeader::find($id);
                        $value=$request->amendmentDetails;
                        $amendmentdetails=AmendmentsDetails::where('amendment_header_id',$amendmentHeader->id)->delete();
                        foreach($request->amendmentDetails as $key=>$service){
                            $amendmentdetails=new AmendmentsDetails();
                            $amendmentdetails->amendment_header_id=$amendmentHeader->id;
                            $amendmentdetails->nature_if_identification=$value[$key]['nature_if_identification'];
                            $amendmentdetails->document_evidence=$value[$key]['document_evidence'];
                            $amendmentdetails->parties_noticed=$value[$key]['parties_noticed'];
                            $amendmentdetails->conclution=$value[$key]['conclution'];
                            $amendmentdetails->name_of_the_officer=$value[$key]['name_of_the_officer'];
                            $amendmentdetails->rejected=0;
                            $amendmentdetails->save();
                        }
                        DB::commit();
                        return redirect()->route('amendments-new-requests')->with('success', 'Created Successfully!');
                        break;
                    case 'regional_commisioner_approval':
                        $amendment->current_stage='Publication verify';
                        $amendment->regional_office_approval=Auth::user()->id;
                        $amendment->regional_office_approved_date=Carbon::now();
                        $amendment->comment=$request->comment;
                        $amendment->save();
                        $amendmentHeader=AmendmentsHeader::find($id);
                        $value=$request->amendmentDetails;
                        $amendmentdetails=AmendmentsDetails::where('amendment_header_id',$amendmentHeader->id)->delete();
                        foreach($request->amendmentDetails as $key=>$service){
                            $amendmentdetails=new AmendmentsDetails();
                            $amendmentdetails->amendment_header_id=$amendmentHeader->id;
                            $amendmentdetails->nature_if_identification=$value[$key]['nature_if_identification'];
                            $amendmentdetails->document_evidence=$value[$key]['document_evidence'];
                            $amendmentdetails->parties_noticed=$value[$key]['parties_noticed'];
                            $amendmentdetails->conclution=$value[$key]['conclution'];
                            $amendmentdetails->name_of_the_officer=$value[$key]['name_of_the_officer'];
                            $amendmentdetails->rejected=0;
                            $amendmentdetails->save();
                        }
                        DB::commit();
                        return redirect()->route('amendments-new-requests')->with('success', 'Created Successfully!');
                        break;
                    case 'forward_asst_comm':
                        $amendment->current_stage='Assistant commisioner';
                        $amendment->publication_verify=1;
                        $amendment->publication_verify_by=Auth::user()->id;
                        $amendment->publication_verify_date=Carbon::now();
                        $amendment->comment=$request->comment;
                        $amendment->save();
                        $amendmentHeader=AmendmentsHeader::find($id);
                        $value=$request->amendmentDetails;
                        $amendmentdetails=AmendmentsDetails::where('amendment_header_id',$amendmentHeader->id)->delete();
                        foreach($request->amendmentDetails as $key=>$service){
                            $amendmentdetails=new AmendmentsDetails();
                            $amendmentdetails->amendment_header_id=$amendmentHeader->id;
                            $amendmentdetails->nature_if_identification=$value[$key]['nature_if_identification'];
                            $amendmentdetails->document_evidence=$value[$key]['document_evidence'];
                            $amendmentdetails->parties_noticed=$value[$key]['parties_noticed'];
                            $amendmentdetails->conclution=$value[$key]['conclution'];
                            $amendmentdetails->name_of_the_officer=$value[$key]['name_of_the_officer'];
                            $amendmentdetails->rejected=0;
                            $amendmentdetails->save();
                        }
                        DB::commit();
                        return redirect()->route('amendments-new-requests')->with('success', 'Created Successfully!');
                        break;
                    case 'forward_bim_comm':
                        $amendment->current_stage='Bimsaviya commisioner';
                        $amendment->computer_branch_officer=$request->computer_officer;
                        $amendment->asst_com_approval=Auth::user()->id;
                        $amendment->asst_com_approval_date=Carbon::now();
                        $amendment->comment=$request->comment;
                        $amendment->save();
                        DB::commit();
                        return redirect()->route('amendments-new-requests')->with('success', 'Created Successfully!');
                        break;
                    case 'forward_comm_general':
                        $amendment->current_stage='Commissioner general';
                        $amendment->bimsaviya_com_approval=Auth::user()->id;
                        $amendment->bimsaviya_com_approval_date=Carbon::now();
                        $amendment->comment=$request->comment;
                        $amendment->save();
                        DB::commit();
                        return redirect()->route('amendments-new-requests')->with('success', 'Created Successfully!');
                        break;
                    case 'comm_general_approval':
                        $amendment->current_stage='Computer branch';
                        $amendment->commissioner_general_apprival=Auth::user()->id;
                        $amendment->commissioner_general_apprival_date=Carbon::now();
                        $amendment->comment=$request->comment;
                        $amendment->save();
                        DB::commit();
                        return redirect()->route('amendments-new-requests')->with('success', 'Created Successfully!');
                        break;
                    case 'forward_to_proof_read':
                        $amendment->current_stage='Proof read(Sinhala)';
                        $amendment->computer_checked=1;
                        $amendment->computer_checked_by=Auth::user()->id;
                        $amendment->computer_checked_date=Carbon::now();
                        $amendment->comment=$request->comment;
                        $amendment->save();
                        DB::commit();
                        return redirect()->route('amendments-new-requests')->with('success', 'Created Successfully!');
                        break;
                    case 'proof_read_sinhala':
                        $amendment->current_stage='Proof read(Sinhala)-Computer';
                        $amendment->comment=$request->comment;
                        $amendment->save();
                        $proof_details=[
                            'form_name'=>'amendment',
                            'language'=>'sinhala',
                            'ref_number'=>$amendment->id,
                            'proof_read_by'=>Auth::user()->id,
                            'proof_read_date'=>Carbon::now(),
                        ];
                        $proof_read=new ProofRead($proof_details);
                        $proof_read->save();
                        DB::commit();
                        return redirect()->route('amendments-new-requests')->with('success', 'Created Successfully!');
                        break;
                    case 'forward_to_proof_read_translation':
                        $amendment->current_stage='Proof read(Translates)';
                        $amendment->sinhala_amended=1;
                        $amendment->sinhala_amended_by=Auth::user()->id;
                        $amendment->sinhala_amended_date=Carbon::now();
                        $amendment->comment=$request->comment;
                        $amendment->save();
                        DB::commit();
                        return redirect()->route('amendments-new-requests')->with('success', 'Created Successfully!');
                        break;
                    case 'proof_read_translation':
                        $amendment->current_stage='Proof read(Translation)-Computer';
                        $amendment->comment=$request->comment;
                        $amendment->save();
                        $proof_details=[
                            'form_name'=>'amendment',
                            'language'=>'translate',
                            'ref_number'=>$amendment->id,
                            'proof_read_by'=>Auth::user()->id,
                            'proof_read_date'=>Carbon::now(),
                        ];
                        $proof_read=new ProofRead($proof_details);
                        $proof_read->save();
                        DB::commit();
                        return redirect()->route('amendments-new-requests')->with('success', 'Created Successfully!');
                        break;
                    case 'proof_read_complete':
                        $amendment->current_stage='Proof read complete';
                        $amendment->proof_read_complete_by=Auth::user()->id;
                        $amendment->proof_read_complete_date=Carbon::now();
                        $amendment->comment=$request->comment;
                        $amendment->save();
                        DB::commit();
                        return redirect()->route('amendments-new-requests')->with('success', 'Created Successfully!');
                        break;
                    case 'publication_without_G':
                        $amendment->current_stage='Publication without G';
                        $amendment->gazette_without=1;
                        $amendment->gazette_without_by=Auth::user()->id;
                        $amendment->gazette_without_date=Carbon::now();
                        $amendment->comment=$request->comment;
                        $amendment->save();
                        DB::commit();
                        return redirect()->route('amendments-new-requests')->with('success', 'Created Successfully!');
                        break;
                    case 'press_without_G':
                        $amendment->current_stage='Gov Press without G';
                        $amendment->press_without=1;
                        $amendment->press_without_by=Auth::user()->id;
                        $amendment->press_without_date=Carbon::now();
                        $amendment->comment=$request->comment;
                        $amendment->save();
                        DB::commit();
                        return redirect()->route('amendments-new-requests')->with('success', 'Created Successfully!');
                        break;
                    case 'computer_with_G':
                        $amendment->current_stage='Gazette with G';
                        $amendment->computer_with=1;
                        $amendment->computer_with_by=Auth::user()->id;
                        $amendment->computer_with_date=Carbon::now();
                        $amendment->comment=$request->comment;
                        $amendment->save();
                        DB::commit();
                        return redirect()->route('amendments-new-requests')->with('success', 'Created Successfully!');
                        break;
                    case 'publication_with_G':
                        $amendment->current_stage='Publication with G';
                        $amendment->gazette_with=1;
                        $amendment->gazette_with_by=Auth::user()->id;
                        $amendment->gazette_with_date=Carbon::now();
                        $amendment->comment=$request->comment;
                        $amendment->save();
                        DB::commit();
                        return redirect()->route('amendments-new-requests')->with('success', 'Created Successfully!');
                        break;
                    case 'press_with_G':
                        $amendment->current_stage='Gov press with G';
                        $amendment->sent_to_gov_press=Auth::user()->id;
                        $amendment->sent_to_gov_press_date=Carbon::now();
                        $amendment->comment=$request->comment;
                        $amendment->save();
                        DB::commit();
                        return redirect()->route('amendments-new-requests')->with('success', 'Created Successfully!');
                        break;
                    case 'reject':
                        $amendment->rejected=1;
                        $amendment->rejected_date=Carbon::now();
                        $amendment->rejected_reason=$request->reason;
                        $amendment->save();
                        DB::commit();
                        return redirect()->route('amendments-new-requests')->with('success', 'Created Successfully!');
                        break;
                    case 'recheck':
                        $recheck_data=[
                            'ref_form_id'=>$amendment->id,
                            'recheck_by'=>Auth::user()->id,
                            'recheck_stage'=>$amendment->current_stage,
                            'recheck_reason'=>$request->recheck_reason,
                            'form_name'=>request()->segment(1),
                        ];
                        $recheck=new Recheck($recheck_data);
                        $recheck->save();
                        if($amendment->current_stage=='Regional commissioner'){
                            $amendment->current_stage='Regional data entry';
                        }else{
                            $amendment->current_stage='Regional commissioner';
                        }
                        $amendment->recheck=1;
                        $amendment->recheck_reason=$request->recheck_reason;
                        $amendment->recheck_date=Carbon::now();
                        $amendment->save();
                        DB::commit();
                        return redirect()->route('amendments-new-requests')->with('success', 'Created Successfully!');
                        break;
                    case 'online':
                        $amendment->current_stage='Online Publish';
                        $amendment->comment=$request->comment;
                        $amendment->save();
                        DB::commit();
                        return redirect()->route('amendments-new-requests')->with('success', 'Created Successfully!');
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
            //  $reg_verify=AmendmentsHeader::where('regional_officer',Auth::user()->id)->whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
            //         ->where('current_stage','Regional officer')->where('rejected',0)->get();
            //     $reg_verify=AmendmentsHeader::where('regional_officer',Auth::user()->id)
            //    -> where('current_stage','Regional officer')->where('rejected',0)->get();
            $reg_verify=AmendmentsHeader::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('current_stage','Regional officer')->where('rejected',0)->get();

                }
            if($this->getAccessRegApprove(request()->segment(1))=='Yes'){
                $reg_approve=AmendmentsHeader::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('current_stage','Regional commissioner')->where('rejected',0)->get();
            }
            if($this->getAccessPubVerify(request()->segment(1))=='Yes'){
                $pub_verify=AmendmentsHeader::where('current_stage','Publication verify')->where('ref_no',null)->where('rejected',0)->get();
            }
            if($this->getAccessAsstComm(request()->segment(1))=='Yes'){
                $asst_comm=AmendmentsHeader::where('current_stage','Assistant commisioner')->where('rejected',0)->get();
            }
            if($this->getAccessBimsaviyaComm(request()->segment(1))=='Yes'){
                $bim_comm=AmendmentsHeader::where('current_stage','Bimsaviya commisioner')->where('rejected',0)->get();
            }
            if($this->getAccessCommGen(request()->segment(1))=='Yes'){
                $comm_gen=AmendmentsHeader::where('current_stage','Commissioner general')->where('rejected',0)->get();
            }
            if($this->getAccessForwardProof(request()->segment(1))=='Yes'){
                $computer=AmendmentsHeader::where('computer_branch_officer',Auth::user()->id)->where('current_stage','Computer branch')->orWhere('current_stage','Proof read(Sinhala)-Computer')->where('rejected',0)->get();
            }
            if($this->getAccessProof(request()->segment(1))=='Yes'){
                $proof_sinhala=AmendmentsHeader::where('current_stage','Proof read(Sinhala)')->where('rejected',0)->get();
            }
            if($this->getAccessForwardTransProof(request()->segment(1))=='Yes'){
                $computer_sinhala=AmendmentsHeader::where('computer_branch_officer',Auth::user()->id)->where('current_stage','Proof read(Translation)-Computer')->where('rejected',0)->get();
            }
            if($this->getAccessTransProof(request()->segment(1))=='Yes'){
                $proof_translate=AmendmentsHeader::where('current_stage','Proof read(Translates)')->where('rejected',0)->get();
            }
            if($this->getAccessForwardPublication(request()->segment(1))=='Yes'){
                $computer_translate=AmendmentsHeader::where('computer_branch_officer',Auth::user()->id)->where('current_stage','Proof read complete')->orWhere('current_stage','Gazette with G')->where('rejected',0)->get();
            }
            if($this->getAccessForwardPress(request()->segment(1))=='Yes'){
                $pub_without=AmendmentsHeader::where('current_stage','Publication without G')->orWhere('current_stage','Publication with G')->orWhere('current_stage','Gov press with G')->where('rejected',0)->get();
            }
            if($this->getAccessGazette(request()->segment(1))=='Yes'){
                $press_without=AmendmentsHeader::where('current_stage','Gov Press without G')->where('rejected',0)->get();
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
                    ->addColumn('ag_division_id', function ($pending) {
                          $AGName  =  $this->getAGDName($pending->ag_division_id);
                          return $AGName;

                        })
                    ->addColumn('gn_division_id', function ($pending) {
                        $newgnname='';
                        foreach(explode(',',$pending->gn_division_id) as $str)
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

        public function getpendingdata(){
            $pending=collect([]);
            $create=collect([]);$reg_verify=collect([]);$reg_approve=collect([]);$pub_verify=collect([]);$asst_comm=collect([]);$bim_comm=collect([]);
            $comm_gen=collect([]);$computer=collect([]);$proof_sinhala=collect([]);$computer_sinhala=collect([]);$proof_translate=collect([]);$computer_translate=collect([]);
            $pub_without=collect([]);$press_without=collect([]);
            if($this->getAccessCreate(request()->segment(1))=='Yes'){
                $create=AmendmentsHeader::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('current_stage','Regional officer')->where('rejected',0)->get();
            }
            if($this->getAccessRegVerify(request()->segment(1))=='Yes'){
                $reg_verify=AmendmentsHeader::where('regional_officer',Auth::user()->id)->whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('current_stage','Regional commissioner')->where('rejected',0)->get();
            }
            if($this->getAccessRegApprove(request()->segment(1))=='Yes'){
                $reg_approve=AmendmentsHeader::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('current_stage','Publication verify')->where('rejected',0)->get();
            }
            if($this->getAccessPubVerify(request()->segment(1))=='Yes'){
                $pub_verify=AmendmentsHeader::where('current_stage','Assistant commisioner')->where('rejected',0)->get();
            }
            if($this->getAccessAsstComm(request()->segment(1))=='Yes'){
                $asst_comm=AmendmentsHeader::where('current_stage','Bimsaviya commisioner')->where('rejected',0)->get();
            }
            if($this->getAccessBimsaviyaComm(request()->segment(1))=='Yes'){
                $bim_comm=AmendmentsHeader::where('current_stage','Commissioner general')->where('rejected',0)->get();
            }
            if($this->getAccessCommGen(request()->segment(1))=='Yes'){
                $comm_gen=AmendmentsHeader::where('current_stage','Computer branch')->where('rejected',0)->get();
            }
            if($this->getAccessForwardProof(request()->segment(1))=='Yes'){
                $computer=AmendmentsHeader::where('computer_branch_officer',Auth::user()->id)->where('current_stage','Proof read(Sinhala)')->where('rejected',0)->get();
            }
            if($this->getAccessProof(request()->segment(1))=='Yes'){
                $proof_sinhala=AmendmentsHeader::where('current_stage','Proof read(Sinhala)-Computer')->where('rejected',0)->get();
            }
            if($this->getAccessForwardTransProof(request()->segment(1))=='Yes'){
                $computer_sinhala=AmendmentsHeader::where('computer_branch_officer',Auth::user()->id)->where('current_stage','Proof read(Translates)')->where('rejected',0)->get();
            }
            if($this->getAccessTransProof(request()->segment(1))=='Yes'){
                $proof_translate=AmendmentsHeader::where('current_stage','Proof read(Translation)-Computer')->where('rejected',0)->get();
            }
            if($this->getAccessForwardPublication(request()->segment(1))=='Yes'){
                $computer_translate=AmendmentsHeader::where('computer_branch_officer',Auth::user()->id)->where('current_stage','Proof read complete')->orWhere('current_stage','Publication without G')
                    ->orWhere('current_stage','Publication with G')->where('rejected',0)->get();
            }
            if($this->getAccessForwardPress(request()->segment(1))=='Yes'){
                $pub_without=AmendmentsHeader::where('current_stage','Gov Press without G')->orWhere('current_stage','Gov press with G')->where('rejected',0)->get();
            }
            if($this->getAccessGazette(request()->segment(1))=='Yes'){
                $press_without=AmendmentsHeader::where('current_stage','Gazette with G')->where('rejected',0)->get();
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
                $current=AmendmentsHeader::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('current_stage','<>','Online Publish')->where('rejected',0)->where('recheck',0)->get();
            }else{
                $current=AmendmentsHeader::where('current_stage','<>','Online Publish')->Where('current_stage','<>','Regional commissioner')->Where('current_stage','<>','Regional officer')->Where('current_stage','<>','Regional data entry')->where('rejected',0)->where('recheck',0)->get();
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
        public function getrecheck()
        {
            $rejected=[];
            if($this->getAccessCreate(request()->segment(1))=='Yes' || $this->getAccessRegVerify(request()->segment(1))=='Yes' || $this->getAccessRegApprove(request()->segment(1))=='Yes'){
                $rejected=AmendmentsHeader::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                        ->where('rejected',0)->where('recheck',1)->get();
            }
            else{
                $rejected=AmendmentsHeader::where('rejected',0)->where('recheck',1)->get();
            }
            return DataTables::of($rejected)
                    ->addIndexColumn()
                    ->addColumn('ag_division', function ($newdata) {
                        $AGName  =  $this->getAGDName($newdata->ag_division_id);
                        return $AGName;

                      })
                  ->addColumn('gn_division', function ($newdata) {
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
                $rejected=AmendmentsHeader::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('rejected',1)->get();
            }
            else{
                $rejected=AmendmentsHeader::where('rejected',1)->get();
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
                $gazetted=AmendmentsHeader::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('current_stage','Online Publish')->where('rejected',0)->get();
            }else{
                $gazetted=AmendmentsHeader::where('current_stage','Online Publish')->where('rejected',0)->get();
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
         public function getForm14Map($map)
         {
              if($this->getAccessCreate('amendments')=='Yes')
              {
             $from14=Form14Header::where('map_no',$map)->where('rejected',0)->where('current_stage','Online Publish')->where('ag_division_id',Auth::user()->branch_id)->distinct()->get('block_no');
              }
              else
              {
                 $from14=Form14Header::where('map_no',$map)->distinct()->get('block_no');
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
            // error_log($map+' '+$block);
              if($this->getAccessCreate('amendments')=='Yes')
              {
                $form14=DB::table('form_14_header')
                ->join('form_14_detail','form_14_header.id','=','form_14_detail.form_14_Header_id')
               
                ->where('form_14_header.map_no',$map)->where('form_14_header.block_no',$block)->where('form_14_header.current_stage','Online Publish')
                // ->where('form_14_header.rejected',0)->where('form_14_header.current_stage','Online Publish')
                // ->where('form_14_header.ag_division_id',Auth::user()->branch_id)
                ->distinct()->get('form_14_detail.lot_no');
              }
              else
              {
                $form14=DB::table('form_14_header')
                ->join('form_14_detail','form_14_header.id','=','form_14_detail.form_14_Header_id')
                ->where('form_14_header.map_no',$map)->where('form_14_header.block_no',$block)->where('form_14_header.current_stage','Online Publish')
                ->distinct()->get('form_14_detail.lot_no');
              }
            if($form14){
                return $form14;
            }else{
                return ['message'=>'Form 14 not found..'];
            }
        }
}
