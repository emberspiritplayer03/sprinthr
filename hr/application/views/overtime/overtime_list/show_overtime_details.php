<script>
	$(function() {
		load_overtime_list_dt('<?php echo $h_employee_id; ?>');}
	);
</script>

<div id="request_overtime_form_wrapper" style="display:none;"><?php include_once('form/request_overtime.php'); ?></div>
<div id="employee_view">
	<div class="employee_viewholder">
    	<div class="employee_view_photo"><img src="<?php echo $filename; ?>" alt="Employee Photo" ></div>
        	<div class="sectionarea">
                <div id="formwrap" class="employee_form_summary">
                    <div id="leave_available_table_wrapper">
                      <div id="form_main" class="inner_form">
                        <div id="form_default">
                          <h3 class="section_title">Employee Information</h3>
                          <table width="100%">
                            <tr>
                                <td class="field_label">Name:</td>                                
                                <td><strong><?php echo $employee['employee_name']; ?></strong></td>
                                <td class="field_label">Position:</td>
                                <td><?php echo $employee['position']; ?></td>
                              </tr>
                              <tr>
                                <td class="field_label">Employee Code:</td>
                                <td><?php echo $employee['employee_code']; ?></td>
                                <td class="field_label">Employment Status:</td>
                                <td><?php echo $employee['employment_status']; ?></td>
                              </tr>
                              <tr>
                                <td class="field_label">Department:</td>
                                <td><?php echo $employee['department']; ?></td>
                                <td class="field_label">Hired Date:</td>
                                <td><?php echo $employee['hired_date']; ?></td>
                              </tr>
                            </table>                          
                        </div><!-- #form_default -->
                      </div><!-- #form_main.inner-form -->
                    </div><!-- #leave_available_table_wrapper -->
                </div><!-- #formwrap.employee_form_summary -->
            </div><!-- .sectionarea -->
          
			<div id="overtime_list_dt_wrapper"></div>
        <div class="clear"></div>
    </div>
</div><!-- #employee_view -->
