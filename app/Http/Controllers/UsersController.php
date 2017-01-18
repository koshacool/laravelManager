<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UsersController extends Controller
{
    public function authorisation() {
       return view('manager.Users.Authorisation');
    }

    public function registration() {
        return view('manager.Users.Registration');
    }

    public function create(Request $request) {
        User::create($request->all());
        return $request->all();
        return view('manager.Users.registration');
    }
}
