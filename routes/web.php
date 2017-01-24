<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', 'Auth\LoginController@showLoginForm');//->name('login')
Auth::routes();//Laravel auth


$getRoutes = [
    'Contact' => [
        'showlist' => 'showlist',
        'record' => 'record',
        'remove' => 'remove',
        'emails' => 'emails',
        'select' => 'select',
        'view' => 'view',
        'remove' => 'remove',
    ],
    'Auth\Login' => [
        '' => 'showLoginForm'
    ],

];//Key is controller name, key is controller action

$postRoutes = [
    'Contact' => [
        'showlist' => 'showlist',
        'emails' => 'emails',
        'select' =>  'select',
        'record' => 'record',
        'remove' => 'remove',
    ],
];//Key is controller name, key is controller action

$urlPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);//The URI which was given in order to access this page
$action = substr($urlPath, 1);//Get action name

$existParam = strpos($action, '/');//Check exist parameter after action name
if ($existParam) {
    $action = substr($action, 0, $existParam);//Get action name
    $urlPath = '/' . $action . '/{id}';//Get url with parameter for router
}

//Find route in 'get' queries
foreach ($getRoutes as $key => $value) {
    if (array_key_exists($action, $value)) {
        $params = [$urlPath, $key . 'Controller@' . $value[$action]];
        call_user_func_array('Route::get', $params);
    }
}
//Find route in 'post' queries
foreach ($postRoutes as $key => $value) {
    if (array_key_exists($action, $value)) {
        $params = [$urlPath, $key . 'Controller@' . $value[$action]];
        call_user_func_array('Route::post', $params);

    }
}


