<form method="post" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>">
    <div class="lms-login-form">
        <h3 class="register_head"> Registration </h3>
        <div class="form-group">
            <label class="login-field-icon fui-user" for="reg-name">Username:</label>
            <input name="reg_name" type="text" class="form-control login-field <?=isset($this->error['reg_name'])? 'error' : ''?>"
                   value="<?php echo(isset($_POST['reg_name']) ? $_POST['reg_name'] : null); ?>"
                   placeholder="Username" id="reg-name" required/>
        </div>

        <div class="form-group">
            <label class="login-field-icon fui-mail" for="reg-email">Email:</label>
            <input name="reg_email" type="email" class="form-control login-field <?=isset($this->error['reg_email'])? 'error' : ''?>"
                   value="<?php echo(isset($_POST['reg_email']) ? $_POST['reg_email'] : null); ?>"
                   placeholder="Email" id="reg-email" required/>
        </div>

        <div class="form-group">
            <label class="login-field-icon fui-lock" for="reg-pass">Password:</label>
            <input name="reg_password" type="password" class="form-control login-field <?=isset($this->error['reg_password']) ? 'error' : ''?>"
                   value="<?php echo(isset($_POST['reg_password']) ? $_POST['reg_password'] : null); ?>"
                   placeholder="Password" id="reg-pass" required/>
        </div>

        <div class="form-group">
            <label class="login-field-icon fui-lock" for="confirm-reg-pass">Confirm Password:</label>
            <input name="confirm_reg_password" type="password" class="form-control login-field <?=isset($this->error['confirm_reg_password']) ? 'error' : ''?>"
                   value="<?php echo(isset($_POST['confirm_reg_password']) ? $_POST['confirm_reg_password'] : null); ?>"
                   placeholder="Confirm Password" id="confirm-reg-pass" required/>
        </div>

        <div class="form-group">
            <label class="login-field-icon fui-user" for="reg-fname">First Name:</label>
            <input name="reg_fname" type="text" class="form-control login-field <?=isset($this->error['reg_fname']) ? 'error' : ''?>"
                   value="<?php echo(isset($_POST['reg_fname']) ? $_POST['reg_fname'] : null); ?>"
                   placeholder="First Name" id="reg-fname"/>
        </div>

        <div class="form-group">
            <label class="login-field-icon fui-user" for="reg-lname">Last Name:</label>
            <input name="reg_lname" type="text" class="form-control login-field <?=isset($this->error['reg_lname']) ? 'error' : ''?>"
                   value="<?php echo(isset($_POST['reg_lname']) ? $_POST['reg_lname'] : null); ?>"
                   placeholder="Last Name" id="reg-lname"/>
        </div>

       <!--  <div class="form-group">
            <label class="login-field-icon fui-user" for="reg-nickname">Nickname:</label>
            <input name="reg_nickname" type="text" class="form-control login-field"
                   value="<?php echo(isset($_POST['reg_nickname']) ? $_POST['reg_nickname'] : null); ?>"
                   placeholder="Nickname" id="reg-nickname"/>
        </div>    -->             

        <div class="form-group">
            <label class="login-field-icon fui-new" for="group_selected">Group ID:</label>
            <input name="group_selected" type="text" class="form-control login-field <?=isset($this->error['group_selected']) ? 'error' : ''?>"
                   value="<?php echo(isset($_POST['group_selected']) ? $_POST['group_selected'] : null); ?>"
                   placeholder="Group ID" id="group_selected"/>
        </div>

        <div class="form-group">
            <?php $GLOBALS['users']->the_captcha() ?>
        </div>

        <input class="btn btn-primary btn-lg btn-block" type="submit" name="reg_submit" value="Register"/>
    </div>
</form>