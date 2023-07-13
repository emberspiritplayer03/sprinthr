<h2><?php echo $title; ?></h2>
<script>

$(function(){
    $("#frm-report-employee-details").validationEngine({scroll:false});
    $('#tags').tagsInput({width:'289px'});   
});
</script>
<div id="form_main" class="employee_form">
<form id="frm-report-employee-details" name="form1" method="post" action="<?php echo url('reports/download_employee_details'); ?>">
    <div id="form_default">
        <table width="100%">
            <tr>
                <td class="field_label">Search by:</td>
                <td>
                    <select class="select_option" name="search_field" id="employmee_details_search_field" onChange="javascript:checkIfAllEmploymentStatus();">
                        <option value="all">All</option>
                        <option value="firstname">Firstname</option>
                        <option value="lastname">Lastname</option>
                        <option value="employee_code">Employee Code</option
                        <option value="birthdate">Birthdate</option>
                        <option value="marital_status">Marital Status</option>
                    </select>                         
                    <input style="display:none;margin-top:5px;" type="text" name="search" id="employment_status_search" />
                    <input type="text" style="display:none;margin-top:5px;" name="birthdate" id="employment_status_birthdate" />
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
                        <option value="all">All Status</option>
                        <?php foreach($status as $key => $value) { ?>
                            <option value="<?php echo $value->id; ?>"><?php echo $value->status; ?></option>
                        <?php } ?> 
                    </select>
                </td>            
            </tr>

            <tr><td colspan="2">&nbsp;</td></tr>

            <tr>
                <td class="field_label">Report Type:</td>
                <td><label for="department_applied"></label>
                <select class="report_type" name="report_type" id="report_type" >
                    <option value="summarized">Summarized</option>
                    <option value="detailed">Detailed</option>
                </select></td>
            </tr>
            <tr>
                <td></td>
                <td class="form-inline">                
                    <div class="rep-checkbox-container">
                      <label class="checkbox"><input type="checkbox" name="ed_remove_resigned" checked="checked" value="1" />Remove Resigned Employees</label> 
                      <label class="checkbox"><input type="checkbox" name="ed_remove_terminated" checked="checked" value="1" />Remove Terminated Employees</label>
                      <label class="checkbox"><input type="checkbox" name="ed_remove_endo" checked="checked" value="1" />Remove End of Contract</label>
                      <label class="checkbox"><input type="checkbox" name="ed_remove_inactive" checked="checked" value="1" />Remove Inactive Employees</label>
                    </div>
                </td>                
            </tr>   
            <tr>
                <td class="field_label">Tags:</td>
                <td>
                    <input type="text" value="<?php echo $t ? $t->getTags() : ''; ?>" name="tags" id="tags" />
                </td>  
            </tr>
            <!-- <tr>
                <td></td>
                <td class="form-inline">                
                    <div class="rep-checkbox-container">
                      <label class="checkbox"><input type="checkbox" name="leave_balance_remove_resigned" checked="checked" value="1" />Remove Resigned Employees</label> 
                      <label class="checkbox"><input type="checkbox" name="leave_balance_remove_terminated" checked="checked" value="1" />Remove Terminated Employees</label>
                      <label class="checkbox"><input type="checkbox" name="leave_balance_remove_endo" checked="checked" value="1" />Remove End of Contract</label>
                      <label class="checkbox"><input type="checkbox" name="leave_balance_remove_inactive" checked="checked" value="1" />Remove Inactive Employees</label>
                    </div>
                </td>
            </tr> -->    

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