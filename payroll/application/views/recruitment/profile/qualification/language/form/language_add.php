<script>
$("#language_add_form").validationEngine({scroll:false});
$('#language_add_form').ajaxForm({
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
<form id="language_add_form" name="form1" method="post" action="<?php echo url('recruitment/_update_language'); ?>">
<div id="form_main" class="employee_form">
<input type="hidden" name="applicant_id" value="<?php echo $applicant_id; ?>" />
<div id="form_default">
  <table>
  	 <tr>
      <td class="field_label">Language:</td>
      <td><input type="text" class="validate[required] text-input" name="language" id="language" /></td>
    </tr>
    <tr>
      <td class="field_label">Fluency:</td>
      <td><select class="validate[required] select_option" name="fluency" id="fluency">
        <option value="">-- select --</option>
      	
        <option value="Writing">Writing</option>
        <option value="Speaking">Speaking</option>
        <option value="Reading">Reading</option>
      </select></td>
    </tr>
    <tr>
      <td class="field_label">Competency:</td>
      <td><select class="validate[required] select_option" name="competency" id="competency">
        <option value="">-- select --</option>
       
        <option value="Basic">Basic</option>
        <option value="Good">Good</option>
        <option value="Mother Tongue">Mother Tongue</option>
      </select></td>
    </tr>
    <tr>
      <td class="field_label">Comments:</td>
      <td><textarea name="comments" id="comments"></textarea></td>
    </tr>
  </table>
</div><!-- #form_default -->
<div class="form_action_section" id="form_default">
    <table>
    	<tr>
            <td class="field_label">&nbsp;</td>
            <td><input class="blue_button" type="submit" name="button" id="button" value="Add" />&nbsp;<a href="javascript:void(0);" onclick="javascript:loadLanguageTable();">Cancel</a></td>
    	</tr>
    </table>
</div><!-- #form_default.form_action_section -->
</div><!-- #form_main.employee_form -->
</form>
