<div class="block">
    <div class="authorizationForm">
        <form class='authorization' action="/users/authorisation" method="post" id="authorisation">
            <div class="text">
                <p>Authorisation</p>
            </div>
            <div><span class='errorMessage' id="loginMessage"> </span>
            </div>
            <div>
                <span class='login'>Login:</span>
                <input type="text" name='login' id="login" class="forValidation"
                       placeholder="enter your login"
                       value=>
            </div>

            <div><span class='errorMessage'
                       id="passwordMessage">  </span></div>
            <div>
                <span class='password'>Password:</span>
                <input type="password" name='password' id="password" class="forValidation" placeholder="enter your password">
            </div>

            <div class="link"><a href="#">Forgot Password?</a></div>
            <div class="link"><a href="/users/registration">Register Now!</a></div>

            <button class="buttonStyle" type="submit">
                <img src="/Public/Images/login.png">
                <span>Login</span>
            </button>
        </form>
    </div>
</div>