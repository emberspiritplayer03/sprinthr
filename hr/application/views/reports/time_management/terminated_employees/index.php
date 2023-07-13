<h2><?php echo $title; ?></h2>
<script>
$("#terminated_date_from").datepicker({	
	dateFormat:'yy-mm-dd',
	changeMonth:true,
	changeYear:true,
	showOtherMonths:true,
	onSelect	:function() { 
		$("#terminated_date_to").datepicker('option',{minDate:$(this).datepicker('getDate')});
	}
});	

$("#terminated_date_to").datepicker({	
	dateFormat:'yy-mm-dd',
	changeMonth:true,
	changeYear:true,
	showOtherMonths:true,
	onSelect	:function() { 
	
	}
});	

$(function(){
    $("#frm-report-terminated-employees").validationEngine({scroll:false}); 
});
</script>
<div id="form_main" class="employee_form">
<form id="frm-report-terminated-employees" name="form-terminated-employees" method="post" action="<?php echo url('reports/download_terminated_employees_data'); ?>">
     <div id="form_default">
        <h3 class="section_title">Date Applied</h3>
        <table width="100%">
            <tr>
                <td class="field_label">From:</td>
                <td><input type="text" id="terminated_date_from" name="date_from" class="validate[required]" /></td>
            </tr>
            <tr>
                <td class="field_label">To:</td>
                <td><input type="text" id="terminated_date_to" name="date_to" class="validate[required]" /></td>
            </tr>
    	</table>
    </div>
    <div class="form_separator"></div>
    <div id="form_default">
        <table width="100%">
        	<tr>
                <!--<td class="field_label">Report Type:</td>-->
                <td>
                    <select style="display : none;" class="select_option" name="report_type" id="report_type">
                    	<option value="<?php echo SUMMARIZED; ?>">Summarized</option>
                    	<option selected="selected" value="<?php echo DETAILED; ?>">Detailed</option> 
                    </select>              
            </tr>
            <tr>
                <td class="field_label">Search by:</td>
                <td>
                    <select class="select_option" name="search_field" id="eoc_search_field" onChange="javascript:checkIfAllEndOfContract();">
                        <option value="all">All</option>
                        <option value="firstname">Firstname</option>
                        <option value="lastname">Lastname</option>
                        <option value="employee_code">Employee Code</option>
                        <option value="birthdate">Birthdate</option>
                        <!--<option value="gender">Gender</option>-->
                        <option value="marital_status">Marital Status</option>
                        <!--<option value="address">Address</option>-->
                    </select>                         
                    <input style="display:none;margin-top:5px;" type="text" name="search" id="eoc_search" />
                    <input type="text" style="display:none;margin-top:5px;" name="birthdate" id="eoc_birthdate" />
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