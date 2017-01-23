<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
Use View;
Use Auth;
use Validator;
use App\Http\Middleware\CustomValidator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::resolver(function($translator, $data, $rules, $messages)
        {
            return new CustomValidator($translator, $data, $rules, $messages); //Add our own validator
        });
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
