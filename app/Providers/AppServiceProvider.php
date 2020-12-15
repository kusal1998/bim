<?php

namespace App\Providers;
use Auth;
use App\Models\UserRolePermissions;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
       /*  $permissions = UserRolePermissions::where('role_code',Auth::user()->role_code)->get();
           view()->share('permissions', $permissions); */
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
       
        Schema::defaultStringLength(191);

        //Add this custom validation rule.
        Validator::extend('alpha_spaces', function ($attribute, $value) {
        // This will only accept alpha and spaces. 
        // If you want to accept hyphens use: /^[\pL\s-]+$/u.
        return preg_match('/^[\pL\s]+$/u', $value); 

        });
    }
}
