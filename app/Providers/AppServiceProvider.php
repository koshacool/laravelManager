<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
Use View;
Use Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
//        View::share('user', Auth::user());
//        View::creator('*', function($view){
//            $view->with('user', Auth::user());
//        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
