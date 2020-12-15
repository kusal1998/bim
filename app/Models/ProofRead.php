<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
class ProofRead extends Model implements Auditable
{
    //use SoftDeletes;
    protected $table = 'proof_read';
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'form_name',
        'language',
        'ref_number',
        'proof_read_by',
        'proof_read_date',
        'remarks',
        'created_at',
        'updated_at',
    ];
    protected $dates = ['deleted_at'];
    public $timestamps = true;
}



