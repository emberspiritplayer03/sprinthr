<script>
$("#date_attached").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#attachment_add_form").validationEngine({scroll:false});
$('#attachment_add_form').ajaxForm({
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
<form id="attachment_add_form" name="form1" action="<?php echo url('recruitment/_update_attachment'); ?>" method="post"  >
<div id="form_main" class="attachment_form">
<input type="hidden" name="applicant_id" value="<?php echo $applicant_id; ?>" />
<div id="form_default">
  <table>
  	 <tr>
  	   <td class="field_label">Name</td>
  	   <td><input class="text-input validate[required]" type="text" name="name" id="name" value="<?php echo $details->description; ?>" /></td>
	   </tr>
  	 <tr>
  	   <td class="field_label">Filename:</td>
  	   <td><input class="text-input validate[required]" type="file" name="filename" id="filename" /></td>
    </tr>
  	 <tr>
  	   <td class="field_label">Description:</td>
  	   <td><input class="text-input validate[required]" type="text" name="description" id="description" value="<?php echo $details->description; ?>" /></td>
    </tr>
  	 <tr>
  	   <td class="field_label">Date Attached:</td>
  	   <td><input class="text-input validate[required]" type="text" name="date_attached" id="date_attached" value="<?php echo  $details->date_attached; ?>" /></td>
    </tr>
  </table>
</div><!-- #form_default -->
<div class="form_action_section" id="form_default">
    <table>
    	<tr>
            <td class="field_label">&nbsp;</td>
            <td><input class="blue_button" type="submit" name="button" id="button" value="Add" />&nbsp;<a href="javascript:void(0);" onclick="javascript:loadAttachmentTable();">Cancel</a></td>
    	</tr>
    </table>
</div><!-- #form_default.form_action_section -->
</div><!-- #form_main.employee_form -->
</form>
