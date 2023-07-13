<script>
$(function() { 
	  var oTable = $('#dtCompany').dataTable({   
	   "aoColumns": [		   		
				{"bSortable": false,sWidth: '5%','bVisible':false },
				{sWidth: '20%',sClass:'dt_small_font'},
				{sWidth: '20%',sClass:'dt_small_font'},
				{'bVisible':true,"bSortable": false,sWidth: '10%',sClass:'dt_small_font action_button'}							
		 ],
		'bProcessing':false,
		'bServerSide':false,
		"bAutoWidth": true,
		"bInfo":false,
		"bJQueryUI": true,
		"aaSorting": [[ 1, "asc" ]],
		"sPaginationType": "two_button",
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
      	<th></th>  
        <th>Employee Name</th>
        <th>Cutoff</th>
        <th></th>
      </tr>
    </thead>
    <tbody>  
    	<?php foreach($no_payslip_data as $npd) { ?> 
	    	<tr>
	    		<td>-</td>
	    		<td><?php echo $npd['full_name']; ?></td>
	    		<td><?php echo $npd['cutoff_period']; ?></td>
	    		<td>
                    <a target="_blank" href="<?php echo url("notifications/get_link?module=payroll&sub_module=payroll_profile&emp_code=".$npd['employee_code']) . ""; ?> "  >
                        <i class="icon-edit"></i> PROFILE
                    </a>
                    <br />
                    <a target="_blank" href="<?php echo url("notifications/get_link?module=payroll&sub_module=payroll_dtr&emp_code=".$npd['employee_code']) . "&from=" . $from . "&to=" . $to; ?> "  >
                        <i class="icon-list"></i> DTR
                    </a>
                    <br />
                    <a target="_blank" href="<?php echo url("notifications/get_link?module=payroll&sub_module=payroll_timesheet&emp_code=".$npd['employee_code']) . "&from=" . $from . "&to=" . $to; ?> "  >
                        <i class="icon-edit"></i> TIMESHEET
                    </a>
                    <br />
                    <a target="_blank" href="<?php echo url("notifications/get_link?module=payroll&sub_module=payroll_schedule&emp_code=".$npd['employee_code']) . "&from=" . $from . "&to=" . $to; ?> "  >
                        <i class="icon-list"></i> SCHEDULE
                    </a>
	    		</td>
	    	</tr>
    <?php } ?>
    </tbody>	
</table>
</div>