<script>
$("#hired_date").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#terminated_date").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#resignation_date").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#endo_date").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#inactive_date").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#active_date").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#awol_date").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#employment_status_form").validationEngine({scroll:false});
$('#employment_status_form').ajaxForm({
	success:function(o) {
		if(o==1) {
			loadPhoto();
			dialogOkBox('Successfully Updated',{});
			$("#employment_status_wrapper").html('');
			$("#job_history_wrapper").html('');
			$("#subdivision_history_wrapper").html('');
			$("#compensation_wrapper").html('');
			$("#compensation_history_wrapper").html('');
			$("#memo_notes_wrapper").html('');
			loadEmployeeSummary();
			loadPage("#employment_status");
			
		}else {
			dialogOkBox(o,{});

		}		
	},
	beforeSubmit:function() {
		showLoadingDialog('Saving...');	
	}
});

 $('#estatus_attachment_wrapper').hide();

</script>
<form id="employment_status_form" name="form1" method="post" action="<?php echo url('employee/_update_employment_status'); ?>"  style="display:none">
<div id="form_main" class="employee_form">
<input type="hidden" id="validate_estatus_endo_id" value="<?php echo G_Settings_Employee_Status::ENDO; ?>" />
<input type="hidden" id="validate_estatus_resigned_id" value="<?php echo G_Settings_Employee_Status::RESIGNED; ?>" />
<input type="hidden" id="validate_estatus_terminated_id" value="<?php echo G_Settings_Employee_Status::TERMINATED; ?>" />
<input type="hidden" id="validate_estatus_inactive_id" value="<?php echo G_Settings_Employee_Status::INACTIVE; ?>" />
<input type="hidden" id="validate_estatus_active_id" value="<?php echo G_Settings_Employee_Status::ACTIVE; ?>" />
<input type="hidden" id="validate_estatus_awol_id" value="<?php echo G_Settings_Employee_Status::AWOL; ?>" />
<input type="hidden" name="employee_id" value="<?php echo Utilities::encrypt($d[id]); ?>" />
<input type="hidden" name="current_employee_status_id" value="<?php echo $employee_status_id; ?>" />
<div id="form_default">
<table>
    <tr>
      <td class="field_label">Section:</td>
      <td>
        <div id="job_description_label">
          <select class="select_option" name="section_id" id="section_id">
            <?php foreach($sections as $s){ ?>
              <option <?php echo $section_name == $s->getTitle() ? 'selected="selected"' : ''; ?>  value=<?php echo $s->getId(); ?>><?php echo ucfirst($s->getTitle()); ?></option>
            <?php } ?>
          </select>
        </div>
      </td>
    </tr>
    <tr>
      <td class="field_label">Employee Status:</td>
      <td>
        <div id="job_description_label">
          <select class="select_option employee_status_id" name="employee_status_id" id="employee_status_id" onchange="javascript:validateEmployeeStatus(this.value);">
            <?php foreach($employee_status as $es){ ?>
              <option <?php echo($employee_status_id == $es->getId() ? 'selected="selected"' : ''); ?> value=<?php echo $es->getId(); ?>><?php echo ucfirst($es->getName()); ?></option>
            <?php } ?>
          </select>
        </div>
      </td>
    </tr>
    <tr>
      <td class="field_label">EEO Category:</td>
      <td>
       <select class="select_option" name="job_category_id" id="job_category_id">
        <option value="<?php echo $d['eeo_job_category_id']; ?>"><?php echo $d['job_category_name']; ?></option>
        
        <?php foreach($job_category as $key=>$value){  ?>
        <option value="<?php echo $value->id; ?>"><?php echo $value->category_name; ?></option>
        <?php } ?>
      </select>
      </td>
    </tr>
    <tr>
      <td class="field_label">Hired Date:</td>
      <?php $hired_date = ($d['hired_date']=='0000-00-00')? '': $d['hired_date']; ?>
      <td><input class="validate[required] text-input" type="text" name="hired_date" id="hired_date" value="<?php echo $hired_date; ?>" /></td>
    </tr>
    <?php
		include_once("_termination_date.php");
		include_once("_endo_date.php");
		include_once("_resigned_date.php");
    include_once("_inactive_date.php");
    include_once("_active_date.php");
      include_once("_awol_date.php");
	?>
    
    <tr id="estatus_attachment_wrapper" style="<?php echo $style; ?>">
      <td class="field_label">Attach File</td>      
      <td>
        <input type="file" name="attached_file" id="attached_file" />
      </td>
    </tr>
    
    <!--<tr id="termination_memo_wrapper" style="<?php //echo $style; ?>">
      <td class="field_label">Reason of Termination</td>
      <td><textarea name="memo" id="memo" cols="45" rows="5"></textarea></td>
    </tr>-->
  </table>
</div><!-- #form_default -->
<div class="form_action_section" id="form_default">
    <table>
    	<tr>
            <td class="field_label">&nbsp;</td>
            <td><input class="blue_button" type="submit" name="button" id="button" value="Update" /> 
        		<a href="javascript:void(0);" onclick="javascript:loadEmploymentStatusTable();">Cancel</a></td>
    	</tr>
    </table>
</div><!-- #form_default.form_action_section -->
</div><!-- #form_main.employee_form -->
</form>
<?php //if($employee_status_id == G_Settings_Employee_Status::TERMINATED){ ?>
	<script>
	//validateEmployeeStatus($("#employee_status_id").val());
	//checkForTerminationEmployeeStatus(<?php //echo G_Settings_Employee_Status::TERMINATED; ?>);
	</script>
<?php //} ?>
