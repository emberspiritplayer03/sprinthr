<script>
$("#editDefaultLeaveForm").validationEngine({});
$('#editDefaultLeaveForm').ajaxForm({
	success:function(o) {		
		$("#edit_default_leave_wrapper_form_startup").dialog("destroy");
		disablePopUp();
		$dialog.dialog('destroy');
		if(o.success == 1){
			dialogOkBox(o.message,{});	
			load_leave_default_startup();
			$("#edit_default_leave_wrapper_form_startup").html('');
		}else{
			dialogOkBox(o.message,{});				
		}		
	},
	dataType:'json',
	beforeSubmit:function() {
		showLoadingDialog('Saving...');	
	}
});
</script>
<div id="form_main" class="inner_form popup_form wider">
	<form name="editDefaultLeaveForm" id="editDefaultLeaveForm" method="post" action="<?php echo $default_leave_form_action; ?>">
   <input type="hidden" name="leave_id" id="leave_id" value="<?php echo Utilities::encrypt($leave->getId()); ?>" />
   
      <div id="form_default">
    <table width="100%"> 
         <tr>
            <td class="field_label">Leave Name:</td>
            <td><input type="text" value="<?php echo $leave->getName(); ?>" name="name"  id="name" /></td>
            
        </tr>
        <tr>
            <td class="field_label">*Default Credits:</td>
            <td><input type="text" name="number_of_days_default" class="validate[required]  text" id="number_of_days_default" value="<?php echo $leave->getDefaultCredit();?>" /></td>
        </tr>
        <tr>
            <td class="field_label">With Pay:</td>
            <td>
            	<select id="is_paid" name="is_paid" style="width:46%;">
                	<option <?php echo($leave->getIsPaid() == G_Leave::YES ? 'selected="selected"' : ''); ?> value="<?php echo G_Leave::YES; ?>"><?php echo G_Leave::YES; ?></option>
                    <option <?php echo($leave->getIsPaid() == G_Leave::NO ? 'selected="selected"' : ''); ?> value="<?php echo G_Leave::NO; ?>"><?php echo G_Leave::NO; ?></option>
                </select>
            </td>
        </tr>
    </table>
    </div><!-- #form_default -->
    <div id="form_default" class="form_action_section">
   	  <table width="100%">
          <tr>
            <td class="field_label">&nbsp;</td>
            <td><input class="blue_button" type="submit" value="Save" />&nbsp;<a href="javascript:void(0);" onclick="javascript:closeDialogBox('#_dialog-box_','#editDefaultLeaveForm');">Cancel</a></td>
          </tr>
        </table>    
    </div><!-- #form_default.form_action_section -->
    </form>
</div>