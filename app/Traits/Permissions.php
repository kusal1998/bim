<?php
 namespace App\Traits;

use Auth;
use App\Models\Branches;
use App\Models\Countries;
use App\Models\Currencies;
use App\Models\UserRolePermissions;
use App\Models\UserRoles;
use App\Models\Modules;
use App\Models\Counters;
use App\Models\Services;
use App\Models\AgDivisions;
use App\Models\GnDivisions;
use App\User;
use Request;

trait Permissions
 {

    public function getBranchName($id)
    {
        $Element= Branches::find($id);
        if(isset($Element)){
            return $Element->name;
        }
    }


    public function getAGDName($id)
    {
        $Element= AgDivisions::find($id);
        if(isset($Element)){
            return $Element->ag_name;
        }
    }
    public function getGNDName($id)
    {
        $Element= GnDivisions::find($id);
        if(isset($Element)){
            return $Element->gn_name;
        }
    }


    public function getUserName($id)
    {
        $Element= User::find($id);
        if(isset($Element)){
            return $Element->name.' '.$Element->last_name;
        }
    }

    public function getServiceName($id)
    {
        $Element= Services::find($id);
        if(isset($Element)){
            return $Element->name;
        }
    }

    public function getCounterNo($id)
    {
        $Element= Counters::find($id);
        if(isset($Element)){
            return $Element->counter_no;
        }
    }


    public function getAccessCreate($module_code)
    {
           if(Auth::user()){
            $Permissions = UserRolePermissions::where('role_code',Auth::user()->role_code)->where('module_code',$module_code)->first();

            if(isset($Permissions)){
             if($Permissions->can_create==1){
                 $GrantPermission="Yes";
                 return $GrantPermission;;
               }
            }
            else{
                $GrantPermission="No";
                return $GrantPermission;;
            }
         }
    }

    public function getAccessView($module_code)
    {
           if(Auth::user()){
            $Permissions = UserRolePermissions::where('role_code',Auth::user()->role_code)->where('module_code',$module_code)->first();

            if(isset($Permissions)){
             if($Permissions->can_read==1){
                 $GrantPermission="Yes";
                 return $GrantPermission;;
               }

            else{
                $GrantPermission="No";
                return $GrantPermission;;
            }
            }
         }
    }


    public function getAccessUpdate($module_code)
    {

           if(Auth::user()){
            $Permissions = UserRolePermissions::where('role_code',Auth::user()->role_code)->where('module_code',$module_code)->first();

            if(isset($Permissions)){
             if($Permissions->can_update==1){
                 $GrantPermission="Yes";
                 return $GrantPermission;
               }

            else{
                $GrantPermission="No";
                return $GrantPermission;
            }
        }
         }
    }



    public function getAccessDelete($module_code)
    {
           if(Auth::user()){
            $Permissions = UserRolePermissions::where('role_code',Auth::user()->role_code)->where('module_code',$module_code)->first();

            if(isset($Permissions)){
             if($Permissions->can_delete==1){
                 $GrantPermission="Yes";
                 return $GrantPermission;;
               }

            else{
                $GrantPermission="No";
                return $GrantPermission;;
            }
        }
         }
    }

    public function getAccessRegVerify($module_code)
    {
           if(Auth::user()){
            $Permissions = UserRolePermissions::where('role_code',Auth::user()->role_code)->where('module_code',$module_code)->first();

            if(isset($Permissions)){
             if($Permissions->can_verify==1){
                 $GrantPermission="Yes";
                 return $GrantPermission;;
               }
            }
            else{
                $GrantPermission="No";
                return $GrantPermission;;
            }
         }
    }
    public function getAccessRegRecheck($module_code)
    {
           if(Auth::user()){
            $Permissions = UserRolePermissions::where('role_code',Auth::user()->role_code)->where('module_code',$module_code)->first();

            if(isset($Permissions)){
             if($Permissions->can_recheck==1){
                 $GrantPermission="Yes";
                 return $GrantPermission;;
               }
            }
            else{
                $GrantPermission="No";
                return $GrantPermission;;
            }
         }
    }
    public function getAccessRegApprove($module_code)
    {
           if(Auth::user()){
            $Permissions = UserRolePermissions::where('role_code',Auth::user()->role_code)->where('module_code',$module_code)->first();

            if(isset($Permissions)){
             if($Permissions->can_approve==1){
                 $GrantPermission="Yes";
                 return $GrantPermission;;
               }
            }
            else{
                $GrantPermission="No";
                return $GrantPermission;;
            }
         }
    }
    public function getAccessRegReject($module_code)
    {
           if(Auth::user()){
            $Permissions = UserRolePermissions::where('role_code',Auth::user()->role_code)->where('module_code',$module_code)->first();

            if(isset($Permissions)){
             if($Permissions->can_reject==1){
                 $GrantPermission="Yes";
                 return $GrantPermission;;
               }
            }
            else{
                $GrantPermission="No";
                return $GrantPermission;;
            }
         }
    }
    public function getAccessPubVerify($module_code)
    {
           if(Auth::user()){
            $Permissions = UserRolePermissions::where('role_code',Auth::user()->role_code)->where('module_code',$module_code)->first();

            if(isset($Permissions)){
             if($Permissions->can_publication_verify==1){
                 $GrantPermission="Yes";
                 return $GrantPermission;;
               }
            }
            else{
                $GrantPermission="No";
                return $GrantPermission;;
            }
         }
    }
    public function getAccessAsstComm($module_code)
    {
           if(Auth::user()){
            $Permissions = UserRolePermissions::where('role_code',Auth::user()->role_code)->where('module_code',$module_code)->first();

            if(isset($Permissions)){
             if($Permissions->can_asst_comm==1){
                 $GrantPermission="Yes";
                 return $GrantPermission;;
               }
            }
            else{
                $GrantPermission="No";
                return $GrantPermission;;
            }
         }
    }
    public function getAccessBimsaviyaComm($module_code)
    {
           if(Auth::user()){
            $Permissions = UserRolePermissions::where('role_code',Auth::user()->role_code)->where('module_code',$module_code)->first();

            if(isset($Permissions)){
             if($Permissions->can_bimsaviya_comm==1){
                 $GrantPermission="Yes";
                 return $GrantPermission;;
               }
            }
            else{
                $GrantPermission="No";
                return $GrantPermission;;
            }
         }
    }
    public function getAccessCommGen($module_code)
    {
           if(Auth::user()){
            $Permissions = UserRolePermissions::where('role_code',Auth::user()->role_code)->where('module_code',$module_code)->first();

            if(isset($Permissions)){
             if($Permissions->can_comm_general==1){
                 $GrantPermission="Yes";
                 return $GrantPermission;;
               }
            }
            else{
                $GrantPermission="No";
                return $GrantPermission;;
            }
         }
    }
    public function getAccessForwardProof($module_code)
    {
           if(Auth::user()){
            $Permissions = UserRolePermissions::where('role_code',Auth::user()->role_code)->where('module_code',$module_code)->first();

            if(isset($Permissions)){
             if($Permissions->can_forward_to_proof==1){
                 $GrantPermission="Yes";
                 return $GrantPermission;;
               }
            }
            else{
                $GrantPermission="No";
                return $GrantPermission;;
            }
         }
    }
    public function getAccessProof($module_code)
    {
           if(Auth::user()){
            $Permissions = UserRolePermissions::where('role_code',Auth::user()->role_code)->where('module_code',$module_code)->first();

            if(isset($Permissions)){
             if($Permissions->can_proof_read==1){
                 $GrantPermission="Yes";
                 return $GrantPermission;;
               }
            }
            else{
                $GrantPermission="No";
                return $GrantPermission;;
            }
         }
    }
    public function getAccessForwardTransProof($module_code)
    {
           if(Auth::user()){
            $Permissions = UserRolePermissions::where('role_code',Auth::user()->role_code)->where('module_code',$module_code)->first();

            if(isset($Permissions)){
             if($Permissions->can_forward_to_translate==1){
                 $GrantPermission="Yes";
                 return $GrantPermission;;
               }
            }
            else{
                $GrantPermission="No";
                return $GrantPermission;;
            }
         }
    }
    public function getAccessTransProof($module_code)
    {
           if(Auth::user()){
            $Permissions = UserRolePermissions::where('role_code',Auth::user()->role_code)->where('module_code',$module_code)->first();

            if(isset($Permissions)){
             if($Permissions->can_translate_proof==1){
                 $GrantPermission="Yes";
                 return $GrantPermission;;
               }
            }
            else{
                $GrantPermission="No";
                return $GrantPermission;;
            }
         }
    }
    public function getAccessForwardPublication($module_code)
    {
           if(Auth::user()){
            $Permissions = UserRolePermissions::where('role_code',Auth::user()->role_code)->where('module_code',$module_code)->first();

            if(isset($Permissions)){
             if($Permissions->can_close==1){
                 $GrantPermission="Yes";
                 return $GrantPermission;;
               }
            }
            else{
                $GrantPermission="No";
                return $GrantPermission;;
            }
         }
    }
    public function getAccessForwardPress($module_code)
    {
           if(Auth::user()){
            $Permissions = UserRolePermissions::where('role_code',Auth::user()->role_code)->where('module_code',$module_code)->first();

            if(isset($Permissions)){
             if($Permissions->can_press==1){
                 $GrantPermission="Yes";
                 return $GrantPermission;;
               }
            }
            else{
                $GrantPermission="No";
                return $GrantPermission;;
            }
         }
    }
    public function getAccessGazette($module_code)
    {
           if(Auth::user()){
            $Permissions = UserRolePermissions::where('role_code',Auth::user()->role_code)->where('module_code',$module_code)->first();

            if(isset($Permissions)){
             if($Permissions->can_gazzete==1){
                 $GrantPermission="Yes";
                 return $GrantPermission;;
               }
            }
            else{
                $GrantPermission="No";
                return $GrantPermission;;
            }
         }
    }
    public function getAccessCertificate($module_code)
    {
           if(Auth::user()){
            $Permissions = UserRolePermissions::where('role_code',Auth::user()->role_code)->where('module_code',$module_code)->first();

            if(isset($Permissions)){
             if($Permissions->can_certificate==1){
                 $GrantPermission="Yes";
                 return $GrantPermission;;
               }
            }
            else{
                $GrantPermission="No";
                return $GrantPermission;;
            }
         }
    }
 }
