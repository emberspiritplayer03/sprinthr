<form id="login_form" method="post" action="<?php echo url('login/_login'); ?>">
<div class="top_title"><a class="loggedin_actionout" href="<?php echo url("applicant_login/logout"); ?>"><i class="icon-off icon-white"></i>  Logout</a><img src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/login/account_login_title.png" border="0" /></div>
 <div class="bottom_div"></div>
 <div class="gleent_logo"></div>
 <label class="input_container loggedin">
 <div align="center" class="user_selection">
 			<div align="center" style="font-size:16px;">Welcome! <strong><?php echo $_SESSION['sprint_applicant']['username']; ?></strong></div><br /> 			
 			<a class="blue_button" href="<?php echo url("applicant/dashboard"); ?>"><i class="icon-home icon-white"></i> My Dashboard</a>
 			<a class="blue_button" href="<?php echo url('applicant/profile'); ?>"><i class="icon-user icon-white"></i> My Profile</a>
         <a class="blue_button" href="<?php echo url('applicant/change_password'); ?>"><i class="icon-wrench icon-white"></i> Change password</a>
 			<a class="blue_button" href="<?php echo main_url("job_vacancy"); ?>"><i class="icon-briefcase icon-white"></i> Job Vacancy List</a>	
   <div class="clear"></div>
 </div>
 </label>
</form>