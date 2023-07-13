<script>

$("#login_form").validationEngine({scroll:false});
$('#login_form').ajaxForm({
	success:function(o) {
		if(o==1) {
			//dialogOkBox('Successfully Login',{ok_url:'dashboard'});
			window.location = base_url+'dashboard';
			
		}else {
			$("#error_message").html('<label class="input_container"><div class="error_message"><img src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/login/error.png" alt="error" class="error_icon" />Invalid Username or Password</div></label>');
		}		
	}
});
</script>

<div id="login_wrapper">
    <!--<div align="center"><img src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/login/logo.png" border="0" /></div>-->
    <div id="login_container">
        <div class="login_content">
        <?php 
		echo 'test'.$company_structure_id;
		if (!$company_structure_id || !$username || !$employee_id ) { ?>
        	
            <form name="login_form" id="login_form" method="post" action="<?php echo url('login/_login'); ?>">
            	<div class="top_title"><img src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/login/account_login_title.png" border="0" /></div>
                <div class="bottom_div"></div>
                <div class="gleent_logo"></div>
                <div id="error_message"></div>
                <label class="input_container">
                	<span>Username</span>
                    <input name="username" type="text" class="validate[required] input_field" id="username" />
                </label>
                <label class="input_container">
                	<span>Password</span>
                    <input name="password" type="password" class="validate[required] input_field" id="password" />
                </label>
                <label class="button_container">
                	<span>&nbsp;</span>
                    <input type="submit" class="login_button" value="LOGIN" />
                    <a href="#" class="forgot_password">Forgot your Password?</a>
                </label>
            </form>
            <?php }else {?> 

			 <form id="login_form" method="post" action="<?php echo url('login/_login'); ?>">
            	<div class="top_title"><img src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/login/account_login_title.png" border="0" /></div>
                <div class="bottom_div"></div>
                <div class="gleent_logo"></div>
                <label class="input_container">
       			Welcome! <?php echo $_SESSION['hr']['username']; ?>
			<br /><br />
    				<a href="<?php echo MAIN_FOLDER; ?>hr">Human Resource</a> | <a href="<?php echo MAIN_FOLDER; ?>payroll">Payroll</a> |<a href="<?php echo MAIN_FOLDER; ?>clerk">Clerk</a> | <a href="<?php echo MAIN_FOLDER; ?>employee">Employee</a>
                  
                </label>
            </form>
		<?php } ?>
        </div>
    </div>
</div>
