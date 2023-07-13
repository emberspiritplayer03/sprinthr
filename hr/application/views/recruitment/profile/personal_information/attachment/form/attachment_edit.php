<script>
$("#attachment_edit_form").validationEngine({scroll:false});
$("#edit_date_attached").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$('#attachment_edit_form').ajaxForm({
	success:function(o) {
		if(o==1) {
			dialogOkBox('Successfully Updated',{});
			$("#attachment_wrapper").html('');
			loadPage("#attachment");
			
		}else {
			dialogOkBox(o,{});	
		}		
	},
	beforeSubmit:function() {
		showLoadingDialog('Saving...');	
	}
});
</script>
<form id="attachment_edit_form" name="form1" method="post" action="<?php echo url('recruitment/_update_attachment'); ?>" >
<div id="form_main" class="employee_form">
<input type="hidden" name="id" value="<?php echo $details->id ?>" />
<input type="hidden" name="applicant_id" value="<?php echo Utilities::encrypt($details->applicant_id); ?>" />
<input type="hidden" name="name" value="<?php echo $details->name; ?>" />
<div id="form_default">
  <table>
  	 <tr>
      <td class="field_label">Filename:</td>
      <td>
      <input readonly="readonly" type="text" name="filename_current" id="filename_current" value="<?php echo $details->filename; ?>" /><br />
      <input type="file" name="filename" id="filename" />      
      </td>
    </tr>
    <tr>
      <td class="field_label">Description:</td>
      <td><input class="text-input" type="text" name="description" id="description" value="<?php echo $details->description; ?>" /></td>
    </tr>
    <tr>
      <td class="field_label">Date Attached:</td>
      <td>
      <input class="text-input" type="text" name="date_attached" id="edit_date_attached" value="<?php echo  $details->date_attached; ?>" /></td>
    </tr>
  </table>
</div><!-- #form_default -->
<div class="form_action_section" id="form_default">
    <table>
    	<tr>
            <td class="field_label">&nbsp;</td>
            <td><a class="delete_link red float-right" href="javascript:void(0);" onclick="javascript:loadAttachmentDeleteDialog('<?php echo $details->id; ?>')"><span class="delete"></span>Delete this Attachment</a><input class="blue_button" type="submit" name="button" id="button" value="Update" />&nbsp;<a href="javascript:void(0);" onclick="javascript:loadAttachmentTable();">Cancel</a></td>
    	</tr>
    </table>
</div><!-- #form_default.form_action_section -->
</div><!-- #form_main.employee_form -->
</form>
