<script>
$(document).ready(function() {		
	var t = new $.TextboxList('#employee_id', {
			unique: true,
			plugins: {
				autocomplete: {
					minLength: 3,				
					onlyFromValues: true,
					queryRemote: true,
					remote: {url: base_url + 'autocomplete/ajax_get_employees'}			
				}
		}});
		
		<?php
			//Employee
			$ebenefits = G_Employee_Benefit_Finder::findAllEmployeeByBenefitId($b->getId());
			if($ebenefits){
				foreach($ebenefits as $eb){
					$e = G_Employee_Finder::findById($eb->getObjId());
					if($e){
		?>
			t.add('Entry','<?php echo Utilities::encrypt($e->getId()); ?>', '<?php echo $e->getFirstname(). ' '. $e->getLastname(); ?>');
		<?php		
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
</script>
<div id="form_main" class="inner_form popup_form">

	<form name="assignCompanyBenefit" id="assignCompanyBenefit" method="post" action="<?php echo url('settings/_assign_company_benefit'); ?>">   
	<input type="hidden" name="eid" id="eid" value="<?php echo Utilities::encrypt($b->getId()); ?>" />
    <input type="hidden" name="token" id="token" value="<?php echo $token; ?>" />
    <div id="form_default">
    <table width="100%"> 
        <tr>
            <td class="field_label">Benefit Name:</td>
            <td >
                <input type="text" value="<?php echo $b->getBenefitCode() . ": " . $b->getBenefitName(); ?>" name="benefit_name" class="validate[required] text" id="benefit_name" style="width:83%;" readonly="readonly" />                                   
            </td>
        </tr>
        <tr>
            <td class="field_label">Amount:</td>
            <td >
            	<div class="input-append">
                	<input type="text" readonly="readonly" value="<?php echo number_format($b->getBenefitAmount(),2,".",","); ?>" name="benefit_amount" class="validate[required,custom[money]] text-input" id="benefit_amount" style="width:20%;position:static;" />    
                	<span class="add-on" style="height:15px;">Php</span>
                </div>
                
            </td>
        </tr> 
        <tr>
            <td class="field_label">Assign Benefit:</td>
            <td >
                <div id="autcomplete_emp">
                    <input class="validate[required] input-large" type="text" name="employee_id" id="employee_id" value="" />
                </div>
                <div id="all_employee" style="display:none;">
                    <input class="input-large" type="text" name="all_emp" id="disabledInput" disabled="" value="All Employee" style="width:292px;" />
                </div>
                <label class="checkbox">
                    <input type="checkbox" <?php echo($is_applied_to_all == G_Employee_Benefit::YES && $eb->getApplyToAll() == G_Employee_Benefit::EMPLOYEE ? 'checked="checked"' : ''); ?> onchange="javascript:chkEmployee();" id="apply_to_all_employee" name="apply_to_all_employee" />Apply to all Employee
                </label>  
            </td>
        </tr>                         
    </table>
    </div>   
    <div id="form_default" class="form_action_section">
        <table width="100%">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td><input value="Save" id="add_leave_type_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="javascript:closeDialogBox('#_dialog-box_','#addRequirement');">Cancel</a></td>
            </tr>
		</table>
    </div>    
    </form>
</div>
<script>
chkEmployee();
</script>