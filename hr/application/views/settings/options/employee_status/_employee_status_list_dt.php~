<script>
	$(function() {
		  var oTable = $('#employee_status').dataTable({   
		   "aoColumns": [		   		
					{ "bSortable": false,sWidth: '8%'},									
					{sWidth: '60%',sClass:'dt_small_font'}					
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
			'sAjaxSource': base_url + 'settings/_load_server_employee_status_list_dt',
			"fnDrawCallback": function() {					
					$('.i_container #edit').tipsy({gravity: 's'});
					$('.i_container #delete').tipsy({gravity: 's'});
					$('.i_container #view').tipsy({gravity: 's'});
				}
			}).fnSetFilteringDelay();
	});
</script>
<div class="table-container">
<table id="employee_status" class="display">
    <thead>
      <tr>     
        <th valign="top" width="10%"></th>       
        <th valign="top" width="10%">Name</th>
      </tr>
    </thead>
    <tbody>   
    </tbody>	
</table>
</div>
