<script>
	$(function() {
		  var oTable = $('#employee_leave_available_dt').dataTable({   
		   "aoColumns": [					
					{sWidth: '7%',sClass:'dt_small_font'},
					{sWidth: '7%',sClass:'dt_small_font'},					
					{sWidth: '8%',sClass:'dt_small_font'}
			 ],
			'bProcessing':true,
			'bServerSide':true,
			"bAutoWidth": true,
			"bInfo":false,
			"bJQueryUI": true,
			"aaSorting": [[ 1, "desc" ]],
			"sPaginationType": "full_numbers",
			"bPaginate": true,
			'sAjaxSource': base_url + 'leave/_load_server_employee_leave_available_dt?employee_id=<?php echo Utilities::encrypt($h_employee_id); ?>',
			"fnDrawCallback": function() {
					$('.i_container #edit').tipsy({gravity: 's'});
					$('.i_container #delete').tipsy({gravity: 's'});
					$('.i_container #view').tipsy({gravity: 's'});
				}
			}).fnSetFilteringDelay();
	});
</script>
<h3 class="section_title">Leave Available</h3>
<br />
<div class="table-container">
<table id="employee_leave_available_dt" class="display">
    <thead>
      <tr>      	
        <th valign="top" width="10%">Leave Type</th>
        <th valign="top" width="10%">Alloted Days</th>
        <th valign="top" width="10%">Available Days</th>        
      </tr>
    </thead>
    <tbody>   
    </tbody>	
</table>
</div>
