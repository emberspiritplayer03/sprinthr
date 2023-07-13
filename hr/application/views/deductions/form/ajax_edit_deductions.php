<script>
$(document).ready(function() {	
	$('#edit_deduction').validationEngine({scroll:false});	
	
	var t = new $.TextboxList('#employee_id', {
			unique: true,
			plugins: {
				autocomplete: {
					minLength: 3,				
					onlyFromValues: true,
					queryRemote: true,
					remote: {url: base_url + 'deductions/ajax_get_employees_autocomplete'}			
				}
		}});
		
	<?php
		//Employees				
		if($gee) {
			if($gee->getApplyToAllEmployee() == G_Employee_Earnings::NO){
				$emp_ids = explode(",",unserialize($gee->getEmployeeId()));			
				foreach($emp_ids as $e){
					$emp = G_Employee_Finder::findById($e);
					if($emp){
	?>
		t.add('Entry','<?php echo Utilities::encrypt($emp->getId()); ?>', '<?php echo $emp->getFirstname(). ' '. $emp->getLastname(); ?>');
	<?php
					}
				}
			}
		}
	?>				
});

function chkEmployee() {		
	if($("#apply_to_all_employee").is(':checked')){				
		$("#all_employee").show();
		$("#autcomplete_emp").hide();
	}else{		
		$("#all_employee").hide();
		$("#autcomplete_emp").show();
	}
}

function checkForm()
{
	if ($('#edit_deduction').validationEngine({returnIsValid: true })) {		
		$('#edit_deduction').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {	
					load_deductions_list_dt('"' + o.eid + '"');
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

<div id="form_main" class="inner_form popup_form wider">
<form id="edit_deduction" name="edit_deduction" onsubmit="javascript:checkForm();"  action="<?php echo url('deductions/_save_deduction'); ?>" method="post"> 
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<input type="hidden" id="cutoff_period_id" name="cutoff_period_id" value="<?php echo Utilities::encrypt($gee->getPayrollPeriodId()); ?>" />
<input type="hidden" id="deduction_id" name="deduction_id" value="<?php echo Utilities::encrypt($gee->getId()); ?>" />      
    <div id="form_default">      
        <table>
        	 <tr>
               <td class="field_label">Title:</td>
               <td>
               		<input class="validate[required] input-large" type="text" name="e_title" id="e_title" value="<?php echo $gee->getTitle(); ?>" style="width:292px;" />
               </td>
             </tr>     
             <tr>
               <td class="field_label">Employee:</td>
               <td>
               		<div id="autcomplete_emp">
               			<input class="validate[required] input-large" type="text" name="employee_id" id="employee_id" value="" />
                    </div>
                    <div id="all_employee" style="display:none;">
                    	<input class="input-large" type="text" name="all_emp" id="disabledInput" disabled="" value="All Employee" style="width:292px;" />
                    </div>
                    <label class="checkbox">
                    	<input <?php echo($gee->getApplyToAllEmployee() == G_Employee_Deductions::YES ? 'checked="checked"' : ''); ?> type="checkbox" onchange="javascript:chkEmployee();" id="apply_to_all_employee" name="apply_to_all_employee" />Apply to all Employee
                    </label>
               
               </td>
             </tr>                    
             <tr>
               <td class="field_label">Amount:</td>
               <td>
               		 <div class="input-append" style="display:inline;">
                     	<input style="width:200px;height:20px;" class="validate[required,custom[money]] text-input" type="text" name="amount" id="amount" value="<?php echo number_format($gee->getAmount(),2,".",","); ?>" />
                    	<span class="add-on">Php</span>
                    </div>&nbsp;
                     <!--<label class="checkbox inline">
                    	<input <?php //echo($gee->getTaxable() == G_Employee_Deductions::YES ? 'checked="checked"' : '') ?> type="checkbox" id="is_taxable" name="is_taxable" />Taxable
                    </label> -->              		
               </td>
             </tr>
             <tr>
               <td class="field_label">Add to Payroll Period:</td>
               <td>
               		<input class="input-large" type="text" name="all_emp" id="disabledInput" disabled="" value="<?php echo $cutoff_period; ?>" style="width:292px;font-weight:bold;" />
               		<!--<select class="validate[required] select_option" name="payroll_period_id" id="payroll_period_id">        
               		<?php //foreach($cutoff_periods as $ct){ ?>
                    	<option value="<?php //echo Utilities::encrypt($ct->getId()); ?>"><?php //echo $ct->getStartDate() . ' to ' . $ct->getEndDate(); ?></option>
                    <?php //} ?>
                    </select>-->
               </td>
             </tr>                                      
             <tr>
               <td class="field_label">Status:</td>
               <td>
               		<select class="validate[required] select_option" name="status" id="status">        
               		<option <?php echo($gee->getStatus() == G_Employee_Deductions::PENDING ? 'selected="selected"' : '') ?> value="<?php echo G_Employee_Deductions::PENDING; ?>" selected="selected"><?php echo G_Employee_Deductions::PENDING; ?></option>  
                    <option <?php echo($gee->getStatus() == G_Employee_Deductions::APPROVED ? 'selected="selected"' : '') ?> value="<?php echo G_Employee_Deductions::APPROVED; ?>"><?php echo G_Employee_Deductions::APPROVED; ?></option>                  
                    </select>
               </td>
             </tr>
             <tr>
               <td class="field_label">Remarks:</td>
               <td>
               		<textarea class="input-large" id="remarks" name="remarks"><?php echo $gee->getRemarks(); ?></textarea>               		
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
</form>
</div><!-- #form_main -->
<script>
chkEmployee();
</script>

