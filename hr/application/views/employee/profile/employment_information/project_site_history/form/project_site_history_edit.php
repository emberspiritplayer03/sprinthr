<script>
$("#project_history_from").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#project_history_to").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#status_date").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#renewal_date").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#job_history_edit_form").validationEngine({scroll:false});
$('#job_history_edit_form').ajaxForm({
	success:function(o) {
		if(o==1) {
			/*dialogOkBox('Successfully Updated',{});
			$("#job_history_wrapper").html('');
			$("#employment_status_wrapper").html('');
			var hash = window.location.hash;
			loadPage(hash);*/
      loadPhoto();
      dialogOkBox('Successfully Updated',{});
      $("#employment_status_wrapper").html('');
      $("#job_history_wrapper").html('');
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
 <?php  $s_project = $selected_project_site[0]; ?>

<form id="job_history_edit_form" name="form1" method="post" action="<?php echo url('employee/_update_project'); ?>" >
<div id="form_main" class="employee_form">
<input type="hidden" name="project_site_id" value="<?php echo $s_project['id'] ?>" />
<input type="hidden" name="employee_id" value="<?php echo Utilities::encrypt($s_project['employee_id']); ?>" />
<div id="form_default">

 
  <table>

  	 <tr>
  	   <td class="field_label">Project Site:</td>
      <td><select name="site_id" id="job_id" class="validate[required] select_option" > 
       <option value="">--Select Project Site--</option>

        <?php
         foreach($project_lists as $key=>$value){  ?>
        <option <?php echo ($s_project['project_id']==$value['id']) ? 'selected="selected"' : '' ; ?> value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
        <?php } ?>
         

      </select></td>
    </tr>
    
    <tr>
      <td class="field_label">Start Date:</td>
      <td>
      <input type="text" class="validate[required] text-input" name="start_date" id="project_history_from" value="<?php echo  ucfirst($s_project['start_date']); ?>" onchange="javascript:checkif()"/>
      </td>
      <script type="text/javascript">
           function checkif(){
               var project_history_from = $('#project_history_from').val(); 
               var project_history_to   = $('#project_history_to').val();
               (project_history_from > project_history_to) ? $('#project_history_to').val('') : '';
           }
      </script>
    </tr>
    <tr>
      <td class="field_label">End Date:</td>
      <td><input type="text" class="text-input"  name="end_date" id="project_history_to" value="<?php echo $s_project['end_date']; ?>" /><br /><small style="font-size:11px;">Note : Leave it blank if current position</small></td>
    </tr>
    <!--
    <tr>
      <td class="field_label">Employee Status:</td>
      <td>
        <div id="job_description_label">
          
          <select class="select_option employee_status" name="employee_status" id="employee_status" onchange="javascript:validateEmployeeStatus(this.value);">
            <option value=""> -- Select Status -- </option>
            <?php foreach($employee_status as $es){?>
              <option <?php echo($es->getName() == $s_project['employee_status'] ? 'selected="selected"' : ''); ?> value=<?php echo ucfirst($es->getName()); ?>><?php echo ucfirst($es->getName()); ?></option>
            <?php } ?>
          </select>
        </div>
      </td>
    </tr>
  <tr>
    <td class="field_label">Status Date</td>
    <td><input class="text-input" type="text" name="status_date" id="status_date" value="<?php echo $s_project['status_date']; ?>" /></td>
  </tr>-->
  </table>
</div><!-- #form_default -->
<div class="form_action_section" id="form_default">
    <table>
    	<tr>
            <td class="field_label">&nbsp;</td>
            <td><a class="delete_link red float-right" href="javascript:void(0);" onclick="javascript:loadProjectSiteHistoryDeleteDialog('<?php echo $s_project['id']; ?>')"><span class="delete"></span>Delete Project Site History</a><input class="blue_button" type="submit" name="button" id="button" value="Update" />&nbsp;<a href="javascript:void(0);" onclick="javascript:loadProjectSiteHistoryTable();">Cancel</a></td>
    	</tr>
    </table>
</div><!-- #form_default.form_action_section -->
</div><!-- #form_main.employee_form -->
</form>
