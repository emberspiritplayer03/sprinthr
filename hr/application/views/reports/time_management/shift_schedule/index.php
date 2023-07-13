<h2><?php echo $title; ?></h2>
<script>
$("#shift_schedule_date_from").datepicker({	
	dateFormat:'yy-mm-dd',
	changeMonth:true,
	changeYear:true,
	showOtherMonths:true,
	onSelect	:function() { 
		$("#shift_schedule_date_to").datepicker('option',{minDate:$(this).datepicker('getDate')});
	}
});	

$("#shift_schedule_date_to").datepicker({ 
    dateFormat:'yy-mm-dd',
    changeMonth:true,
    changeYear:true,
    showOtherMonths:true
}); 

$("#shift_schedule_date_to").datepicker({	
	dateFormat:'yy-mm-dd',
	changeMonth:true,
	changeYear:true,
	showOtherMonths:true,
	onSelect	:function() { 
	
	}
 
});	

$(function(){
    $("#frm-report-tardiness").validationEngine({scroll:false}); 
});

</script>
<div id="form_main" class="employee_form">
<form id="frm-report-tardiness" name="form1" method="post" action="<?php echo url('reports/download_shift_schedule_data'); ?>">
     <div class="form_default">
        <h3 class="section_title">Date Range</h3>
        <table width="100%">
            <tr>
                <td class="field_label">From</td>
                <td><input type="text" id="shift_schedule_date_from" class="validate[required]" name="shift_schedule_date_from" /></td>
            </tr> 
            <tr>
                <td class="field_label">To</td>
                <td><input type="text" id="shift_schedule_date_to" class="validate[required]" name="shift_schedule_date_to" /></td>
            </tr>                        
    	</table>
    </div>
    <div class="form_separator"></div>
    <div class="form_default">
        <table width="100%">                   
            <tr>
                <td class="field_label" width="93">Shift</td>
                <td>
                  <select name="shift_type" id="shift_type" style="width:37%;">
                    <option value="ds">Day Shift</option>
                    <option value="ns">Night Shift</option>
                    <option value="ns_ds">Both</option>
                  </select>
                </td>
            </tr>
            <tr>
                <td></td>
                <td class="form-inline">                
                    <div class="rep-checkbox-container">
                      <label class="checkbox"><input type="checkbox" name="shift_schedule_remove_resigned" checked="checked" value="1" />Remove Resigned Employees</label> 
                      <label class="checkbox"><input type="checkbox" name="shift_schedule_remove_terminated" checked="checked" value="1" />Remove Terminated Employees</label>
                      <label class="checkbox"><input type="checkbox" name="shift_schedule_remove_endo" checked="checked" value="1" />Remove End of Contract</label>
                      <label class="checkbox"><input type="checkbox" name="shift_schedule_remove_inactive" checked="checked" value="1" />Remove Inactive Employees</label>
                    </div>
                </td>                
            </tr>     
        </table>
    </div>    
    <div id="form_default" class="form_action_section">
        <table width="100%">
            <tr>
                <td class="field_label">&nbsp;</td>
                <td><input class="blue_button" type="submit" name="button" id="button"  value="Search"></td>
            </tr>
        </table>
    </div>
</form>
</div>