<?php

namespace App\Http\Middleware\Permissions;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\UserRolePermissions;
use App\Models\UserRoles;
use App\Models\Modules; 
use Request;

class UserActiveMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::user()) {
            
           
            if(Auth::user()->is_active==1){
                return $next($request);
              }
       
           else{

         Auth::logout();
            
         //   return view('errors.inactive');
           // abort(402);
            abort(402, 'Your account is inactive!');
           }
        }

        abort(403);
    }
}