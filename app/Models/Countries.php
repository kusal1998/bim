<?php

    namespace App\Models;
    use Illuminate\Notifications\Notifiable;
    use Illuminate\Database\Eloquent\Model;
  //  use Illuminate\Database\Eloquent\SoftDeletes;
    class Countries extends Model
    {
        use Notifiable;
      //  use SoftDeletes;
        /**
         * The attributes that are mass assignable.
         *
         * @var array
         */
    
        protected $table = 'countries';
      ///  protected $dates = ['deleted_at'];
        /**
         * The primary key for the model.
         *
         * @var string
         */
        protected $primaryKey = 'code';
        public $incrementing = false;
        protected $appends = ['icode'];
        protected $visible = ['code', 'name', 'asciiname', 'icode', 'currency_code', 'phone', 'languages', 'currency', 'admin_type', 'admin_field_active'];
        
        protected $fillable = [
            'code',
            'name',
            'asciiname',
            'capital',
            'continent_code',
            'tld',
            'currency_code',
            'phone',
            'languages',
            'admin_type',
            'admin_field_active',
            'active'
        ];
    
      
    
    }
    