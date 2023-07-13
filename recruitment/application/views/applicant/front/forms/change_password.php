<script>
$(document).ready(function() {		
	$('#change_password_form').validationEngine({scroll:false});	
		
	$('#change_password_form').ajaxForm({
		success:function(o) {
			if (o.is_success == 1) {
				$("#token").val(o.token);
				closeDialog('#' + DIALOG_CONTENT_HANDLER);				
				$("#message_container").html(o.message);
				//dialogOkBox(o.message,{});						
			} else {
				$("#token").val(o.token);
				closeDialog('#' + DIALOG_CONTENT_HANDLER);
				$("#message_container").html(o.message);	
				//dialogOkBox(o.message,{});			
			}
		},
		dataType:'json',
		beforeSubmit: function() {
			showLoadingDialog('Saving...');
		}
	});	
		
});
</script>
<div id="formcontainer">
<form action="<?php echo $action; ?>" method="post" name="change_password_form" id="change_password_form">
<input type="hidden" value="<?php echo $token; ?>" name="token" id="token" />
<div id="formwrap">

	<h3 class="form_sectiontitle" id="candidate_form_wrapper">Change Password</h3>	
    <div id="form_main">
		
		<div id="message_container"></div>
 
        <div id="form_default">            
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="field_label">Old Password: </td>
            <td>
            	<input type="password" class="validate[required]" name="old_password" id="old_password" style="width:276px;"><br />
            	<small style="font-size:85%;">You may check your email for your current password.</small>
            </td>
          </tr>
          <tr>
            <td class="field_label">New Password: </td>
            <td><input type="password" class="validate[required]" value="" name="new_password" id="new_password" style="width:276px;" /></td>
          </tr>
          <tr>
            <td valign="top" class="field_label">Repeat Password:</td>
            <td>
            	<input type="password" class="validate[required,equals[new_password]]" name="repeat_new_password" id="repeat_new_password" style="width:276px;">
            </td>
          </tr>
          <tr>
            <td colspan="2" valign="top" class="field_label">&nbsp;</td>
          </tr>
          </table>
        </div>
        
        <div id="form_default" class="form_action_section">
            <table>
            	<tr>
                	<td class="field_label">&nbsp;</td>
                    <td>
                    	<input type="submit" value="Update" class="curve blue_button" /> <a class="curve blue_button" href="<?php echo url("applicant/dashboard"); ?>"><i class="icon-home icon-white"></i> My Dashboard<a/>                  
                    </td>
                </tr>
            </table>
        </div>        
    		
    </div>

</div>

</form>
</div>