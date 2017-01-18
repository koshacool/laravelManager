@extends('layouts.app')

@section('content')
    <div class="block">
        <div class="authorizationForm">
            <form action="{{ url('/register') }}" method="post" class="registration">
                {{ csrf_field() }}
                <div class="text">Registration</div>

                <div><span class='errorMessage' id="loginMessage"> {{ $errors->first('name') }} </span></div>
                <div>
                    <span class='login'>Login:</span>
                    <input type="text" name='name' id="login" class="forValidation"
                           placeholder="enter your login"
                           value="{{ old('name') }}">
                </div>

                <div><span class='errorMessage' id="passwordMessage"> {{ $errors->first('password') }} </span></div>
                <div>
                    <span class='password'>Password:</span>
                    <input type="password" name='password' id="password" class="forValidation"
                           placeholder="enter your password">
                </div>

                <div><span class='errorMessage'
                           id="repeatPasswordMessage"> {{ $errors->first('password_confirmation') }}</span></div>
                <div>
                    <span class='password'>Repeat password:</span>
                    <input type="password" name='password_confirmation' class="forValidation"
                           id="repeatPassword"
                           placeholder="repeat password">
                </div>

                <div>
                    <button class="buttonStyle" type="submit">
                        <img src="/images/login.png">
                        <span>Registraion</span>
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection
