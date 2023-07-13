
<script>
	$(function() {
		var h_employee_id = '<?php echo $h_employee_id; ?>';
		  var oTable = $('#pending_overtime_list_dt').dataTable({   
		   "aoColumns": [
					{sWidth: '7%',sClass:'dt_small_font'},
					{sWidth: '7%',sClass:'dt_small_font'},
					{sWidth: '7%',sClass:'dt_small_font'},
					{sWidth: '20%',sClass:'dt_small_font'},
			 ],
			'bProcessing':true,
			'bServerSide':true,
			"bAutoWidth": true,
			"bStateSave": true,
			"bInfo":false,
			"bJQueryUI": true,
			"aaSorting": [[ 1, "asc" ]],
			"sPaginationType": "full_numbers",
			"bPaginate": true,
			'sAjaxSource': base_url + 'overtime/_load_server_employee_overtime_history_list_dt?employee_id='+h_employee_id,
			"fnDrawCallback": function() {
					$('.i_container #add_request').tipsy({gravity: 's'});
					$('.i_container #delete').tipsy({gravity: 's'});
					$('.i_container #view').tipsy({gravity: 's'});
				}
			}).fnSetFilteringDelay();
	});
</script>

<div class="table-container">
<table id="pending_overtime_list_dt" class="formtable">
    <thead>
      <tr>
        <th valign="top" width="10%">Date of Overtime</th>
        <th valign="top" width="10%">Time In</th>
        <th valign="top" width="10%">Time Out</th>
        <th valign="top" width="10%">Reason</th>
      </tr>
    </thead>
    <tbody>   
    </tbody>	
</table>
</div>
