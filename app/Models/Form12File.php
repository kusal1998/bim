<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Form12File extends Model
{
    protected $table = 'form_12_file';
    protected $fillable =[
        'code',
        'computer_branch_officer',
        'publication_branch',
        'publication_branch_date',
        'asst_commissioner_approval',
        'asst_commissioner_approval_date',
        'bim_approval',
        'bim_approval_date',
        'comm_gen_approval',
        'comm_gen_approval_date',
        'computer_branch',
        'computer_branch_date',
        'proof_read_sinhala',
        'proof_read_sinhala_date',
        'proof_read_english',
        'proof_read_english_date',
        'publication_without_gazette',
        'publication_without_gazzete_date',
        'gov_press_without_gazzette',
        'gov_press_without_gazzette_date',
        'computer_branch_g',
        'computer_branch_g_date',
        'publication_g',
        'publication_g_date',
        'gov_press_g',
        'gov_press_g_date',
        'gazzeted',
        'gazzeted_date',
        'current_stage',
        'created_by',
        'is_archived',
        'created_at',
        'updated_at',
        'publication_checked_by',
        'gazette_no',
        'gazette_date',
    ];
    public $timestamps = true;
}
