<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
class Form14Details extends Model implements Auditable
{
    //use SoftDeletes;
    protected $table = 'form_14_detail';
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'form_14_Header_id',
        'lot_no',
        'name',
        'addres',
        'nic_number',
        'size',
        'ownership_type',
        'class',
        'mortgages',
        'other_boudages',
        'type',
        'owner_details_gn_division_id',
        'sub_type',
        'certificate_number',
        'certificate_number_date',
        'rejected',
        'rejected_reason',
        'created_at',
        'updated_at',
    ];
    protected $dates = ['deleted_at'];
    public $timestamps = true;
}



