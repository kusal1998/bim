<?php

namespace App\Services;

use App\Models\AgDivisions;
use Auth;
use App\Models\Branches;
use App\Models\Services;
use App\Models\Counters;
use App\Models\Countries;
use App\Models\Currencies;
use App\Models\Districts;
use App\Models\UserRolePermissions;
use App\Models\UserRoles;
use App\Models\Modules;
use App\Models\Provinces;
use App\User;


use Request;

class UtilityService
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function getBranchName($id)
    {
        $Element= Branches::find($id);
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


    public function getServicesName($id)
    {
        $Element= Services::find($id);
        if(isset($Element)){
            return $Element->name;
        }
    }

   // Services::where('id',$CounterService->service_id)->where('is_active',1)->get();

    public function getRoleName($id)
    {
        $Element= UserRoles::where('code',$id)->first();
        if(isset($Element)){
            return $Element->name;
        }
    }

    public function getUserName($id)
    {
        $Element= User::find($id);


        if(isset($Element)){
            $name = $Element->name.' '.$Element->last_name;
            return $name;
        }
    }


    public function getCountBranches()
    {
        $Element= Branches::count();
        return $Element;
    }



    public function getCountCurrencies()
    {
        $Element= Currencies::count();
        return $Element;
    }

    public function getCountCountries()
    {
        $Element= Countries::count();
        return $Element;
    }

    public function getAccess($role_code,$module_code)
    {
        $Element= UserRolePermissions::where('role_code',$role_code)->where('module_code',$module_code)->first();
        return $Element;
    }

    public function getPermissionsCount($md_group)
    {
        $Element = UserRolePermissions::where('role_code',Auth::user()->role_code)->where('md_group',$md_group)->where('is_enable',1)->count();
        return $Element;
    }

    public function getAccessByRole()
    {
        $Element= UserRolePermissions::where('role_code',Auth::user()->role_code)->where('is_enable',1)->get();
        return $Element;
    }

    public function getAccessModule($module_code,$md_group)
    {
        $Element =  Modules::where('hide_menu',0)->where('md_code',$module_code)->where('md_group',$md_group)->first();
        return $Element;
    }
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }




    public function getAccessCreate($module_code)
    {
           if(Auth::user()){
            $module_code = Request::segment(1);
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
            $module_code = Request::segment(1);
            $Permissions = UserRolePermissions::where('role_code',Auth::user()->role_code)->where('module_code',$module_code)->first();

            if(isset($Permissions)){
             if($Permissions->can_read==1){
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


    public function getAccessUpdate($module_code)
    {
           if(Auth::user()){
            $module_code = Request::segment(1);
            $Permissions = UserRolePermissions::where('role_code',Auth::user()->role_code)->where('module_code',$module_code)->first();

            if(isset($Permissions)){
             if($Permissions->can_update==1){
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



    public function getAccessDelete($module_code)
    {
           if(Auth::user()){
            $module_code = Request::segment(1);
            $Permissions = UserRolePermissions::where('role_code',Auth::user()->role_code)->where('module_code',$module_code)->first();

            if(isset($Permissions)){
             if($Permissions->can_delete==1){
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

    public function getAccessRegVerify($module_code)
    {
           if(Auth::user()){
            $module_code = Request::segment(1);
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
            $module_code = Request::segment(1);
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
            $module_code = Request::segment(1);
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
            $module_code = Request::segment(1);
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
            $module_code = Request::segment(1);
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
            $module_code = Request::segment(1);
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
            $module_code = Request::segment(1);
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
            $module_code = Request::segment(1);
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
            $module_code = Request::segment(1);
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
            $module_code = Request::segment(1);
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
            $module_code = Request::segment(1);
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
            $module_code = Request::segment(1);
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
            $module_code = Request::segment(1);
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
            $module_code = Request::segment(1);
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
            $module_code = Request::segment(1);
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
            $module_code = Request::segment(1);
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
    public function getDistrictByUser(){
        if(Auth::user()){
            $ag=AgDivisions::where('id',Auth::user()->branch_id)->select('district_id')->first();
            $district=Districts::find($ag->district_id);
            return $district;
        }else{
            return null;
        }
    }
    public function getProvinceByUser(){
        if(Auth::user()){
            $ag=AgDivisions::where('id',Auth::user()->branch_id)->select('district_id')->first();
            $district=Districts::where('id',$ag->district_id)->select('province_id')->first();
            $province=Provinces::find($district->province_id);
            return $province;
        }else{
            return null;
        }
    }
    public function getAgDivByUser(){
        if(Auth::user()){
            $ag=AgDivisions::find(Auth::user()->branch_id);
            return $ag;
        }else{
            return null;
        }
    }
}
