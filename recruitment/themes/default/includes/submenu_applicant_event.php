<div id="submenu">
    <ul class="ulmenu">
        <h2>Applicant Event</h2>
        <li class="selected">
        	<div class="tabtitle">
            <a id="personal_information_tab"  style="cursor:pointer"  >Action <!--<img id="personal_information_min_button" class="subdropicon" src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/submenu_down_icon.png" /><img id="personal_information_max_button" class="subdropicon" src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/submenu_left_icon.png" style="display:none" />--></a>
            </div>        
            <ul id="personal_information_submenu" class="ulsubmenu">
				<?php if($_GET['status']=='application_submitted') { ?>
                <li id="application_history_nav" class="left_nav"><a href="#application_history" onclick="javascript:hashClick('#application_history');">Interview</a></li>
                <li id="personal_details_nav" class="left_nav"><a href="#personal_details" onclick="javascript:hashClick('#personal_details');">Offer a Job</a></li>
                <li id="requirements_nav" class="left_nav"><a href="#requirements" onclick="javascript:hashClick('#requirements');">Rejected</a></li>
                <li id="examination_nav" class="left_nav"><a href="#examination" onclick="javascript:hashClick('#examination');">Hired</a></li>
                <?php } ?>
                <?php if($_GET['status']=='interview') { ?>
                <li id="application_history_nav" class="left_nav"><a href="#application_history" onclick="javascript:hashClick('#application_history');">Interview</a></li>
                <li id="personal_details_nav" class="left_nav"><a href="#personal_details" onclick="javascript:hashClick('#personal_details');">Offer a Job</a></li>
                <li id="requirements_nav" class="left_nav"><a href="#requirements" onclick="javascript:hashClick('#requirements');">Rejected</a></li>
                <li id="examination_nav" class="left_nav"><a href="#examination" onclick="javascript:hashClick('#examination');">Hired</a></li>
                <?php } ?>
                
                <?php if($_GET['status']=='offer_job') { ?>
                <li id="application_history_nav" class="left_nav"><a href="#application_history" onclick="javascript:hashClick('#application_history');">Decline Offer</a></li>
                <li id="personal_details_nav" class="left_nav"><a href="#personal_details" onclick="javascript:hashClick('#personal_details');">Reject</a></li>
                <li id="requirements_nav" class="left_nav"><a href="#requirements" onclick="javascript:hashClick('#requirements');">Hired</a></li>
                <?php } ?>
            </ul>
        </li>
    </ul>
</div>