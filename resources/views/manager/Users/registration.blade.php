<div class="block">
    <div class="authorizationForm">
        <form action="/users/registration" method="post" class="registration">
            {!! csrf_field()  !!}
            <div class="text">Registration</div>
            <div><span class='errorMessage' id="loginMessage"> </span></div>
            <div><span class='login'>Login:</span> <input type="text" name='name' id="login" class="forValidation"
                                                          placeholder="enter your login"
                                                          value=></div>

            <div><span class='errorMessage' id="passwordMessage">  </span></div>
            <div><span class='password'>Password:</span> <input type="password" name='password' id="password" class="forValidation"
                                                                placeholder="enter your password"></div>

            <div><span class='errorMessage' id="repeatPasswordMessage"> </span></div>
            <div><span class='password'>Repeat password:</span> <input type="password" name='repeatPassword' class="forValidation"
                                                                       id="repeatPassword"
                                                                       placeholder="repeat password"></div>

            <div>
                <button class="buttonStyle" type="submit" >
                    <img src="/Public/Images/login.png">
                    <span>Registraion</span>
                </button>
            </div>

        </form>
    </div>
</div>