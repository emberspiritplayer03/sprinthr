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
<form id="employment_status_form" name="form1" method="post" action="<?php echo url('employee/_update_employment_status'); ?>" style="display:none">
<input type="hidden" name="employee_id" value="<?php echo Utilities::encrypt($d[id]); ?>" />


  <table class="table_form" width="418" border="0" cellpadding="3" cellspacing="3">
    <tr>
      <td width="156" align="right" valign="top">Branch Name:</td>
      <td width="241" valign="top"><select name="branch_id" id="branch_id">
        <option value="<?php echo $d['branch_id']; ?>"><?php echo $d['branch_name']; ?></option>
        
        <?php foreach($branch as $key=>$value){  ?>
        <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
        <?php } ?>
      </select></td>
    </tr>
    <tr>
      <td align="right" valign="top">Department / Team:</td>
      <td valign="top">
       <select name="department_id" id="department_id">
        <option value="<?php echo $d['department_id']; ?>"><?php echo $d['department']; ?></option>
        
        <?php foreach($department as $key=>$value){  ?>
        <option value="<?php echo $value->id; ?>"><?php echo $value->title; ?></option>
        <?php } ?>
      </select>
      </td>
    </tr>
    <tr>
      <td align="right" valign="top">&nbsp;</td>
      <td valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td align="right" valign="top">Position:</td>
      <td valign="top">
        <select name="job_id" id="job_id" onchange="javascript:loadJobDutiesDescriptionStatus();">
        <option value="<?php echo $d['job_id']; ?>"><?php echo $d['position']; ?></option>
        
        <?php foreach($job as $key=>$value){  ?>
        <option value="<?php echo $value->id; ?>"><?php echo $value->title; ?></option>
        <?php } ?>
      </select>
     </td>
    </tr>
      <tr>
      <td align="right" valign="top">Employment Status:</td>
      <td valign="top">
      <div id="employment_status_dropdown_wrapper">
     <?php 
		 $i = count($status); 
		$c=1;
		?>
		<?php if($status_type==0) { ?>
		
		<select class="validate[required] select_option" name="employment_status_id" id="employment_status_id" onchange="javascript:checkForTermination();">
		   <?php if($employment_status=='') { ?>
			<option value="" selected="selected">-- Select Employment Status --</option>
			<?php }else { ?>
			<option value="<?php echo $employment_status; ?>" selected="selected"><?php echo $employment_status; ?></option>
			<?php } ?>
				<?php foreach($status as $key=>$value) {
				$selected = ($c == $i)? 'selected' : '';	
					 ?>
				<option value="<?php echo $value->status;  ?>"><?php echo $value->status; ?></option>
				<?php
				$c++;
				 } ?>
			<option value="0" >Terminated</option>

		</select>
		<?php } ?>
		
		<?php if($status_type==1) { ?>
		
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
      <td align="right" valign="top">Job Description:</td>
      <td valign="top"><div id="job_description_label"><?php echo $d['job_description']; ?></div></td>
    </tr>
    <tr>
      <td align="right" valign="top">Job Duties:</td>
      <td valign="top"><div id="job_duties_label"><?php echo $d['job_duties']; ?></div></td>
    </tr>
    <tr>
      <td align="right" valign="top">&nbsp;</td>
      <td valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td align="right" valign="top">EEO Category:</td>
      <td valign="top">
       <select name="job_category_id" id="job_category_id">
        <option value="<?php echo $d['eeo_job_category_id']; ?>"><?php echo $d['job_category_name']; ?></option>
        
        <?php foreach($job_category as $key=>$value){  ?>
        <option value="<?php echo $value->id; ?>"><?php echo $value->category_name; ?></option>
        <?php } ?>
      </select>
      </td>
    </tr>
    <tr>
      <td align="right" valign="top">&nbsp;</td>
      <td valign="top">&nbsp;</td>
    </tr>
  
    <tr>
      <td colspan="2" align="right" valign="top"></td>
    </tr>
    <tr>
      <td align="right" valign="top">Hired Date:</td>
      <?php $hired_date = ($d['hired_date']=='0000-00-00')? '': $d['hired_date']; ?>
      <td valign="top"><input class="validate[required]" type="text" name="hired_date" id="hired_date" value="<?php echo $hired_date; ?>" /></td>
    </tr>
	<?php if($employment_status!='Terminated') { 
		$style ='display:none';
	}
	?>
    <tr id="termination_date_wrapper" style="<?php echo $style;  ?>">
      <td align="right" valign="top">Termination Date</td>
       <?php $terminated_date = ($d['terminated_date']=='0000-00-00')? '': $d['terminated_date']; ?>
      <td valign="top"><input class="validate[required]" type="text" name="terminated_date" id="terminated_date" value="<?php echo $terminated_date; ?>" /></td>
    </tr>
	
    <tr>
      <td align="right" valign="top">&nbsp;</td>
      <td valign="top"><input type="submit" name="button" id="button" value="Update" /> 
        <a href="javascript:void(0);" onclick="javascript:loadEmploymentStatusTable();">Cancel</a></td>
    </tr>
  </table>
</form>
