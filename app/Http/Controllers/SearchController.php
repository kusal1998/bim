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
use DB;
use DateTime;

class SearchController extends Controller
{
    public function getForm14lot($map,$block,$lotno){
        $form14=DB::table('form_14_header')
            ->join('form_14_detail','form_14_header.id','=','form_14_detail.form_14_Header_id')
            ->where('form_14_header.map_no',$map)->where('form_14_header.block_no',$block)->where('form_14_detail.lot_no',$lotno)
            ->get();
            if(sizeof($form14)>0){
                return ['message'=>$form14,'states'=>200];
            }else{
                return ['message'=>'Data Not found..','states'=>500];
            }
    }

}