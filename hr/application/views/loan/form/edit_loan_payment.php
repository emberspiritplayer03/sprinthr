<script>
$(document).ready(function() {	
	$('#edit_loan_payment').validationEngine({scroll:false});
	$("#edit_loan_payment").datepicker({
		dateFormat:'yy-mm-dd',
		changeMonth:true,
		changeYear:true,
		showOtherMonths:true		
	});						
});

function checkForm(obj)
{
	if ($('#edit_loan_payment').validationEngine({returnIsValid: true })) {		
		$('#edit_loan_payment').ajaxForm({
			success:function(o) {
				if(o.is_success == 1) {
					load_loan_details_list_dt('<?php echo Utilities::encrypt($geld->getLoanId()); ?>');	
					$("#balance").val(o.balance);			
					$('#request_loan_button').show();
					$('#request_loan_form_wrapper').hide();
					closeDialog('#' + DIALOG_CONTENT_HANDLER);	
					dialogOkBox(o.message,{});						
				}else{
					$('#request_loan_form_wrapper').hide();
					closeDialog('#' + DIALOG_CONTENT_HANDLER);	
					dialogOkBox(o.message,{});					
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
<form id="edit_loan_payment" name="edit_loan_payment" onsubmit="return checkForm();" action="<?php echo url('loan/_insert_new_loan_payment'); ?>" method="post"  name="employee_form" > 
<input type="hidden" id="loan_id" name="loan_id" value="<?php echo Utilities::encrypt($geld->getLoanId()); ?>" />
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<input type="hidden" id="employee_loan_payment_id" name="employee_loan_payment_id" value="<?php echo Utilities::encrypt($geld->getId()); ?>" />
  
    <div id="form_default">      
        <table>
             <tr>
               <td class="field_label">Date of Payment:</td>
               <td>
               		<input disabled="disabled" class="validate[required] text-input" type="text" name="date_of_payment" id="edit_date_of_payment" value="<?php echo $geld->getDateOfPayment(); ?>" />
               </td>
             </tr>   
        	<tr>
               <td class="field_label">Amount due:</td>
               <td>
               		<input disabled="disabled" class="validate[required,custom[money]] text-input" type="text" name="amount" id="amount" value="<?php echo number_format($geld->getAmount(),2,".",","); ?>" />
               </td>
             </tr>
             <tr>
               <td class="field_label">Remarks:</td>
               <td>
               		<textarea style="width:315px;min-width:327px;" name="loan_remarks" id="loan_remarks"><?php echo $geld->getRemarks(); ?></textarea>
               </td>
             </tr>                     
         </table>
         <br />
         <div id="wrapper_breakdown">
         	<div id="loan_payment_breakdown_wrapper"></div>
         </div>          
         <script>
         	load_loan_payment_breakdown('<?php echo Utilities::encrypt($geld->getId()); ?>',0);
         </script>
    </div>
    <div id="form_default" class="form_action_section">
    	<table width="100%" border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td>
                <input type="submit" value="Update" class="curve blue_button" />
                <a href="javascript:void(0)" onclick="javascript:closeDialogBox('#_dialog-box_','#edit_loan_payment');">Cancel</a>
                </td>
            </tr>
        </table>
    </div>
</form>
</div><!-- #form_main -->

