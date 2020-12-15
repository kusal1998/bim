<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Form14File extends Model
{
    protected $table = 'form_14_file';
    protected $fillable =[
        'ag_division',
        'code',
        'asst_commisioner_approval',
        'publication_branch',
        'publication_branch_date',
        'asst_commisioner_date',
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
        'publication_without_gazzette',
        'publication_without_gazzette_date',
        'gov_press_without_gazette',
        'gov_press_without_gazette_date',
        'computer_branch_g',
        'computer_branch_g_date',
        'publication_g',
        'publication_g_date',
        'gov_press_g',
        'gov_press_g_date',
        'gazetted',
        'gazetted_date',
        'current_stage',
        'created_by',
        'is_archived',
        'computer_branch_officer',
        'gazette_no',
        'gazette_date',
        'created_at',
        'updated_at',
    ];
    public $timestamps = true;
}
