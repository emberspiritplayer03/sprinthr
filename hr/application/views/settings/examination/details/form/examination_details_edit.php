<script>
function disableTextBox(obj_txtboxlist_id,obj_default_id,checkbox_id){	
	if($("#" + checkbox_id).is(':checked')){		
		$("#" + obj_txtboxlist_id).hide();
		$("#" + obj_default_id).show();
	}else{		
		$("#" + obj_txtboxlist_id).show();
		$("#" + obj_default_id).hide();
	}
}
$(function() {
	$("#date_created").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
	$("#examination_details_form1").validationEngine({scroll:false});
	$('#examination_details_form1').ajaxForm({
		success:function(o) {
			if(o==1) {
				dialogOkBox('Successfully Updated',{});
				$("#examination_details_edit_form_wrapper").hide();
				$("#examination_details_table_wrapper").show();
				loadExaminationDetailsSettings(<?php echo $details->id ?>);
				
				
			}else {
				dialogOkBox(o,{});	
			}		
		},
		beforeSubmit:function() {
			showLoadingDialog('Saving...');	
		}
	});
	
	var edit_emp_pos = new $.TextboxList('#edit_emp_pos', {unique: true,plugins: {
			autocomplete: {
				minLength: 1,
				onlyFromValues: true,
				queryRemote: true,
				remote: {url: base_url + 'settings/ajax_get_positions_autocomplete'}
			
			}
		}});
	
		<?php
		 	//Positions		
			if(!empty($ejobs_array)) {				
				foreach($ejobs_array as $p){
					$pos = G_Job_Finder::findById($p);
					if($pos){			
		?>
			edit_emp_pos.add('Entry','<?php echo Utilities::encrypt($pos->getId()); ?>', '<?php echo $pos->getTitle(); ?>');			
		<?php
					}
				}			
			}
		 ?>

});
</script>
<div id="examination_details_form" style="display:none;" class="section_container">
<div class="employee_form" id="form_main">
<form name="examination_details_form1" id="examination_details_form1" method="post" action="<?php echo url('settings/_update_examination_details'); ?>">
<input type="hidden" name="examination_id" value="<?php echo Utilities::encrypt($details->id); ?>" />
<input type="hidden" name="company_structure_id" value="<?php echo $company_structure_id; ?>" />
<div id="form_default">
  <table width="100%">
    <tr>
      <td class="field_label">Exam Title:</td>
      <td><input class="validate[required]" type="text" name="title" id="title" value="<?php echo $details->title; ?>"  /></td>
    </tr>
    <tr>
      <td class="field_label">Apply to job(s):</td>
      <td>
      	<div id="edit_txt_positions">
      		<input type="text" name="emp_pos" id="edit_emp_pos" />
      	</div>
      	<div id="edit_txt_hidden_positions" style="display:none;">
         	<input type="text" name="dummy_pos" id="dummy_pos" disabled="disabled" style="width:290px" value="Apply to all jobs" />
         </div>
         <label class="checkbox">         	
         	<input id="edit_apply_to_all_jobs" <?php echo $checked; ?> value="Yes" name="apply_to_all_jobs" type="checkbox" onclick="javascript:disableTextBox('edit_txt_positions','edit_txt_hidden_positions','edit_apply_to_all_jobs');" />Apply to all jobs
        	</label>        
      </td>
    </tr>
    <tr>
      <td class="field_label">Description:</td>
      <td><textarea name="description" id="description"><?php echo $details->description; ?></textarea></td>
    </tr>
    <tr>
      <td class="field_label">Passing Percentage:</td>
      <td>
      	<div class="input-append">
          <input type="text" class="validate[required,custom[integer]] input-mini" name="passing_percentage" id="passing_percentage" value="<?php echo $details->passing_percentage; ?>"  />
          <span class="add-on" style="height:17px;">%</span>
        </div>
      </td>
    </tr>
    <tr>
      <td class="field_label">Duration:</td>
      <td>
      	<input type="text" placeholder="Hours" value="<?php echo $d['d']; ?>" name="days" class="validate[required,custom[integer]] text input-mini" id="days" />&nbsp;<input type="text" placeholder="Minutes" value="<?php echo $d['h']; ?>" name="hours" class="validate[required,custom[integer]] text input-mini" id="hours" />&nbsp;<input type="text" placeholder="Seconds" value="<?php echo $d['m']; ?>" name="minutes" class="validate[required,custom[integer]] text input-mini" id="minutes" />
      </td>
    </tr>    
    <tr>
      <td class="field_label">Created by:</td>
      <td><input type="text" class="validate[required]" name="created_by" id="created_by" value="<?php echo $details->created_by; ?>"  /></td>
    </tr>
    <tr>
      <td class="field_label">Date Created:</td>
      <td><input type="text" class="validate[required]" name="date_created" id="date_created" value="<?php echo $details->date_created; ?>"  /></td>
    </tr>
  </table>
 </div>
 <div id="form_default" class="form_action_section">
  <table width="100%">
    <tr>checked
      <td class="field_label">&nbsp;</td>
      <td><input class="blue_button" name="button" type="submit" id="button" value="Update" /> 
        <a href="javascript:void(0);" onclick="javascript:loadExaminationDetailsTable();">Cancel</a>
        <a onclick="javascript:deleteExam('<?php echo Utilities::encrypt($details->id); ?>');" href="javascript:void(0);" class="delete_link red float-right"><span class="delete"></span>Delete Examination</a>
        </td>
    </tr>
  </table>
 </div>
</form>
</div>
</div>
<script>
<?php if($checked){ ?>
	disableTextBox('edit_txt_positions','edit_txt_hidden_positions','edit_apply_to_all_jobs');
<?php } ?>
</script>
