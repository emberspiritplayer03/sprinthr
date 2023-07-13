<style>
div#form_default table td.field_label {
padding-left:43px;	
}
</style>
<script>
$(document).ready(function() {	
	$('#add_loan_payment').validationEngine({scroll:false});
					
});

function checkForm(obj)
{
	if ($('#add_loan_payment').validationEngine({returnIsValid: true })) {		
		$('#add_loan_payment').ajaxForm({
			success:function(o) {
				if(o.is_success == 1) {
					load_loan_details_list_dt('<?php echo Utilities::encrypt($gel->getId()); ?>');	
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
<form id="add_loan_payment" name="add_loan_payment" onsubmit="return checkForm();" action="<?php echo url('loan/_insert_new_loan_payment'); ?>" method="post"  name="employee_form" > 
<input type="hidden" id="loan_id" name="loan_id" value="<?php echo Utilities::encrypt($gel->getId()); ?>" />
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<div id="form_main">     
  
    <div id="form_default">      
        <table>
        	 <tr>
               <td class="field_label">Select Due Date:</td>
               <td>
               	  <select class="validate[required] select_option" name="payment_period" id="payment_period" onchange="javascript:show_add_loan_payment_form(this.value);"> 
                  	<option value=""></option>
                  	<?php foreach($geld as $d){ ?>
                    	<option value="<?php echo Utilities::encrypt($d->getId()); ?>"><?php echo date("F d, o",strtotime($d->getDateOfPayment())); ?></option>
                    <?php } ?>
                  </select>
               </td>
             </tr>
        </table>
        <div id="wrapper_add_payment_form" style="width:100%;"></div>
    </div>
    
    
    
    <div id="form_default" class="form_action_section">
    	<table width="100%" border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td>
                <input type="submit" value="Update" class="curve blue_button" />
                <a href="javascript:void(0)" onclick="javascript:closeDialogBox('#_dialog-box_','#add_loan_payment');">Cancel</a>
                </td>
            </tr>
        </table>
    </div>
</div><!-- #form_main -->
</form>

