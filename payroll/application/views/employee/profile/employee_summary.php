<div class="employee_summaryholder">
	<div id="photo_frame_wrapper" class="employee_profile_photo"><img onclick="javascript:loadPhotoDialog();" src="<?php echo $filename;?>?<?php echo $filemtime; ?>" width="140" alt="Profile Photo"  /><!--<a class="action_change_photo" href="javascript:void(0);" onClick="javascript:loadPhotoDialog();">Change Picture</a>--></div>
	<div class="employeesummary_details">
    	<div id="formwrap" class="employee_form_summary">
            <div id="form_main" class="inner_form">
                <div id="form_default">
                <div class="action_holder action_holder_right">
                    <div id="dropholder" class="dropright"><a class="gray_button dropbutton" href="javascript:void(0);"><span><span class="dark_gear"></span></span></a>
                    	<div class="dropcontent hide_option" style="display:none;">
                        	<ul><li><a href="#">Hide</a></li></ul>
                        </div>
                    </div>
                </div><!-- .action_holder -->
                <h3 class="section_title">Summary Employee Information</h3>
                <table>
                  <tr>
                    <td class="field_label">Employee Code:</td>
                    <td><?php echo $employee_details['employee_code']; ?></td>
                  </tr>
                  <tr>
                    <td class="field_label">Name:</td>
                    <td class="bold"><div id="employee_name_wrapper"><?php echo $employee_details['salutation']; ?> <?php echo $employee_details['employee_name']; ?></div></td>
                  </tr>
                  <tr>
                    <td class="field_label">Branch: </td>
                    <td><?php echo $employee_details['branch_name']; ?></td>
                  </tr>
                  <tr>
                    <td class="field_label">Department: </td>
                    <td><?php echo $employee_details['department']; ?></td>
                  </tr>
                  <tr>
                    <td class="field_label">Position: </td>
                    <td><?php echo $employee_details['position']; ?></td>
                  </tr>
                    <?php $hired_date = ($employee_details['hired_date']=='0000-00-00' || $employee_details['hired_date']=='') ? '' : $employee_details['hired_date']; ?>
                  <?php if($hired_date!='') { ?>  
                  <tr>
                    <td class="field_label">Employment Status:</td>
                    <td><?php echo $employee_details['employment_status']; ?></td>
                  </tr>
                  <tr>
                    <td class="field_label">Hired Date:</td>          
                    <td><?php echo Date::convertDateIntIntoDateString($hired_date); ?></td>
                  </tr>
                 <?php  } ?>
                </table>
                </div><!-- #form_default -->
            </div><!-- #form_main -->
		</div><!-- #formwrap -->
    </div>
    <div class="clear"></div>
</div>