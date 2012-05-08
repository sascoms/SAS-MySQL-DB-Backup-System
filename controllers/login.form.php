<div class="loginForm">
    <form action="" method="post">
    	<span class="error"><?php echo $loginMsg;?></span>

        <p><label for="login_user">username</label><input type="text" name="login_user" id="login_user" /></p>
        <p><label for="login_pass">password</label><input type="password" name="login_pass" id="login_pass" /></p>
        <p><input type="submit" name="loginSubmit" id="loginSubmit" class="submit" value="Login" /></p>
    </form>
</div>