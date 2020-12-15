<?php

namespace App\Services;
use Auth;
use App\Models\Modules;
use App\User;
use App\Models\Provinces;
use App\Models\Districts;
use App\Models\AgDivisions;
use App\Models\GnDivisions;
use App\Models\Form12;
use App\Models\Form14Header;
use App\Models\Form55Header;
use App\Models\AmendmentsHeader;
use App\Models\RegionalOffices;
use App\Models\ProofRead;
Use Alert;
use DataTables;
use Validator;
use Log;
use Exception;
use DB;
use Carbon\Carbon;
use Request;
use App\Traits\Permissions;
class DashboardService
{

    use Permissions;
/* Form12 Start  */
    public function getF12PendingCount(){

        $pending=0;
        $create=0;$reg_verify=0;$reg_approve=0;$pub_verify=0;$asst_comm=0;$bim_comm=0;
        $comm_gen=0;$computer=0;$proof_sinhala=0;$computer_sinhala=0;$proof_translate=0;$computer_translate=0;
        $pub_without=0;$press_without=0;
       //dd($this->getAccessCreate(request()->segment(1)));
        if($this->getAccessCreate('12th-sentence')=='Yes'){
            $create=Form12::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('current_stage','Regional officer')->where('rejected',0)->count();
        }
        if($this->getAccessRegVerify('12th-sentence')=='Yes'){
            $reg_verify=Form12::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('current_stage','Regional commissioner')->where('rejected',0)->count();
        }
        if($this->getAccessRegApprove('12th-sentence')=='Yes'){
            $reg_approve=Form12::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('current_stage','Publication verify')->where('rejected',0)->count();
        }
        if($this->getAccessPubVerify('12th-sentence')=='Yes'){
            $pub_verify=Form12::where('current_stage','Assistant commisioner')->where('rejected',0)->count();
        }
        if($this->getAccessAsstComm('12th-sentence')=='Yes'){
            $asst_comm=Form12::where('current_stage','Bimsaviya commisioner')->where('rejected',0)->count();
        }
        if($this->getAccessBimsaviyaComm('12th-sentence')=='Yes'){
            $bim_comm=Form12::where('current_stage','Commissioner general')->where('rejected',0)->count();
        }
        if($this->getAccessCommGen('12th-sentence')=='Yes'){
            $comm_gen=Form12::where('current_stage','Computer branch')->where('rejected',0)->count();
        }
        if($this->getAccessForwardProof('12th-sentence')=='Yes'){
            $computer=Form12::where('current_stage','Proof read(Sinhala)')->where('rejected',0)->count();
        }
        if($this->getAccessProof('12th-sentence')=='Yes'){
            $proof_sinhala=Form12::where('current_stage','Proof read(Sinhala)-Computer')->where('rejected',0)->count();
        }
        if($this->getAccessForwardTransProof('12th-sentence')=='Yes'){
            $computer_sinhala=Form12::where('current_stage','Proof read(Translates)')->where('rejected',0)->count();
        }
        if($this->getAccessTransProof('12th-sentence')=='Yes'){
            $proof_translate=Form12::where('current_stage','Proof read(Translation)-Computer')->where('rejected',0)->count();
        }
        if($this->getAccessForwardPublication('12th-sentence')=='Yes'){
            $computer_translate=Form12::where('current_stage','Proof read complete')->orWhere('current_stage','Publication without G')
                ->orWhere('current_stage','Publication with G')->where('rejected',0)->count();
        }
        if($this->getAccessForwardPress('12th-sentence')=='Yes'){
            $pub_without=Form12::where('current_stage','Gov Press without G')->orWhere('current_stage','Gov press with G')->where('rejected',0)->count();
        }
        if($this->getAccessGazette('12th-sentence')=='Yes'){
            $press_without=Form12::where('current_stage','Gazette with G')->where('rejected',0)->count();
        }

        $pending=$create+$reg_verify+$reg_approve+$pub_verify+$asst_comm+$bim_comm+$comm_gen+$computer+$proof_sinhala+$computer_sinhala+$proof_translate+$computer_translate+$pub_without+$press_without;
        return  $pending;
    }

    public function getF12CurrentCount(){
        $current=0;
        if($this->getAccessCreate('12th-sentence')=='Yes' || $this->getAccessRegVerify('12th-sentence')=='Yes' || $this->getAccessRegApprove('12th-sentence')=='Yes'){
            $current=Form12::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('current_stage','<>','Online Publish')->where('rejected',0)->count();
        }else{
            $current=Form12::where('current_stage','<>','Online Publish')->orWhere('current_stage','<>','Regional commissioner')->orWhere('current_stage','<>','Regional officer')->orWhere('current_stage','<>','Regional data entry')->where('rejected',0)->count();
        }
        return  $current;
    }

    public function getF12NewCount(){
        $new=0;
        $create=0;$reg_verify=0;$reg_approve=0;$pub_verify=0;$asst_comm=0;$bim_comm=0;
        $comm_gen=0;$computer=0;$proof_sinhala=0;$computer_sinhala=0;$proof_translate=0;$computer_translate=0;
        $pub_without=0;$press_without=0;
        if($this->getAccessCreate('12th-sentence')=='Yes'){
            $create=Form12::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('current_stage','Regional data entry')->where('rejected',0)->count();
        }
        if($this->getAccessRegVerify('12th-sentence')=='Yes'){
            $reg_verify=Form12::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('current_stage','Regional officer')->where('rejected',0)->count();
        }
        if($this->getAccessRegApprove('12th-sentence')=='Yes'){
            $reg_approve=Form12::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('current_stage','Regional commissioner')->where('rejected',0)->count();
        }
        if($this->getAccessPubVerify('12th-sentence')=='Yes'){
            $pub_verify=Form12::where('current_stage','Publication verify')->where('rejected',0)->count();
        }
        if($this->getAccessAsstComm('12th-sentence')=='Yes'){
            $asst_comm=Form12::where('current_stage','Assistant commisioner')->where('rejected',0)->count();
        }
        if($this->getAccessBimsaviyaComm('12th-sentence')=='Yes'){
            $bim_comm=Form12::where('current_stage','Bimsaviya commisioner')->where('rejected',0)->count();
        }
        if($this->getAccessCommGen('12th-sentence')=='Yes'){
            $comm_gen=Form12::where('current_stage','Commissioner general')->where('rejected',0)->count();
        }
        if($this->getAccessForwardProof('12th-sentence')=='Yes'){
            $computer=Form12::where('current_stage','Computer branch')->orWhere('current_stage','Proof read(Sinhala)-Computer')->where('rejected',0)->count();
        }
        if($this->getAccessProof('12th-sentence')=='Yes'){
            $proof_sinhala=Form12::where('current_stage','Proof read(Sinhala)')->where('rejected',0)->count();
        }
        if($this->getAccessForwardTransProof('12th-sentence')=='Yes'){
            $computer_sinhala=Form12::where('current_stage','Proof read(Sinhala)-Computer')->orWhere('current_stage','Proof read(Translation)-Computer')->where('rejected',0)->count();
        }
        if($this->getAccessTransProof('12th-sentence')=='Yes'){
            $proof_translate=Form12::where('current_stage','Proof read(Translates)')->where('rejected',0)->count();
        }
        if($this->getAccessForwardPublication('12th-sentence')=='Yes'){
            $computer_translate=Form12::where('current_stage','Proof read complete')->orWhere('current_stage','Gazette with G')->where('rejected',0)->count();
        }
        if($this->getAccessForwardPress('12th-sentence')=='Yes'){
            $pub_without=Form12::where('current_stage','Publication without G')->orWhere('current_stage','Publication with G')->where('rejected',0)->count();
        }
        if($this->getAccessGazette('12th-sentence')=='Yes'){
            $press_without=Form12::where('current_stage','Gov Press without G')->where('rejected',0)->count();
        }
        $new=$create+$reg_verify+$reg_approve+$pub_verify+$asst_comm+$bim_comm+$comm_gen+$computer+$proof_sinhala+$computer_sinhala+$proof_translate+$computer_translate+$pub_without+$press_without;

        return  $new;


    }
    public function getF12RejectedCount(){
        $rejected=0;

    if($this->getAccessCreate('12th-sentence')=='Yes' || $this->getAccessRegVerify('12th-sentence')=='Yes' || $this->getAccessRegApprove('12th-sentence')=='Yes'){
        $rejected=Form12::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                ->where('rejected',1)->count();
    }
    else{
        $rejected=Form12::where('rejected',1)->count();
    }
        return  $rejected;
    }
    public function get12gazetted(){
        $gazetted=0;
        if($this->getAccessCreate('12th-sentence')=='Yes' || $this->getAccessRegVerify('12th-sentence')=='Yes' || $this->getAccessRegApprove('12th-sentence')=='Yes'){
            $gazetted=Form12::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('current_stage','Online Publish')->where('rejected',0)->count();
        }else{
            $gazetted=Form12::where('current_stage','Online Publish')->where('rejected',0)->count();
        }
        return $gazetted;
    }
    public function get55gazetted(){
        $gazetted=0;
        if($this->getAccessCreate('55th-sentence')=='Yes' || $this->getAccessRegVerify('55th-sentence')=='Yes' || $this->getAccessRegApprove('55th-sentence')=='Yes'){
            $gazetted=Form55Header::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                ->where('current_stage','Online Publish')->where('rejected',0)->count();
        }else{
            $gazetted=Form55Header::where('current_stage','Online Publish')->where('rejected',0)->count();
        }
        return $gazetted;
    }
    public function get14gazetted(){
        $gazetted=0;
        if($this->getAccessCreate('14th-sentence')=='Yes' || $this->getAccessRegVerify('14th-sentence')=='Yes' || $this->getAccessRegApprove('14th-sentence')=='Yes'){
            $gazetted=Form14Header::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('current_stage','Online Publish')->orWhere('current_stage','Certificate issued')->where('rejected',0)->count();
        }else{
            $gazetted=Form14Header::where('current_stage','Online Publish')->orWhere('current_stage','Certificate issued')->where('rejected',0)->count();
        }
        return $gazetted;
    }
    public function getAmdgazetted(){
        $gazetted=0;
        if($this->getAccessCreate('amendments')=='Yes' || $this->getAccessRegVerify('amendments')=='Yes' || $this->getAccessRegApprove('amendments')=='Yes'){
            $gazetted=AmendmentsHeader::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                ->where('current_stage','Online Publish')->where('rejected',0)->count();
        }else{
            $gazetted=AmendmentsHeader::where('current_stage','Online Publish')->where('rejected',0)->count();
        }
        return $gazetted;
    }

/* Form12 END  */

/* Form14 Start  */

    public function getF14CurrentCount(){
        $current=0;
        if($this->getAccessCreate('14th-sentence')=='Yes' || $this->getAccessRegVerify('14th-sentence')=='Yes' || $this->getAccessRegApprove('14th-sentence')=='Yes'){
            $current=Form14Header::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('current_stage','<>','Certificate issued')->orWhere('current_stage',null)->where('rejected',0)->count();
        }else{
            $current=Form14Header::where('current_stage','<>','Certificate issued')->orWhere('current_stage','<>','Regional commissioner')->orWhere('current_stage','<>','Regional officer')->orWhere('current_stage','<>','Regional data entry')->where('rejected',0)->count();
        }
        return  $current;
    }

    public function getF14PendingCount(){
        $pending=0;
            $create=0;$reg_verify=0;$reg_approve=0;$pub_verify=0;$asst_comm=0;$bim_comm=0;
            $comm_gen=0;$computer=0;$proof_sinhala=0;$computer_sinhala=0;$proof_translate=0;$computer_translate=0;
            $pub_without=0;$press_without=0;
            if($this->getAccessCreate('14th-sentence')=='Yes'){
                $create=Form14Header::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                        ->where('current_stage','Regional officer')->where('rejected',0)->count();
            }
            if($this->getAccessRegVerify('14th-sentence')=='Yes'){
                $reg_verify=Form14Header::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                        ->where('current_stage','Regional commissioner')->where('rejected',0)->count();
            }
            if($this->getAccessRegApprove('14th-sentence')=='Yes'){
                $reg_approve=Form14Header::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                        ->where('current_stage','Publication verify')->where('rejected',0)->count();
            }
            if($this->getAccessPubVerify('14th-sentence')=='Yes'){
                $pub_verify=Form14Header::where('current_stage','Assistant commisioner')->where('rejected',0)->count();
            }
            if($this->getAccessAsstComm('14th-sentence')=='Yes'){
                $asst_comm=Form14Header::where('current_stage','Bimsaviya commisioner')->where('rejected',0)->count();
            }
            if($this->getAccessBimsaviyaComm('14th-sentence')=='Yes'){
                $bim_comm=Form14Header::where('current_stage','Commissioner general')->where('rejected',0)->count();
            }
            if($this->getAccessCommGen('14th-sentence')=='Yes'){
                $comm_gen=Form14Header::where('current_stage','Computer branch')->where('rejected',0)->count();
            }
            if($this->getAccessForwardProof('14th-sentence')=='Yes'){
                $computer=Form14Header::where('current_stage','Proof read(Sinhala)')->where('rejected',0)->count();
            }
            if($this->getAccessProof('14th-sentence')=='Yes'){
                $proof_sinhala=Form14Header::where('current_stage','Proof read(Sinhala)-Computer')->where('rejected',0)->count();
            }
            if($this->getAccessForwardTransProof('14th-sentence')=='Yes'){
                $computer_sinhala=Form14Header::where('current_stage','Proof read(Translates)')->where('rejected',0)->count();
            }
            if($this->getAccessTransProof('14th-sentence')=='Yes'){
                $proof_translate=Form14Header::where('current_stage','Proof read(Translation)-Computer')->where('rejected',0)->count();
            }
            if($this->getAccessForwardPublication('14th-sentence')=='Yes'){
                $computer_translate=Form14Header::where('current_stage','Proof read complete')->orWhere('current_stage','Publication without G')
                    ->orWhere('current_stage','Publication with G')->where('rejected',0)->count();
            }
            if($this->getAccessForwardPress('14th-sentence')=='Yes'){
                $pub_without=Form14Header::where('current_stage','Gov Press without G')->orWhere('current_stage','Gov press with G')->where('rejected',0)->count();
            }
            if($this->getAccessGazette('14th-sentence')=='Yes'){
                $press_without=Form14Header::where('current_stage','Gazette with G')->where('rejected',0)->count();
            }

            $pending=$create+$reg_verify+$reg_approve+$pub_verify+$asst_comm+$bim_comm+$comm_gen+$computer+$proof_sinhala+$computer_sinhala+$proof_translate+$computer_translate+$pub_without+$press_without;

        return  $pending;


    }


    public function getF14NewCount(){
        $new=0;
        $create=0;$reg_verify=0;$reg_approve=0;$pub_verify=0;$asst_comm=0;$bim_comm=0;
        $comm_gen=0;$computer=0;$proof_sinhala=0;$computer_sinhala=0;$proof_translate=0;$computer_translate=0;
        $pub_without=0;$press_without=0;
        if($this->getAccessCreate('14th-sentence')=='Yes'){
            $create=Form14Header::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('current_stage','Regional data entry')->where('rejected',0)->count();
        }
        if($this->getAccessRegVerify('14th-sentence')=='Yes'){
            $reg_verify=Form14Header::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('current_stage','Regional officer')->where('rejected',0)->count();
        }
        if($this->getAccessRegApprove('14th-sentence')=='Yes'){
            $reg_approve=Form14Header::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('current_stage','Regional commissioner')->where('rejected',0)->count();
        }
        if($this->getAccessPubVerify('14th-sentence')=='Yes'){
            $pub_verify=Form14Header::where('current_stage','Publication verify')->where('rejected',0)->count();
        }
        if($this->getAccessAsstComm('14th-sentence')=='Yes'){
            $asst_comm=Form14Header::where('current_stage','Assistant commisioner')->where('rejected',0)->count();
        }
        if($this->getAccessBimsaviyaComm('14th-sentence')=='Yes'){
            $bim_comm=Form14Header::where('current_stage','Bimsaviya commisioner')->where('rejected',0)->count();
        }
        if($this->getAccessCommGen('14th-sentence')=='Yes'){
            $comm_gen=Form14Header::where('current_stage','Commissioner general')->where('rejected',0)->count();
        }
        if($this->getAccessForwardProof('14th-sentence')=='Yes'){
            $computer=Form14Header::where('current_stage','Computer branch')->orWhere('current_stage','Proof read(Sinhala)-Computer')->where('rejected',0)->count();
        }
        if($this->getAccessProof('14th-sentence')=='Yes'){
            $proof_sinhala=Form14Header::where('current_stage','Proof read(Sinhala)')->where('rejected',0)->count();
        }
        if($this->getAccessForwardTransProof('14th-sentence')=='Yes'){
            $computer_sinhala=Form14Header::where('current_stage','Proof read(Sinhala)-Computer')->orWhere('current_stage','Proof read(Translation)-Computer')->where('rejected',0)->count();
        }
        if($this->getAccessTransProof('14th-sentence')=='Yes'){
            $proof_translate=Form14Header::where('current_stage','Proof read(Translates)')->where('rejected',0)->count();
        }
        if($this->getAccessForwardPublication('14th-sentence')=='Yes'){
            $computer_translate=Form14Header::where('current_stage','Proof read complete')->orWhere('current_stage','Gazette with G')->where('rejected',0)->count();
        }
        if($this->getAccessForwardPress('14th-sentence')=='Yes'){
            $pub_without=Form14Header::where('current_stage','Publication without G')->orWhere('current_stage','Publication with G')->where('rejected',0)->count();
        }
        if($this->getAccessGazette('14th-sentence')=='Yes'){
            $press_without=Form14Header::where('current_stage','Gov Press without G')->where('rejected',0)->count();
        }
        $new=$create+$reg_verify+$reg_approve+$pub_verify+$asst_comm+$bim_comm+$comm_gen+$computer+$proof_sinhala+$computer_sinhala+$proof_translate+$computer_translate+$pub_without+$press_without;

        return  $new;


    }

    public function getF14RejectedCount(){
        $rejected=0;

        if($this->getAccessCreate('14th-sentence')=='Yes' || $this->getAccessRegVerify('14th-sentence')=='Yes' || $this->getAccessRegApprove('14th-sentence')=='Yes'){
            $rejected=Form14Header::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('rejected',1)->count();
        }
        else{
            $rejected=Form14Header::where('rejected',1)->count();
        }
        return  $rejected;
    }


/* Form14 END  */

/* Form55 Start  */
    public function getF55NewCount(){
        $new=0;
        $create=0;$reg_verify=0;$reg_approve=0;$pub_verify=0;$asst_comm=0;$bim_comm=0;
        $comm_gen=0;$computer=0;$proof_sinhala=0;$computer_sinhala=0;$proof_translate=0;$computer_translate=0;
        $pub_without=0;$press_without=0;
        if($this->getAccessCreate('55th-sentence')=='Yes'){
            $create=Form55Header::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                ->where('current_stage','Regional data entry')->where('rejected',0)->count();
        }
    if($this->getAccessRegVerify('55th-sentence')=='Yes'){
        $reg_verify=Form55Header::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
            ->where('current_stage','Regional officer')->where('rejected',0)->count();
    }
    if($this->getAccessRegApprove('55th-sentence')=='Yes'){
        $reg_approve=Form55Header::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
            ->where('current_stage','Regional commissioner')->where('rejected',0)->count();
    }
    if($this->getAccessPubVerify('55th-sentence')=='Yes'){
        $pub_verify=Form55Header::where('current_stage','Publication verify')->where('rejected',0)->count();
    }
    if($this->getAccessAsstComm('55th-sentence')=='Yes'){
        $asst_comm=Form55Header::where('current_stage','Assistant commisioner')->where('rejected',0)->count();
    }
    if($this->getAccessBimsaviyaComm('55th-sentence')=='Yes'){
        $bim_comm=Form55Header::where('current_stage','Bimsaviya commisioner')->where('rejected',0)->count();
    }
    if($this->getAccessCommGen('55th-sentence')=='Yes'){
        $comm_gen=Form55Header::where('current_stage','Commissioner general')->where('rejected',0)->count();
    }
    if($this->getAccessForwardProof('55th-sentence')=='Yes'){
        $computer=Form55Header::where('current_stage','Computer branch')->orWhere('current_stage','Proof read(Sinhala)-Computer')->where('rejected',0)->count();
    }
    if($this->getAccessProof('55th-sentence')=='Yes'){
        $proof_sinhala=Form55Header::where('current_stage','Proof read(Sinhala)')->where('rejected',0)->count();
    }
    if($this->getAccessForwardTransProof('55th-sentence')=='Yes'){
        $computer_sinhala=Form55Header::where('current_stage','Proof read(Sinhala)-Computer')->orWhere('current_stage','Proof read(Translation)-Computer')->where('rejected',0)->count();
    }
    if($this->getAccessTransProof('55th-sentence')=='Yes'){
        $proof_translate=Form55Header::where('current_stage','Proof read(Translates)')->where('rejected',0)->count();
    }
    if($this->getAccessForwardPublication('55th-sentence')=='Yes'){
        $computer_translate=Form55Header::where('current_stage','Proof read complete')->orWhere('current_stage','Gazette with G')->where('rejected',0)->count();
    }
    if($this->getAccessForwardPress('55th-sentence')=='Yes'){
        $pub_without=Form55Header::where('current_stage','Publication without G')->orWhere('current_stage','Publication with G')->where('rejected',0)->count();
    }
    if($this->getAccessGazette('55th-sentence')=='Yes'){
        $press_without=Form55Header::where('current_stage','Gov Press without G')->where('rejected',0)->count();
    }
    $new=$create+$reg_verify+$reg_approve+$pub_verify+$asst_comm+$bim_comm+$comm_gen+$computer+$proof_sinhala+$computer_sinhala+$proof_translate+$computer_translate+$pub_without+$press_without;

        return  $new;
    }


    public function getF55CurrentCount(){
        $current=0;
        if($this->getAccessCreate('55th-sentence')=='Yes' || $this->getAccessRegVerify('55th-sentence')=='Yes' || $this->getAccessRegApprove('55th-sentence')=='Yes'){
            $current=Form55Header::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                ->where('current_stage','<>','Online Publish')->orWhere('current_stage',null)->where('rejected',0)->count();
        }else{
            $current=Form55Header::where('current_stage','<>','Online Publish')->orWhere('current_stage','<>','Regional commissioner')->orWhere('current_stage','<>','Regional officer')->orWhere('current_stage','<>','Regional data entry')->where('rejected',0)->count();
        }
        return  $current;
    }

    public function getF55PendingCount(){
        $pending=0;
            $create=0;$reg_verify=0;$reg_approve=0;$pub_verify=0;$asst_comm=0;$bim_comm=0;
            $comm_gen=0;$computer=0;$proof_sinhala=0;$computer_sinhala=0;$proof_translate=0;$computer_translate=0;
            $pub_without=0;$press_without=0;


            if($this->getAccessCreate('55th-sentence')=='Yes'){
                $create=Form55Header::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('current_stage','Regional officer')->where('rejected',0)->count();
            }
            if($this->getAccessRegVerify('55th-sentence')=='Yes'){
                $reg_verify=Form55Header::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('current_stage','Regional commissioner')->where('rejected',0)->count();
            }
            if($this->getAccessRegApprove('55th-sentence')=='Yes'){
                $reg_approve=Form55Header::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                    ->where('current_stage','Publication verify')->where('rejected',0)->count();
            }
            if($this->getAccessPubVerify('55th-sentence')=='Yes'){
                $pub_verify=Form55Header::where('current_stage','Assistant commisioner')->where('rejected',0)->count();
            }
            if($this->getAccessAsstComm('55th-sentence')=='Yes'){
                $asst_comm=Form55Header::where('current_stage','Bimsaviya commisioner')->where('rejected',0)->count();
            }
            if($this->getAccessBimsaviyaComm('55th-sentence')=='Yes'){
                $bim_comm=Form55Header::where('current_stage','Commissioner general')->where('rejected',0)->count();
            }
            if($this->getAccessCommGen('55th-sentence')=='Yes'){
                $comm_gen=Form55Header::where('current_stage','Computer branch')->where('rejected',0)->count();
            }
            if($this->getAccessForwardProof('55th-sentence')=='Yes'){
                $computer=Form55Header::where('current_stage','Proof read(Sinhala)')->where('rejected',0)->count();
            }
            if($this->getAccessProof('55th-sentence')=='Yes'){
                $proof_sinhala=Form55Header::where('current_stage','Proof read(Sinhala)-Computer')->where('rejected',0)->count();
            }
            if($this->getAccessForwardTransProof('55th-sentence')=='Yes'){
                $computer_sinhala=Form55Header::where('current_stage','Proof read(Translates)')->where('rejected',0)->count();
            }
            if($this->getAccessTransProof('55th-sentence')=='Yes'){
                $proof_translate=Form55Header::where('current_stage','Proof read(Translation)-Computer')->where('rejected',0)->count();
            }
            if($this->getAccessForwardPublication('55th-sentence')=='Yes'){
                $computer_translate=Form55Header::where('current_stage','Proof read complete')->orWhere('current_stage','Publication without G')
                    ->orWhere('current_stage','Publication with G')->where('rejected',0)->count();
            }
            if($this->getAccessForwardPress('55th-sentence')=='Yes'){
                $pub_without=Form55Header::where('current_stage','Gov Press without G')->orWhere('current_stage','Gov press with G')->where('rejected',0)->count();
            }
            if($this->getAccessGazette('55th-sentence')=='Yes'){
                $press_without=Form55Header::where('current_stage','Gazette with G')->where('rejected',0)->count();
            }

            $pending=$create+$reg_verify+$reg_approve+$pub_verify+$asst_comm+$bim_comm+$comm_gen+$computer+$proof_sinhala+$computer_sinhala+$proof_translate+$computer_translate+$pub_without+$press_without;

        return  $pending;


    }

    public function getF55RejectedCount(){
        $rejected=0;

        if($this->getAccessCreate('55th-sentence')=='Yes' || $this->getAccessRegVerify('55th-sentence')=='Yes' || $this->getAccessRegApprove('55th-sentence')=='Yes'){
            $rejected=Form55Header::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                ->where('rejected',1)->count();
        }
        else{
            $rejected=Form55Header::where('rejected',1)->count();
        }
        return  $rejected;
    }


/* Form55 END  */


/* Amendement Start  */

public function getAmdNewCount(){
    $new=0;
    $create=0;$reg_verify=0;$reg_approve=0;$pub_verify=0;$asst_comm=0;$bim_comm=0;
    $comm_gen=0;$computer=0;$proof_sinhala=0;$computer_sinhala=0;$proof_translate=0;$computer_translate=0;
    $pub_without=0;$press_without=0;
    if($this->getAccessCreate('amendments')=='Yes'){
        $create=AmendmentsHeader::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
            ->where('current_stage','Regional data entry')->where('rejected',0)->count();
    }
    if($this->getAccessRegVerify('amendments')=='Yes'){
        $reg_verify=AmendmentsHeader::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
            ->where('current_stage','Regional officer')->where('rejected',0)->count();
    }
    if($this->getAccessRegApprove('amendments')=='Yes'){
        $reg_approve=AmendmentsHeader::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
            ->where('current_stage','Regional commissioner')->where('rejected',0)->count();
    }
    if($this->getAccessPubVerify('amendments')=='Yes'){
        $pub_verify=AmendmentsHeader::where('current_stage','Publication verify')->where('rejected',0)->count();
    }
    if($this->getAccessAsstComm('amendments')=='Yes'){
        $asst_comm=AmendmentsHeader::where('current_stage','Assistant commisioner')->where('rejected',0)->count();
    }
    if($this->getAccessBimsaviyaComm('amendments')=='Yes'){
        $bim_comm=AmendmentsHeader::where('current_stage','Bimsaviya commisioner')->where('rejected',0)->count();
    }
    if($this->getAccessCommGen('amendments')=='Yes'){
        $comm_gen=AmendmentsHeader::where('current_stage','Commissioner general')->where('rejected',0)->count();
    }
    if($this->getAccessForwardProof('amendments')=='Yes'){
        $computer=AmendmentsHeader::where('current_stage','Computer branch')->orWhere('current_stage','Proof read(Sinhala)-Computer')->where('rejected',0)->count();
    }
    if($this->getAccessProof('amendments')=='Yes'){
        $proof_sinhala=AmendmentsHeader::where('current_stage','Proof read(Sinhala)')->where('rejected',0)->count();
    }
    if($this->getAccessForwardTransProof('amendments')=='Yes'){
        $computer_sinhala=AmendmentsHeader::where('current_stage','Proof read(Sinhala)-Computer')->orWhere('current_stage','Proof read(Translation)-Computer')->where('rejected',0)->count();
    }
    if($this->getAccessTransProof('amendments')=='Yes'){
        $proof_translate=AmendmentsHeader::where('current_stage','Proof read(Translates)')->where('rejected',0)->count();
    }
    if($this->getAccessForwardPublication('amendments')=='Yes'){
        $computer_translate=AmendmentsHeader::where('current_stage','Proof read complete')->orWhere('current_stage','Gazette with G')->where('rejected',0)->count();
    }
    if($this->getAccessForwardPress('amendments')=='Yes'){
        $pub_without=AmendmentsHeader::where('current_stage','Publication without G')->orWhere('current_stage','Publication with G')->where('rejected',0)->count();
    }
    if($this->getAccessGazette('amendments')=='Yes'){
        $press_without=AmendmentsHeader::where('current_stage','Gov Press without G')->where('rejected',0)->count();
    }
    $new=$create+$reg_verify+$reg_approve+$pub_verify+$asst_comm+$bim_comm+$comm_gen+$computer+$proof_sinhala+$computer_sinhala+$proof_translate+$computer_translate+$pub_without+$press_without;

    return  $new;
}


public function getAmdCurrentCount(){
    $current=0;
    if($this->getAccessCreate('amendments')=='Yes' || $this->getAccessRegVerify('amendments')=='Yes' || $this->getAccessRegApprove('amendments')=='Yes'){
        $current=AmendmentsHeader::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
            ->where('current_stage','<>','Online Publish')->where('rejected',0)->count();
    }else{
        $current=AmendmentsHeader::where('current_stage','<>','Online Publish')->orWhere('current_stage','<>','Regional commissioner')->orWhere('current_stage','<>','Regional officer')->orWhere('current_stage','<>','Regional data entry')->where('rejected',0)->count();
    }
    return  $current;
}

public function getAmdPendingCount(){
    $pending=0;
        $create=0;$reg_verify=0;$reg_approve=0;$pub_verify=0;$asst_comm=0;$bim_comm=0;
        $comm_gen=0;$computer=0;$proof_sinhala=0;$computer_sinhala=0;$proof_translate=0;$computer_translate=0;
        $pub_without=0;$press_without=0;


        if($this->getAccessCreate('amendments')=='Yes'){
            $create=AmendmentsHeader::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                ->where('current_stage','Regional officer')->where('rejected',0)->count();
        }
        if($this->getAccessRegVerify('amendments')=='Yes'){
            $reg_verify=AmendmentsHeader::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                ->where('current_stage','Regional commissioner')->where('rejected',0)->count();
        }
        if($this->getAccessRegApprove('amendments')=='Yes'){
            $reg_approve=AmendmentsHeader::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
                ->where('current_stage','Publication verify')->where('rejected',0)->count();
        }
        if($this->getAccessPubVerify('amendments')=='Yes'){
            $pub_verify=AmendmentsHeader::where('current_stage','Assistant commisioner')->where('rejected',0)->count();
        }
        if($this->getAccessAsstComm('amendments')=='Yes'){
            $asst_comm=AmendmentsHeader::where('current_stage','Bimsaviya commisioner')->where('rejected',0)->count();
        }
        if($this->getAccessBimsaviyaComm('amendments')=='Yes'){
            $bim_comm=AmendmentsHeader::where('current_stage','Commissioner general')->where('rejected',0)->count();
        }
        if($this->getAccessCommGen('amendments')=='Yes'){
            $comm_gen=AmendmentsHeader::where('current_stage','Computer branch')->where('rejected',0)->count();
        }
        if($this->getAccessForwardProof('amendments')=='Yes'){
            $computer=AmendmentsHeader::where('current_stage','Proof read(Sinhala)')->where('rejected',0)->count();
        }
        if($this->getAccessProof('amendments')=='Yes'){
            $proof_sinhala=AmendmentsHeader::where('current_stage','Proof read(Sinhala)-Computer')->where('rejected',0)->count();
        }
        if($this->getAccessForwardTransProof('amendments')=='Yes'){
            $computer_sinhala=AmendmentsHeader::where('current_stage','Proof read(Translates)')->where('rejected',0)->count();
        }
        if($this->getAccessTransProof('amendments')=='Yes'){
            $proof_translate=AmendmentsHeader::where('current_stage','Proof read(Translation)-Computer')->where('rejected',0)->count();
        }
        if($this->getAccessForwardPublication('amendments')=='Yes'){
            $computer_translate=AmendmentsHeader::where('current_stage','Proof read complete')->orWhere('current_stage','Publication without G')
                ->orWhere('current_stage','Publication with G')->where('rejected',0)->count();
        }
        if($this->getAccessForwardPress('amendments')=='Yes'){
            $pub_without=AmendmentsHeader::where('current_stage','Gov Press without G')->orWhere('current_stage','Gov press with G')->where('rejected',0)->count();
        }
        if($this->getAccessGazette('amendments')=='Yes'){
            $press_without=AmendmentsHeader::where('current_stage','Gazette with G')->where('rejected',0)->count();
        }

        $pending=$create+$reg_verify+$reg_approve+$pub_verify+$asst_comm+$bim_comm+$comm_gen+$computer+$proof_sinhala+$computer_sinhala+$proof_translate+$computer_translate+$pub_without+$press_without;

    return  $pending;


}

public function getAmdRejectedCount(){
    $rejected=0;

    if($this->getAccessCreate('amendments')=='Yes' || $this->getAccessRegVerify('amendments')=='Yes' || $this->getAccessRegApprove('amendments')=='Yes'){
        $rejected=AmendmentsHeader::whereIn('prepared_by',User::where('branch_id',Auth::user()->branch_id)->select('id')->where('is_active',1)->get())
            ->where('rejected',1)->count();
    }
    else{
        $rejected=AmendmentsHeader::where('rejected',1)->count();
    }
    return  $rejected;
}


/* Amendement END  */

public function getPre($stageCount,$totalCount){

    if($totalCount!=0){
        $pre = round(($stageCount/$totalCount)*100);
    }else{
        $pre =0;
    }
    return  $pre;
}


}
