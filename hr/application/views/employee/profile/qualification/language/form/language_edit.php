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
       <td>
       	<select name="language" id="language">
        	<?php foreach($g_language as $gl){ ?>
            	<option <?php echo($details->language == $gl->getLanguage() ? 'selected="selected"' : ''); ?> value="<?php echo $gl->getLanguage(); ?>"><?php echo $gl->getLanguage(); ?></option>
            <?php } ?>
        </select>        	
       </td>
    </tr>
     <tr>
       <td class="field_label">Fluency:</td>
       <td><select class="select_option" name="fluency" id="fluency">	       
           <option <?php echo($details->fluency == "Writing" ? 'selected="selected"' : ''); ?> value="Writing">Writing</option>
           <option <?php echo($details->fluency == "Speaking" ? 'selected="selected"' : ''); ?> value="Speaking">Speaking</option>
           <option <?php echo($details->fluency == "Reading" ? 'selected="selected"' : ''); ?> value="Reading">Reading</option>
     </select></td>
     </tr>
     <tr>
       <td class="field_label">Competency:</td>
       <td>
      	<select class="select_option" name="competency" id="competency">              
           <option <?php echo($details->competency == "Poor" ? 'selected="selected"' : ''); ?> value="Poor">Poor</option>
           <option <?php echo($details->competency == "Basic" ? 'selected="selected"' : ''); ?> value="Basic">Basic</option>
           <option <?php echo($details->competency == "Good" ? 'selected="selected"' : ''); ?> value="Good">Good</option>
           <option <?php echo($details->competency == "Mother Tongue" ? 'selected="selected"' : ''); ?> value="Mother Tongue">Mother Tongue</option>
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
