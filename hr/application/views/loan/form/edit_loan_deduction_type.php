<script>
$(document).ready(function() {	
	$('#edit_loan_deduction_type').validationEngine({scroll:false});					
});

function checkForm(obj)
{
	if ($('#edit_loan_deduction_type').validationEngine({returnIsValid: true })) {		
		$('#edit_loan_deduction_type').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {								
					load_loan_deduction_type_list_dt();				
					$('#request_button').show();
					$('#request_loan_type_form_wrapper').hide();
					closeDialog('#' + DIALOG_CONTENT_HANDLER);	
					dialogOkBox(o.message,{});						
				} else {
					
				}
			},
			dataType:'json',
			beforeSubmit: function() {
				showLoadingDialog('Saving...');
			}
		});		
		return true;			
	}else{return false;}
}
</script>
<div id="form_main" class="inner_form popup_form wider2">
<form id="edit_loan_deduction_type" name="edit_loan_deduction_type" onsubmit="return checkForm();" action="<?php echo url('loan/_insert_new_loan_deduction_type'); ?>" method="post"  name="employee_form" > 
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<input type="hidden" id="loan_deduction_type_id" name="loan_deduction_type_id" value="<?php echo Utilities::encrypt($gldt->getId()); ?>" />

    <div id="form_default">      
        <table>
             <tr>
               <td class="field_label">Name:</td>
               <td><input class="validate[required] text-input" type="text" name="deduction_type" id="deduction_type" value="<?php echo $gldt->getDeductionType(); ?>" /></td>
             </tr>                  
         </table>
    </div>
    <div id="form_default" class="form_action_section">
    	<table width="100%" border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td>
                <input type="submit" value="Update" class="curve blue_button" />
                <a href="javascript:void(0)" onclick="javascript:closeDialogBox('#_dialog-box_','#edit_loan_deduction_type');">Cancel</a>
                </td>
            </tr>
        </table>
    </div>
</form>
</div><!-- #form_main -->

