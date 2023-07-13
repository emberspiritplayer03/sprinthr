<div id="sidebar" class="sidebar_left">
    <ul class="ulmenu">
        <h2>Applicant Profile</h2>
        <li class="selected">
          <div class="tabtitle">
          <a id="personal_information_tab"  style="cursor:pointer"  >Personal Information <!--<img id="personal_information_min_button" class="subdropicon" src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/submenu_down_icon.png" /><img id="personal_information_max_button" class="subdropicon" src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/submenu_left_icon.png" style="display:none" />--></a>
          </div>
          <ul id="personal_information_submenu" class="ulsubmenu">
               <li id="application_history_nav" class="left_nav"><a href="#application_history" onclick="javascript:hashClick('#application_history');">Application History</a></li>
              <li id="personal_details_nav" class="left_nav"><a href="#personal_details" onclick="javascript:hashClick('#personal_details');">Personal Details</a></li>
              <li id="contact_details_nav" class="left_nav"><a href="#contact_details" onclick="javascript:hashClick('#contact_details');">Contact Details</a></li>
              <li id="requirements_nav" class="left_nav"><a href="#requirements" onclick="javascript:hashClick('#requirements');">Requirements</a></li>
              <li id="examination_nav" class="left_nav"><a href="#examination" onclick="javascript:hashClick('#examination');">Examination</a></li>
              <li id="attachment_nav" class="left_nav"><a href="#attachment" onclick="javascript:hashClick('#attachment');">Attachment</a></li>
          </ul>
        </li>
       
        <li class="selected">
          <div class="tabtitle">
          <a id="personal_information_tab"  style="cursor:pointer"  >Qualification<!--<img id="personal_information_min_button" class="subdropicon" src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/submenu_down_icon.png" /><img id="personal_information_max_button" class="subdropicon" src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/submenu_left_icon.png" style="display:none" />--></a>
          </div>
          <ul id="personal_information_submenu" class="ulsubmenu">
               <li id="work_experience_nav" class="left_nav"><a href="#work_experience" onclick="javascript:hashClick('#work_experience');">Work Experience</a></li>
              <li id="education_nav" class="left_nav"><a href="#education" onclick="javascript:hashClick('#education');">Education</a></li>
              <li id="training_nav" class="left_nav"><a href="#training" onclick="javascript:hashClick('#training');">Training</a></li>
              <li id="skills_nav" class="left_nav"><a href="#skills" onclick="javascript:hashClick('#skills');">Skills</a></li>
              <li id="license_nav" class="left_nav"><a href="#license" onclick="javascript:hashClick('#license');">License</a></li>
              <li id="language_nav" class="left_nav"><a href="#language" onclick="javascript:hashClick('#language');">Language</a></li>
          </ul>
        </li>
        <h2>Applicant Event</h2>
        <li class="selected">
            <div class="tabtitle">
            <a id="personal_information_tab"  style="cursor:pointer"  >Action <!--<img id="personal_information_min_button" class="subdropicon" src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/submenu_down_icon.png" /><img id="personal_information_max_button" class="subdropicon" src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/submenu_left_icon.png" style="display:none" />--></a>
            </div>    
            <ul id="personal_information_submenu" class="ulsubmenu">
            <?php if($_GET['status']==APPLICATION_SUBMITTED) { ?>
               <li id="interview_nav" class="left_nav"><a href="#interview" onclick="javascript:hashClick('#interview');">Interview</a></li>
              <li id="offer_job_nav" class="left_nav"><a href="#offer_job" onclick="javascript:hashClick('#offer_job');">Offer a Job</a></li>
              <li id="rejected_nav" class="left_nav"><a href="#rejected" onclick="javascript:hashClick('#rejected');">Rejected</a></li>
              <li id="hired_nav" class="left_nav"><a href="#hired" onclick="javascript:hashClick('#hired');">Hired</a></li>
            <?php } ?>
            <?php if($_GET['status']==INTERVIEW) { ?>
              <li id="interview_nav" class="left_nav"><a href="#interview" onclick="javascript:hashClick('#interview');">Interview</a></li>
              <li id="offer_job_nav" class="left_nav"><a href="#offer_job" onclick="javascript:hashClick('#offer_job');">Offer a Job</a></li>
              <li id="rejected_nav" class="left_nav"><a href="#rejected" onclick="javascript:hashClick('#rejected');">Rejected</a></li>
              <li id="hired_nav" class="left_nav"><a href="#hired" onclick="javascript:hashClick('#hired');">Hired</a></li>
            <?php } ?>
            
            <?php if($_GET['status']==JOB_OFFERED) { ?>
               <li id="declined_offer_nav" class="left_nav"><a href="#declined_offer" onclick="javascript:hashClick('#declined_offer');">Decline Offer</a></li>
              <li id="rejected_nav" class="left_nav"><a href="#rejected" onclick="javascript:hashClick('#rejected');">Reject</a></li>
              <li id="hired_nav" class="left_nav"><a href="#hired" onclick="javascript:hashClick('#hired');">Hired</a></li>
            <?php } ?>        
            </ul>
        </li>       
    </ul>
</div>