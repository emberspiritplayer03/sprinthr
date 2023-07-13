<script>
$(function() { 
	  var oTable = $('#dtCompany').dataTable({   
	   "aoColumns": [		   		
				{sWidth: '20%',sClass:'dt_small_font'},
				{sWidth: '20%',sClass:'dt_small_font'},
				{sWidth: '20%',sClass:'dt_small_font'},	
				{sWidth: '20%',sClass:'dt_small_font'},	
				{sWidth: '20%',sClass:'dt_small_font'},
                {sWidth: '20%',sClass:'dt_small_font'},                					
		 ],

		"bAutoWidth": true,
		//"bStateSave": true,
		"bInfo":false,
		"bJQueryUI": true,
		"aaSorting": [[ 0, "asc" ]],
		"sPaginationType": "full_numbers", // full_numbers - two_button
		"bPaginate": true,
		"fnDrawCallback": function() {

			}
		});		
});
</script>

<div style="position:relative; top:-6px;" class="ui-state-highlight ui-corner-all">
	<span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span>
	<div id="total_result_wrapper">
		Total Record(s): <?php echo ($n ? $n->getItem() : 0);?>
	</div>
</div>

<div class="table-container">
<table id="dtCompany" class="mws-table mws-datatable thead-title-black">
    <thead>
      <tr>   
        <th>Employee Name</th>
        <th>Department</th>
        <th>Date</th>
        <th>Time In</th>
        <th>Time Out</th>
        <th>-</th>
      </tr>
    </thead>
    <tbody>   
    	<?php foreach($inc_dtr_data as $key => $value) { ?>
        <?php if(!empty($value['employee_name'])) { ?>
            	<tr>
            		<td>
            			<a target="_blank" href="<?php echo url("notifications/get_link?module=incomplete_dtr&emp_code=".$value['employee_code']) . "&attendance_date=" . $value['date_attendance']; ?> "  >
            				<?php echo $value['employee_name'] ?>
            			</a>
            		</td>
            		<td><?php echo $value['department_name'] ?></td>
            		<td><?php echo $value['date_attendance'] ?></td>
            		<td><?php echo !empty($value['actual_time_in']) ? $value['actual_time_in'] : '<div style="color: red;"><strong>No In</strong></div>'; ?></td>
            		<td><?php echo !empty($value['actual_time_out']) ? $value['actual_time_out'] : '<div style="color: red;"><strong>No Out</strong></div>'; ?></td>
                    <td>
                        <a target="_blank" href="<?php echo url("notifications/get_link?module=incomplete_dtr&emp_code=".$value['employee_code']) . "&attendance_date=" . $value['date_attendance']; ?> "  >
                            <i class=\"icon-edit\"></i> DTR
                        </a>
                        <a target="_blank" href="<?php echo url("notifications/get_link?module=incomplete_dtr&sub_module=incomplete_dtr_timesheet&emp_code=".$value['employee_code']) . "&attendance_date=" . $value['date_attendance']; ?> "  >
                            <i class=\"icon-list\"></i> Timesheet
                        </a>
                    </td>
            	<?php } ?>
            	</tr>
        <?php } ?>
    </tbody>	
</table>
</div>
