<script>
$(document).ready(function() {		
	$('#forgot_password').validationEngine({scroll:false});	
	
	$("#birthdate").datepicker({
		dateFormat:'yy-mm-dd',
		changeMonth:true,
		changeYear:true,
		showOtherMonths:true				
	});
		
	$('#forgot_password').ajaxForm({
		success:function(o) {
			if (o.is_success == 1) {
				closeDialog('#' + DIALOG_CONTENT_HANDLER);					
				$("#error_container").html(o.message);																
			} else {
				if(o.attempt > 3){
					closeDialog('#' + DIALOG_CONTENT_HANDLER);	
					$("#error_container").html(o.message);
					$("#form_container").html("<div class=\"alert alert-block alert-error fade in\"><b>Kindly contact your system administrator.</b></div>");
				}else{					
					closeDialog('#' + DIALOG_CONTENT_HANDLER);										
					$("#error_container").html(o.message);
				}
			}
		},
		dataType:'json',
		beforeSubmit: function() {
			showLoadingDialog('Updating...');
		}
	});		
});

</script>
<div id="detailscontainer" class="detailscontainer_blue">
<div id="applicant_details">
<div id="formwrap" class="employee_form_summary">
	<div id="form_main" class="inner_form">
        <div id="error_container"></div>
        <div id="form_container">
        <?php if($attempt <= 3){ ?>
        <form class="form-horizontal" id="forgot_password" name="forgot_password" method="post" action="<?php echo url('forgot_password/update_password'); ?>">
        <div id="form_default" style="margin-bottom:15px;">
        	<div id="applicant_details">
            <div id="applicant_details">
            <table width="100%">
                <tr>
                    <td class="field_label">Your Username:</td>
                    <td><input style="width:270px;" type="text" id="username" name="username" placeholder="" class="validate[required] input-xlarge"></td>
                </tr>
                <tr>
                    <td class="field_label">Your Firstname:</td>
                    <td><input style="width:270px;" type="text" id="firstname" name="firstname" placeholder="" class="validate[required] input-xlarge"></td>
                </tr>
                <tr>
                    <td class="field_label">Your Lastname:</td>
                    <td><input style="width:270px;" type="text" id="lastname" name="lastname" placeholder="" class="validate[required] input-xlarge"></td>
                </tr>
                <tr>
                    <td class="field_label">Your Birthdate:</td>
                    <td><input style="width:270px;" type="text" id="birthdate" name="birthdate" placeholder="" class="validate[required] input-xlarge"></td>
                </tr>
             </table>
             </div>
             </div>
         </div>
         <div id="applicant_details"><div class="form_separator"></div></div>
         <div id="form_default">
         	<div id="applicant_details">
            <div id="applicant_details">
             <table width="100%">
                <tr>
                    <td class="field_label"><strong>New Password:</strong></td>
                    <td><input type="password" id="password" name="password" placeholder="" class="validate[required] input-xlarge"></td>
                </tr>
                <tr>
                    <td class="field_label"><strong>Re-type Password:</strong></td>
                    <td><input type="password" id="repassword" name="repassword" placeholder="" class="validate[required,equals[password]] input-xlarge"></td>
                </tr>
             </table>
             </div>
             </div>
        </div>        
        <div id="form_default" class="yellow_form_action_section form_action_section yellow_section">
        	<div id="applicant_details">
            <div id="applicant_details">
        	<table width="100%">                
                <tr>
                	<td class="field_label">&nbsp;</td>
                    <td><button type="submit" class="blue_button">Submit</button>&nbsp;<a href="<?php echo url('login'); ?>">Cancel</a>
                    </td>
                </tr>
            </table>
            </div>
            </div>
        </div>
        </form>
        <?php }else{ ?>
            <div class="alert alert-block alert-error fade in"><b>Kindly contact your system administrator.</b></div>
        <?php } ?>
        </div>
    </div>
</div>
</div>
</div>