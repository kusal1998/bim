<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
class Modules extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'md_name',
        'md_code',
        'active',
        'md_group',
        'icon',
        'url',
        'can_create',
        'can_read',
        'can_update',
        'can_delete',
        'can_approve',
        'can_proof_read',
        'can_certificate',
        'can_gazzete',
        'can_press',
        'can_recheck',
        'can_verify',
        'can_asst_comm',
        'can_bimsaviya_comm',
        'can_comm_general',
        'can_forward_to_proof',
        'can_forward_to_translate',
        'can_translate_proof',
        'can_close',
        'can_reject',
        'updated_at',
        'created_at',
        'hide_menu',
        'order_menu'
    ];
    protected $dates = ['deleted_at'];
    protected $rules = [
        'md_name' => 'required',
        'md_code' => 'required',
    ];

    public $timestamps = true;
}



