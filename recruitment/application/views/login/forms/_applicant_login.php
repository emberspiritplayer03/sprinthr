<script>
$(function(){
$('#username').tipsy({gravity: 'se', html: true, fade: true, title:
      function(){
			return tipysyGenerateTitle(this.getAttribute('original-title'));
		}		
});

tipysyGenerateTitle = function(title){
	title = '<i class="icon-envelope icon-white"></i> ' + title;	
	return title;
 }
});
</script>
<form name="applicant_login_form" id="applicant_login_form" class="applicant_login_form" method="post" action="<?php echo url('applicant_login/_login'); ?>">
<input type="hidden" name="token" id="token" value="<?php echo $token; ?>" />
	<div class="top_title"><img src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/login/account_login_title.png" border="0" /></div>
   <div class="bottom_div"></div>
   <div class="gleent_logo"></div>
   <div id="error_message" style="display:none;"></div>
   <label class="input_container">
   	<span>Username</span>
      <input name="username" type="text" title="Registered Email Address" class="validate[required] input_field" id="username" onfocus="javascript:hideAlertBox();" />
   </label>
   <label class="input_container">
      <span>Password</span>
      <input name="password" type="password" class="validate[required] input_field" id="password" onfocus="javascript:hideAlertBox();" />
   </label>
   <label class="button_container">
   	<span>&nbsp;</span>
      <input type="submit" class="login_button" value="LOGIN" />
      <!-- <a href="<?php echo url('forgot_password'); ?>" class="forgot_password">Forgot your Password?</a> --> 
      <a href="<?php echo main_url('register'); ?>" target="_blank" class="">Register?</a>
   </label>
</form>