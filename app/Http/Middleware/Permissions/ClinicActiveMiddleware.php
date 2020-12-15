<?php

namespace App\Http\Middleware\Permissions;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\UserRolePermissions;
use App\Models\UserRoles;
use App\Models\Modules; 
use App\Models\Clinics; 
use Request;

class ClinicActiveMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::user()) {
            
            

            $clinic = Clinics::where('id',Auth::user()->clinic_id)->first();

            if($clinic->is_active==1){
                return $next($request);
              }
       
           else{

         Auth::logout();
            
         //   return view('errors.inactive');
            //abort(402);
            abort(402, ' '.$clinic->name.' is inactive!');
           }
        }

        abort(403);
    }
}