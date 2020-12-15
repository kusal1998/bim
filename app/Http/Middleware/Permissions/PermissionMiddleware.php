<?php

namespace App\Http\Middleware\Permissions;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\UserRolePermissions;
use App\Models\UserRoles;
use App\Models\Modules;
use Request;

class PermissionMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::user()) {

           $module_code = Request::segment(1);
           $Permissions = UserRolePermissions::where('role_code',Auth::user()->role_code)->where('module_code',$module_code)->first();
           if(isset($Permissions)){
            if($Permissions->is_enable==1){
                return $next($request);
              }
           }else{
            abort(403);
           }
        }

        abort(403);
    }
}
