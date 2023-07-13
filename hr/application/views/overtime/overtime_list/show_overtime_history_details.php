<style>
div#formwrap{margin:0 0px 30px !important;}
div#form_default table td {text-align:right;}
</style>
<script>
	$(function() {		
		load_employee_overtime_history_list_dt('<?php echo $h_employee_id; ?>');
	});
</script>
<div id="request_leave_form_wrapper"></div>
<div id="formcontainer">
<div id="formwrap">	
	<h3 class="form_sectiontitle">Employee Overtime History</h3>
    <div id="form_main">
    <form id="leave_request_edit_form" name="form1" method="post" action="<?php echo url('leave/_update_leave_request'); ?>" >
    <input type="hidden" name="emp_id" id="emp_id" value="<?php echo $h_employee_id; ?>" />
    <div class="sectionarea">
       <div id="formwrap" class="employee_form_summary">
            <div id="leave_available_table_wrapper">
              <div id="form_main" class="inner_form">
                <div id="form_default">
                  <h3 class="section_title">Employee Information</h3>
                  <table width="100%">
                  	  <tr>
                      	<td><img src="<?php echo $filename; ?>" alt="Employee Photo" ></td>
                        <td>
                        	<table>
                           	    <tr>
                                <td class="field_label">Name:</td>                                
                                <td><strong><?php echo $employee['employee_name']; ?></strong></td>
                                </tr>
                                <tr>
                                <td class="field_label">Position:</td>
                                <td><?php echo $employee['position']; ?></td>
                                </tr>
                                <tr>
                                <td class="field_label">Employee Code:</td>
                                <td><?php echo $employee['employee_code']; ?></td>
                                </tr>
                                <tr>
                                <td class="field_label">Employment Status:</td>
                                <td><?php echo $employee['employment_status']; ?></td>
                                </tr>
                                <tr>
                                <td class="field_label">Department:</td>
                                <td><?php echo $employee['department']; ?></td>
                                </tr>
                                <tr>
                                <td class="field_label">Hired Date:</td>
                                <td><?php echo $employee['hired_date']; ?></td>
                                </tr>        	
                            </table>
                        </td>
                      </tr>                  	                
                    </table>                          
                </div><!-- #form_default -->
              </div><!-- #form_main.inner-form -->
            </div><!-- #leave_available_table_wrapper -->
        </div><!-- #formwrap.employee_form_summary -->
    </div>
    
    <div id="employee_overtime_history_dt_wrapper" class="dtContainer"></div>
    
    <div id="form_default" class="form_action_section">
    	<table width="100%" border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td>
                <button type="button" class="curve blue_button" onclick="javascript:load_hide_overtime_details();">Close</button>           
                </td>
            </tr>
        </table>
    </div>
    
    </form>
    <div class="clear"></div>
    </div><!-- #employee_view -->
</div>