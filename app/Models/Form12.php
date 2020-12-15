<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
class Form12 extends Model implements Auditable
{
    //use SoftDeletes;
    protected $table = 'form_12';
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'ag_division',
        'map_no',
        'block_no',
        'province_id',
        'district_id',
        'gn_division',
        'village',
        'name',
        'government_lands',
        'private_lands',
        'total_lands',
        'file_no',
        'ref_no',
        'ref_no2',
        'publication_branch',
        'publication_branch_date',
        'computer_branch',
        'computer_branch_date',
        'prepared_by',
        'prepared_date',
        'regional_approved',
        'regional_approved_by',
        'regional_approved_date',
        'publication_checked_by',
        'regional_checked',
        'regional_checked_by',
        'regional_checked_date',
        'computer_branch_officer',
        'computer_checked_by',
        'asst_comm_approval',
        'asst_comm_approval_date',
        'bimsaviya_approval',
        'bimsaviya_approval_date',
        'comm_gen_approval',
        'comm_gen_approal_date',
        'first_proof_read',
        'first_proof_read_by',
        'first_proof_read_date',
        'second_proof_read',
        'second_proof_read_by',
        'second_proof_read_date',
        'first_proof_read_tamil',
        'first_proof_read_tamil_by',
        'first_proof_read_tamil_date',
        'second_proof_read_tamil',
        'second_proof_read_tamil_by',
        'second_proof_read_tamil_date',
        'first_proof_english',
        'first_proof_english_by',
        'first_proof_english_date',
        'second_proof_english',
        'second_proof_english_by',
        'second_proof_english_date',
        'sent_gov_press',
        'sent_gov_press_date',
        'gazette_no',
        'gazette_date',
        'current_stage',
        'rejected',
        'rejected_date',
        'rejected_reason',
        'recheck',
        'recheck_reason',
        'recheck_date',
        'created_at',
        'updated_at',
        'comment',
    ];
    protected $dates = ['deleted_at'];
    public $timestamps = true;
}



