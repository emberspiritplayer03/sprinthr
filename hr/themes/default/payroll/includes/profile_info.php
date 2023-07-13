<?php
    $hr_mod         = $GLOBALS['module_package']['hr'];
    $attendance_mod = $GLOBALS['module_package']['attendance'];
?>
<?php 
 $e = G_Employee_Helper::findByEmployeeId(Utilities::decrypt($hdr_user_eid)); 
 $u = G_User_Finder::findByEmployeeId(Utilities::decrypt($hdr_user_eid));
        //echo Utilities::isHashValid(Utilities::decrypt($eid),$hash);
        if($u) {
            $mod = explode(',', $u->getModule());   
        }
$file = PHOTO_FOLDER.$e['photo'];
if(Tools::isFileExist($file)==1 && $e['photo']!='') {
    $filemtime = md5($e['photo']).date("His");
    $filename = $file;
    }else {
        $filename = BASE_FOLDER. 'images/profile_noimage.gif';}?>
<div id="profile_tab">    
    <a class="blue_button btn-help" title="Help" target='_blank' href="<?php echo $hdr_help_url; ?>"><i class="icon-white icon-question-sign"></i></a>
    <ul>
        <li class="user_photo"><a href="#"><img src="<?php echo $filename; ?>?s=<?php echo $filemtime; ?>" alt="User Photo" /></a></li>
        <li class="userfname"><a href="#"><?php echo $hdr_employee_name; ?> <img src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/dropdicon_blck.png" alt="Down Arrow" /></a>
            <div id="usrprfl_cntnr">
                <div class="usrprfl_content">
                    <div class="usrprfldetails">                
                        <img src="<?php echo $filename; ?>?s=<?php echo $filemtime; ?>" alt="User Photo Big" class="userimage" />
                        <span class="bold userdname"><?php echo $hdr_employee_name; ?></span>
                        <span class="userdposition"><?php echo $hdr_empployee_position;?></span>
                        <!-- <span class="userworkingemail"><?php //echo $e['working_email'];?></span> -->
                        <div class="clearleft"></div>                     
                  </div>
                    <div class="usrprflfooter">
                        <?php echo $hdr_switch_to; ?>
                        <a class="button_link gray_button logout" href="<?php echo MAIN_FOLDER.'index.php/login/logout'; ?>">Logout</a>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </li>        
        <!-- <li class="settings <?php echo $settings; ?>"><a href="<?php //echo url('settings'); ?>">Settings</a></li>         -->
    </ul>
    <div class="clear"></div>
</div><!-- #top_navigation -->