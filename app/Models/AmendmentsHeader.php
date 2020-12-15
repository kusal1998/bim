<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
class AmendmentsHeader extends Model implements Auditable
{
    //use SoftDeletes;
    protected $table ='amendments_header';
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'province_id',
        'district_id',
        'ag_division_id',
        'gn_division_id',
        'village',
        'map_no',
        'block_no',
        'lot_no',
        '_14_gazzert',
        '_14_gazette_date',
        'column_name',
        'column_value_to_be_changed',
        'column_new_value',
        'reasons',
        'complain_date',
        'gazette_no',
        'gazette_date',
        'regional_office_approval',
        'prepared_date',
        'prepared_by',
        'regional_officer',
        'regional_office_approved_date',
        'publication_verify',
        'publication_verify_by',
        'commissioner_general_apprival',
        'commissioner_general_apprival_date',
        'document_no',
        'proof_read_complete_by',
        'proof_read_complete_date',
        'sent_to_gov_press',
        'rejected',
        'current_stage',
        'created_at',
        'updated_at',
        'publication_verify_date',
        'asst_com_approval',
        'asst_com_approval_date',
        'bimsaviya_com_approval',
        'bimsaviya_com_approval_date',
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
        'sent_to_gov_press_date',
        'ref_no'
    ];
    protected $dates = ['deleted_at'];
    public $timestamps = true;
}



