<script>
	$(function() {
		var h_branch_id = '<?php echo $h_branch_id; ?>';
		  var oTable = $('#employee_list_dt').dataTable({   
		   "aoColumns": [
				   	{ "bSortable": false,sWidth: '2%'},
					{ "bSortable": false,sWidth: '60%'},
			 ],
			'bProcessing':true,
			'bServerSide':true,
			"bAutoWidth": true,
			"bInfo":false,
			"bStateSave": true,
			"bJQueryUI": true,
			"aaSorting": [[ 1, "asc" ]],
			"sPaginationType": "full_numbers",
			"bPaginate": true,
			'sAjaxSource': base_url + 'settings/_load_server_employee_group_list_dt?h_branch_id='+h_branch_id,
			"fnDrawCallback": function() {
					$('.i_container #add_request').tipsy({gravity: 's'});
					$('.i_container #delete').tipsy({gravity: 's'});
					$('.i_container #view').tipsy({gravity: 's'});
				}
			}).fnSetFilteringDelay();
	});
</script>

<div class="table-container">
<table id="employee_list_dt" class="display">
    <thead>
      <tr>
      	<th valign="middle" width="2%"></th>
      	<th valign="top" width="10%">Department</th>
      </tr>
    </thead>
    <tbody>   
    </tbody>	
</table>
</div>
