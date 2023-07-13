<script>

$("#addDefaultLeaveFormStartup").validationEngine({});

$('#addDefaultLeaveFormStartup').ajaxForm({
			success:function(o) {
				
				$("#default_leave_wrapper_form_startup").dialog("destroy");
				disablePopUp();
				$dialog.dialog('destroy');
				load_leave_default_startup();
				$("#default_leave_wrapper_form_startup").html('');
			},
			beforeSubmit:function() {
				showLoadingDialog('Saving...');	
			}
		});
</script>
<div id="form_main" class="inner_form popup_form wider">
	<form name="addDefaultLeaveFormStartup" id="addDefaultLeaveFormStartup" method="post" action="<?php echo $default_leave_form_action; ?>">
   <input type="hidden" name="leave_id" id="leave_id" value="<?php echo $leave_id; ?>" />
   
      <div id="form_default">
    <table width="100%"> 
         <tr>
            <td class="field_label">Leave Name:</td>
            <td><strong><?php if($leave){echo $leave->getName();} ?></strong></td>
            
        </tr>
        <tr>
            <td class="field_label">*Number of Days:</td>
            <td><input type="text" value="" name="number_of_days_default"  id="number_of_days_default" /></td>
        </tr>
    </table>
    </div><!-- #form_default -->
    <div id="form_default" class="form_action_section">
   	  <table width="100%">
          <tr>
            <td class="field_label">&nbsp;</td>
            <td><input class="blue_button" type="submit" value="Save" />&nbsp;<a href="javascript:void(0);" onclick="javascript:closeDialog('#default_leave_wrapper_form_startup','#addDefaultLeaveFormStartup');">Cancel</a></td>
          </tr>
        </table>    
    </div><!-- #form_default.form_action_section -->
    </form>
</div>