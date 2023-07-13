<?php 
 $e = G_Employee_Helper::findByEmployeeId(Utilities::decrypt($_SESSION['sprint_hr']['employee_id'])); 
 $u = G_User_Finder::findByEmployeeId(Utilities::decrypt($_SESSION['sprint_hr']['employee_id']));
		//echo Utilities::isHashValid(Utilities::decrypt($eid),$hash);
		if($u) {
			$mod = explode(',', $u->getModule());	
		}
 
 
$file =  MAIN_FOLDER .'hr/files/photo/'.$e['photo'];
if(Tools::isFileExist($file)==1 && $e['photo']!='') {
	$filemtime = md5($e['photo']).date("His");
	$filename = $file;
	}else {
		$filename = BASE_FOLDER. 'images/profile_noimage.gif';}?>
<div id="profile_tab">
<ul>
	<li class="user_photo"><a href="#"><img src="<?php echo $filename; ?>?s=<?php echo $filemtime; ?>" alt="User Photo" /></a></li>
    <li class="userfname"><a href="#"><?php echo $e['salutation'].' '. $e['firstname'].' '.$e['lastname'].' '.$e['extension_name']; ?> <img src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/dropdicon_blck.png" alt="Down Arrow" /></a>
    	<div id="usrprfl_cntnr">
        	<div class="usrprfl_content">
            	<div class="usrprfldetails">                
               	  	<img src="<?php echo $filename; ?>?s=<?php echo $filemtime; ?>" alt="User Photo Big" class="userimage" />
                	<span class="bold userdname"><?php echo $e['salutation'].' '. $e['firstname'].' '.$e['lastname'].' '.$e['extension_name']; ?></span>
                    <span class="userdposition"><?php echo $e['position'];?></span>
                    <span class="userworkingemail"><?php echo $e['working_email'];?></span>
                    <div class="clearleft"></div>                     
              </div>
                <div class="usrprflfooter">
                	<?php if(MOD_EMPLOYEE == false && MOD_PAYROLL == false && MOD_HR == false){ ?>
                    
                    <?php }else{ ?>
                		<span class="switchto"><!--<i class="icon-random"></i> -->Switch To : </span>
                    <?php } ?>
					<?php foreach($mod as $key =>$value) { ?>
                        <?php if($value=='hr' && MOD_HR == true) { ?>
                        	<a class="button_link blue_button view_profile" href="<?php echo MAIN_FOLDER.'hr/index.php/dashboard/employee_dashboard'; ?>">HR</a>
                        <?php } ?>

                        <?php if($value=='employee' && MOD_EMPLOYEE) { ?>
                        	<a class="button_link blue_button view_profile" href="<?php echo MAIN_FOLDER.'employee/index.php/dashboard'; ?>">Employee</a>
                        <?php } ?>
                        
                        <?php if($value=='payroll' && MOD_PAYROLL) { ?>
                       		<a class="button_link blue_button view_profile" href="<?php echo MAIN_FOLDER.'payroll/index.php/dashboard/employee_dashboard'; ?>">Payroll</a>
                        <?php } ?>
                  	<?php } ?>
                	
                	<a class="button_link gray_button logout" href="<?php echo MAIN_FOLDER.'index.php/login/logout'; ?>">Logout</a>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </li>    
</ul>
</div><!-- #top_navigation -->