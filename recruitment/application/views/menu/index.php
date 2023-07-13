
<div id="login_wrapper">
    <!--<div align="center"><img src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/login/logo.png" border="0" /></div>-->
    <div id="login_container">
        <div class="login_content">
       

			 <form id="login_form" method="post" action="<?php echo url('login/_login'); ?>">
            	<div class="top_title" style="text-align:left;"><img src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/login/account_login_title.png" border="0" /></div>
                <div class="bottom_div"></div>
                <div class="gleent_logo"></div>                
                <label class="input_container loggedin">
                <div align="center" class="user_selection">
       				<div align="center" style="font-size:16px;">Welcome! <strong><?php echo $_SESSION['hr']['username']; ?></strong></div><br />
                	<a class="blue_button" href="<?php echo MAIN_FOLDER; ?>recruitment/index.php/registration"><i class="icon-pencil icon-white"></i> Online Registration</a>
                    <a class="blue_button" href="<?php echo MAIN_FOLDER; ?>recruitment/index.php/examination"><i class="icon-list-alt icon-white"></i> Examination</a>
                    <div class="clear"></div>
                </div>
                </label>
			 </form>
	
        </div>
    </div>
</div>
