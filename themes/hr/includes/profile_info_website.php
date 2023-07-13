<?php  
 $e = G_Applicant_Finder::findByEmailAddress($_SESSION['sprint_applicant']['username']); 
 $u = G_User_Finder::findByEmployeeId(Utilities::decrypt($_SESSION['sprint_hr']['employee_id']));
		//echo Utilities::isHashValid(Utilities::decrypt($eid),$hash);
		if($u) {
			$mod = explode(',', $u->getModule());	
		}

if($e) {
	if($e->getPhoto() == '') {
		$p_photo = G_Applicant_Profile_Finder::findByApplicantLogId(Utilities::decrypt($_SESSION['sprint_applicant']['applicant_id']));
		$a_profile_photo = $p_photo->getPhoto();
	} else {
		$p_photo = G_Applicant_Profile_Finder::findByApplicantLogId(Utilities::decrypt($_SESSION['sprint_applicant']['applicant_id']));
		if($p_photo){
			if($p_photo->getPhoto() != ''){
				$a_profile_photo = $p_photo->getPhoto();
			}else{
				$a_profile_photo = $e->getPhoto();	
			}
		}else{
			$a_profile_photo = '';		
		}
			
	}
}else {
	$p_photo = G_Applicant_Profile_Finder::findByApplicantLogId(Utilities::decrypt($_SESSION['sprint_applicant']['applicant_id']));
	if($p_photo) {
		$a_profile_photo = $p_photo->getPhoto();	
	}else{	
		$a_profile_photo = BASE_FOLDER. 'images/profile_noimage.gif';
	}
}

//$file = PHOTO_FOLDER.$a_profile_photo;
$file = HR_BASE_FOLDER . "files/photo/" . $a_profile_photo;
if(Tools::isFileExist($file)==1 && $a_profile_photo != '') {
	$filemtime = md5($a_profile_photo).date("His");
	$filename = $file;
	}else {
		$filename = BASE_FOLDER. 'images/profile_noimage.gif';}
		
?>
<div id="profile_tab">
<ul>
	<li class="user_photo"><a href="#"><img src="<?php echo $filename; ?>?s=<?php echo $filemtime; ?>" alt="User Photo" /></a></li>
    <li class="userfname"><a href="#"><?php echo $hdr_applicant_name; ?> <img src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/dropdicon_blck.png" alt="Down Arrow" /></a>
    	<div id="usrprfl_cntnr">
        	<div class="usrprfl_content">        	
            	<div class="usrprfldetails">                
               	  <img src="<?php echo $filename; ?>?s=<?php echo $filemtime; ?>" alt="User Photo Big" class="userimage" />    
               	  <span class="bold userdname"><?php echo $hdr_applicant_name; ?></span>            	  
                    <span class="userworkingemail"><?php echo $hdr_email_address;?></span>
                    <div class="clearleft"></div>                     
              </div>
                <div class="usrprflfooter">
           
     
                	<!-- <a class="button_link gray_button logout" href="<?php echo MAIN_FOLDER.'index.php/login/logout'; ?>">Logout</a> -->
                	<a class="button_link gray_button logout" href="<?php echo recruitment_url('applicant_login/logout'); ?>"><i class="icon-off"></i> Logout</a>
                	<a class="button_link gray_button logout" href="<?php echo recruitment_url('applicant/profile'); ?>" target="_blank"><i class="icon-user"></i> My Profile</a>
                	<a class="button_link gray_button logout" href="<?php echo recruitment_url("applicant_login"); ?>"><i class="icon-home"></i> My Cpanel</a>                	
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </li>

</ul>
</div><!-- #top_navigation -->