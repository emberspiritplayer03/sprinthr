<script>
$("#project_site_history_from").datepicker(
	{
		dateFormat:'yy-mm-dd',
		changeMonth:true,
		changeYear:true,
		showOtherMonths:true,
		onSelect	:function() {
			$("#project_site_history_to").datepicker('option',{minDate:$(this).datepicker('getDate')});
		}
	}
);
$("#project_site_history_to").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#status_date").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#renewal_date").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});

$("#project_site_history_add_form").validationEngine({scroll:false});
$('#project_site_history_add_form').ajaxForm({
	success:function(o) {
		if(o==1) {
			/*dialogOkBox('Successfully Updated',{});
			$("#job_history_wrapper").html('');
			$("#employment_status_wrapper").html('');
			var hash = window.location.hash;
			loadPage(hash);			*/
			loadPhoto();
			dialogOkBox('Successfully Updated',{});
			$("#employment_status_wrapper").html('');
      		$("#job_history_wrapper").html('');
			$("#project_site_history_wrapper").html('');
			$("#subdivision_history_wrapper").html('');
			$("#compensation_wrapper").html('');
			$("#compensation_history_wrapper").html('');
			$("#memo_notes_wrapper").html('');
			$("#project_site_history_wrapper").html('');
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
<form id="project_site_history_add_form" name="form1" method="post" action="<?php echo url('employee/_set_project_site'); ?>">
<div id="form_main" class="employee_form">
<input type="hidden" name="employee_id" value="<?php echo $employee_id; ?>" />
<div id="form_default">
  <table>
  	 <tr>
  	   <td class="field_label">Project Site:</td>
  	   <td> <select name="project_site_id" id="project_site_id" class="validate[required] select_option" >
       <option value="">--Select Project Site--</option>
        <?php foreach($projects as $key=>$value){  ?>
         <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>

        <?php } ?>
      </select></td>
    </tr>


  	 <tr>
  	   <td class="field_label">Start Date:</td>
  	   <td><input class="validate[required] text-input" type="text" name="start_date" id="project_site_history_from" value="" /></td>
    </tr>
  	 <tr>
  	   <td class="field_label">End Date:</td>
  	   <td>
  	   		<input class="text-input" type="text"  name="end_date" id="project_site_history_to" value="" /><br /><small style="font-size:11px;">Note : Leave it blank if current position</small>

  	   </td>
    </tr>
    <!--
    <tr>
      <td class="field_label">Employee Status:</td>
      <td>
        <div id="job_description_label">
          
          <select class="select_option employee_status" name="employee_status" id="employee_status" onchange="javascript:validateEmployeeStatus(this.value);">
            <option value=""> -- Select Status -- </option>
            <?php foreach($employee_status as $es){?>
              <option <?php echo($employee_status_id == $es->getId() ? 'selected="selected"' : ''); ?> value=<?php echo ucfirst($es->getName()); ?>><?php echo ucfirst($es->getName()); ?></option>
            <?php } ?>
          </select>
        </div>
      </td>
    </tr>
  <tr>
    <td class="field_label">Status Date</td>
     <?php $status_date = ($d['status_date']=='0000-00-00')? '': $d['status_date']; ?>
    <td><input class="text-input" type="text" name="status_date" id="status_date" value="<?php echo $status_date; ?>" /></td>
  </tr>-->
  </table>
</div><!-- #form_default -->
<div class="form_action_section" id="form_default">
    <table>
    	<tr>
            <td class="field_label">&nbsp;</td>
            <td><input class="blue_button" type="submit" name="button" id="button" value="Add" />&nbsp;<a href="javascript:void(0);" onclick="javascript:loadProjectSiteHistoryTable();">Cancel</a></td>
    	</tr>
    </table>
</div><!-- #form_default.form_action_section -->
</div><!-- #form_main.employee_form -->
</form>
