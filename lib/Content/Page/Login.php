<?php
namespace Content\Page;

class Login {
  public function content() {


    $login = '
        <section class="main">
            <form class="loginform clearfix">
                <p>
                    <input type="text" id="loginname" name="login" placeholder="Username">
                    <input type="password" name="password" id="password" placeholder="Password">
                </p>
            <button id="login">
              <i class="icon-arrow-right"></i>
              <span>Sign in</span>
            </button>
            <p class="wrongPassword">
                Your password or username is wrong
            </p>
            <p class="newUser">
                Not a member ?
                <a href="#tosignUp" class="to_register"> Go and sign up</a>
            </p>
            </form>
        ​</section>';

    $register = '
     <section class="registermain">
            <form class="register clearfix">
                <p>
                    <input id="usernamesignup" name="usernamesignup" required="required" type="text" placeholder="username" />
                    <input id="emailsignup" name="emailsignup" required="required" type="text" placeholder="your_email@mail.com"/>
                    <input id="passwordsignup" name="passwordsignup" required="required" type="password" placeholder="your Password"/>
                </p>
            <button id="signup" name="submit">
              <i class="icon-arrow-right"></i>
              <span>Sign up</span>
            </button>
            <p class="emailExist">
                Email exist
            </p>
            <p class="usernameExist">
                User Exist
            </p>
            <p class="required">
                Field required
            </p>
            <p class="registerUser">
                You are a member ?
                <a href="#tologin" class="to_login"> Go and sign in</a>
            </p>
            </form>
        ​</section>';

    return $login .$register;
  }
}


