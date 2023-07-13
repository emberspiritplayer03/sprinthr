<script>
$(document).ready(function() {		
	$('#add_applicant_form').validationEngine({scroll:false});	
		
	$('#add_applicant_form').ajaxForm({
		success:function(o) {
			$("#token").val(o.token);
			if (o.is_success == 1) {
				$("#first-header").html("");					
				$("#form_default").html("");				
				$(".form_action_section").html("");							
				$("#message_container").html(o.message);														
			} else {
				$("#message_container").html(o.message);
			}
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		},
		dataType:'json',
		beforeSubmit: function() {
			showLoadingDialog('Saving...');
		}
	});	
		
});
</script>

<div id="formcontainer">

<form action="<?php echo $action; ?>" method="post" name="add_applicant_form" id="add_applicant_form">
<input type="hidden" value="<?php echo $jeid; ?>" name="jeid" id="jeid" />
<input type="hidden" value="<?php echo $token; ?>" name="token" id="token" />
<div id="formwrap">

	<h3 class="form_sectiontitle" id="candidate_form_wrapper">Step 01 : Basic Information</h3>	
    <div id="form_main">
    	  <div id="first-header"><p><b>Kindly fill-up the below details.</b></p></div>
 		  <div id="message_container"></div>
        <div id="form_default">            
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="field_label">First Name: </td>
            <td><input type="text" class="validate[required] text-input text" name="firstname" id="firstname"></td>
          </tr>
          <tr>
            <td class="field_label">Last Name: </td>
            <td><input type="text" class="validate[required] text-input text" value="" name="lastname" id="lastname" /></td>
          </tr>
          <tr>
            <td valign="top" class="field_label">Email Address:</td>
            <td>
            	<input onblur="checkEmail(this.value);" type="text" class="validate[required,custom[email]]" name="email_address" id="email_address"><span class="help-inline"><small><i>This will be used as your username<i></small></span>
            	<div id="errorHolder"></div>
            </td>
          </tr>
          </table>
        </div>
        
        <div id="form_default" class="form_action_section">
            <table>
            	<tr>
                	<td class="field_label">&nbsp;</td>
                    <td>
                    	<input type="submit" value="Register" class="curve blue_button" /> | <a href="<?php echo recruitment_url("applicant_login"); ?>">Applicant Login</a>                     
                    </td>
                </tr>
            </table>
        </div>        
    		
    </div>

</div>

</form>
</div>