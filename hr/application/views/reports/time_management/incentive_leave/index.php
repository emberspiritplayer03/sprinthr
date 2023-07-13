<h2><?php echo $title; ?></h2>
<script>
$("#incentive_leave_birthdate").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$(function(){
    $("#frm-report-incentive-leave").validationEngine({scroll:false}); 
});
</script>
<div id="form_main" class="employee_form">
<form id="frm-report-incentive-leave" name="form1" method="post" action="<?php echo url('reports/download_incentive_leave_data'); ?>">
     <div id="form_default">
        <h3 class="section_title">Date Applied</h3>
        <table width="100%">
            <tr>
                <td class="field_label">Year:</td>
                <td>   
                    <select name="incentive_leave_year" id="incentive_leave_year">
                    <?php for( $start = $start_year; $start <= date("Y"); $start++ ){ ?>
                        <option value="<?php echo $start; ?>"><?php echo $start; ?></option>
                    <?php } ?>
                    </select>
                </td>
            </tr>            
    	</table>
    </div>
    <div class="form_separator"></div>
    <div id="form_default">
        <table width="100%">        	
            <tr>
                <td class="field_label">Search by:</td>
                <td>
                    <select class="select_option" name="search_field" id="incentive_leave_search_field" onChange="javascript:checkIfAllIncentiveLeave();">
                        <option value="all">All</option>
                        <option value="firstname">Firstname</option>
                        <option value="lastname">Lastname</option>
                        <option value="employee_code">Employee Code</option>
                        <option value="birthdate">Birthdate</option>                        
                        <option value="marital_status">Marital Status</option>                        
                    </select>                         
                    <input style="display:none;margin-top:5px;" type="text" name="search" id="incentive_leave_search" />
                    <input type="text" style="display:none;margin-top:5px;" name="birthdate" id="incentive_leave_birthdate" />
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
                      <label class="checkbox"><input type="checkbox" name="incentive_leave_remove_resigned" checked="checked" value="1" />Remove Resigned Employees</label> 
                      <label class="checkbox"><input type="checkbox" name="incentive_leave_remove_terminated" checked="checked" value="1" />Remove Terminated Employees</label>
                      <label class="checkbox"><input type="checkbox" name="incentive_leave_remove_endo" checked="checked" value="1" />Remove End of Contract</label>
                      <label class="checkbox"><input type="checkbox" name="incentive_leave_remove_inactive" checked="checked" value="1" />Remove Inactive Employees</label>
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
