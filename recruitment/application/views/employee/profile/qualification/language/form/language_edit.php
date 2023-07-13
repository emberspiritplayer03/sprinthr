<script>
$("#language_edit_form").validationEngine({scroll:false});
$('#language_edit_form').ajaxForm({
	success:function(o) {
		if(o==1) {
			dialogOkBox('Successfully Updated',{});
			$("#language_wrapper").html('');
			loadPage("#language");
			
		}else {
			dialogOkBox(o,{});	
		}		
	},
	beforeSubmit:function() {
		showLoadingDialog('Saving...');	
	}
});
</script>
<form id="language_edit_form" name="form1" method="post" action="<?php echo url('employee/_update_language'); ?>" >
<div id="form_main" class="employee_form">
<input type="hidden" name="id" value="<?php echo $details->id ?>" />
<input type="hidden" name="employee_id" value="<?php echo Utilities::encrypt($details->employee_id); ?>" />
<div id="form_default">
  <table>
    <tr>
       <td class="field_label">Language:</td>
       <td><input type="text" class="validate[required] text-input" name="language" id="language" value="<?php echo $details->language; ?>" /></td>
    </tr>
     <tr>
       <td class="field_label">Fluency:</td>
       <td><select class="select_option" name="fluency" id="fluency">
         <option value="<?php echo $details->fluency; ?>"><?php echo $details->fluency; ?></option>
	
          <option value="Writing">Writing</option>
         
         <option value="Speaking">Speaking</option>
           <option value="Reading">Reading</option>
     </select></td>
     </tr>
     <tr>
       <td class="field_label">Competency:</td>
       <td><select class="select_option" name="competency" id="competency">
       
        <option value="<?php echo $details->competency; ?>"><?php echo $details->competency; ?></option>
          <option value="Poor">Poor</option>
           <option value="Basic">Basic</option>
           <option value="Good">Good</option>
           <option value="Mother Tongue">Mother Tongue</option>
       </select></td>
     </tr>
     <tr>
       <td class="field_label">Comments:</td>
       <td><textarea class="validate[required]" name="comments" id="comments"><?php echo $details->comments; ?></textarea></td>
     </tr>
  </table>
</div><!-- #form_default -->
<div class="form_action_section" id="form_default">
    <table>
    	<tr>
            <td class="field_label">&nbsp;</td>
            <td><a class="delete_link red float-right" href="javascript:void(0);" onclick="javascript:loadLanguageDeleteDialog('<?php echo $details->id; ?>')"><span class="delete"></span>Delete Language</a><input class="blue_button" type="submit" name="button" id="button" value="Update" />&nbsp;<a href="javascript:void(0);" onclick="javascript:loadLanguageTable();">Cancel</a></td>
    	</tr>
    </table>
</div><!-- #form_default.form_action_section -->
</div><!-- #form_main.employee_form -->
</form>
