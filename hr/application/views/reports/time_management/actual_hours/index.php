<h2><?php echo $title; ?></h2>
<script>
$("#birthdate_actual_hours").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#actual_hours_date_from").datepicker({	
	dateFormat:'yy-mm-dd',
	changeMonth:true,
	changeYear:true,
	showOtherMonths:true,
	onSelect	:function() { 
		$("#actual_hours_date_to").datepicker('option',{minDate:$(this).datepicker('getDate')});
	}
});	

$("#actual_hours_date_to").datepicker({	
	dateFormat:'yy-mm-dd',
	changeMonth:true,
	changeYear:true,
	showOtherMonths:true,
	onSelect	:function() { 
	
	}
});	

$(function(){
    $("#frm-report-actual-hours").validationEngine({scroll:false}); 
});
</script>
<div id="form_main" class="employee_form">
<form id="frm-report-actual-hours" name="form1" method="post" action="<?php echo url('reports/download_actual_hours_data'); ?>">
     <div id="form_default">
        <h3 class="section_title">Date Applied</h3>
        <table width="100%">
            <tr>
                <td class="field_label">From:</td>
                <td><input type="text" id="actual_hours_date_from" class="validate[required]" name="actual_hours_date_from" /></td>
            </tr>
            <tr>
                <td class="field_label">To:</td>
                <td><input type="text" id="actual_hours_date_to" class="validate[required]"  name="actual_hours_date_to"  /></td>
            </tr>
    	</table>
    </div>
    <div class="form_separator"></div>
    <div id="form_default">
        <table width="100%">
            <tr>
                <td class="field_label">Search by:</td>
                <td>
                    <select class="select_option" name="search_field" id="search_field" onChange="javascript:checkIfAllActualHoursReport();">
                        <option value="all">All</option>
                        <option value="firstname">Firstname</option>                        
                        <option value="lastname">Lastname</option>
                        <option value="employee_code">Employee Code</option>
                        <option value="birthdate">Birthdate</option>
                        <option value="marital_status">Marital Status</option>
                    </select>                         
                    <input style="display:none; margin-top:5px;" type="text" name="search" id="search" />
                    <input type="text" style="display:none; margin-top:5px;" name="birthdate" id="birthdate_actual_hours" />
                </td>
            </tr>
            <tr>
            	<td class="field_label">Position:</td>
                <td><label for="position_applied"></label>
                <select class="select_option" name="position_applied" id="position_applied" >
                <option value="all">All Position</option>
                <?php foreach($job as $key=>$value) { ?>
                <option value="<?php echo $value->id; ?>"><?php echo $value->title; ?></option>
                <?php } ?>
                </select></td>
            </tr>     
            <tr>
            	<td class="field_label">Department:</td>
                <td><label for="department_applied"></label>
                <select class="select_option" name="department_applied" id="department_applied" >
                <option value="all">All Department</option>
                <?php foreach($departments as $d) { ?>
                <option value="<?php echo $d->getId(); ?>"><?php echo $d->getTitle(); ?></option>
                <?php } ?>
                </select></td>
            </tr>     
            <tr>
                <td></td>
                <td class="form-inline">                
                    <div class="rep-checkbox-container">
                      <label class="checkbox"><input type="checkbox" name="absences_remove_resigned" checked="checked" value="1" />Remove Resigned Employees</label> 
                      <label class="checkbox"><input type="checkbox" name="absences_remove_terminated" checked="checked" value="1" />Remove Terminated Employees</label>
                      <label class="checkbox"><input type="checkbox" name="absences_remove_endo" checked="checked" value="1" />Remove End of Contract</label>
                      <label class="checkbox"><input type="checkbox" name="absences_remove_inactive" checked="checked" value="1" />Remove Inactive Employees</label>
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
<div class="yui-skin-sam">
  <div id="applicant_list_datatable"></div>
</div>
