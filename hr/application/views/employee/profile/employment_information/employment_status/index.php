<h2 class="field_title"><?php echo $title_employment_status; ?></h2>
<?php  include 'form/employment_status_edit.php'; ?>
<div id="employment_status_table_wrapper">
<div id="form_main" class="employee_form">
<div id="form_default">
<table>
  <tr>
    <td class="field_label">Branch Name:</td>
    <td><?php echo $d['branch_name']; ?></td>
  </tr>
  <tr>
    <td class="field_label">Department:</td>
    <td><div id="salutation_label"><?php echo  ucfirst($d['department']); ?></div></td>
  </tr>
  <tr>
    <td class="field_label">Section:</td>
    <td><div id="salutation_label"><?php echo  ucfirst($section_name); ?></div></td>
  </tr>
</table>
</div><!-- #form_default -->
<div class="form_separator"></div>
<div id="form_default">
<table>
  <tr>
    <td class="field_label">Position:</td>
    <td><div id="firstname_label"><?php echo  ucfirst($d['position']); ?></div></td>
  </tr>
  <tr>
    <td class="field_label">Employment Status:</td>
    <td><?php echo ucfirst($d['employment_status']); ?></td>
  </tr>
  <tr>
    <td class="field_label">Employee Status:</td>
    <td>	
    	<?php 
    		if($estatus_id == G_Settings_Employee_Status::TERMINATED){
    			echo '<span class="red">' . ucfirst($estatus_title) . '</span>';
    		}else{
    			echo ucfirst($estatus_title); 
    		}
    		
    	?>
    </td>
  </tr>
  <!-- <tr>
    <td class="field_label">Job Description:</td>
    <td><div id="lastname_label"><?php //echo  ucfirst($d['job_description']); ?></div></td>
  </tr>
  <tr>
    <td class="field_label">Job Duties:</td>
    <td><div id="middlename_label"><?php //echo  ucfirst($d['job_duties']); ?></div></td>
  </tr> -->
</table>
</div><!-- #form_default -->
<div class="form_separator"></div>
<div id="form_default">
<table>
  <tr>
    <td class="field_label">EEO Category:</td>
    <td><?php echo  ucfirst($d['job_category_name']); ?></td>
  </tr>
  <tr>
    <td class="field_label">Hired Date: </td>
    <?php  $hired_date = ($d['hired_date']=='0000-00-00')? '': Date::convertDateIntIntoDateString($d['hired_date']); ?>
    <td><?php echo $hired_date; ?></td>
  </tr>
   <?php $terminated_date = ($d['terminated_date']=='0000-00-00')? '': Date::convertDateIntIntoDateString($d['terminated_date']); ?>
   
   <?php 
   	if($estatus_id == G_Settings_Employee_Status::TERMINATED || $estatus_id == G_Settings_Employee_Status::ENDO || $estatus_id == G_Settings_Employee_Status::RESIGNED || $estatus_id == G_Settings_Employee_Status::INACTIVE || $estatus_id == G_Settings_Employee_Status::AWOL) {
			if($estatus_id == G_Settings_Employee_Status::TERMINATED){ 
				$caption = "Terminated Date: ";
				$e_date  = $terminated_date;
			}else if($estatus_id == G_Settings_Employee_Status::ENDO){
				$caption = "End of Contract: ";
				$e_date  = $endo_date;
			}else if($estatus_id == G_Settings_Employee_Status::RESIGNED){
				$caption = "Resignation Date: ";
				$e_date  = $resignation_date;
			}else if($estatus_id == G_Settings_Employee_Status::INACTIVE){
        $caption = "Inactive Date: ";
        $e_date  = $inactive_date;
      }
      else if($estatus_id == G_Settings_Employee_Status::AWOL){
        $caption = "AWOL Date: ";
        $e_date  = $inactive_date;
      }
   ?>
		  <tr>
		    <td class="field_label"><?php echo $caption ?></td>
		    <td><?php echo $e_date; ?></td>
		  </tr>
		 <!-- <tr>
		    <td class="field_label">Reason:</td>
		    <td><?php //echo $terminated_memo; ?></td>
		  </tr>-->
		
  <?php } ?>
</table>
</div><!-- #form_default -->
<div class="form_action_section" id="form_default">
<?php if($can_manage) { ?>
    <table>
    	<tr>
            <td class="field_label">&nbsp;</td>
            <td><?php echo $btn_edit_details; ?></td>
    	</tr>
    </table>
    <!--<div style="text-align: right">
        <button class="red_button" onmouseup="javascript:resignEmployee('<?php echo $employee_id;?>')" type="button">Resign</button>
        <button class="red_button" onmouseup="javascript:terminateEmployee('<?php echo $employee_id;?>')" type="button">Terminate</button>
        <button class="red_button" onmouseup="javascript:endoEmployee('<?php echo $employee_id;?>')" type="button">Endo</button>
    </div>-->
<?php } ?>
</div><!-- #form_default.form_action_section -->
</div><!-- #form_main.employee_form -->
</div>