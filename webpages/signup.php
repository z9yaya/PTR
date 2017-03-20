<div class="containers">
    <input type="hidden" id="ptr:form:method" name="method" value="signup">
            <input type="text" id="username" name="ptr:signup:username" class="text input" autocorrect="off" autocapitalize="off" autofocus required autocomplete="off">
            <label for="username" class="label username">Email</label>
            <div class="errorContainer"><span class="error email"></span></div>
        </div>
        <div class="containers">
            <input id="secret1" type="password" name="ptr:signup:secret1" class="password input" required>
            <label for="secret"  class="label secret">Password</label>
            <div class="errorContainer"><span class="error password"></span></div>
        </div> 
        <div class="containers">
            <input id="secret2" type="password" name="ptr:signup:secret2" class="password input" required>
            <label for="secret2"  class="label secret">Repeat password</label> 
            <div class="errorContainer"><span class="error password"></span></div>
        </div>
        <div class="containers">
            <input type="text" id="empID" name="ptr:signup:empID" class="text input" autocorrect="off" autocapitalize="off" required>
            <label for="empID" class="label username">Employee ID</label>
            <div class="errorContainer"><span class="error ID"></span></div>
        </div>
        <input type="submit" value="SIGN UP" class="submit button">
        <div class="submit wait displayNone"><div class="innerButton animationCircle"></div></div>
        <div class="signup">Already have an account?<input type="checkbox" id="signupCheck" class="HiddenObject CheckBox signIn" onchange="SignInSignUp('signin.php', false)"><label for="signupCheck" class="Link">Sign In</label></div>