<?php include 'form/personal_details_edit.php'; ?>
<div class="employee_summaryholder" id="personal_details_table_wrapper">     
	<div id="photo_frame_personal_edit_wrapper" class="employee_profile_photo">
    	<img  onclick="javascript:loadPhotoDialog();" src="<?php echo $filename; ?>?<?php echo $filemtime; ?>" width="140" border="1"  />
        <a class="action_change_photo" href="javascript:void(0);" onClick="javascript:loadPhotoDialog();">Change Picture</a>
   </div>
    <div class="employeesummary_details">
    <div id="form_main" class="employee_form">
    <div id="form_default">
    	<h3 class="section_title"><?php echo $title_personal_details; ?></h3>
        <table>
          <tr>
            <td class="field_label">Firstname:</td>
            <td><div id="firstname_label" class="bold"><?php echo ucfirst($details->firstname); ?></div></td>
          </tr>
          <tr>
            <td class="field_label">Lastname:</td>
            <td><div id="lastname_label" class="bold"><?php echo  ucfirst($details->lastname); ?></div></td>
          </tr>
          <tr>
            <td class="field_label">Middlename:</td>
            <td><div id="middlename_label" class="bold"><?php echo  ucfirst($details->middlename); ?></div></td>
          </tr>
          <tr>
            <td class="field_label">Extension Name:</td>
            <td><?php echo  ucfirst($details->extension_name); ?></td>
          </tr>
          <tr>
            <td class="field_label">Gender:</td>
            <td><?php echo ucfirst($details->gender); ?></td>
          </tr>
          <tr>
            <td class="field_label">Marital Status:</td>
            <td><?php echo ucfirst($details->marital_status); ?></td>
          </tr>
          <tr>
            <td class="field_label">Birthdate:</td>
              <?php 
             $birthdate = ($details->birthdate=='0000-00-00')? '' : Date::convertDateIntIntoDateString($details->birthdate); ?>
            <td><?php echo $birthdate; ?></td>
          </tr>
          <tr>
            <td class="field_label">Birth Place:</td>
            <td><?php echo $details->birth_place; ?></td>
          </tr>
          <tr>
            <td class="field_label">Address:</td>
            <td><?php echo $details->address; ?></td>
          </tr>
          <tr>
            <td class="field_label">City:</td>
            <td><?php echo $details->city; ?></td>
          </tr>
          <tr>
            <td class="field_label">State:</td>
            <td><?php echo $details->province ?></td>
          
          </tr>
          <tr>
            <td class="field_label">Zip Code:</td>
            <td><?php echo $details->zip_code; ?></td>
          </tr>
          <tr>
            <td class="field_label">Country:</td>
            <td><?php echo $details->country; ?></td>
          </tr>
        </table>
          </div><!-- #form_default -->
          <div class="form_separator"></div>
          <div id="form_default">
                <h3 class="section_title">Other Details</h3>
              <table>
          <tr>
            <td class="field_label">SSS Number:</td>
            <td><?php echo $details->sss_number; ?></td>
          </tr>
          <tr>
            <td class="field_label">Pagibig Number:</td>
            <td><?php echo $details->pagibig_number; ?></td>
          </tr>
          <tr>
            <td class="field_label">TIN:</td>
            <td><?php echo $details->tin_number; ?></td>
          </tr>
          <tr>
            <td class="field_label">Philhealth Number</td>
            <td><?php echo $details->philhealth_number; ?>&nbsp;</td>
          </tr>
          <tr>
            <td class="field_label">Date Applied:</td>
            <td><?php echo Date::convertDateIntIntoDateString($details->applied_date_time); ?></td>
          </tr>
          <tr>
            <td class="field_label">Applied Position:</td>
            <td><?php echo $job_name; ?></td>
          </tr>
          <!--<tr>
            <td class="field_label">Resume:</td>
            <td><?php //echo $details->resume_name; ?></td>
          </tr>-->
        </table>
    </div><!-- #form_default -->
    <div class="form_action_section" id="form_default">
    	<table width="100%" cellspacing="0" cellpadding="0" border="0">
        	<tbody>
            <tr>
            	<td class="field_label">&nbsp;</td>
                <td>
                <a href="#personal_details" class="edit_button" onclick="javascript:loadPersonalDetailsForm();"><strong></strong>Edit Details</a>
                </td>
            </tr>
        </tbody></table>
    </div><!-- .form_action_section -->
    </div><!-- #form_main.inner_form -->
	</div>
</div>