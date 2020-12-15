<?php

    namespace App\Models;
    use Illuminate\Notifications\Notifiable;
    use Illuminate\Database\Eloquent\Model;
  //  use Illuminate\Database\Eloquent\SoftDeletes;
    class Currencies extends Model
    {
        use Notifiable;
    //    use SoftDeletes;
        /**
         * The attributes that are mass assignable.
         *
         * @var array
         */
    protected $table = 'currencies';
   // protected $dates = ['deleted_at'];
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'code';
    public $incrementing = false;
    protected $appends = ['symbol'];
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    //public $timestamps = false;
    
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
        'html_entity',
        'font_arial',
        'font_code2000',
        'unicode_decimal',
        'unicode_hex',
        'in_left',
        'decimal_places',
        'decimal_separator',
        'thousand_separator'
    ];
    
    
      
    
    }
    