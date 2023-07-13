<script>

$("#login_form").validationEngine({scroll:false});
$('#login_form').ajaxForm({
	success:function(o) {
		if(o==1) {
		//	dialogOkBox('Successfully Login',{ok_url:'login'});
			window.location = base_url+'login';
			
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
        

		

			 <form id="login_form" method="post" action="<?php echo url('login/_login'); ?>">
            	<div class="top_title"><a class="loggedin_actionout" href="<?php echo BASE_FOLDER; ?>index.php/login/logout"><i class="icon-off icon-white"></i>  Logout</a><img src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/login/account_login_title.png" border="0" /></div>
                <div class="bottom_div"></div>
                <div class="gleent_logo"></div>
                <label class="input_container loggedin">
                <div align="center" class="user_selection">
       			<div align="center" style="font-size:16px;">Welcome! <strong><?php echo $_SESSION['sprint_hr']['username']; ?></strong></div><br />
	
    
    
    
    
    
                    <div class="clear"></div>
                </div>
                </label>
            </form>
		
        
        
        
        </div>
    </div>
</div>
