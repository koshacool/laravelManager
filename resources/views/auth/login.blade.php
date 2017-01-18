@extends('layouts.app')

@section('content')
    <div class="block">
        <div class="authorizationForm">
            <form class='authorization' action="{{ url('/login') }}" method="post" id="authorisation">
                {{ csrf_field() }}
                <div class="text">
                    <p>Authorisation</p>
                </div>
                <div>
                    <span class='errorMessage' id="loginMessage"> {{ $errors->first('name') }}  </span>
                </div>
                <div>
                    <span class='login'>Login:</span>
                    <input type="text" name='name' id="login" class="forValidation"
                           placeholder="enter your login"
                           value="{{ old('name') }}">
                </div>


                <div><span class='errorMessage'
                           id="passwordMessage"> {{ $errors->first('password') }} </span></div>
                <div>
                    <span class='password'>Password:</span>
                    <input type="password" name='password' id="password" class="forValidation" placeholder="enter your password">
                </div>

                <div class="link"><a href="#">Forgot Password?</a></div>
                <div class="link"><a href="{{ url('/register') }}">Register Now!</a></div>

                <button class="buttonStyle" type="submit">
                    <img src="/images/login.png">
                    <span>Login</span>
                </button>
            </form>
        </div>
    </div>

@endsection
