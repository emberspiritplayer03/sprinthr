<div id="applicant_summary_table" class="employee_summaryholder">    	
    <div class="employee_profile_photo" id="photo_frame_personal_wrapper">
        <div id="photo_frame_wrapper">
        <a onclick="javascript:loadPhotoDialog();" href="javascript:void(0);" class="action_change_photo">Change Picture</a>
        <img onclick="javascript:loadPhotoDialog();" src="<?php echo $filename;?>?<?php echo $filemtime; ?>" width="140" />        
        </div>
    </div>
    <div class="employeesummary_details">
        <div id="formwrap" class="employee_form_summary">
            <div id="form_main" class="inner_form">
                <div id="form_default">
                    <div class="action_holder action_holder_right">
                        <div class="dropright btn-group pull-right" id="dropholder"><a href="javascript:void(0);" class="gray_button dropbutton"><span><span class="dark_gear"></span></span></a>
                            <ul class="dropdown-menu"><li><a href="javascript:void(0);" onclick="javascript:hideApplicantSummary()"><i class="icon-chevron-up"></i> Hide</a></li></ul>
                        </div>
                    </div>
                    <h3 class="section_title">Summary Applicant Information</h3>                    
                    <table>
                      <tr>
                        <td class="field_label">Date Applied:</td>
                        <td>
                        <?php 
                        	if($applicant_details['applied_date_time'] != '') {
                        		echo Date::convertDateIntIntoDateString($applicant_details['applied_date_time']);	
                        	}
                        ?>
                        
                        </td>
                      </tr>
                      <tr>
                        <td class="field_label">Name:</td>
                        <td><div id="employee_name_wrapper"><strong><?php echo ucfirst($applicant_details['lastname']) . ', ' . ucfirst($applicant_details['firstname']). ' '. substr	(ucfirst($applicant_details['middlename']),0,1).'.'; ?></strong></div></td>
                      </tr>
                      <tr>
                        <td class="field_label">Applied Position: </td>
                        <td><?php echo $applicant_details['job_name']; ?></td>
                      </tr>
                      <tr>
                        <td class="field_label">Status:</td>
                        <td><?php echo $GLOBALS['hr']['application_status'][$applicant_details['application_status_id']]; ?></td>
                      </tr>
                      <?php if($requirements!='') { ?>
                      <tr>
                        <td class="field_label">Requirements:</td>
                        <td><?php echo $requirements; ?></td>
                      </tr>
                      <?php } ?>
                        <?php $hired_date = ($applicant_details['hired_date']=='0000-00-00' || $applicant_details['hired_date']=='') ? '' : $applicant_details['hired_date'];
                         ?>
                      <?php if($hired_date!='') { ?>
                        <tr>
                        <td class="field_label">Hired Date:</td>
                        <td><?php echo Date::convertDateIntIntoDateString($hired_date); ?></td>
                      </tr>
                     <?php  } ?>
                      <?php if($applicant_details['employee_id']!=0) { 
                        $hash = Utilities::createHash($applicant_details['employee_id']);
                        $employee_id = Utilities::encrypt($applicant_details['employee_id']);
                      ?>
                        <!-- <tr>
                        <td class="field_label">&nbsp;</td>
                        <td><a class="btn btn-small" href="<?php echo url('employee/profile?eid='.$employee_id.'&hash='.$hash.'#personal_details'); ?>">View Employee Profile</a></td>
                      	</tr>-->
                     <?php  } ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>

