<script>
$(document).ready(function() {		
	$('#add_loan_form').validationEngine({scroll:false});	
		
	$('#add_loan_form').ajaxForm({
		success:function(o) {
			if (o.is_success == 1) {
				load_loan_list_dt();				
				$('#request_loan_button').show();
				$('#request_loan_form_wrapper').hide();
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
	
	$("#start_date").datepicker({
		dateFormat:'yy-mm-dd',
		changeMonth:true,		
		changeYear:true, 	    
		showOtherMonths:true,
		onSelect	:function() { 
			getEndDate('no_of_installment','type_of_deduction_id','start_date','end_date');
			$("#end_date").datepicker('option',{minDate:$(this).datepicker('getDate')});								
		}
	});
		
	$("#end_date").datepicker({
		dateFormat:'yy-mm-dd',
		changeMonth:true,
		changeYear:true,
		showOtherMonths:true,
		onSelect	:function() {
			getNumberOfInstallment('#type_of_deduction_id','#start_date','#end_date');				
		}
	});
	
	var t = new $.TextboxList('#employee_id', {max:1,plugins: {
			autocomplete: {
				minLength: 3,
				onlyFromValues: true,
				queryRemote: true,
				remote: {url: base_url + 'loan/ajax_get_employees_autocomplete'}
			
			}
		}});
	
});
</script>
<div id="formcontainer">
<form id="add_loan_form" name="add_loan_form" action="<?php echo url('loan/_insert_new_loan'); ?>" method="post"> 
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<div id="formwrap">	
	<h3 class="form_sectiontitle">Add New Deduction</h3>
<div id="form_main">     
  
    <div id="form_default">      
        <table>
             <tr>
               <td class="field_label">Employee:</td>
               <td><input class="validate[required] text-input" type="text" name="employee_id" id="employee_id" value="" /></td>
             </tr>
             <tr>
               <td class="field_label">Type of Deduction:</td>
               <td>
               		<select class="validate[required] select_option" name="loan_type_id" id="loan_type_id">        
               		<?php foreach($loan_type as $lt){ ?>
                    	<option value="<?php echo Utilities::encrypt($lt->getId()); ?>" selected="selected"><?php echo $lt->getLoanType(); ?></option>
                    <?php } ?>
                    </select>
               </td>
             </tr>  
             <tr>
               <td class="field_label">Interest Rate:</td>
               <td>
                    <div class="input-append">
                    	<input style="width:35px;height:18px;" class="validate[required,custom[integer]] input-mini" type="text" name="interest_rate" id="interest_rate" value="<?php echo G_Employee_Loan::DEFAULT_INTEREST; ?>" /><span class="add-on">%</span>
                    </div>
                   
               </td>
             </tr>
             <tr>
               <td class="field_label">Amount:</td>
               <td>
               		 <div class="input-append">
                     	<input style="width:254px;height:18px;" class="validate[required,custom[money]] text-input" type="text" name="loan_amount" id="loan_amount" value="" />
                    	<span class="add-on">Php</span>
                    </div>               		
               </td>
             </tr>
             <tr>
               <td class="field_label">Deduction Period:</td>
               <td>
               		<select onchange="javascript:getEndDate('no_of_installment','type_of_deduction_id','start_date','end_date');" class="validate[required] select_option" name="type_of_deduction_id" id="type_of_deduction_id">        
               		<?php foreach($deduction_type as $dt){ ?>
                    	<option <?php echo($dt->getId() == G_Employee_Loan::MONTHLY ? 'selected="selected"' : ''); ?> value="<?php echo Utilities::encrypt($dt->getId()); ?>"><?php echo $dt->getDeductionType(); ?></option>
                    <?php } ?>
                    </select>
               </td>
             </tr>   
             <tr>
               <td class="field_label">Number of Installment:</td>
               <td>
               		<input onchange="javascript:getEndDate('no_of_installment','type_of_deduction_id','start_date','end_date');" style="width:35px;margin-right:6px;" class="validate[required,custom[integer]] text-input" type="text" name="no_of_installment" id="no_of_installment" value="" />
               </td>
             </tr>   
             <tr>
               <td class="field_label">Start Date:</td>
               <td>
               		<input class="validate[required] text-input" type="text" name="start_date" id="start_date" value="<?php echo date("Y-m-d"); ?>" />
               </td>
             </tr>
             <tr>
               <td class="field_label">End Date:</td>
               <td>
               		<input class="validate[required] text-input" readonly="readonly" type="text" name="end_date" id="end_date" value="" />
               </td>
             </tr>            
             <tr>
               <td class="field_label">Status:</td>
               <td>
               		<select class="validate[required] select_option" name="status" id="status">        
               		<option value="<?php echo G_Employee_Loan::IN_PROGRESS; ?>" selected="selected"><?php echo G_Employee_Loan::IN_PROGRESS; ?></option>  
                    <option value="<?php echo G_Employee_Loan::PENDING; ?>"><?php echo G_Employee_Loan::PENDING; ?></option>                  
                    <option value="<?php echo G_Employee_Loan::DONE; ?>"><?php echo G_Employee_Loan::DONE; ?></option>
                    <option value="<?php echo G_Employee_Loan::CANCELLED; ?>"><?php echo G_Employee_Loan::CANCELLED; ?></option>
                    
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

