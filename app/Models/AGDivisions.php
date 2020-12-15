<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
class AgDivisions extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'district_id','ag_name','ag_code','sinhala_name','phone_number','fax_number','email_address','is_active'
    ];
    protected $dates = ['deleted_at'];
    public $timestamps = true;
}



