<div id="sidebar" class="sidebar_left">
    <ul class="ulmenu">
        <h2> Employee Profile</h2>

        <li class="selected">
            <?php if(!empty($btn_personal) || !empty($btn_contact_details) || !empty($btn_emergency_contacts) || !empty($btn_dependents) || !empty($btn_bank)) { ?>
            	<div class="tabtitle">
                	<a id="personal_information_tab"  style="cursor:pointer"  >Personal Information <!--<img id="personal_information_min_button" class="subdropicon" src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/submenu_down_icon.png" /><img id="personal_information_max_button" class="subdropicon" src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/submenu_left_icon.png" style="display:none" />--></a>
                </div>
                <ul id="personal_information_submenu" class="ulsubmenu">
                    <?php echo $btn_personal;?>
                    <?php echo $btn_contact_details;?>
                    <?php echo $btn_emergency_contacts;?>
                    <?php echo $btn_dependents;?>
                    <?php echo $btn_bank;?>           
                </ul>
            <?php } ?>
        </li>  

        <li class="selected">
            <?php if(!empty($btn_employment_status) || !empty($btn_compensation) || !empty($btn_contract) || !empty($btn_contribution) || !empty($btn_training) || !empty($btn_memo) || !empty($btn_requirements) || !empty($btn_supervisor) || !empty($btn_leave) ) { ?>
            	<div class="tabtitle">
                	<a id="employment_information_tab"  style="cursor:pointer">Employment Information <!--<img id="employment_information_min_button" class="subdropicon" src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/submenu_down_icon.png" /><img id="employment_information_max_button" class="subdropicon" src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/submenu_left_icon.png" style="display:none" />--></a>
                </div>
                <ul id="employment_information_submenu" class="ulsubmenu">
                    <?php echo $btn_employment_status; ?>
                    <?php echo $btn_compensation; ?>
                    <?php echo $btn_benefits; ?>
                    <?php echo $btn_contract; ?>
                    <?php echo $btn_contribution; ?>                    
                    <?php echo $btn_training; ?>
                    <?php echo $btn_memo; ?>
                    <?php echo $btn_requirements; ?>                    
                    <?php echo $btn_supervisor; ?>
                    <?php echo $btn_leave; ?>                    
                </ul>
            <?php } ?>
        </li>
        
        <li class="selected">
            <?php if(!empty($btn_work_experience) || !empty($btn_education) || !empty($btn_skills) || !empty($btn_language) || !empty($btn_license)) { ?>
                <div class="tabtitle">
                	<a id="qualification_tab"  style="cursor:pointer">Qualification<!--<img id="qualification_min_button" class="subdropicon" src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/submenu_down_icon.png" /><img id="qualification_max_button" class="subdropicon" src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/submenu_left_icon.png" style="display:none" />--></a>
                </div>
                <ul id="qualification_submenu" class="ulsubmenu">
                    <?php echo $btn_work_experience; ?>
                    <?php echo $btn_education; ?>
                    <?php echo $btn_skills; ?>
                    <?php echo $btn_language; ?>
                    <?php echo $btn_license; ?>
                </ul>
            <?php } ?>
        </li>

        <li class="selected">
            <?php if(!empty($btn_attachment) ) { ?>
                <div class="tabtitle">
                	<a id="schedule_tab"  style="cursor:pointer">Attachment <!--<img id="attachment_min_button" class="subdropicon" src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/submenu_down_icon.png" /><img id="attachment_max_button" class="subdropicon" src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/submenu_left_icon.png" style="display:none" />--></a>
                </div>
                <ul id="attachment_submenu" class="ulsubmenu">
                	<?php echo $btn_attachment; ?>
            	</ul>
            <?php } ?>
        </li>

    </ul>
</div>