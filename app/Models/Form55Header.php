<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
class Form55Header extends Model implements Auditable
{
    //use SoftDeletes;
    protected $table ='form_55_header';
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'province_id',
        'district_id',
        'ag_division_id',
        'gn_division_id',
        'map_no',
        'block_no',
        'lot_no',
        'village',
        'name_of_the_deceased',
        'date_of_notice',
        'date_of_last_notice',
        'regional_office',
        'regional_officer',
        'office_of_registration',
        'ref_no2',
        'prepared_by',
        'prepared_date',
        'verified_by',
        'verified_date',
        'regional_officer_approval',
        'regional_officer_approval_date',
        'regional_checked',
        'regional_checked_by',
        'regional_checked_date',
        'regional_checked',
        'regional_checked_by',
        'regional_checked_date',
        'publication_checked',
        'publication_checked_date',
        'asst_com_approval',
        'asst_com_approval_date',
        'bimsaviya_com_approval',
        'bimsaviya_com_approval_date',
        'commisioner_genaral_approval',
        'commisioner_genaral_approval_date',
        'proof_read_complete',
        'proof_read_complete_date',
        'sent_to_press',
        'sent_to_press_date',
        'gazette_number',
        'gazette_date',
        'rejected',
        'current_stage',
        'created_at',
        'updated_at',
        'computer_branch_officer',
        'computer_checked',
        'computer_checked_by',
        'computer_checked_date',
        'sinhala_amended',
        'sinhala_amended_by',
        'sinhala_amended_date',
        'gazette_without',
        'gazette_without_by',
        'gazette_without_date',
        'press_without',
        'press_without_by',
        'press_without_date',
        'computer_with',
        'computer_with_by',
        'computer_with_date',
        'gazette_with',
        'gazette_with_by',
        'gazette_with_date',
    ];
    protected $dates = ['deleted_at'];
    public $timestamps = true;
}



