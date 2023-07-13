<style>
.alert-danger h4, .alert-error h4 {
    color: #B94A48;
	font-size:17.5px;
}
.alert-error img{
	margin-right:5px;
}
</style>
<script>
$(document).ready(function() {
	$("#login_form").validationEngine({scroll:false});
	$('#login_form').ajaxForm({
		success:function(o) {
			if ( o.is_success ) {
				window.location = base_url+'login';
			} else {
				closeDialog('#' + DIALOG_CONTENT_HANDLER);
				showAlertBox();	
				$("#error_message").html(o.message);	
				$("#username").val("");
				$("#password").val("");
			}
		},
		dataType:'json',
		beforeSubmit: function() {
			showLoadingDialog('Validating...');
		}
	});	
});	

function hideAlertBox(){
	$("#error_message").fadeOut(800);
}

function showAlertBox(){
	$("#error_message").fadeIn(800);
}
</script>

<div id="login_wrapper">    
    <div id="login_container">
        <div class="login_content">
            <form name="login_form" id="login_form" class="login_form" method="post" action="<?php echo url('login/_login'); ?>">
                <div class="top_title"><img src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/login/account_login_title.png" border="0" /></div>
                <div class="bottom_div"></div>
                <div class="gleent_logo"></div>
                <div id="error_message" style="display:none;"></div>
                <label class="input_container">
                    <span>Username</span>
                    <input name="username" type="text" class="validate[required] input_field" id="username" onfocus="javascript:hideAlertBox();" />
                </label>
                <label class="input_container">
                    <span>Password</span>
                    <input name="password" type="password" class="validate[required] input_field" id="password" onfocus="javascript:hideAlertBox();" />
                </label>
                <label class="button_container">
                    <span>&nbsp;</span>
                    <input type="submit" class="login_button" value="LOGIN" />
                    <a href="<?php echo url('forgot_password'); ?>" class="forgot_password">Forgot your Password?</a>
                </label>
            </form>
        </div>
    </div>
</div>
