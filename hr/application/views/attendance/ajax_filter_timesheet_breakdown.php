<script>
var date_from_str = "<?php echo $date_from; ?>";
var date_to_str   = "<?php echo $date_to; ?>";	
$(document).ready(function() {
	$("#filter_timesheet_breakdown_date_from").datepicker({
		minDate: date_from_str,
    	maxDate: date_to_str,
		dateFormat:'yy-mm-dd',
		changeMonth:true,
		changeYear:true,
		showOtherMonths:true,
		onSelect	:function() { 
			$("#filter_timesheet_breakdown_date_to").datepicker('option',{minDate:$(this).datepicker('getDate')});
		}
	});	
	
	$("#filter_timesheet_breakdown_date_to").datepicker({
		minDate: date_from_str,
    	maxDate: date_to_str,
		dateFormat:'yy-mm-dd',
		changeMonth:true,
		changeYear:true,
		showOtherMonths:true,
		onSelect	:function() { 
		
		}
	});	
	
	var t = new $.TextboxList('#employee_id', {
			unique: true,
			//max:1,
			plugins: {
				autocomplete: {
					minLength: 3,				
					onlyFromValues: true,
					queryRemote: true,
					//remote: {url: base_url + 'autocomplete/ajax_get_active_employees'}	
					remote: {url: base_url + 'autocomplete/ajax_get_active_and_terminated_employees_within_date_range'}
				}
		}});
	
});

$(function(){
	$('ul.textboxlist-bits').attr("title","Type employee name to see suggestions");
	$('ul.textboxlist-bits').tipsy({gravity: 's'});	
});

function disableTextBox(obj_txtboxlist_id,obj_default_id,checkbox_id){	
	if($("#" + checkbox_id).is(':checked')){
		$("#" + obj_txtboxlist_id).show();
		$("#" + obj_default_id).hide();
	}else{		
		$("#" + obj_txtboxlist_id).hide();
		$("#" + obj_default_id).show();
	}
}
</script>
<form method="post" id="filter_timesheet_breakdown_form" name="filter_timesheet_breakdown_form" action="<?php echo url("attendance/download_filtered_timesheet_breakdown_by_employee_and_period"); ?>">
<div id="form_main" class="inner_form popup_form">
    <div id="form_default">
        <table width="100%">
          <tr>
            <td class="field_label">Employee:</td>
            <td>
            	<div id="txt_employees">
            		<input class="validate[required] input-large" type="text" name="employee_id" id="employee_id" value="" />
            	</div>
            	<div id="txt_hidden_employees" style="display:none;">
               	<input type="text" name="dummy_employees" id="dummy_employees" disabled="disabled" style="width:290px" value="" />
               </div>            		
         		<label class="checkbox">
         			<input id="all_employee" value="Yes" name="all_employee" type="checkbox" onclick="javascript:disableTextBox('txt_hidden_employees','txt_employees','all_employee');" />All Employee         			
         		</label>
            </td>
          </tr>
          <tr>
            <td class="field_label">Date From:</td>
            <td>
            		<input class="validate[required] input-large" type="text" name="filter_timesheet_breakdown_date_from" id="filter_timesheet_breakdown_date_from" value="" />
            </td>
          </tr>
          <tr>
            <td class="field_label">Date To:</td>
            <td>
            		<input class="validate[required] input-large" type="text" name="filter_timesheet_breakdown_date_to" id="filter_timesheet_breakdown_date_to" value="" />
            </td>
          </tr>
        </table>        
	</div><!-- #form_default -->
    <div id="form_default" class="form_action_section">
        <table width="100%">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td><input value="Download Timesheet" id="edit_schedule_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="closeTheDialog()">Cancel</a></td>
            </tr>
		</table>
    </div>
</div><!-- #form_main.inner_form -->   
</form>