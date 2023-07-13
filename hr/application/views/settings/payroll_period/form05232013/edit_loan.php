<script>
$(document).ready(function() {	
	$('#edit_loan').validationEngine({scroll:false});	
	
	/*$("#edit_start_date").datepicker({
		dateFormat:'yy-mm-dd',
		changeMonth:true,
		changeYear:true,
		showOtherMonths:true,
		onSelect	:function() { 
		
			//$("#edit_end_date").datepicker('option',{minDate:$(this).datepicker('getDate')});					
			//var output = computeDays($("#edit_start_date").val(),$("#edit_end_date").val());					
			//$("#edit_number_of_days").val(output);			
		}
	});*/
		
	$("#edit_end_date").datepicker({
		minDate: '<?php echo $gel->getEndDate(); ?>',
		dateFormat:'yy-mm-dd',
		changeMonth:true,
		changeYear:true,
		showOtherMonths:true,
		onSelect	:function() {
			getNumberOfInstallment('#type_of_deduction_id','#edit_start_date','#edit_end_date');
			//var output = computeDays($("#edit_start_date").val(),$("#edit_end_date").val());	 					
			//$("#edit_number_of_days").val(output);			
		}
	});
	
	var t = new $.TextboxList('#edit_employee_id', {max:1,plugins: {
			autocomplete: {
				minLength: 3,
				onlyFromValues: true,
				queryRemote: true,
				remote: {url: base_url + 'loan/ajax_get_employees_autocomplete'}
			
			}
		}});
	
	<?php 
			//Employee		
			if($gel) { 				
				$emp = G_Employee_Finder::findById($gel->getEmployeeId());
				if($emp){
			
	 ?>
					t.add('Entry','<?php echo Utilities::encrypt($emp->getId()); ?>', '<?php echo $emp->getFirstname(). ' '. $emp->getLastname(); ?>');
	 <?	
				}
			}
		 ?>
				
});

function checkForm()
{
	if ($('#edit_loan').validationEngine({returnIsValid: true })) {		
		$('#edit_loan').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {								
					load_loan_list_dt();				
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
<form id="edit_loan" name="edit_loan" onsubmit="javascript:checkForm();"  action="<?php echo url('loan/_update_loan'); ?>" method="post"> 
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<input type="hidden" id="employee_loan_id" name="employee_loan_id" value="<?php echo Utilities::encrypt($gel->getId()); ?>" />
<div id="form_main">     
  
    <div id="form_default">      
        <table>
             <tr>
               <td class="field_label">Employee:</td>
               <td><input class="validate[required] text-input" type="text" name="employee_id" id="edit_employee_id" value="" /></td>
             </tr>
             <tr>
               <td class="field_label">Type of Deduction:</td>
               <td>
               		<select class="validate[required] select_option" name="loan_type_id" id="loan_type_id">        
               		<?php foreach($loan_type as $lt){ ?>
                    	<option <?php echo($lt->getId() == $gel->getTypeOfLoanId() ? 'selected="selected"' : ''); ?> value="<?php echo Utilities::encrypt($lt->getId()); ?>"><?php echo $lt->getLoanType(); ?></option>
                    <?php } ?>
                    </select>
               </td>
             </tr>  
             <tr>
               <td class="field_label">Interest Rate:</td>
               <td>
               		<div class="input-append">
               		<input readonly="readonly" style="width:35px;height:18px;" class="validate[required,custom[integer]] input-mini" type="text" name="interest_rate" id="interest_rate" value="<?php echo $gel->getInterestRate(); ?>" /><span class="add-on">%</span>
                    </div>
               </td>
             </tr>
             <tr>
               <td class="field_label">Amount:</td>
               <td>
                   <div class="input-append">
                        <input readonly="readonly" style="width:254px;height:18px;z-index:9999;" class="validate[required,custom[money]]" type="text" name="loan_amount" id="loan_amount" value="<?php echo number_format($gel->getLoanAmount(),2,".",","); ?>" />					
                        <span class="add-on">Php</span>
                   </div>
               </td>
             </tr>
             <tr>
               <td class="field_label">Deduction Period:</td>
               <td>
               		<select class="validate[required] select_option" name="" id="" onchange="javascript:getEndDate('no_of_installment','type_of_deduction_id','edit_start_date','edit_end_date');" disabled="disabled">        
               		<?php foreach($deduction_type as $dt){ ?>
                    	<option <?php echo($dt->getId() == $gel->getTypeOfDeductionId() ? 'selected="selected"' : ''); ?> value="<?php echo Utilities::encrypt($dt->getId()); ?>"><?php echo $dt->getDeductionType(); ?></option>
                    <?php } ?>
                    </select>
                    <input type="hidden" id="type_of_deduction_id" name="type_of_deduction_id" value="<?php echo Utilities::encrypt($gel->getTypeOfDeductionId()); ?>" />
               </td>
             </tr>   
             <tr>
               <td class="field_label">Number of Installment:</td>
               <td>
               		<input onchange="javascript:getEndDate('no_of_installment','type_of_deduction_id','edit_start_date','edit_end_date');" style="width:35px;margin-right:6px;" class="validate[required,custom[integer]] text-input" type="text" name="no_of_installment" id="no_of_installment" value="<?php echo $gel->getNoOfInstallment(); ?>" />
               </td>
             </tr>   
             <tr>
               <td class="field_label">Start Date:</td>
               <td>
               		<input readonly="readonly" class="validate[required] text-input" type="text" name="start_date" id="edit_start_date" value="<?php echo $gel->getStartDate(); ?>" />
               </td>
             </tr>
             <tr>
               <td class="field_label">End Date:</td>
               <td>
               		<input readonly="readonly" class="validate[required] text-input" type="text" name="end_date" id="edit_end_date" value="<?php echo $gel->getEndDate(); ?>" />
               </td>
             </tr>             
             <tr>
               <td class="field_label">Status:</td>
               <td>
               		<select class="validate[required] select_option" name="status" id="status">        
               		<option <?php echo($gel->getStatus() == G_Employee_Loan::IN_PROGRESS ? 'selected="selected"' : ''); ?> value="<?php echo G_Employee_Loan::IN_PROGRESS; ?>"><?php echo G_Employee_Loan::IN_PROGRESS; ?></option>
                    <option <?php echo($gel->getStatus() == G_Employee_Loan::PENDING ? 'selected="selected"' : ''); ?> value="<?php echo G_Employee_Loan::PENDING; ?>"><?php echo G_Employee_Loan::PENDING; ?></option>
                    <option <?php echo($gel->getStatus() == G_Employee_Loan::DONE ? 'selected="selected"' : ''); ?> value="<?php echo G_Employee_Loan::DONE; ?>"><?php echo G_Employee_Loan::DONE; ?></option>
                    <option <?php echo($gel->getStatus() == G_Employee_Loan::CANCELLED ? 'selected="selected"' : ''); ?> value="<?php echo G_Employee_Loan::CANCELLED; ?>"><?php echo G_Employee_Loan::CANCELLED; ?></option>
                    </select>
               </td>
             </tr>                
         </table>
    </div>
    <div id="form_default" class="form_action_section">
    	<table width="100%" border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td>
                <input type="submit" value="Update" class="curve blue_button" />
                <a href="javascript:void(0)" onclick="javascript:closeDialogBox('#_dialog-box_','#edit_loan');">Cancel</a>
                </td>
            </tr>
        </table>
    </div>
</div><!-- #form_main -->
</form>
<script>
var output = computeDays($("#edit_start_date").val(),$("#edit_end_date").val());					
$("#edit_number_of_days").val(output);		
</script>

