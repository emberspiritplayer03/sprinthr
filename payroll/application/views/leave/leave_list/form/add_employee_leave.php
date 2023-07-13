<script>

$(document).ready(function() {
$("#date_start").datepicker({
	dateFormat:'yy-mm-dd',
	changeMonth:true,
	changeYear:true,
	showOtherMonths:true,
	onSelect	:function() { 
		$("#date_end").datepicker('option',{minDate:$(this).datepicker('getDate')});
		start = $("#date_start").val();
		end = $("#date_end").val();
		output = computeDays(start, end);
		$("#number_of_days").val(output);
	}
});

$("#date_applied").datepicker({
	dateFormat:'yy-mm-dd',
	changeMonth:true,changeYear:true,
	showOtherMonths:true
});

$("#date_end").datepicker({
	dateFormat:'yy-mm-dd',
	changeMonth:true,
	changeYear:true,
	showOtherMonths:true,
	onSelect	:function() { 
		$("#date_start").datepicker('option',{maxDate:$(this).datepicker('getDate')}); 	
		start = $("#date_start").val();
		end = $("#date_end").val();
		output = computeDays(start, end);
		$("#number_of_days").val(output);
		
	}
});

	$("#employee_leave_form").validationEngine({scroll:false});

	$('#employee_leave_form').ajaxForm({
		success:function(o) {
			if(o==0){
				 dialogOkBox('Please Fill Up the Form Completely',{}) 
			}else {
				dialogOkBox('Successfully Added!',{ok_url: 'leave'}) 
			}
			
		},
		beforeSubmit:function() {
			showLoadingDialog('Saving...');	
		}
	});
	var t = new $.TextboxList('#employee_id', {max:1,plugins: {
	autocomplete: {
		minLength: 3,
		onlyFromValues: true,
		queryRemote: true,
		remote: {url: base_url + 'employee/ajax_get_employees_autocomplete'}
	
	}
}});
	
	
});


</script>
<div id="formcontainer">
<div class="mtshad"></div>
<form id="employee_leave_form"  action="<?php echo url('leave/_insert_new_employee_leave'); ?>" method="post"  name="employee_form" > 
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<input type="hidden" id="company_structure_id" name="company_structure_id" value="<?php echo $company_structure_id; ?>"  />
<div id="formwrap">	
	<h3 class="form_sectiontitle">Add Employee Leave</h3>
<div id="form_main">     
  
    <div id="form_default">      
        <table>
             <tr>
               <td class="field_label">Employee</td>
               <td><input class="validate[required] text-input" type="text" name="employee_id" id="employee_id" value="" /></td>
             </tr>
             <tr>
              <td class="field_label">Leave Type:</td>
              <td>
              <select class="validate[required] select_option" name="leave_id" id="leave_id">
                  <option value="">-- select --</option>
                <?php foreach($leaves as $l) { ?>               
                <option value="<?php echo $l->getId(); ?>"><?php echo $l->getName(); ?></option>
                <?php } ?>
               </select>
              </td>
            </tr>
            <tr>
              <td class="field_label">Date Applied:</td>
              <td><input class="validate[required] text-input" type="text" name="date_applied" id="date_applied" value="" /></td>
            </tr>
            <tr>
              <td class="field_label">Date Start:</td>
              <td>
              <input type="text" class="validate[required] text-input" name="date_start" id="date_start" value=""  onchange="javascript:computeDays();"/></td>
            </tr>
            <tr>
        
              <td class="field_label">Date End:</td>
              <td><input type="text" class="validate[required] text-input" name="date_end" id="date_end" value="" onchange="javascript:computeDays();" /></td>
            </tr>
            <tr>
              <td class="field_label">Days</td>
              <td><input name="number_of_days" type="text" class="validate[required,custom[integer],min[1]]" id="number_of_days" readonly="readonly" /></td>
            </tr>
            <tr>
              <td>Is Paid</td>
              <td><select class="validate[required]" name="is_paid" id="is_paid">
                <option value="yes">Yes</option>
                <option value="no" selected="selected">No</option>
                <?php 
				   if($details->is_paid=='yes') { 
				   	$yes="selected='selected'";
				   }else { 
				   	$no="selected='selected'";
				   }
				    ?>
              </select></td>
            </tr>
            <tr>
              <td>Leave Comments:</td>
              <td><textarea name="leave_comments" id="leave_comments" cols="45" rows="5"></textarea></td>
            </tr>
            <tr>
              <td class="field_label">&nbsp;</td>
              <td><select class="select_option" name="is_approved" id="is_approved">
                <option value="<?php echo G_Employee_Leave_Request::PENDING; ?>"><?php echo G_Employee_Leave_Request::PENDING; ?></option>
                <option value="<?php echo G_Employee_Leave_Request::APPROVED; ?>"><?php echo G_Employee_Leave_Request::APPROVED; ?></option>
                <option value="<?php echo G_Employee_Leave_Request::DISAPPROVED; ?>"><?php echo G_Employee_Leave_Request::DISAPPROVED; ?></option>
              </select></td>
            </tr>
         </table>
    </div>
    <div id="form_default" class="form_action_section">
    	<table width="100%" border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td>
                <input type="submit" value="Add New Employee Leave" class="curve blue_button" />
                <a href="javascript:void(0)" onclick="javascript:cancel_add_employee_leave_form();">Cancel</a>
                </td>
            </tr>
        </table>
    </div>
</div><!-- #form_main -->
</div><!-- #formwrap -->

</form>
</div>
<div id="error_message"></div>
