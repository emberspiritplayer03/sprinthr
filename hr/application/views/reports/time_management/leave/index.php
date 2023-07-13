<h2><?php echo $title; ?></h2>
<script>
$("#leave_birthdate").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#leave_date_from").datepicker({	
	dateFormat:'yy-mm-dd',
	changeMonth:true,
	changeYear:true,
	showOtherMonths:true,
	onSelect	:function() { 
		$("#leave_date_to").datepicker('option',{minDate:$(this).datepicker('getDate')});
	}
});	

$("#leave_date_to").datepicker({	
	dateFormat:'yy-mm-dd',
	changeMonth:true,
	changeYear:true,
	showOtherMonths:true,
	onSelect	:function() { 
	
	}
});	

$(function(){
    $("#frm-report-leave").validationEngine({scroll:false}); 
});
</script>
<div id="form_main" class="employee_form">
<form id="frm-report-leave" name="form1" method="post" action="<?php echo url('reports/download_leave_data'); ?>">
     <div id="form_default">
        <h3 class="section_title">Date Applied</h3>
        <table width="100%">
            <tr>
                <td class="field_label">From:</td>
                <td><input type="text" id="leave_date_from" name="date_from" class="validate[required]" /></td>
            </tr>
            <tr>
                <td class="field_label">To:</td>
                <td><input type="text" id="leave_date_to" name="date_to" class="validate[required]" /></td>
            </tr>
    	</table>
    </div>
    <div class="form_separator"></div>
    <div id="form_default">
        <table width="100%">
        	<tr>
                <!--<td class="field_label">Report Type:</td>-->
                <td>
                    <select style="display:none;" class="select_option" name="report_type" id="report_type">
                    	<option value="<?php echo SUMMARIZED; ?>">Summarized</option>
                    	<option selected="selected" value="<?php echo DETAILED; ?>">Detailed</option> 
                    </select> 
                </td>             
            </tr>
            <tr>
                <td class="field_label">Search by:</td>
                <td>
                    <select class="select_option" name="search_field" id="leave_search_field" onChange="javascript:checkIfAllLeave();">
                        <option value="all">All</option>
                        <option value="firstname">Firstname</option>
                        <option value="lastname">Lastname</option>
                        <option value="employee_code">Employee Code</option>
                        <option value="birthdate">Birthdate</option>
                        <!--<option value="gender">Gender</option>-->
                        <option value="marital_status">Marital Status</option>
                        <!--<option value="address">Address</option>-->
                    </select>                         
                    <input style="display:none;margin-top:5px;" type="text" name="search" id="leave_search" />
                    <input type="text" style="display:none;margin-top:5px;" name="birthdate" id="leave_birthdate" />
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
                <td class="field_label">Status:</td>
                <td>
                    <select class="select_option" name="status" id="status">
                        <option value="all" >All</option>
                    	<option value="<?php echo G_Employee_Leave_Request::APPROVED; ?>" ><?php echo G_Employee_Leave_Request::APPROVED; ?></option>
                    	<option value="<?php echo G_Employee_Leave_Request::PENDING; ?>" ><?php echo G_Employee_Leave_Request::PENDING; ?></option> 
                        <option value="<?php echo G_Employee_Leave_Request::DISAPPROVED; ?>" ><?php echo G_Employee_Leave_Request::DISAPPROVED; ?></option> 
                    </select>              
            </tr>

            <tr>
                <td class="field_label">Project Site:</td>
                <td><label for="department_applied"></label>
                <select class="select_option" name="project_site_id" id="project_site_id" >
                <option value="all">All Project Site</option>
                     <?php foreach($project_site as $key=>$value){  ?>
                          <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>

                      <?php } ?>
                </select></td>
            </tr>  
            
            <tr>
                <td></td>
                <td class="form-inline">                
                    <div class="rep-checkbox-container">
                      <label class="checkbox"><input type="checkbox" name="leave_remove_resigned" checked="checked" value="1" />Remove Resigned Employees</label> 
                      <label class="checkbox"><input type="checkbox" name="leave_remove_terminated" checked="checked" value="1" />Remove Terminated Employees</label>
                      <label class="checkbox"><input type="checkbox" name="leave_remove_endo" checked="checked" value="1" />Remove End of Contract</label>
                      <label class="checkbox"><input type="checkbox" name="leave_remove_inactive" checked="checked" value="1" />Remove Inactive Employees</label>
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