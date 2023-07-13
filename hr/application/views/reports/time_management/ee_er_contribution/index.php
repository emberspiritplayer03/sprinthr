<h2><?php echo $title; ?></h2>
<script>
$("#ee_er_contribtion_birthdate").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#ee_er_contribtion_date_from").datepicker({	
	dateFormat:'yy-mm-dd',
	changeMonth:true,
	changeYear:true,
	showOtherMonths:true,
	onSelect	:function() { 
		$("#ee_er_contribtion_date_to").datepicker('option',{minDate:$(this).datepicker('getDate')});
	}
});	

$("#ee_er_contribtion_date_to").datepicker({	
	dateFormat:'yy-mm-dd',
	changeMonth:true,
	changeYear:true,
	showOtherMonths:true,
	onSelect	:function() { 
	
	}
});	

$(function(){
    $("#frm-report-ee-er-contribution").validationEngine({scroll:false}); 
});
</script>
<div id="form_main" class="employee_form">
<form id="frm-report-ee-er-contribution" name="form1" method="post" action="<?php echo url('reports/download_ee_er_contribution'); ?>">
     <div id="form_default">
        <h3 class="section_title">Date Applied</h3>
        <table width="100%">
            <tr>
                <td class="field_label">From:</td>
                <td><input type="text" id="ee_er_contribtion_date_from" name="date_from" class="validate[required]" /></td>
            </tr>
            <tr>
                <td class="field_label">To:</td>
                <td><input type="text" id="ee_er_contribtion_date_to" name="date_to" class="validate[required]" /></td>
            </tr>
    	</table>
    </div>
    <div class="form_separator"></div>
    <div id="form_default">
        <table width="100%">
        	<!-- <tr>
                <td class="field_label">Report Type:</td>
                <td>
                    <select class="select_option" name="report_type" id="report_type">
                    	<option value="<?php echo SUMMARIZED; ?>">Summarized</option>
                    	<option selected="selected" value="<?php echo DETAILED; ?>">Detailed</option> 
                    </select>              
            </tr> -->
            <tr>
                <td class="field_label">Contribution Type:</td>
                <td style="font-size:12px;">
                     <input id="sss" name="contribution_type[]" value="sss" type="checkbox" class="validate[required]"> <label style="display:inline-flex; vertical-align:text-top;" for="sss"> SSS</label><br/>
                     <input id="pagibig" name="contribution_type[]" value="pagibig" type="checkbox" class="validate[required]"> <label style="display:inline-flex; vertical-align:text-top" for="pagibig"> Pagibig</label><br/>
                     <input id="philhealth" name="contribution_type[]" value="philhealth" type="checkbox" class="validate[required]"> <label style="display:inline-flex; vertical-align:text-top" for="philhealth"> Philhealth</label><br/>
                </td>
            </tr>
            <tr>
                <td class="field_label">Search by:</td>
                <td>
                    <select class="select_option" name="search_field" id="ee_er_contribtion_search_field" onChange="javascript:checkIfAllEeErContribution();">
                        <option value="all">All</option>
                        <option value="firstname">Firstname</option>
                        <option value="lastname">Lastname</option>
                        <option value="employee_code">Employee Code</option>
                        <option value="birthdate">Birthdate</option>
                        <!--<option value="gender">Gender</option>-->
                        <option value="marital_status">Marital Status</option>
                        <!--<option value="address">Address</option>-->
                    </select>                         
                    <input style="display:none;margin-top:5px;" type="text" name="search" id="ee_er_contribtion_search" />
                    <input type="text" style="display:none;margin-top:5px;" name="birthdate" id="ee_er_contribtion_birthdate" />
                </td>
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
                      <label class="checkbox"><input type="checkbox" name="eeer_remove_resigned" checked="checked" value="1" />Remove Resigned Employees</label> 
                      <label class="checkbox"><input type="checkbox" name="eeer_remove_terminated" checked="checked" value="1" />Remove Terminated Employees</label>
                      <label class="checkbox"><input type="checkbox" name="eeer_remove_endo" checked="checked" value="1" />Remove End of Contract</label>
                      <label class="checkbox"><input type="checkbox" name="eeer_remove_inactive" checked="checked" value="1" />Remove Inactive Employees</label>
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
