<div class="containers">
    <input type="hidden" name="method" value="login">
            <input type="text" id="username" name="ptr:login:email" class="text input" autocorrect="off" autocapitalize="off" autofocus required autocomplete="off">
            <label for="username" class="label username">Email</label>
            <div class="errorContainer"><span class="error username"></span></div>
        </div>
        <div class="containers">
            <input id="secret" type="password" name="ptr:login:secret" class="password input" required>
            <label for="secret"  class="label secret">Password</label>
            <div class="errorContainer"><span class="error password"></span></div>
        </div>
        
        <input type="submit" value="SIGN IN" class="submit button">
        <div class="submit wait displayNone"><div class="innerButton animationCircle"></div></div>
        <div class="signup">Don't have an account yet?<input type="checkbox" id="signupCheck" class="HiddenObject CheckBox signIn" onchange="SignInSignUp('signup.php', true)"><label for="signupCheck" class="Link">Sign Up</label></div>