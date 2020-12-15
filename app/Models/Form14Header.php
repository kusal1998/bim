<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
class Form14Header extends Model implements Auditable
{
    //use SoftDeletes;
    protected $table = 'form_14_header';
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'province_id',
        'district_id',
        'ag_division_id',
        'map_no',
        'block_no',
        'gn_division_id',
        'village_name',
        'governments_lands',
        'private_lands',
        'total_lands',
        'file_no',
        'ref_no',
        'publication_branch',
        'publication_branch_date',
        'computer_branch',
        'computer_branch_date',
        'prepared_by',
        'prepared_date',
        'regional_officer',
        'regional_approved',
        'regional_checked',
        'regional_approved_by',
        'regional_approved_date',
        'regional_checked_by',
        'regional_checked_date',
        'publication_checked',
        'publication_checked_by',
        'publication_checked_date',
        'asst_commissioner_approval',
        'asst_commissioner_approved_date',
        'bimsaviya_commissioner_approval',
        'bimsaviya_commissioner_approved_date',
        'comm_gen_approval',
        'comm_gen_approval_date',
        'current_stage',
        'rejected',
        'rejected_date',
        'rejected_reason',
        'recheck',
        'recheck_reason',
        'recheck_date',
        'gazetted_no',
        'gazetted_date',
        'certificate_isssue_gazzette',
        'certificate_isssue_gazzette_date',
        'proof_read_complete',
        'proof_read_complete_date',
        'proof_read_translation_complete',
        'sent_gov_press',
        'sent_gov_press_date',
        'created_at',
        'updated_at',
        'sinhala_amended',
        'sinhala_amended_by',
        'sinhala_amended_date',
        'gazette_without',
        'gazette_without_by',
        'gazette_without_date',
        'press_without',
        'press_without_by',
        'press_without_date',
        'computer_branch_officer',
        'computer_with',
        'computer_with_by',
        'computer_with_date',
        'gazette_with',
        'gazette_with_by',
        'gazette_with_date',
        'comment',
    ];
    protected $dates = ['deleted_at'];
    public $timestamps = true;
}



