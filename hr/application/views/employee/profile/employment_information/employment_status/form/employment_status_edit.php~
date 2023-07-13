<script>
$("#hired_date").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#terminated_date").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
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
</script>
<form id="employment_status_form" name="form1" method="post" action="<?php echo url('employee/_update_employment_status'); ?>"  style="display:none">
<div id="form_main" class="employee_form">
<input type="hidden" name="employee_id" value="<?php echo Utilities::encrypt($d[id]); ?>" />
<div id="form_default">
  <table>
    <tr>
      <td class="field_label">Branch Name:</td>
      <td><select class="validate[required] select_option" name="branch_id" id="branch_id">
        <option value="">Select Branch</option>       
        <?php foreach($branch as $key=>$value){  ?>
         <?php $selected = ($d['branch_id']==$value->id)? "selected='selected'" : ''; ?>
        <option <?php echo $selected; ?> value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
        <?php } ?>
      </select></td>
    </tr>
    <tr>
      <td class="field_label">Department / Team:</td>
      <td>
       <select class="validate[required] select_option" name="department_id" id="department_id">
        <option value="">Select Branch / Team</option>
        
        <?php foreach($department as $key=>$value){  ?>
          <?php $selected = ($d['department_id']==$value->id)? "selected='selected'" : ''; ?>
        <option <?php echo $selected; ?> value="<?php echo $value->id; ?>"><?php echo $value->title; ?></option>
        <?php } ?>
      </select>
      </td>
    </tr>
</table>
</div><!-- #form_default -->
<div class="form_separator"></div>
<div id="form_default">
<table>
    <tr>
      <td class="field_label">Position:</td>
      <td>
        <select class="validate[required] select_option" name="job_id" id="job_id" onchange="javascript:loadJobDutiesDescriptionStatus();">
        <option value="">Select Position</option>
        
        <?php foreach($job as $key=>$value){  ?>
         <?php $selected = ($d['job_id']==$value->id)? "selected='selected'" : ''; ?>
        <option <?php echo $selected; ?> value="<?php echo $value->id; ?>"><?php echo $value->title; ?></option>
        <?php } ?>
      </select>
     </td>
    </tr>
      <tr>
      <td class="field_label">Employment Status:</td>
      <td>
      <div id="employment_status_dropdown_wrapper">
     <?php 
		 $i = count($status); 
		$c=1;
		?>
		<?php 
		//default status
		if($status_type==0) { ?>
		
		<select class="validate[required] select_option" name="employment_status_id" id="employment_status_id" onchange="javascript:checkForTermination();">
		   <?php if($employment_status=='') { ?>
			<option value="" selected="selected">-- Select Employment Status --</option>
			<?php } ?>
				<?php foreach($status as $key=>$value) {			
					 ?>
                <?php $selected = ($d['employment_status']==$value->status)? "selected='selected'" : ''; ?>     
				<option value="<?php echo $value->status;  ?>" <?php echo $selected; ?>><?php echo $value->status; ?></option>
				<?php
				$c++;
				 } ?>
             <?php $selected = ($d['employment_status']=='Terminated')? "selected='selected'" : ''; ?>         
			<option <?php echo $selected; ?> value="0" >Terminated</option>

		</select>
		<?php } ?>
		
		<?php 
		//status by position 
		if($status_type==1) { ?>
		
		<select class="validate[required] select_option" name="employment_status_id" id="employment_status_id" onchange="javascript:checkForTermination();">
			<?php if($employment_status=='') { ?>
			<option value="" selected="selected">-- Select Employment Status --</option>
			<?php }else { ?>
			<option value="<?php echo $employment_status; ?>" selected="selected"><?php echo $employment_status; ?></option>
			<?php } ?>
				<?php foreach($status as $key=>$value) {
				$selected = ($c == $i)? 'selected' : '';	
					 ?>
				<option value="<?php echo $value->employment_status;  ?>"><?php echo $value->employment_status; ?></option>
				<?php
				$c++;
				 } ?>
			<option value="0" >Terminated</option>
		
		</select>
		<?php } ?>
			 
	 </div>
      </td>
     
    </tr>
     <tr>
      <td class="field_label">Employee Status:</td>
      <td>
      	<div id="job_description_label">
      		<select class="select_option" name="employee_status_id" id="employee_status_id" onchange="javascript:checkForTerminationEmployeeStatus(<?php echo G_Settings_Employee_Status::TERMINATED; ?>);">
      			<?php foreach($employee_status as $es){ ?>
      				<option <?php echo($employee_status_id == $es->getId() ? 'selected="selected"' : ''); ?> value=<?php echo $es->getId(); ?>><?php echo ucfirst($es->getName()); ?></option>
      			<?php } ?>
      		</select>
      	</div>
      </td>
    </tr>
    <tr>
      <td class="field_label">Job Description:</td>
      <td><div id="job_description_label"><?php echo $d['job_description']; ?></div></td>
    </tr>
    <tr>
      <td class="field_label">Job Duties:</td>
      <td><div id="job_duties_label"><?php echo $d['job_duties']; ?></div></td>
    </tr>
</table>
</div><!-- #form_default -->
<div class="form_separator"></div>
<div id="form_default">
<table>
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
	<?php if($employment_status!='Terminated') { 
		$style ='display:none';
	}
	?>
    <tr id="termination_date_wrapper" style="<?php echo $style; ?>">
      <td class="field_label">Termination Date</td>
       <?php $terminated_date = ($d['terminated_date']=='0000-00-00')? '': $d['terminated_date']; ?>
      <td><input class="validate[required] text-input" type="text" name="terminated_date" id="terminated_date" value="<?php echo $terminated_date; ?>" /></td>
    </tr>
    <tr id="termination_memo_wrapper" style="<?php echo $style; ?>">
      <td class="field_label">Reason of Termination</td>
      <td><textarea name="memo" id="memo" cols="45" rows="5"></textarea></td>
    </tr>
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
<?php if($employee_status_id == G_Settings_Employee_Status::TERMINATED){ ?>
	<script>
	checkForTerminationEmployeeStatus(<?php echo G_Settings_Employee_Status::TERMINATED; ?>);
	</script>
<?php } ?>
