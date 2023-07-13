<script>
	$(function() {		
		var h_department_id = $('#department_id').val();
		  var oTable = $('#leave_history_dt').dataTable({   
		   "aoColumns": [
					{ "bSortable": false,sWidth: '3%'},					
					{sWidth: '8%',sClass:'dt_small_font'},
					{sWidth: '10%',sClass:'dt_small_font'},
					{sWidth: '10%',sClass:'dt_small_font'},
					{sWidth: '8%',sClass:'dt_small_font'}					
			 ],
			"bStateSave": true,
			'bProcessing':true,
			'bServerSide':true,
			"bAutoWidth": true,
			"bInfo":false,
			"bJQueryUI": true,
			"aaSorting": [[ 1, "asc" ]],
			"sPaginationType": "full_numbers",
			"bPaginate": true,
			
			'sAjaxSource': base_url + 'overtime/_load_server_employee_list_dt?department='+h_department_id,
			"fnDrawCallback": function() {
					$('.i_container #edit').tipsy({gravity: 's'});
					$('.i_container #delete').tipsy({gravity: 's'});
					$('.i_container #view').tipsy({gravity: 's'});
					//document.getElementById('leave_history_dt').deleteTHead();
				}
			}).fnSetFilteringDelay();
	});
</script>
<div class="table-container">
<table id="leave_history_dt" class="display">
    <thead>
      <tr>
      	<th valign="top" width="10%"></th>       
        <th valign="top" width="10%">Employee Number</th>        
        <th valign="top" width="10%">Employee Name</th>
        <th valign="top" width="10%">Position</th>
        <th valign="top" width="10%">Employment Status</th>       
      </tr>
    </thead>
    <tbody>   
    </tbody>	
</table>
</div>
