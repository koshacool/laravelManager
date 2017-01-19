<?php
namespace App\Http\ViewComposer;
use Illuminate\View\View;
Use Auth;

class ProfileComposer {
    public function create (View $view) {
            $view->with('user', Auth::user());
    }
}