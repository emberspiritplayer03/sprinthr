<script>
$(document).ready(function() {	
	$('#add_loan_payment_form').validationEngine({scroll:false});	
		
	$('#add_loan_payment_form').ajaxForm({
		success:function(o) {
			if(o.is_success == 1) {
				load_loan_details_list_dt('<?php echo $eid; ?>');	
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
	
	$("#date_of_payment").datepicker({
		dateFormat:'yy-mm-dd',
		changeMonth:true,
		changeYear:true,
		showOtherMonths:true		
	});		
	
});
</script>
<div id="formcontainer">
<form id="add_loan_payment_form" name="add_loan_payment_form" action="<?php echo url('loan/_insert_new_loan_payment'); ?>" method="post"> 
<input type="hidden" id="loan_id" name="loan_id" value="<?php echo $eid; ?>" />
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<div id="formwrap">	
	<h3 class="form_sectiontitle">Add Payment</h3>
<div id="form_main">     
  
    <div id="form_default">      
        <table> 
        	 <tr>
               <td class="field_label">Date of Payment:</td>
               <td>
               		<input class="validate[required] text-input" type="text" name="date_of_payment" id="date_of_payment" value="" />
               </td>
             </tr>   
        	<tr>
               <td class="field_label">Amount:</td>
               <td>
               		<input class="validate[required,custom[money]] text-input" type="text" name="amount" id="amount" value="" />
               </td>
             </tr>         
             <tr>
               <td class="field_label">Is Paid:</td>
               <td>
               		<select style="width:211px;" class="validate[required] select_option" name="is_paid" id="is_paid">        				
                    	<option value="<?php echo G_Employee_Loan_Details::YES; ?>" selected="selected"><?php echo G_Employee_Loan_Details::YES; ?></option>    
                    	<option value="<?php echo G_Employee_Loan_Details::NO; ?>"><?php echo G_Employee_Loan_Details::NO; ?></option>       		
                    </select>
               </td>
             </tr>        
             <tr>
               <td class="field_label">Remarks:</td>
               <td>
               		<textarea name="remarks" id="remarks"></textarea>
               </td>
             </tr>                       
         </table>
    </div>
    <div id="form_default" class="form_action_section">
    	<table width="100%" border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td>
                <input type="submit" value="Save" class="curve blue_button" />
                <a href="javascript:void(0)" onclick="javascript:hide_show_loan_form();">Cancel</a>
                </td>
            </tr>
        </table>
    </div>
</div><!-- #form_main -->
</div>
</form>
</div>

