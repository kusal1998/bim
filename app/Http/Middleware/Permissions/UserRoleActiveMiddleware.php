<?php

namespace App\Http\Middleware\Permissions;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\UserRolePermissions;
use App\Models\UserRoles;
use App\Models\Modules; 
use Request;

class UserRoleActiveMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::user()) {
            
            $UserRole = UserRoles::where('code',Auth::user()->role_code)->first();

            if($UserRole->is_active==1){
                return $next($request);
              }
       
           else{

         Auth::logout();
            
         //   return view('errors.inactive');
          //  abort(402);
            abort(402, 'User Role is inactive!');
           }
        }

        abort(403);
    }
}