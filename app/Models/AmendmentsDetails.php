<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
class AmendmentsDetails extends Model implements Auditable
{
    //use SoftDeletes;
    protected $table = 'amendments_details';
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'amendment_header_id',
        'nature_if_identification',
        'document_evidence',
        'parties_noticed',
        'conclution',
        'name_of_the_officer',
        'rejected',
        'rejected_reason',
        'created_at',
        'updated_at',
    ];
    protected $dates = ['deleted_at'];
    public $timestamps = true;
}



