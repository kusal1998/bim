<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegionalOffices;
use App\Models\UserRolePermissions;
use App\Models\Form14Header;
use App\Models\Form14Details;
use App\Models\AgDivisions;
use App\Models\Form12;
use App\User;
use Auth;
Use Alert;
use DataTables;
use Validator;
use PDF;
use App\Traits\Permissions;
use DB;
use DateTime;

class ReportsController extends Controller
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

        public function on_process()
        {

            return view('pages.reports.on_process');
        }
        public function report1()
        {

            return view('pages.reports.report1');
        }
        public function report1list($id){
            $current=[];
            if($this->getAccessCreate(request()->segment(1))=='Yes' || $this->getAccessRegVerify(request()->segment(1))=='Yes' || $this->getAccessRegApprove(request()->segment(1))=='Yes'){
                $current=Form14Details::where('form_14_Header_id',$id)->where('rejected',0)->get();
            }else{
                $current=Form14Details::where('form_14_Header_id',$id)->where('rejected',0)->get();
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
        public function report1export()
        {

            $pdf = PDF::loadView('pdfs.report1');
            return $pdf->download('report1.pdf');
         /*    return view('pages.reports.report2'); */
        }
        public function report2()
        {
            $date=(new DateTime())->format('Y-m');
            $ags=Form14Header::select('ag_division_id')
                    ->where('created_at','like','%' .$date. '%')->distinct('ag_division_id')->groupBy('ag_division_id')
                    ->get();
            $ag_divisions=[];
            $total=0;
            foreach($ags as $ag){
                $agDivision=AgDivisions::where('id',$ag->ag_division_id)->where('is_active',1)->first();
                $officers=Form14Header::select('regional_officer')
                ->where('created_at','like','%' .$date. '%')
                ->where('ag_division_id',$ag->ag_division_id)
                ->distinct('regional_officer')->groupBy('regional_officer')
                ->get();
                $officer_array=[];
                $ag_total=0;
                foreach($officers as $officer){
                    $reg_officer=User::where('id',$officer->regional_officer)->first();
                    $jobs=Form14Header::where('created_at','like','%' .$date. '%')
                            ->where('regional_officer',$officer->regional_officer)
                            ->get();
                    $job_array=[];
                    $officer_total=0;
                    foreach($jobs as $job){
                        $lots=Form14Details::where('form_14_Header_id',$job->id)->get();
                        $lotstring_private=null;
                        $lotstring_government=null;
                        $job_total=0;
                        if($lots)
                        {
                            foreach($lots as $key=>$lot)
                            {
                                if($lot->type=='Private'||$lot->type=='පුද්ගලික'){
                                    $lotstring_private=$lotstring_private.', '.$lot->lot_no;
                                }
                                if($lot->type=='Government'||$lot->type=='රාජ්‍ය'){
                                    $lotstring_government=$lotstring_government.', '.$lot->lot_no;
                                }
                                $job_total=$job_total+1;
                            }
                            if(isset($lotstring_private))
                            {
                                $lotstring_private=substr($lotstring_private,1);
                            }
                            if(isset($lotstring_government))
                            {
                                $lotstring_government=substr($lotstring_government,1);
                            }
                        }
                        $officer_total=$officer_total+$job_total;
                        $job_element=[
                            'map'=>$job->map_no,
                            'block'=>$job->block_no,
                            'lot_string_pvt'=>$lotstring_private,
                            'lot_string_govt'=>$lotstring_government,
                            'total'=>$job_total,
                        ];
                        array_push($job_array,$job_element);
                    }
                    $officer_element=[
                        'name'=>$reg_officer->name.' '.$reg_officer->last_name,
                        'jobs'=>$job_array,
                        'officer_total'=>$officer_total,
                    ];
                    array_push($officer_array,$officer_element);
                    $ag_total=$ag_total+$officer_total;
                }
                $ag_element=[
                    'name'=>$agDivision->ag_name,
                    'sinhala_name'=>$agDivision->sinhala_name,
                    'officers'=>$officer_array,
                    'ag_total'=>$ag_total,
                ];
                array_push($ag_divisions,$ag_element);
                $total=$total+$ag_total;
            }
            $report2=[
                'ag_divisions'=>$ag_divisions,
                'total'=>$total,
            ];
            view()->share('elements',$report2);
            return view('pages.reports.report2');
        }
        public function report2export($date)
        {
            $ags=Form14Header::select('ag_division_id')
            ->where('created_at','like','%' .$date. '%')->distinct('ag_division_id')->groupBy('ag_division_id')
            ->get();
    $ag_divisions=[];
    $total=0;
    foreach($ags as $ag){
        $agDivision=AgDivisions::where('id',$ag->ag_division_id)->where('is_active',1)->first();
        $officers=Form14Header::select('regional_officer')
        ->where('created_at','like','%' .$date. '%')
        ->where('ag_division_id',$ag->ag_division_id)
        ->distinct('regional_officer')->groupBy('regional_officer')
        ->get();
        $officer_array=[];
        $ag_total=0;
        foreach($officers as $officer){
            $reg_officer=User::where('id',$officer->regional_officer)->first();
            $jobs=Form14Header::where('created_at','like','%' .$date. '%')
                    ->where('regional_officer',$officer->regional_officer)
                    ->get();
            $job_array=[];
            $officer_total=0;
            foreach($jobs as $job){
                $lots=Form14Details::where('form_14_Header_id',$job->id)->get();
                $lotstring_private=null;
                $lotstring_government=null;
                $job_total=0;
                if($lots)
                {
                    foreach($lots as $key=>$lot)
                    {
                        if($lot->type=='Private'||$lot->type=='පුද්ගලික'){
                            $lotstring_private=$lotstring_private.', '.$lot->lot_no;
                        }
                        if($lot->type=='Government'||$lot->type=='රාජ්‍ය'){
                            $lotstring_government=$lotstring_government.', '.$lot->lot_no;
                        }
                        $job_total=$job_total+1;
                    }
                    if(isset($lotstring_private))
                    {
                        $lotstring_private=substr($lotstring_private,1);
                    }
                    if(isset($lotstring_government))
                    {
                        $lotstring_government=substr($lotstring_government,1);
                    }
                }
                $officer_total=$officer_total+$job_total;
                $job_element=[
                    'map'=>$job->map_no,
                    'block'=>$job->block_no,
                    'lot_string_pvt'=>$lotstring_private,
                    'lot_string_govt'=>$lotstring_government,
                    'total'=>$job_total,
                ];
                array_push($job_array,$job_element);
            }
            $officer_element=[
                'name'=>$reg_officer->name.' '.$reg_officer->last_name,
                'jobs'=>$job_array,
                'officer_total'=>$officer_total,
            ];
            array_push($officer_array,$officer_element);
            $ag_total=$ag_total+$officer_total;
        }
        $ag_element=[
            'name'=>$agDivision->ag_name,
            'sinhala_name'=>$agDivision->sinhala_name,
            'officers'=>$officer_array,
            'ag_total'=>$ag_total,
        ];
        array_push($ag_divisions,$ag_element);
        $total=$total+$ag_total;
    }
    $report2=[
        'ag_divisions'=>$ag_divisions,
        'total'=>$total,
    ];
    $year = date('Y', strtotime($date));
    $month = date('F', strtotime($date));
    $formatteDate=$year.'-'.$month;

            view()->share('formatteDate',$formatteDate);
            view()->share('elements',$report2);
            return view('pdfs.report2');
        }


        public function report3()
        {
            $date=(new DateTime())->format('Y-m');
            $ags=Form14Header::select('ag_division_id')
                    ->where('created_at','like','%' .$date. '%')->distinct('ag_division_id')->groupBy('ag_division_id')
                    ->get();
            $ag_divisions=[];
            $total=0;
            foreach($ags as $ag){
                $agDivision=AgDivisions::where('id',$ag->ag_division_id)->where('is_active',1)->first();
                $officers=Form14Header::select('regional_officer')
                ->where('created_at','like','%' .$date. '%')
                ->where('ag_division_id',$ag->ag_division_id)
                ->distinct('regional_officer')->groupBy('regional_officer')
                ->get();
                $officer_array=[];
                $ag_total=0;
                foreach($officers as $officer){
                    $reg_officer=User::where('id',$officer->regional_officer)->first();
                    $jobs=Form14Header::where('created_at','like','%' .$date. '%')
                            ->where('regional_officer',$officer->regional_officer)
                            ->get();
                    $job_array=[];
                    $officer_total=0;
                    foreach($jobs as $job){
                        $lots=Form14Details::where('form_14_Header_id',$job->id)->get();
                        $lotstring_private=0;
                        $lotstring_government=0;
                        $job_total=0;
                        if($lots)
                        {
                            foreach($lots as $key=>$lot)
                            {
                                if($lot->type=='Private'||$lot->type=='පුද්ගලික'){
                                    $lotstring_private=$lotstring_private+1;
                                }
                                if($lot->type=='Government'||$lot->type=='රාජ්‍ය'){
                                    $lotstring_government=$lotstring_government+1;
                                }
                                $job_total=$job_total+1;
                            }
                        }
                        $officer_total=$officer_total+$job_total;
                        $job_element=[
                            'map'=>$job->map_no,
                            'block'=>$job->block_no,
                            'lot_string_pvt'=>$lotstring_private,
                            'lot_string_govt'=>$lotstring_government,
                            'total'=>$job_total,
                        ];
                        array_push($job_array,$job_element);
                    }
                    $officer_element=[
                        'name'=>$reg_officer->name.' '.$reg_officer->last_name,
                        'jobs'=>$job_array,
                        'officer_total'=>$officer_total,
                    ];
                    array_push($officer_array,$officer_element);
                    $ag_total=$ag_total+$officer_total;
                }
                $ag_element=[
                    'name'=>$agDivision->ag_name,
                    'sinhala_name'=>$agDivision->sinhala_name,
                    'officers'=>$officer_array,
                    'ag_total'=>$ag_total,
                ];
                array_push($ag_divisions,$ag_element);
                $total=$total+$ag_total;
            }
            $report2=[
                'ag_divisions'=>$ag_divisions,
                'total'=>$total,
            ];
            view()->share('elements',$report2);

            return view('pages.reports.report3');
        }
        public function report3export($date)
        {
            $ags=Form14Header::select('ag_division_id')
            ->where('created_at','like','%' .$date. '%')->distinct('ag_division_id')->groupBy('ag_division_id')
            ->get();
    $ag_divisions=[];
    $total=0;
    foreach($ags as $ag){
        $agDivision=AgDivisions::where('id',$ag->ag_division_id)->where('is_active',1)->first();
        $officers=Form14Header::select('regional_officer')
        ->where('created_at','like','%' .$date. '%')
        ->where('ag_division_id',$ag->ag_division_id)
        ->distinct('regional_officer')->groupBy('regional_officer')
        ->get();
        $officer_array=[];
        $ag_total=0;
        foreach($officers as $officer){
            $reg_officer=User::where('id',$officer->regional_officer)->first();
            $jobs=Form14Header::where('created_at','like','%' .$date. '%')
                    ->where('regional_officer',$officer->regional_officer)
                    ->get();
            $job_array=[];
            $officer_total=0;
            foreach($jobs as $job){
                $lots=Form14Details::where('form_14_Header_id',$job->id)->get();
                $lotstring_private=0;
                $lotstring_government=0;
                $job_total=0;
                if($lots)
                {
                    foreach($lots as $key=>$lot)
                    {
                        if($lot->type=='Private'||$lot->type=='පුද්ගලික'){
                            $lotstring_private=$lotstring_private+1;
                        }
                        if($lot->type=='Government'||$lot->type=='රාජ්‍ය'){
                            $lotstring_government=$lotstring_government+1;
                        }
                        $job_total=$job_total+1;
                    }
                }
                $officer_total=$officer_total+$job_total;
                $job_element=[
                    'map'=>$job->map_no,
                    'block'=>$job->block_no,
                    'lot_string_pvt'=>$lotstring_private,
                    'lot_string_govt'=>$lotstring_government,
                    'total'=>$job_total,
                ];
                array_push($job_array,$job_element);
            }
            $officer_element=[
                'name'=>$reg_officer->name.' '.$reg_officer->last_name,
                'jobs'=>$job_array,
                'officer_total'=>$officer_total,
            ];
            array_push($officer_array,$officer_element);
            $ag_total=$ag_total+$officer_total;
        }
        $ag_element=[
            'name'=>$agDivision->ag_name,
            'sinhala_name'=>$agDivision->sinhala_name,
            'officers'=>$officer_array,
            'ag_total'=>$ag_total,
        ];
        array_push($ag_divisions,$ag_element);
        $total=$total+$ag_total;
    }
    $report2=[
        'ag_divisions'=>$ag_divisions,
        'total'=>$total,
    ];
    $year = date('Y', strtotime($date));
    $month = date('F', strtotime($date));
    $formatteDate=$year.'-'.$month;
    view()->share('formatteDate',$formatteDate);
    view()->share('elements',$report2);
    return view('pdfs.report3');
        }

        public function report4()
        {
            $date=(new DateTime())->format('Y-m');
            $ags=Form14Header::select('ag_division_id')
                    ->where('created_at','like','%' .$date. '%')->distinct('ag_division_id')->groupBy('ag_division_id')
                    ->get();
            $ag_divisions=[];
            $total=0;
            foreach($ags as $ag){
                $agDivision=AgDivisions::where('id',$ag->ag_division_id)->where('is_active',1)->first();
                $officers=Form14Header::select('regional_officer')
                ->where('created_at','like','%' .$date. '%')
                ->where('ag_division_id',$ag->ag_division_id)
                ->distinct('regional_officer')->groupBy('regional_officer')
                ->get();
                $officer_array=[];
                $ag_total=0;
                foreach($officers as $officer){
                    $reg_officer=User::where('id',$officer->regional_officer)->first();
                    $jobs=Form14Header::where('created_at','like','%' .$date. '%')
                            ->where('regional_officer',$officer->regional_officer)
                            ->get();
                    $job_array=[];
                    $officer_total=0;
                    foreach($jobs as $job){
                        $lots=Form14Details::where('form_14_Header_id',$job->id)->get();
                        $lotstring_private=null;
                        $lotstring_government=null;
                        $lotstring_private_rem=null;
                        $lotstring_government_rem=null;
                        $lotstring_private_sent=null;
                        $lotstring_government_sent=null;
                        $job_total=0;
                        $job_total_rem=0;
                        $job_total_sent=0;
                        if($lots)
                        {
                            foreach($lots as $key=>$lot)
                            {
                                if($lot->type=='Private'||$lot->type=='පුද්ගලික'){
                                    $lotstring_private=$lotstring_private.', '.$lot->lot_no;
                                }
                                if($lot->type=='Government'||$lot->type=='රාජ්‍ය'){
                                    $lotstring_government=$lotstring_government.', '.$lot->lot_no;
                                }
                                $job_total=$job_total+1;
                                if(($job->current_stage!='Online Publish') && ($job->current_stage!='Certificate issued')){
                                    if($lot->type=='Private'||$lot->type=='පුද්ගලික'){
                                        $lotstring_private_rem=$lotstring_private_rem.', '.$lot->lot_no;
                                    }
                                    if($lot->type=='Government'||$lot->type=='රාජ්‍ය'){
                                        $lotstring_government_rem=$lotstring_government_rem.', '.$lot->lot_no;
                                    }
                                    $job_total_rem=$job_total_rem+1;
                                }
                                if($job->current_stage!='Regional data entry' && $job->current_stage!='Regional officer' && $job->current_stage!='Regional commissioner'){
                                    if($lot->type=='Private'||$lot->type=='පුද්ගලික'){
                                        $lotstring_private_sent=$lotstring_private_sent.', '.$lot->lot_no;
                                    }
                                    if($lot->type=='Government'||$lot->type=='රාජ්‍ය'){
                                        $lotstring_government_sent=$lotstring_government_sent.', '.$lot->lot_no;
                                    }
                                    $job_total_sent=$job_total_sent+1;
                                }
                            }
                            if(isset($lotstring_private))
                            {
                                $lotstring_private=substr($lotstring_private,1);
                            }
                            if(isset($lotstring_private_rem))
                            {
                                $lotstring_private_rem=substr($lotstring_private_rem,1);
                            }
                            if(isset($lotstring_private_sent))
                            {
                                $lotstring_private_sent=substr($lotstring_private_sent,1);
                            }

                            if(isset($lotstring_government))
                            {
                                $lotstring_government=substr($lotstring_government,1);
                            }
                            if(isset($lotstring_government_rem))
                            {
                                $lotstring_government_rem=substr($lotstring_government_rem,1);
                            }
                            if(isset($lotstring_government_sent))
                            {
                                $lotstring_government_sent=substr($lotstring_government_sent,1);
                            }
                        }
                        $officer_total=$officer_total+$job_total;
                        $job_element=[
                            'map'=>$job->map_no,
                            'block'=>$job->block_no,
                            'lot_string_pvt'=>$lotstring_private,
                            'lot_string_govt'=>$lotstring_government,
                            'lot_string_pvt_rem'=>$lotstring_private_rem,
                            'lot_string_govt_rem'=>$lotstring_government_rem,
                            'lot_string_pvt_sent'=>$lotstring_private_sent,
                            'lot_string_govt_sent'=>$lotstring_government_sent,
                            'total'=>$job_total,
                            'total_rem'=>$job_total_rem,
                            'total_sent'=>$job_total_sent,
                        ];
                        array_push($job_array,$job_element);
                    }
                    $officer_element=[
                        'name'=>$reg_officer->name.' '.$reg_officer->last_name,
                        'jobs'=>$job_array,
                        'officer_total'=>$officer_total,
                    ];
                    array_push($officer_array,$officer_element);
                    $ag_total=$ag_total+$officer_total;
                }
                $ag_element=[
                    'name'=>$agDivision->ag_name,
                    'sinhala_name'=>$agDivision->sinhala_name,
                    'officers'=>$officer_array,
                    'ag_total'=>$ag_total,
                ];
                array_push($ag_divisions,$ag_element);
                $total=$total+$ag_total;
            }
            $report2=[
                'ag_divisions'=>$ag_divisions,
                'total'=>$total,
            ];
            view()->share('elements',$report2);
            return view('pages.reports.report4');
        }
        public function report4export($date)
        {
            $ags=Form14Header::select('ag_division_id')
            ->where('created_at','like','%' .$date. '%')->distinct('ag_division_id')->groupBy('ag_division_id')
            ->get();
    $ag_divisions=[];
    $total=0;
    foreach($ags as $ag){
        $agDivision=AgDivisions::where('id',$ag->ag_division_id)->where('is_active',1)->first();
        $officers=Form14Header::select('regional_officer')
        ->where('created_at','like','%' .$date. '%')
        ->where('ag_division_id',$ag->ag_division_id)
        ->distinct('regional_officer')->groupBy('regional_officer')
        ->get();
        $officer_array=[];
        $ag_total=0;
        foreach($officers as $officer){
            $reg_officer=User::where('id',$officer->regional_officer)->first();
            $jobs=Form14Header::where('created_at','like','%' .$date. '%')
                    ->where('regional_officer',$officer->regional_officer)
                    ->get();
            $job_array=[];
            $officer_total=0;
            foreach($jobs as $job){
                $lots=Form14Details::where('form_14_Header_id',$job->id)->get();
                $lotstring_private=null;
                $lotstring_government=null;
                $lotstring_private_rem=null;
                $lotstring_government_rem=null;
                $lotstring_private_sent=null;
                $lotstring_government_sent=null;
                $job_total=0;
                $job_total_rem=0;
                $job_total_sent=0;
                if($lots)
                {
                    foreach($lots as $key=>$lot)
                    {
                        if($lot->type=='Private'||$lot->type=='පුද්ගලික'){
                            $lotstring_private=$lotstring_private.', '.$lot->lot_no;
                        }
                        if($lot->type=='Government'||$lot->type=='රාජ්‍ය'){
                            $lotstring_government=$lotstring_government.', '.$lot->lot_no;
                        }
                        $job_total=$job_total+1;
                        if(($job->current_stage!='Online Publish') && ($job->current_stage!='Certificate issued')){
                            if($lot->type=='Private'||$lot->type=='පුද්ගලික'){
                                $lotstring_private_rem=$lotstring_private_rem.', '.$lot->lot_no;
                            }
                            if($lot->type=='Government'||$lot->type=='රාජ්‍ය'){
                                $lotstring_government_rem=$lotstring_government_rem.', '.$lot->lot_no;
                            }
                            $job_total_rem=$job_total_rem+1;
                        }
                        if($job->current_stage!='Regional data entry' && $job->current_stage!='Regional officer' && $job->current_stage!='Regional commissioner'){
                            if($lot->type=='Private'||$lot->type=='පුද්ගලික'){
                                $lotstring_private_sent=$lotstring_private_sent.', '.$lot->lot_no;
                            }
                            if($lot->type=='Government'||$lot->type=='රාජ්‍ය'){
                                $lotstring_government_sent=$lotstring_government_sent.', '.$lot->lot_no;
                            }
                            $job_total_sent=$job_total_sent+1;
                        }
                    }
                    if(isset($lotstring_private))
                    {
                        $lotstring_private=substr($lotstring_private,1);
                    }
                    if(isset($lotstring_private_rem))
                    {
                        $lotstring_private_rem=substr($lotstring_private_rem,1);
                    }
                    if(isset($lotstring_private_sent))
                    {
                        $lotstring_private_sent=substr($lotstring_private_sent,1);
                    }

                    if(isset($lotstring_government))
                    {
                        $lotstring_government=substr($lotstring_government,1);
                    }
                    if(isset($lotstring_government_rem))
                    {
                        $lotstring_government_rem=substr($lotstring_government_rem,1);
                    }
                    if(isset($lotstring_government_sent))
                    {
                        $lotstring_government_sent=substr($lotstring_government_sent,1);
                    }
                }
                $officer_total=$officer_total+$job_total;
                $job_element=[
                    'map'=>$job->map_no,
                    'block'=>$job->block_no,
                    'lot_string_pvt'=>$lotstring_private,
                    'lot_string_govt'=>$lotstring_government,
                    'lot_string_pvt_rem'=>$lotstring_private_rem,
                    'lot_string_govt_rem'=>$lotstring_government_rem,
                    'lot_string_pvt_sent'=>$lotstring_private_sent,
                    'lot_string_govt_sent'=>$lotstring_government_sent,
                    'total'=>$job_total,
                    'total_rem'=>$job_total_rem,
                    'total_sent'=>$job_total_sent,
                ];
                array_push($job_array,$job_element);
            }
            $officer_element=[
                'name'=>$reg_officer->name.' '.$reg_officer->last_name,
                'jobs'=>$job_array,
                'officer_total'=>$officer_total,
            ];
            array_push($officer_array,$officer_element);
            $ag_total=$ag_total+$officer_total;
        }
        $ag_element=[
            'name'=>$agDivision->ag_name,
            'sinhala_name'=>$agDivision->sinhala_name,
            'officers'=>$officer_array,
            'ag_total'=>$ag_total,
        ];
        array_push($ag_divisions,$ag_element);
        $total=$total+$ag_total;
    }
    $report2=[
        'ag_divisions'=>$ag_divisions,
        'total'=>$total,
    ];
    $year = date('Y', strtotime($date));
    $month = date('F', strtotime($date));
    $formatteDate=$year.'-'.$month;
    view()->share('formatteDate',$formatteDate);
    view()->share('elements',$report2);
    return view('pdfs.report4');

        }
        public function report5()
        {
            $grandMapTot=0;
            $grandPlTot=0;
            $grandGlTot=0;
            $grandTotal=0;
            $currentdate=(new DateTime())->format('Y-m');
            $fromDate=date('Y-m-01', strtotime($currentdate));
            $toDate=date('Y-m-t', strtotime($currentdate));
            $element=Form12::selectRaw('district_id as district')
            ->where('rejected',0)
            ->where('created_at','like','%'.$currentdate.'%')
            ->distinct()->get('district');
            $report=[];
            foreach($element as $itms)
            {
                $aglist =Form12::selectRaw('COUNT(map_no) as mp,SUM(government_lands) as gl,SUM(private_lands) as
                pl,SUM(total_lands) as total,ag_division as agdivision')
                ->where('rejected',0)
                ->where('district_id',$itms->district)->where('created_at','like','%'.$currentdate.'%')
                ->groupBy('agdivision')->get();
                foreach($aglist as $values)
                {
                $grandMapTot=$grandMapTot+$values->mp;
                $grandPlTot=$grandPlTot+$values->pl;
                $grandGlTot=$grandGlTot+$values->gl;
                $grandTotal=$grandTotal+$values->total;
                }
                $report_element=[
                    'district'=>$itms->district,
                    'ags'=>$aglist,
                ];
                array_push($report,$report_element);
            }
            view()->share('fdate',$fromDate);
            view()->share('tdate',$toDate);
            view()->share('report',$report);
            view()->share('grandMpTot',$grandMapTot);
            view()->share('grandPlTot',$grandPlTot);
            view()->share('grandGlTot',$grandGlTot);
            view()->share('grandTotal',$grandTotal);
            view()->share('elements',$element);
            return view('pages.reports.report5');
        }
        public function report5export($fromDate,$toDate)
        {
            $grandMapTot=0;
            $grandPlTot=0;
            $grandGlTot=0;
            $grandTotal=0;
            $element=Form12::selectRaw('COUNT(map_no) as mp,SUM(government_lands) as gl,SUM(private_lands) as pl,SUM(total_lands) as total,district_id as district')
            ->where('rejected',0)
            ->whereBetween('created_at',[$fromDate,$toDate])
            ->groupBy('district')->get();

            foreach($element as $itms)
            {
                $aglist =Form12::selectRaw('COUNT(map_no) as mp,SUM(government_lands) as gl,SUM(private_lands) as
                pl,SUM(total_lands) as total,ag_division as agdivision')
                ->where('rejected',0)
                ->where('district_id',$itms->district)
                ->groupBy('agdivision')->get();
                foreach($aglist as $values)
                {
                $grandMapTot=$grandMapTot+$values->mp;
                $grandPlTot=$grandPlTot+$values->pl;
                $grandGlTot=$grandGlTot+$values->gl;
                $grandTotal=$grandTotal+$values->total;
                }

            }
            view()->share('fdate',$fromDate);
            view()->share('tdate',$toDate);
            view()->share('grandMpTot',$grandMapTot);
            view()->share('grandPlTot',$grandPlTot);
            view()->share('grandGlTot',$grandGlTot);
            view()->share('grandTotal',$grandTotal);
            view()->share('elements',$element);
            //$pdf = PDF::loadView('pdfs.report5');
            //return $pdf->download('report5.pdf');
            return view('pdfs.report5');
        }
        public function report6()
        {
            $grandMapTot=0;
            $grandPlTot=0;
            $grandGlTot=0;
            $grandTotal=0;
            $currentdate=(new DateTime())->format('Y-m');
            $fromDate=date('Y-m-01', strtotime($currentdate));
            $toDate=date('Y-m-t', strtotime($currentdate));
            $element=Form12::selectRaw('district_id as district')
            ->where('rejected',0)
            ->where('regional_approved',1)
            ->where('created_at','like','%'.$currentdate.'%')
            ->distinct()->get('district');
            $report=[];
            foreach($element as $itms)
            {
                $aglist =Form12::selectRaw('COUNT(map_no) as mp,SUM(government_lands) as gl,SUM(private_lands) as
                pl,SUM(total_lands) as total,ag_division as agdivision')
                ->where('rejected',0)
                ->where('regional_approved',1)
                ->where('district_id',$itms->district)->where('created_at','like','%'.$currentdate.'%')
                ->groupBy('agdivision')->get();
                foreach($aglist as $values)
                {
                $grandMapTot=$grandMapTot+$values->mp;
                $grandPlTot=$grandPlTot+$values->pl;
                $grandGlTot=$grandGlTot+$values->gl;
                $grandTotal=$grandTotal+$values->total;
                }
                $report_element=[
                    'district'=>$itms->district,
                    'ags'=>$aglist,
                ];
                array_push($report,$report_element);
            }
            view()->share('fdate',$fromDate);
            view()->share('tdate',$toDate);
            view()->share('report',$report);
            view()->share('grandMpTot',$grandMapTot);
            view()->share('grandPlTot',$grandPlTot);
            view()->share('grandGlTot',$grandGlTot);
            view()->share('grandTotal',$grandTotal);
            view()->share('elements',$element);
            return view('pages.reports.report6');
        }
        public function report6export($fromDate,$toDate)
        {
            $grandPlTot=0;
            $grandGlTot=0;
            $grandTotal=0;
            $element=Form12::selectRaw('SUM(government_lands) as gl,SUM(private_lands) as pl,SUM(total_lands) as total,district_id as district')
            ->where('regional_approved',1)
            ->where('rejected',0)
            ->whereBetween('created_at',[$fromDate,$toDate])
            ->groupBy('district')->get();
            foreach($element as $itms)
            {
                $grandPlTot=$grandPlTot+$itms->pl;
                $grandGlTot=$grandGlTot+$itms->gl;
                $grandTotal=$grandTotal+$itms->total;

            }
            view()->share('fdate',$fromDate);
            view()->share('tdate',$toDate);
            view()->share('grandPlTot',$grandPlTot);
            view()->share('grandGlTot',$grandGlTot);
            view()->share('grandTotal',$grandTotal);
            view()->share('elements',$element);
           // $pdf = PDF::loadView('pdfs.report6');
        //     return $pdf->download('report6.pdf');
            return view('pdfs.report6');
        }
        public function report7()
        {
            $grandPlTot=0;
            $grandGlTot=0;
            $grandTotal=0;
            $currentdate=(new DateTime())->format('Y-m');
            $fromDate=date('Y-m-01', strtotime($currentdate));
            $toDate=date('Y-m-t', strtotime($currentdate));

            
            $ds=Form14Header::select('district_id')
            ->where('created_at','like','%' .$currentdate. '%')->distinct('district_id')->groupBy('district_id')
            ->get();
            $report=[];
            foreach($ds as $dist){
                $aglist =Form14Header::selectRaw('SUM(governments_lands) as gl,SUM(private_lands) as
                pl,SUM(total_lands) as total,ag_division_id as agdivision')
                ->where('rejected',0)
                ->where('comm_gen_approval','!=',null)
                ->where('district_id',$dist->district_id)->where('created_at','like','%'.$currentdate.'%')
                ->groupBy('agdivision')->get();
                foreach($aglist as $itms)
                {
                    $grandPlTot=$grandPlTot+$itms->pl;
                    $grandGlTot=$grandGlTot+$itms->gl;
                    $grandTotal=$grandTotal+$itms->total;
                }
                $report_element=[
                    'district'=>$dist->district_id,
                    'ags'=>$aglist,
                ];
                array_push($report,$report_element);
            }

            view()->share('fdate',$fromDate);
            view()->share('tdate',$toDate);
            view()->share('report',$report);
            view()->share('grandPlTot',$grandPlTot);
            view()->share('grandGlTot',$grandGlTot);
            view()->share('grandTotal',$grandTotal);
            view()->share('elements','');
            return view('pages.reports.report7');
        }
        public function report7export($fromDate,$toDate)
        {
            $grandPlTot=0;
            $grandGlTot=0;
            $grandTotal=0;
            $element=Form12::selectRaw('SUM(government_lands) as gl,SUM(private_lands) as pl,SUM(total_lands) as total,district_id as district')
            ->where('regional_approved',1)
            ->where('publication_branch',1)
            ->where('rejected',0)
            ->whereBetween('created_at',[$fromDate,$toDate])
            ->groupBy('district')->get();
            foreach($element as $itms)
            {
                $grandPlTot=$grandPlTot+$itms->pl;
                $grandGlTot=$grandGlTot+$itms->gl;
                $grandTotal=$grandTotal+$itms->total;

            }
            view()->share('fdate',$fromDate);
            view()->share('tdate',$toDate);
            view()->share('grandPlTot',$grandPlTot);
            view()->share('grandGlTot',$grandGlTot);
            view()->share('grandTotal',$grandTotal);
            view()->share('elements',$element);

            // $pdf = PDF::loadView('pdfs.report7');
            // return $pdf->download('report7.pdf');
             return view('pdfs.report7');
        }
        public function report8()
        {
            $grandPlTot=0;
            $grandGlTot=0;
            $grandTotal=0;
            $currentdate=(new DateTime())->format('Y-m-d');
            $fromDate=date('Y-m-01', strtotime($currentdate));
            $toDate=date('Y-m-t', strtotime($currentdate));
            $element=Form12::selectRaw('SUM(government_lands) as gl,SUM(private_lands) as pl,SUM(total_lands) as total,district_id as district')
            ->where('regional_approved',1)
            ->where('rejected',1)
            ->whereBetween('created_at',[$fromDate,$toDate])
            ->groupBy('district')->get();
            foreach($element as $itms)
            {
                $grandPlTot=$grandPlTot+$itms->pl;
                $grandGlTot=$grandGlTot+$itms->gl;
                $grandTotal=$grandTotal+$itms->total;

            }
            view()->share('fdate',$fromDate);
            view()->share('tdate',$toDate);
            view()->share('grandPlTot',$grandPlTot);
            view()->share('grandGlTot',$grandGlTot);
            view()->share('grandTotal',$grandTotal);
            view()->share('elements',$element);
            return view('pages.reports.report8');
        }
        public function report8export($fromDate,$toDate)
        {
            $grandPlTot=0;
            $grandGlTot=0;
            $grandTotal=0;
            $element=Form12::selectRaw('SUM(government_lands) as gl,SUM(private_lands) as pl,SUM(total_lands) as total,district_id as district')
            ->where('regional_approved',1)
            ->where('rejected',1)
            ->whereBetween('created_at',[$fromDate,$toDate])
            ->groupBy('district')->get();
            foreach($element as $itms)
            {
                $grandPlTot=$grandPlTot+$itms->pl;
                $grandGlTot=$grandGlTot+$itms->gl;
                $grandTotal=$grandTotal+$itms->total;

            }
            view()->share('fdate',$fromDate);
            view()->share('tdate',$toDate);
            view()->share('grandPlTot',$grandPlTot);
            view()->share('grandGlTot',$grandGlTot);
            view()->share('grandTotal',$grandTotal);
            view()->share('elements',$element);
            // $pdf = PDF::loadView('pdfs.report8');
            // return $pdf->download('report8.pdf');
             return view('pdfs.report8');
        }
        public function report9()
        {
            $grandPlTot=0;
            $grandGlTot=0;
            $grandTotal=0;
            $currentdate=(new DateTime())->format('Y-m-d');
            $fromDate=date('Y-m-01', strtotime($currentdate));
            $toDate=date('Y-m-t', strtotime($currentdate));
            $element=Form12::selectRaw('SUM(government_lands) as gl,SUM(private_lands) as pl,SUM(total_lands) as total,district_id as district')
            ->where('sent_gov_press',1)
            ->where('rejected',0)
            ->whereBetween('created_at',[$fromDate,$toDate])
            ->groupBy('district')->get();
            foreach($element as $itms)
            {
                $grandPlTot=$grandPlTot+$itms->pl;
                $grandGlTot=$grandGlTot+$itms->gl;
                $grandTotal=$grandTotal+$itms->total;

            }
            view()->share('fdate',$fromDate);
            view()->share('tdate',$toDate);
            view()->share('grandPlTot',$grandPlTot);
            view()->share('grandGlTot',$grandGlTot);
            view()->share('grandTotal',$grandTotal);
            view()->share('elements',$element);
            return view('pages.reports.report9');
        }
        public function report9export($fromDate,$toDate)
        {
            $grandPlTot=0;
            $grandGlTot=0;
            $grandTotal=0;
            $element=Form12::selectRaw('SUM(government_lands) as gl,SUM(private_lands) as pl,SUM(total_lands) as total,district_id as district')
            ->where('sent_gov_press',1)
            ->where('rejected',0)
            ->whereBetween('created_at',[$fromDate,$toDate])
            ->groupBy('district')->get();
            foreach($element as $itms)
            {
                $grandPlTot=$grandPlTot+$itms->pl;
                $grandGlTot=$grandGlTot+$itms->gl;
                $grandTotal=$grandTotal+$itms->total;

            }
            view()->share('fdate',$fromDate);
            view()->share('tdate',$toDate);
            view()->share('grandPlTot',$grandPlTot);
            view()->share('grandGlTot',$grandGlTot);
            view()->share('grandTotal',$grandTotal);
            view()->share('elements',$element);

            // $pdf = PDF::loadView('pdfs.report9');
            // return $pdf->download('report9.pdf');
            return view('pdfs.report9');
        }
        public function report10()
        {
            $grandPlTot=0;
            $grandGlTot=0;
            $grandTotal=0;
            $currentdate=(new DateTime())->format('Y-m-d');
            $fromDate=date('Y-m-01', strtotime($currentdate));
            $toDate=date('Y-m-t', strtotime($currentdate));
            $element=Form12::selectRaw('SUM(government_lands) as gl,SUM(private_lands) as pl,SUM(total_lands) as total,district_id as district')
            ->where('gazette_no','<>',null)
            ->where('rejected',0)
            ->whereBetween('created_at',[$fromDate,$toDate])
            ->groupBy('district')->get();
            foreach($element as $itms)
            {
                $grandPlTot=$grandPlTot+$itms->pl;
                $grandGlTot=$grandGlTot+$itms->gl;
                $grandTotal=$grandTotal+$itms->total;

            }
            view()->share('fdate',$fromDate);
            view()->share('tdate',$toDate);
            view()->share('grandPlTot',$grandPlTot);
            view()->share('grandGlTot',$grandGlTot);
            view()->share('grandTotal',$grandTotal);
            view()->share('elements',$element);
            return view('pages.reports.report10');
        }
        public function report10export($fromDate,$toDate)
        {
            $grandPlTot=0;
            $grandGlTot=0;
            $grandTotal=0;
            $element=Form12::selectRaw('SUM(government_lands) as gl,SUM(private_lands) as pl,SUM(total_lands) as total,district_id as district')
            ->where('gazette_no','<>',null)
            ->where('rejected',0)
            ->whereBetween('created_at',[$fromDate,$toDate])
            ->groupBy('district')->get();
            foreach($element as $itms)
            {
                $grandPlTot=$grandPlTot+$itms->pl;
                $grandGlTot=$grandGlTot+$itms->gl;
                $grandTotal=$grandTotal+$itms->total;

            }
            view()->share('fdate',$fromDate);
            view()->share('tdate',$toDate);
            view()->share('grandPlTot',$grandPlTot);
            view()->share('grandGlTot',$grandGlTot);
            view()->share('grandTotal',$grandTotal);
            view()->share('elements',$element);

            // $pdf = PDF::loadView('pdfs.report10');
            // return $pdf->download('report10.pdf');
             return view('pdfs.report10');
        }
        public function report11()
        {
            $grandPlTot=0;
            $grandGlTot=0;
            $grandTotal=0;
            $currentdate=(new DateTime())->format('Y-m-d');
            $fromDate=date('Y-m-01', strtotime($currentdate));
            $toDate=date('Y-m-t', strtotime($currentdate));
            $element=Form12::selectRaw('SUM(government_lands) as gl,SUM(private_lands) as pl,SUM(total_lands) as total,ag_division as agdivision')
            ->where('gazette_no','<>',null)
            ->where('rejected',0)
            ->whereBetween('created_at',[$fromDate,$toDate])
            ->groupBy('agdivision')->get();
            foreach($element as $itms)
            {
                $grandPlTot=$grandPlTot+$itms->pl;
                $grandGlTot=$grandGlTot+$itms->gl;
                $grandTotal=$grandTotal+$itms->total;

            }
            view()->share('fdate',$fromDate);
            view()->share('tdate',$toDate);
            view()->share('grandPlTot',$grandPlTot);
            view()->share('grandGlTot',$grandGlTot);
            view()->share('grandTotal',$grandTotal);
            view()->share('elements',$element);

            return view('pages.reports.report11');
        }
        public function report11export()
        {

           
             return view('report11.pdf'); 
        }
        public function report12()
        {
            $element=[];
            $currentdate=(new DateTime())->format('Y-m-d');
            $fromDate=date('Y-m-01', strtotime($currentdate));
            $toDate=date('Y-m-t', strtotime($currentdate));
            $activity_01=Form12::selectRaw('SUM(government_lands) as gl,SUM(private_lands) as pl,SUM(total_lands) as total')
            ->where('rejected',0)
            ->whereBetween('created_at',[$fromDate,$toDate])
            ->first();
            $activity_02=Form12::selectRaw('SUM(government_lands) as gl,SUM(private_lands) as pl,SUM(total_lands) as total')
            ->where('regional_approved',1)
            ->where('rejected',0)
            ->whereBetween('created_at',[$fromDate,$toDate])
            ->first();
            $activity_03=Form12::selectRaw('SUM(government_lands) as gl,SUM(private_lands) as pl,SUM(total_lands) as total')
            ->where('rejected',1)
            ->whereBetween('created_at',[$fromDate,$toDate])
            ->first();
            $activity_04=Form12::selectRaw('SUM(government_lands) as gl,SUM(private_lands) as pl,SUM(total_lands) as total')
            ->where('regional_approved',1)
            ->where('publication_branch',1)
            ->where('rejected',0)
            ->whereBetween('created_at',[$fromDate,$toDate])
            ->first();
            $activity_05=Form12::selectRaw('SUM(government_lands) as gl,SUM(private_lands) as pl,SUM(total_lands) as total')
            ->where('sent_gov_press',1)
            ->where('rejected',0)
            ->whereBetween('created_at',[$fromDate,$toDate])
            ->first();
            $activity_06=Form12::selectRaw('SUM(government_lands) as gl,SUM(private_lands) as pl,SUM(total_lands) as total')
            ->where('gazette_no','<>',null)
            ->where('rejected',0)
            ->whereBetween('created_at',[$fromDate,$toDate])
            ->first();
           $activtiy01=[
               'activity'=>'Size_of_cut_off_maps',
               'gov_land'=>$activity_01->gl,
               'pvt_land'=>$activity_01->pl,
               'total'=>$activity_01->total,
                ];
            array_push($element,$activtiy01);
            $activtiy02=[
                'activity'=>'Number_of_pieces_sent_to_Head_Office',
                'gov_land'=>$activity_02->gl,
                'pvt_land'=>$activity_02->pl,
                'total'=>$activity_02->total,
                 ];
            array_push($element,$activtiy02);
            $activtiy03=[
                'activity'=>'Amount_rejected',
                'gov_land'=>$activity_03->gl,
                'pvt_land'=>$activity_03->pl,
                'total'=>$activity_03->total,
                    ];
            array_push($element,$activtiy03);
            $activtiy04=[
                'activity'=>'Amount_of_approval_of_decision_recommendations',
                'gov_land'=>$activity_04->gl,
                'pvt_land'=>$activity_04->pl,
                'total'=>$activity_04->total,
                    ];
            array_push($element,$activtiy04);
            $activtiy05=[
                'activity'=>'Size_of_the_printing_press',
                'gov_land'=>$activity_05->gl,
                'pvt_land'=>$activity_05->pl,
                'total'=>$activity_05->total,
                    ];
            array_push($element,$activtiy05);
            $activtiy06=[
                'activity'=>'Amount_of_Gazette',
                'gov_land'=>$activity_06->gl,
                'pvt_land'=>$activity_06->pl,
                'total'=>$activity_06->total,
                    ];
            array_push($element,$activtiy06);
            view()->share('fdate',$fromDate);
            view()->share('tdate',$toDate);
            view()->share('elements',$element);
            return view('pages.reports.report12');
        }
        public function report12export($fromDate,$toDate)
        {
            $element=[];
            $activity_01=Form12::selectRaw('SUM(government_lands) as gl,SUM(private_lands) as pl,SUM(total_lands) as total')
            ->where('rejected',0)
            ->whereBetween('created_at',[$fromDate,$toDate])
            ->first();
            $activity_02=Form12::selectRaw('SUM(government_lands) as gl,SUM(private_lands) as pl,SUM(total_lands) as total')
            ->where('regional_approved',1)
            ->where('rejected',0)
            ->whereBetween('created_at',[$fromDate,$toDate])
            ->first();
            $activity_03=Form12::selectRaw('SUM(government_lands) as gl,SUM(private_lands) as pl,SUM(total_lands) as total')
            ->where('rejected',1)
            ->whereBetween('created_at',[$fromDate,$toDate])
            ->first();
            $activity_04=Form12::selectRaw('SUM(government_lands) as gl,SUM(private_lands) as pl,SUM(total_lands) as total')
            ->where('regional_approved',1)
            ->where('publication_branch',1)
            ->where('rejected',0)
            ->whereBetween('created_at',[$fromDate,$toDate])
            ->first();
            $activity_05=Form12::selectRaw('SUM(government_lands) as gl,SUM(private_lands) as pl,SUM(total_lands) as total')
            ->where('sent_gov_press',1)
            ->where('rejected',0)
            ->whereBetween('created_at',[$fromDate,$toDate])
            ->first();
            $activity_06=Form12::selectRaw('SUM(government_lands) as gl,SUM(private_lands) as pl,SUM(total_lands) as total')
            ->where('gazette_no','<>',null)
            ->where('rejected',0)
            ->whereBetween('created_at',[$fromDate,$toDate])
            ->first();
           $activtiy01=[
               'activity'=>'Size_of_cut_off_maps',
               'gov_land'=>$activity_01->gl,
               'pvt_land'=>$activity_01->pl,
               'total'=>$activity_01->total,
                ];
            array_push($element,$activtiy01);
            $activtiy02=[
                'activity'=>'Number_of_pieces_sent_to_Head_Office',
                'gov_land'=>$activity_02->gl,
                'pvt_land'=>$activity_02->pl,
                'total'=>$activity_02->total,
                 ];
            array_push($element,$activtiy02);
            $activtiy03=[
                'activity'=>'Amount_rejected',
                'gov_land'=>$activity_03->gl,
                'pvt_land'=>$activity_03->pl,
                'total'=>$activity_03->total,
                    ];
            array_push($element,$activtiy03);
            $activtiy04=[
                'activity'=>'Amount_of_approval_of_decision_recommendations',
                'gov_land'=>$activity_04->gl,
                'pvt_land'=>$activity_04->pl,
                'total'=>$activity_04->total,
                    ];
            array_push($element,$activtiy04);
            $activtiy05=[
                'activity'=>'Size_of_the_printing_press',
                'gov_land'=>$activity_05->gl,
                'pvt_land'=>$activity_05->pl,
                'total'=>$activity_05->total,
                    ];
            array_push($element,$activtiy05);
            $activtiy06=[
                'activity'=>'Amount_of_Gazette',
                'gov_land'=>$activity_06->gl,
                'pvt_land'=>$activity_06->pl,
                'total'=>$activity_06->total,
                    ];
            array_push($element,$activtiy06);
            view()->share('fdate',$fromDate);
            view()->share('tdate',$toDate);
            view()->share('elements',$element);
    
            return view('pdfs.report12');
        }
        public function report13()
        {
            $element=[];
            $month1=[];$month2=[];$month3=[];$month4=[];$month5=[];$month6=[];
            $currentdate=(new DateTime())->format('Y-m-d');
            $fromDate=date('Y-01-01', strtotime($currentdate));
            $toDate=date('Y-12-t', strtotime($currentdate));
            $activity_01=Form12::select(DB::raw('SUM(government_lands) as gl'),DB::raw('SUM(private_lands) as pl'),DB::raw('SUM(total_lands) as total'),DB::raw('YEAR(created_at) year'),DB::raw('MONTH(created_at) month'))
            ->where('rejected',0)
            ->whereBetween('created_at',[$fromDate,$toDate])
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy('month','ASC')
            ->get();
            $activity_02=Form12::select(DB::raw('SUM(government_lands) as gl'),DB::raw('SUM(private_lands) as pl'),DB::raw('SUM(total_lands) as total'),DB::raw('YEAR(created_at) year'),DB::raw('MONTH(created_at) month'))
            ->where('regional_approved',1)
            ->where('rejected',0)
            ->whereBetween('created_at',[$fromDate,$toDate])
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy('month','ASC')
            ->get();
            $activity_03=Form12::select(DB::raw('SUM(government_lands) as gl'),DB::raw('SUM(private_lands) as pl'),DB::raw('SUM(total_lands) as total'),DB::raw('YEAR(created_at) year'),DB::raw('MONTH(created_at) month'))
            ->where('rejected',1)
            ->whereBetween('created_at',[$fromDate,$toDate])
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy('month','ASC')
            ->get();
            $activity_04=Form12::select(DB::raw('SUM(government_lands) as gl'),DB::raw('SUM(private_lands) as pl'),DB::raw('SUM(total_lands) as total'),DB::raw('YEAR(created_at) year'),DB::raw('MONTH(created_at) month'))
            ->where('regional_approved',1)
            ->where('publication_branch',1)
            ->where('rejected',0)
            ->whereBetween('created_at',[$fromDate,$toDate])
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy('month','ASC')
            ->get();
            $activity_05=Form12::select(DB::raw('SUM(government_lands) as gl'),DB::raw('SUM(private_lands) as pl'),DB::raw('SUM(total_lands) as total'),DB::raw('YEAR(created_at) year'),DB::raw('MONTH(created_at) month'))
            ->where('sent_gov_press',1)
            ->where('rejected',0)
            ->whereBetween('created_at',[$fromDate,$toDate])
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy('month','ASC')
            ->get();
            $activity_06=Form12::select(DB::raw('SUM(government_lands) as gl'),DB::raw('SUM(private_lands) as pl'),DB::raw('SUM(total_lands) as total'),DB::raw('YEAR(created_at) year'),DB::raw('MONTH(created_at) month'))
            ->where('gazette_no','<>',null)
            ->where('rejected',0)
            ->whereBetween('created_at',[$fromDate,$toDate])
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy('month','ASC')
            ->get();
            foreach($activity_01 as $act)
            {
            $month_details=[
                'month'=>$act->month,
                'gov_land'=>$act->gl,
                'pvt_land'=>$act->pl,
            ];
            array_push($month1,$month_details);
            }
            $completMonth1=[];
            $total_gov_land=0;
            $total_pvt_land=0;
            for($i=1;$i<=12;$i++)
            {
                $found=true;
                foreach($month1 as $mon)
                {
                    if($mon['month']==$i)
                    {
                        $found=true;
                        $month_details=[
                            'month'=>$mon['month'],
                            'gov_land'=>$mon['gov_land'],
                            'pvt_land'=>$mon['pvt_land'],
                            ];
                            $total_gov_land=$total_gov_land+$mon['gov_land'];
                            $total_pvt_land=$total_pvt_land+$mon['pvt_land'];
                           array_push($completMonth1,$month_details);
                        break;
                    }
                    else
                    {
                        $found=false;   
                    }
                }
                if($found==false)
                {
                    $month_details=[
                    'month'=>$i,
                    'gov_land'=>0,
                    'pvt_land'=>0,
                    ];
                   array_push($completMonth1,$month_details);
                }  
            }    
            $activtiy01=[
                'activity'=>'Size_of_cut_off_maps',
                'month'=>$completMonth1,
                'total_gov_land'=>$total_gov_land,
                'total_pvt_land'=>$total_pvt_land,
                'Grand_total'=>$total_gov_land+$total_pvt_land,
                 ];
            array_push($element,$activtiy01);
    
            foreach($activity_02 as $act)
            {
            $month_details=[
                'month'=>$act->month,
                'gov_land'=>$act->gl,
                'pvt_land'=>$act->pl,
            ];
            array_push($month2,$month_details);
            }
            $completMonth2=[];
            $total_gov_land=0;
            $total_pvt_land=0;
            for($i=1;$i<=12;$i++)
            {
                $found=true;
                foreach($month2 as $mon)
                {
                    if($mon['month']==$i)
                    {
                        $found=true;
                        $month_details=[
                            'month'=>$mon['month'],
                            'gov_land'=>$mon['gov_land'],
                            'pvt_land'=>$mon['pvt_land'],
                            ];
                            $total_gov_land=$total_gov_land+$mon['gov_land'];
                            $total_pvt_land=$total_pvt_land+$mon['pvt_land'];
                           array_push($completMonth2,$month_details);
                        break;
                    }
                    else
                    {
                        $found=false;
                    }
                }
                if($found==false)
                {
                    $month_details=[
                    'month'=>$i,
                    'gov_land'=>0,
                    'pvt_land'=>0,
                    ];
                   array_push($completMonth2,$month_details);
                }
            }
            $activtiy02=[
                'activity'=>'Number_of_pieces_sent_to_Head_Office',
                'month'=>$completMonth2,
                'total_gov_land'=>$total_gov_land,
                'total_pvt_land'=>$total_pvt_land,
                'Grand_total'=>$total_gov_land+$total_pvt_land,
                 ];
            array_push($element,$activtiy02);
        
            foreach($activity_03 as $act)
            {
            $month_details=[
                'month'=>$act->month,
                'gov_land'=>$act->gl,
                'pvt_land'=>$act->pl,
            ];
            array_push($month3,$month_details);
            }
            $completMonth3=[];
            $total_gov_land=0;
            $total_pvt_land=0;
            for($i=1;$i<=12;$i++)
            {
                $found=true;
                foreach($month3 as $mon)
                {
                    if($mon['month']==$i)
                    {
                        $found=true;
                        $month_details=[
                            'month'=>$mon['month'],
                            'gov_land'=>$mon['gov_land'],
                            'pvt_land'=>$mon['pvt_land'],
                            ];
                            $total_gov_land=$total_gov_land+$mon['gov_land'];
                            $total_pvt_land=$total_pvt_land+$mon['pvt_land'];
                           array_push($completMonth3,$month_details);
                        break;
                    }
                    else
                    {
                        $found=false;
                    }
                }
                if($found==false)
                {
                    $month_details=[
                    'month'=>$i,
                    'gov_land'=>0,
                    'pvt_land'=>0,
                    ];
                   array_push($completMonth3,$month_details);
                }   
            }
            $activtiy03=[
                'activity'=>'Amount_rejected',
                'month'=>$completMonth3,
                'total_gov_land'=>$total_gov_land,
                'total_pvt_land'=>$total_pvt_land,
                'Grand_total'=>$total_gov_land+$total_pvt_land,
                 ];
            array_push($element,$activtiy03);

            foreach($activity_04 as $act)
            {
            $month_details=[
                'month'=>$act->month,
                'gov_land'=>$act->gl,
                'pvt_land'=>$act->pl,
            ];
            array_push($month4,$month_details);
            }
            $completMonth4=[];
            $total_gov_land=0;
            $total_pvt_land=0;
            for($i=1;$i<=12;$i++)
            {
                $found=true;
                foreach($month4 as $mon)
                {
                    if($mon['month']==$i)
                    {
                        $found=true;
                        $month_details=[
                            'month'=>$mon['month'],
                            'gov_land'=>$mon['gov_land'],
                            'pvt_land'=>$mon['pvt_land'],
                            ];
                            $total_gov_land=$total_gov_land+$mon['gov_land'];
                            $total_pvt_land=$total_pvt_land+$mon['pvt_land'];
                           array_push($completMonth4,$month_details);
                        break;
                    }
                    else
                    {
                        $found=false;
                    }
                }
                if($found==false)
                {
                    $month_details=[
                    'month'=>$i,
                    'gov_land'=>0,
                    'pvt_land'=>0,
                    ];
                   array_push($completMonth4,$month_details);
                }   
            }
            $activtiy04=[
                'activity'=>'Amount_of_approval_of_decision_recommendations',
                'month'=>$completMonth4,
                'total_gov_land'=>$total_gov_land,
                'total_pvt_land'=>$total_pvt_land,
                'Grand_total'=>$total_gov_land+$total_pvt_land,
                 ];
            array_push($element,$activtiy04);

            foreach($activity_05 as $act)
            {
            $month_details=[
                'month'=>$act->month,
                'gov_land'=>$act->gl,
                'pvt_land'=>$act->pl,
            ];
            array_push($month5,$month_details);
            }
            $completMonth5=[];
            $total_gov_land=0;
            $total_pvt_land=0;
            for($i=1;$i<=12;$i++)
            {
                $found=true;
                foreach($month5 as $mon)
                {
                    if($mon['month']==$i)
                    {
                        $found=true;
                        $month_details=[
                            'month'=>$mon['month'],
                            'gov_land'=>$mon['gov_land'],
                            'pvt_land'=>$mon['pvt_land'],
                            ];
                            $total_gov_land=$total_gov_land+$mon['gov_land'];
                            $total_pvt_land=$total_pvt_land+$mon['pvt_land'];
                           array_push($completMonth5,$month_details);
                        break;
                    }
                    else
                    {
                        $found=false;
                    }
                }
                if($found==false)
                {
                    $month_details=[
                    'month'=>$i,
                    'gov_land'=>0,
                    'pvt_land'=>0,
                    ];
                   array_push($completMonth5,$month_details);
                }   
            }
            $activtiy05=[
                'activity'=>'Size_of_the_printing_press',
                'month'=>$completMonth5,
                'total_gov_land'=>$total_gov_land,
                'total_pvt_land'=>$total_pvt_land,
                'Grand_total'=>$total_gov_land+$total_pvt_land,
                 ];
            array_push($element,$activtiy05);

            foreach($activity_06 as $act)
            {
            $month_details=[
                'month'=>$act->month,
                'gov_land'=>$act->gl,
                'pvt_land'=>$act->pl,
            ];
            array_push($month6,$month_details);
            }
            $completMonth6=[];
            $total_gov_land=0;
            $total_pvt_land=0;
            for($i=1;$i<=12;$i++)
            {
                $found=true;
                foreach($month6 as $mon)
                {
                    if($mon['month']==$i)
                    {
                        $found=true;
                        $month_details=[
                            'month'=>$mon['month'],
                            'gov_land'=>$mon['gov_land'],
                            'pvt_land'=>$mon['pvt_land'],
                            ];
                            $total_gov_land=$total_gov_land+$mon['gov_land'];
                            $total_pvt_land=$total_pvt_land+$mon['pvt_land'];
                           array_push($completMonth6,$month_details);
                        break;
                    }
                    else
                    {
                        $found=false;
                    }
                }
                if($found==false)
                {
                    $month_details=[
                    'month'=>$i,
                    'gov_land'=>0,
                    'pvt_land'=>0,
                    ];
                   array_push($completMonth6,$month_details);
                }   
            }
            $activtiy06=[
                'activity'=>'Amount_of_Gazette',
                'month'=>$completMonth6,
                'total_gov_land'=>$total_gov_land,
                'total_pvt_land'=>$total_pvt_land,
                'Grand_total'=>$total_gov_land+$total_pvt_land,
                 ];
               
            array_push($element,$activtiy06);
            view()->share('fdate',$fromDate);
            view()->share('tdate',$toDate);
            view()->share('elements',$element);
            return view('pages.reports.report13');
        }
        public function report13export($year)
        {   
            $element=[];
            $month1=[];$month2=[];$month3=[];$month4=[];$month5=[];$month6=[];
            $fromDate=date('Y-01-01', strtotime($year.'-01-01'));
            $toDate=date('Y-12-t', strtotime($year.'-12-31'));
            $activity_01=Form12::select(DB::raw('SUM(government_lands) as gl'),DB::raw('SUM(private_lands) as pl'),DB::raw('SUM(total_lands) as total'),DB::raw('YEAR(created_at) year'),DB::raw('MONTH(created_at) month'))
            ->where('rejected',0)
            ->whereBetween('created_at',[$fromDate,$toDate])
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy('month','ASC')
            ->get();
            $activity_02=Form12::select(DB::raw('SUM(government_lands) as gl'),DB::raw('SUM(private_lands) as pl'),DB::raw('SUM(total_lands) as total'),DB::raw('YEAR(created_at) year'),DB::raw('MONTH(created_at) month'))
            ->where('regional_approved',1)
            ->where('rejected',0)
            ->whereBetween('created_at',[$fromDate,$toDate])
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy('month','ASC')
            ->get();
            $activity_03=Form12::select(DB::raw('SUM(government_lands) as gl'),DB::raw('SUM(private_lands) as pl'),DB::raw('SUM(total_lands) as total'),DB::raw('YEAR(created_at) year'),DB::raw('MONTH(created_at) month'))
            ->where('rejected',1)
            ->whereBetween('created_at',[$fromDate,$toDate])
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy('month','ASC')
            ->get();
            $activity_04=Form12::select(DB::raw('SUM(government_lands) as gl'),DB::raw('SUM(private_lands) as pl'),DB::raw('SUM(total_lands) as total'),DB::raw('YEAR(created_at) year'),DB::raw('MONTH(created_at) month'))
            ->where('regional_approved',1)
            ->where('publication_branch',1)
            ->where('rejected',0)
            ->whereBetween('created_at',[$fromDate,$toDate])
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy('month','ASC')
            ->get();
            $activity_05=Form12::select(DB::raw('SUM(government_lands) as gl'),DB::raw('SUM(private_lands) as pl'),DB::raw('SUM(total_lands) as total'),DB::raw('YEAR(created_at) year'),DB::raw('MONTH(created_at) month'))
            ->where('sent_gov_press',1)
            ->where('rejected',0)
            ->whereBetween('created_at',[$fromDate,$toDate])
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy('month','ASC')
            ->get();
            $activity_06=Form12::select(DB::raw('SUM(government_lands) as gl'),DB::raw('SUM(private_lands) as pl'),DB::raw('SUM(total_lands) as total'),DB::raw('YEAR(created_at) year'),DB::raw('MONTH(created_at) month'))
            ->where('gazette_no','<>',null)
            ->where('rejected',0)
            ->whereBetween('created_at',[$fromDate,$toDate])
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy('month','ASC')
            ->get();
            foreach($activity_01 as $act)
            {
            $month_details=[
                'month'=>$act->month,
                'gov_land'=>$act->gl,
                'pvt_land'=>$act->pl,
            ];
            array_push($month1,$month_details);
            }
            $completMonth1=[];
            $total_gov_land=0;
            $total_pvt_land=0;
            for($i=1;$i<=12;$i++)
            {
                $found=true;
                foreach($month1 as $mon)
                {
                    if($mon['month']==$i)
                    {
                        $found=true;
                        $month_details=[
                            'month'=>$mon['month'],
                            'gov_land'=>$mon['gov_land'],
                            'pvt_land'=>$mon['pvt_land'],
                            ];
                            $total_gov_land=$total_gov_land+$mon['gov_land'];
                            $total_pvt_land=$total_pvt_land+$mon['pvt_land'];
                           array_push($completMonth1,$month_details);
                        break;
                    }
                    else
                    {
                        $found=false;   
                    }
                }
                if($found==false)
                {
                    $month_details=[
                    'month'=>$i,
                    'gov_land'=>0,
                    'pvt_land'=>0,
                    ];
                   array_push($completMonth1,$month_details);
                }  
            }    
            $activtiy01=[
                'activity'=>'Size_of_cut_off_maps',
                'month'=>$completMonth1,
                'total_gov_land'=>$total_gov_land,
                'total_pvt_land'=>$total_pvt_land,
                'Grand_total'=>$total_gov_land+$total_pvt_land,
                 ];
            array_push($element,$activtiy01);
    
            foreach($activity_02 as $act)
            {
            $month_details=[
                'month'=>$act->month,
                'gov_land'=>$act->gl,
                'pvt_land'=>$act->pl,
            ];
            array_push($month2,$month_details);
            }
            $completMonth2=[];
            $total_gov_land=0;
            $total_pvt_land=0;
            for($i=1;$i<=12;$i++)
            {
                $found=true;
                foreach($month2 as $mon)
                {
                    if($mon['month']==$i)
                    {
                        $found=true;
                        $month_details=[
                            'month'=>$mon['month'],
                            'gov_land'=>$mon['gov_land'],
                            'pvt_land'=>$mon['pvt_land'],
                            ];
                            $total_gov_land=$total_gov_land+$mon['gov_land'];
                            $total_pvt_land=$total_pvt_land+$mon['pvt_land'];
                           array_push($completMonth2,$month_details);
                        break;
                    }
                    else
                    {
                        $found=false;
                    }
                }
                if($found==false)
                {
                    $month_details=[
                    'month'=>$i,
                    'gov_land'=>0,
                    'pvt_land'=>0,
                    ];
                   array_push($completMonth2,$month_details);
                }
            }
            $activtiy02=[
                'activity'=>'Number_of_pieces_sent_to_Head_Office',
                'month'=>$completMonth2,
                'total_gov_land'=>$total_gov_land,
                'total_pvt_land'=>$total_pvt_land,
                'Grand_total'=>$total_gov_land+$total_pvt_land,
                 ];
            array_push($element,$activtiy02);
        
            foreach($activity_03 as $act)
            {
            $month_details=[
                'month'=>$act->month,
                'gov_land'=>$act->gl,
                'pvt_land'=>$act->pl,
            ];
            array_push($month3,$month_details);
            }
            $completMonth3=[];
            $total_gov_land=0;
            $total_pvt_land=0;
            for($i=1;$i<=12;$i++)
            {
                $found=true;
                foreach($month3 as $mon)
                {
                    if($mon['month']==$i)
                    {
                        $found=true;
                        $month_details=[
                            'month'=>$mon['month'],
                            'gov_land'=>$mon['gov_land'],
                            'pvt_land'=>$mon['pvt_land'],
                            ];
                            $total_gov_land=$total_gov_land+$mon['gov_land'];
                            $total_pvt_land=$total_pvt_land+$mon['pvt_land'];
                           array_push($completMonth3,$month_details);
                        break;
                    }
                    else
                    {
                        $found=false;
                    }
                }
                if($found==false)
                {
                    $month_details=[
                    'month'=>$i,
                    'gov_land'=>0,
                    'pvt_land'=>0,
                    ];
                   array_push($completMonth3,$month_details);
                }   
            }
            $activtiy03=[
                'activity'=>'Amount_rejected',
                'month'=>$completMonth3,
                'total_gov_land'=>$total_gov_land,
                'total_pvt_land'=>$total_pvt_land,
                'Grand_total'=>$total_gov_land+$total_pvt_land,
                 ];
            array_push($element,$activtiy03);

            foreach($activity_04 as $act)
            {
            $month_details=[
                'month'=>$act->month,
                'gov_land'=>$act->gl,
                'pvt_land'=>$act->pl,
            ];
            array_push($month4,$month_details);
            }
            $completMonth4=[];
            $total_gov_land=0;
            $total_pvt_land=0;
            for($i=1;$i<=12;$i++)
            {
                $found=true;
                foreach($month4 as $mon)
                {
                    if($mon['month']==$i)
                    {
                        $found=true;
                        $month_details=[
                            'month'=>$mon['month'],
                            'gov_land'=>$mon['gov_land'],
                            'pvt_land'=>$mon['pvt_land'],
                            ];
                            $total_gov_land=$total_gov_land+$mon['gov_land'];
                            $total_pvt_land=$total_pvt_land+$mon['pvt_land'];
                           array_push($completMonth4,$month_details);
                        break;
                    }
                    else
                    {
                        $found=false;
                    }
                }
                if($found==false)
                {
                    $month_details=[
                    'month'=>$i,
                    'gov_land'=>0,
                    'pvt_land'=>0,
                    ];
                   array_push($completMonth4,$month_details);
                }   
            }
            $activtiy04=[
                'activity'=>'Amount_of_approval_of_decision_recommendations',
                'month'=>$completMonth4,
                'total_gov_land'=>$total_gov_land,
                'total_pvt_land'=>$total_pvt_land,
                'Grand_total'=>$total_gov_land+$total_pvt_land,
                 ];
            array_push($element,$activtiy04);

            foreach($activity_05 as $act)
            {
            $month_details=[
                'month'=>$act->month,
                'gov_land'=>$act->gl,
                'pvt_land'=>$act->pl,
            ];
            array_push($month5,$month_details);
            }
            $completMonth5=[];
            $total_gov_land=0;
            $total_pvt_land=0;
            for($i=1;$i<=12;$i++)
            {
                $found=true;
                foreach($month5 as $mon)
                {
                    if($mon['month']==$i)
                    {
                        $found=true;
                        $month_details=[
                            'month'=>$mon['month'],
                            'gov_land'=>$mon['gov_land'],
                            'pvt_land'=>$mon['pvt_land'],
                            ];
                            $total_gov_land=$total_gov_land+$mon['gov_land'];
                            $total_pvt_land=$total_pvt_land+$mon['pvt_land'];
                           array_push($completMonth5,$month_details);
                        break;
                    }
                    else
                    {
                        $found=false;
                    }
                }
                if($found==false)
                {
                    $month_details=[
                    'month'=>$i,
                    'gov_land'=>0,
                    'pvt_land'=>0,
                    ];
                   array_push($completMonth5,$month_details);
                }   
            }
            $activtiy05=[
                'activity'=>'Size_of_the_printing_press',
                'month'=>$completMonth5,
                'total_gov_land'=>$total_gov_land,
                'total_pvt_land'=>$total_pvt_land,
                'Grand_total'=>$total_gov_land+$total_pvt_land,
                 ];
            array_push($element,$activtiy05);

            foreach($activity_06 as $act)
            {
            $month_details=[
                'month'=>$act->month,
                'gov_land'=>$act->gl,
                'pvt_land'=>$act->pl,
            ];
            array_push($month6,$month_details);
            }
            $completMonth6=[];
            $total_gov_land=0;
            $total_pvt_land=0;
            for($i=1;$i<=12;$i++)
            {
                $found=true;
                foreach($month6 as $mon)
                {
                    if($mon['month']==$i)
                    {
                        $found=true;
                        $month_details=[
                            'month'=>$mon['month'],
                            'gov_land'=>$mon['gov_land'],
                            'pvt_land'=>$mon['pvt_land'],
                            ];
                            $total_gov_land=$total_gov_land+$mon['gov_land'];
                            $total_pvt_land=$total_pvt_land+$mon['pvt_land'];
                           array_push($completMonth6,$month_details);
                        break;
                    }
                    else
                    {
                        $found=false;
                    }
                }
                if($found==false)
                {
                    $month_details=[
                    'month'=>$i,
                    'gov_land'=>0,
                    'pvt_land'=>0,
                    ];
                   array_push($completMonth6,$month_details);
                }   
            }
            $activtiy06=[
                'activity'=>'Amount_of_Gazette',
                'month'=>$completMonth6,
                'total_gov_land'=>$total_gov_land,
                'total_pvt_land'=>$total_pvt_land,
                'Grand_total'=>$total_gov_land+$total_pvt_land,
                 ];   
            array_push($element,$activtiy06);
            view()->share('fdate',$fromDate);
            view()->share('tdate',$toDate);
            view()->share('elements',$element);
            return view('pdfs.report13');
        }
        public function report14()
        {
            $element=[];
            $year1=[];$year2=[];$year3=[];$year4=[];$year5=[];$year6=[];
            $currentdate=(new DateTime())->format('Y-m-d');
            $fromDate=date('Y-01-01', strtotime($currentdate));
            $toDate=date('Y-12-t', strtotime($currentdate));
         
            $activity_01=Form12::select(DB::raw('SUM(government_lands) as gl'),DB::raw('SUM(private_lands) as pl'),DB::raw('SUM(total_lands) as total'),DB::raw('YEAR(created_at) year'))
            ->where('rejected',0)
            ->whereBetween('created_at',[$fromDate,$toDate])
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->orderBy('year','ASC')
            ->get();
            $activity_02=Form12::select(DB::raw('SUM(government_lands) as gl'),DB::raw('SUM(private_lands) as pl'),DB::raw('SUM(total_lands) as total'),DB::raw('YEAR(created_at) year'))
            ->where('regional_approved',1)
            ->where('rejected',0)
            ->whereBetween('created_at',[$fromDate,$toDate])
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->orderBy('year','ASC')
            ->get();
            $activity_03=Form12::select(DB::raw('SUM(government_lands) as gl'),DB::raw('SUM(private_lands) as pl'),DB::raw('SUM(total_lands) as total'),DB::raw('YEAR(created_at) year'))
            ->where('rejected',1)
            ->whereBetween('created_at',[$fromDate,$toDate])
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->orderBy('year','ASC')
            ->get();
            $activity_04=Form12::select(DB::raw('SUM(government_lands) as gl'),DB::raw('SUM(private_lands) as pl'),DB::raw('SUM(total_lands) as total'),DB::raw('YEAR(created_at) year'))
            ->where('regional_approved',1)
            ->where('publication_branch',1)
            ->where('rejected',0)
            ->whereBetween('created_at',[$fromDate,$toDate])
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->orderBy('year','ASC')
            ->get();
            $activity_05=Form12::select(DB::raw('SUM(government_lands) as gl'),DB::raw('SUM(private_lands) as pl'),DB::raw('SUM(total_lands) as total'),DB::raw('YEAR(created_at) year'))
            ->where('sent_gov_press',1)
            ->where('rejected',0)
            ->whereBetween('created_at',[$fromDate,$toDate])
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->orderBy('year','ASC')
            ->get();
            $activity_06=Form12::select(DB::raw('SUM(government_lands) as gl'),DB::raw('SUM(private_lands) as pl'),DB::raw('SUM(total_lands) as total'),DB::raw('YEAR(created_at) year'))
            ->where('gazette_no','<>',null)
            ->where('rejected',0)
            ->whereBetween('created_at',[$fromDate,$toDate])
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->orderBy('year','ASC')
            ->get();
            $x=date_create($fromDate);
            $y=date_create($toDate);
            $diff=date_diff($x,$y);
            $numofyear=$diff->format("%y");
            $years=[];
            if($numofyear==0)
            {
                $count=0;
                $passyears=date('Y', strtotime($fromDate));
                $count= $passyears+0;
                array_push($years,$count);
            }
            else
            {
                $passyears=date('Y', strtotime($fromDate));
                $count=0;
                for($i=0;$i<=$numofyear;$i++)
                {
                    $count= $passyears+$i;
                    array_push($years,$count);
                }
            }
            $total_gov_land=0;
            $total_pvt_land=0;
            foreach($years as $yr)
            { 
                $found=false;
                foreach($activity_01 as $act)
                {
                    if($act->year==$yr)
                    {
                        $found=true;
                        $year_details=[
                        'year'=>$act->year,
                        'gov_land'=>$act->gl,
                        'pvt_land'=>$act->pl,
                        'total'=>$act->total,
                        ];
                        $total_gov_land=$total_gov_land+$act->gl;
                        $total_pvt_land=$total_pvt_land+$act->pl;
                        array_push($year1,$year_details);
                    break;
                    }
                    else
                    {
                        $found=false;
                    }
                }
                if($found==false)
                {
                    $year_details=[
                        'year'=>$yr,
                        'gov_land'=>0,
                        'pvt_land'=>0,
                        'total'=>0,
                        ];
                        array_push($year1,$year_details);
                }
            }
            $activtiy01=[
                'activity'=>'Size_of_cut_off_maps',
                'year'=>$year1,
                'total_gov_land'=>$total_gov_land,
                'total_pvt_land'=>$total_pvt_land,
                'Grand_total'=>$total_gov_land+$total_pvt_land,
                 ];
            array_push($element,$activtiy01);

            $total_gov_land=0;
            $total_pvt_land=0;
            foreach($years as $yr)
            { 
                $found=false;
                foreach($activity_02 as $act)
                {
                    if($act->year==$yr)
                    {
                    $found=true;
                    $year_details=[
                    'year'=>$act->year,
                    'gov_land'=>$act->gl,
                    'pvt_land'=>$act->pl,
                    'total'=>$act->total,
                    ];
                    $total_gov_land=$total_gov_land+$act->gl;
                    $total_pvt_land=$total_pvt_land+$act->pl;
                    array_push($year2,$year_details);
                    break;
                    }
                    else
                    {
                        $found=false;
                    }
                }
                if($found==false)
                {
                    $year_details=[
                        'year'=>$yr,
                        'gov_land'=>0,
                        'pvt_land'=>0,
                        'total'=>0,
                        ];
                        array_push($year2,$year_details);
                }
            }
            $activtiy02=[
                'activity'=>'Number_of_pieces_sent_to_Head_Office',
                'year'=>$year2,
                'total_gov_land'=>$total_gov_land,
                'total_pvt_land'=>$total_pvt_land,
                'Grand_total'=>$total_gov_land+$total_pvt_land,
                 ];
            array_push($element,$activtiy02);

            $total_gov_land=0;
            $total_pvt_land=0;
            foreach($years as $yr)
            { 
                $found=false;
                foreach($activity_03 as $act)
                {
                if($act->year==$yr)
                {
                    $found=true;
                    $year_details=[
                'year'=>$act->year,
                'gov_land'=>$act->gl,
                'pvt_land'=>$act->pl,
                'total'=>$act->total,
                    ];
                    $total_gov_land=$total_gov_land+$act->gl;
                    $total_pvt_land=$total_pvt_land+$act->pl;
                    array_push($year3,$year_details);
                break;
                }
                else
                {
                    $found=false;
                }
                }
                if($found==false)
                {
                $year_details=[
                    'year'=>$yr,
                    'gov_land'=>0,
                    'pvt_land'=>0,
                    'total'=>0,
                    ];
                    array_push($year3,$year_details);
                }
            }
            $activtiy03=[
                'activity'=>'Amount_rejected',
                'year'=>$year3,
                'total_gov_land'=>$total_gov_land,
                'total_pvt_land'=>$total_pvt_land,
                'Grand_total'=>$total_gov_land+$total_pvt_land,
                 ];
            array_push($element,$activtiy03);

            $total_gov_land=0;
            $total_pvt_land=0;
            foreach($years as $yr)
            { 
                $found=false;
                foreach($activity_04 as $act)
                {
                    if($act->year==$yr)
                    {
                        $found=true;
                    $year_details=[
                    'year'=>$act->year,
                    'gov_land'=>$act->gl,
                    'pvt_land'=>$act->pl,
                    'total'=>$act->total,
                    ];
                    $total_gov_land=$total_gov_land+$act->gl;
                    $total_pvt_land=$total_pvt_land+$act->pl;
                    array_push($year4,$year_details);
                    break;
                    }
                    else
                    {
                    $found=false;
                    }
                }
                if($found==false)
                {
                $year_details=[
                    'year'=>$yr,
                    'gov_land'=>0,
                    'pvt_land'=>0,
                    'total'=>0,
                    ];
                    array_push($year4,$year_details);
                }
            }
            $activtiy04=[
                'activity'=>'Amount_of_approval_of_decision_recommendations',
                'year'=>$year4,
                'total_gov_land'=>$total_gov_land,
                'total_pvt_land'=>$total_pvt_land,
                'Grand_total'=>$total_gov_land+$total_pvt_land,
                 ];
            array_push($element,$activtiy04);

            $total_gov_land=0;
            $total_pvt_land=0;
            foreach($years as $yr)
            { 
                $found=false;
                foreach($activity_05 as $act)
                {
                    if($act->year==$yr)
                    {
                    $found=true;
                    $year_details=[
                    'year'=>$act->year,
                    'gov_land'=>$act->gl,
                    'pvt_land'=>$act->pl,
                    'total'=>$act->total,
                    ];
                    $total_gov_land=$total_gov_land+$act->gl;
                    $total_pvt_land=$total_pvt_land+$act->pl;
                    array_push($year5,$year_details);
                    break;
                    }
                    else
                    {
                        $found=false;
                    }
                }
                if($found==false)
                {
                $year_details=[
                    'year'=>$yr,
                    'gov_land'=>0,
                    'pvt_land'=>0,
                    'total'=>0,
                    ];
                    array_push($year5,$year_details);
                }
            }
            $activtiy05=[
                'activity'=>'Size_of_the_printing_press',
                'year'=>$year5,
                'total_gov_land'=>$total_gov_land,
                'total_pvt_land'=>$total_pvt_land,
                'Grand_total'=>$total_gov_land+$total_pvt_land,
                 ];
            array_push($element,$activtiy05);

            $total_gov_land=0;
            $total_pvt_land=0;
            foreach($years as $yr)
            { 
                $found=false;
                foreach($activity_06 as $act)
                {
                    if($act->year==$yr)
                    {
                    $found=true;
                    $year_details=[
                    'year'=>$act->year,
                    'gov_land'=>$act->gl,
                    'pvt_land'=>$act->pl,
                    'total'=>$act->total,
                    ];
                    $total_gov_land=$total_gov_land+$act->gl;
                    $total_pvt_land=$total_pvt_land+$act->pl;
                    array_push($year6,$year_details);
                    break;
                    }
                    else
                    {
                        $found=false;
                    }
                }
                if($found==false)
                {
                $year_details=[
                    'year'=>$yr,
                    'gov_land'=>0,
                    'pvt_land'=>0,
                    'total'=>0,
                    ];
                    array_push($year6,$year_details);
                }
            }
            $activtiy06=[
                'activity'=>'Amount_of_Gazette',
                'year'=>$year6,
                'total_gov_land'=>$total_gov_land,
                'total_pvt_land'=>$total_pvt_land,
                'Grand_total'=>$total_gov_land+$total_pvt_land,
                 ];   
            array_push($element,$activtiy06);
        
            view()->share('years',$years);
            view()->share('fdate',$fromDate);
            view()->share('tdate',$toDate);
            view()->share('elements',$element);
            return view('pages.reports.report14');
        }

        public function report14export($fyear,$tyear)
        { 
            $element=[];
            $year1=[];$year2=[];$year3=[];$year4=[];$year5=[];$year6=[];
            $fromDate=date('Y-01-01', strtotime($fyear.'-01-01'));
            $toDate=date('Y-12-t', strtotime($tyear.'-12-31'));
         
            $activity_01=Form12::select(DB::raw('SUM(government_lands) as gl'),DB::raw('SUM(private_lands) as pl'),DB::raw('SUM(total_lands) as total'),DB::raw('YEAR(created_at) year'))
            ->where('rejected',0)
            ->whereBetween('created_at',[$fromDate,$toDate])
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->orderBy('year','ASC')
            ->get();
            $activity_02=Form12::select(DB::raw('SUM(government_lands) as gl'),DB::raw('SUM(private_lands) as pl'),DB::raw('SUM(total_lands) as total'),DB::raw('YEAR(created_at) year'))
            ->where('regional_approved',1)
            ->where('rejected',0)
            ->whereBetween('created_at',[$fromDate,$toDate])
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->orderBy('year','ASC')
            ->get();
            $activity_03=Form12::select(DB::raw('SUM(government_lands) as gl'),DB::raw('SUM(private_lands) as pl'),DB::raw('SUM(total_lands) as total'),DB::raw('YEAR(created_at) year'))
            ->where('rejected',1)
            ->whereBetween('created_at',[$fromDate,$toDate])
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->orderBy('year','ASC')
            ->get();
            $activity_04=Form12::select(DB::raw('SUM(government_lands) as gl'),DB::raw('SUM(private_lands) as pl'),DB::raw('SUM(total_lands) as total'),DB::raw('YEAR(created_at) year'))
            ->where('regional_approved',1)
            ->where('publication_branch',1)
            ->where('rejected',0)
            ->whereBetween('created_at',[$fromDate,$toDate])
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->orderBy('year','ASC')
            ->get();
            $activity_05=Form12::select(DB::raw('SUM(government_lands) as gl'),DB::raw('SUM(private_lands) as pl'),DB::raw('SUM(total_lands) as total'),DB::raw('YEAR(created_at) year'))
            ->where('sent_gov_press',1)
            ->where('rejected',0)
            ->whereBetween('created_at',[$fromDate,$toDate])
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->orderBy('year','ASC')
            ->get();
            $activity_06=Form12::select(DB::raw('SUM(government_lands) as gl'),DB::raw('SUM(private_lands) as pl'),DB::raw('SUM(total_lands) as total'),DB::raw('YEAR(created_at) year'))
            ->where('gazette_no','<>',null)
            ->where('rejected',0)
            ->whereBetween('created_at',[$fromDate,$toDate])
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->orderBy('year','ASC')
            ->get();
            $x=date_create($fromDate);
            $y=date_create($toDate);
            $diff=date_diff($x,$y);
            $numofyear=$diff->format("%y");
            $years=[];
            if($numofyear==0)
            {
                $count=0;
                $passyears=date('Y', strtotime($fromDate));
                $count= $passyears+0;
                array_push($years,$count);
            }
            else
            {
                $passyears=date('Y', strtotime($fromDate));
                $count=0;
                for($i=0;$i<=$numofyear;$i++)
                {
                    $count= $passyears+$i;
                    array_push($years,$count);
                }
            }
            $total_gov_land=0;
            $total_pvt_land=0;
            foreach($years as $yr)
            { 
                $found=false;
                foreach($activity_01 as $act)
                {
                    if($act->year==$yr)
                    {
                        $found=true;
                        $year_details=[
                        'year'=>$act->year,
                        'gov_land'=>$act->gl,
                        'pvt_land'=>$act->pl,
                        'total'=>$act->total,
                        ];
                        $total_gov_land=$total_gov_land+$act->gl;
                        $total_pvt_land=$total_pvt_land+$act->pl;
                        array_push($year1,$year_details);
                    break;
                    }
                    else
                    {
                        $found=false;
                    }
                }
                if($found==false)
                {
                    $year_details=[
                        'year'=>$yr,
                        'gov_land'=>0,
                        'pvt_land'=>0,
                        'total'=>0,
                        ];
                        array_push($year1,$year_details);
                }
            }
            $activtiy01=[
                'activity'=>'Size_of_cut_off_maps',
                'year'=>$year1,
                'total_gov_land'=>$total_gov_land,
                'total_pvt_land'=>$total_pvt_land,
                'Grand_total'=>$total_gov_land+$total_pvt_land,
                 ];
            array_push($element,$activtiy01);

            $total_gov_land=0;
            $total_pvt_land=0;
            foreach($years as $yr)
            { 
                $found=false;
                foreach($activity_02 as $act)
                {
                    if($act->year==$yr)
                    {
                    $found=true;
                    $year_details=[
                    'year'=>$act->year,
                    'gov_land'=>$act->gl,
                    'pvt_land'=>$act->pl,
                    'total'=>$act->total,
                    ];
                    $total_gov_land=$total_gov_land+$act->gl;
                    $total_pvt_land=$total_pvt_land+$act->pl;
                    array_push($year2,$year_details);
                    break;
                    }
                    else
                    {
                        $found=false;
                    }
                }
                if($found==false)
                {
                    $year_details=[
                        'year'=>$yr,
                        'gov_land'=>0,
                        'pvt_land'=>0,
                        'total'=>0,
                        ];
                        array_push($year2,$year_details);
                }
            }
            $activtiy02=[
                'activity'=>'Number_of_pieces_sent_to_Head_Office',
                'year'=>$year2,
                'total_gov_land'=>$total_gov_land,
                'total_pvt_land'=>$total_pvt_land,
                'Grand_total'=>$total_gov_land+$total_pvt_land,
                 ];
            array_push($element,$activtiy02);

            $total_gov_land=0;
            $total_pvt_land=0;
            foreach($years as $yr)
            { 
                $found=false;
                foreach($activity_03 as $act)
                {
                if($act->year==$yr)
                {
                    $found=true;
                    $year_details=[
                'year'=>$act->year,
                'gov_land'=>$act->gl,
                'pvt_land'=>$act->pl,
                'total'=>$act->total,
                    ];
                    $total_gov_land=$total_gov_land+$act->gl;
                    $total_pvt_land=$total_pvt_land+$act->pl;
                    array_push($year3,$year_details);
                break;
                }
                else
                {
                    $found=false;
                }
                }
                if($found==false)
                {
                $year_details=[
                    'year'=>$yr,
                    'gov_land'=>0,
                    'pvt_land'=>0,
                    'total'=>0,
                    ];
                    array_push($year3,$year_details);
                }
            }
            $activtiy03=[
                'activity'=>'Amount_rejected',
                'year'=>$year3,
                'total_gov_land'=>$total_gov_land,
                'total_pvt_land'=>$total_pvt_land,
                'Grand_total'=>$total_gov_land+$total_pvt_land,
                 ];
            array_push($element,$activtiy03);

            $total_gov_land=0;
            $total_pvt_land=0;
            foreach($years as $yr)
            { 
                $found=false;
                foreach($activity_04 as $act)
                {
                    if($act->year==$yr)
                    {
                        $found=true;
                    $year_details=[
                    'year'=>$act->year,
                    'gov_land'=>$act->gl,
                    'pvt_land'=>$act->pl,
                    'total'=>$act->total,
                    ];
                    $total_gov_land=$total_gov_land+$act->gl;
                    $total_pvt_land=$total_pvt_land+$act->pl;
                    array_push($year4,$year_details);
                    break;
                    }
                    else
                    {
                    $found=false;
                    }
                }
                if($found==false)
                {
                $year_details=[
                    'year'=>$yr,
                    'gov_land'=>0,
                    'pvt_land'=>0,
                    'total'=>0,
                    ];
                    array_push($year4,$year_details);
                }
            }
            $activtiy04=[
                'activity'=>'Amount_of_approval_of_decision_recommendations',
                'year'=>$year4,
                'total_gov_land'=>$total_gov_land,
                'total_pvt_land'=>$total_pvt_land,
                'Grand_total'=>$total_gov_land+$total_pvt_land,
                 ];
            array_push($element,$activtiy04);

            $total_gov_land=0;
            $total_pvt_land=0;
            foreach($years as $yr)
            { 
                $found=false;
                foreach($activity_05 as $act)
                {
                    if($act->year==$yr)
                    {
                    $found=true;
                    $year_details=[
                    'year'=>$act->year,
                    'gov_land'=>$act->gl,
                    'pvt_land'=>$act->pl,
                    'total'=>$act->total,
                    ];
                    $total_gov_land=$total_gov_land+$act->gl;
                    $total_pvt_land=$total_pvt_land+$act->pl;
                    array_push($year5,$year_details);
                    break;
                    }
                    else
                    {
                        $found=false;
                    }
                }
                if($found==false)
                {
                $year_details=[
                    'year'=>$yr,
                    'gov_land'=>0,
                    'pvt_land'=>0,
                    'total'=>0,
                    ];
                    array_push($year5,$year_details);
                }
            }
            $activtiy05=[
                'activity'=>'Size_of_the_printing_press',
                'year'=>$year5,
                'total_gov_land'=>$total_gov_land,
                'total_pvt_land'=>$total_pvt_land,
                'Grand_total'=>$total_gov_land+$total_pvt_land,
                 ];
            array_push($element,$activtiy05);

            $total_gov_land=0;
            $total_pvt_land=0;
            foreach($years as $yr)
            { 
                $found=false;
                foreach($activity_06 as $act)
                {
                    if($act->year==$yr)
                    {
                    $found=true;
                    $year_details=[
                    'year'=>$act->year,
                    'gov_land'=>$act->gl,
                    'pvt_land'=>$act->pl,
                    'total'=>$act->total,
                    ];
                    $total_gov_land=$total_gov_land+$act->gl;
                    $total_pvt_land=$total_pvt_land+$act->pl;
                    array_push($year6,$year_details);
                    break;
                    }
                    else
                    {
                        $found=false;
                    }
                }
                if($found==false)
                {
                $year_details=[
                    'year'=>$yr,
                    'gov_land'=>0,
                    'pvt_land'=>0,
                    'total'=>0,
                    ];
                    array_push($year6,$year_details);
                }
            }
            $activtiy06=[
                'activity'=>'Amount_of_Gazette',
                'year'=>$year6,
                'total_gov_land'=>$total_gov_land,
                'total_pvt_land'=>$total_pvt_land,
                'Grand_total'=>$total_gov_land+$total_pvt_land,
                 ];   
            array_push($element,$activtiy06);
        
            view()->share('years',$years);
            view()->share('fdate',$fromDate);
            view()->share('tdate',$toDate);
            view()->share('elements',$element);
            return view('pdfs.report14');

        }


}
