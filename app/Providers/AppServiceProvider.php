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
            //$translator -данные о локале, берется из app.conf
            //$data - массив с данными
            //$rule - название правила (required, max, min...)

            return new CustomValidator($translator, $data, $rules, $messages); // здесь мы добавили наш валидатор
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
