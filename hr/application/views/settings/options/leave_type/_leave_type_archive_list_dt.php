<script>
	$(function() {
		  var oTable = $('#leave_list').dataTable({   
		   "aoColumns": [		   		
					{ "bSortable": false,sWidth: '5%'},									
					{sWidth: '70%',sClass:'dt_small_font'},
					{sWidth: '10%',sClass:'dt_small_font dt_center'}						
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
			'sAjaxSource': base_url + 'settings/_load_server_archive_leave_type_list_dt',
			"fnDrawCallback": function() {					
					$('.i_container #edit').tipsy({gravity: 's'});
					$('.i_container #delete').tipsy({gravity: 's'});
					$('.i_container #view').tipsy({gravity: 's'});
				}
			}).fnSetFilteringDelay();
	});
</script>
<div class="table-container">
<table id="leave_list" class="display">
    <thead>
      <tr>     
      	<th valign="top" width="10%"></th>       
        <th valign="top" width="10%">Leave Type</th>  
        <th valign="top" width="10%">Is Paid</th>       
      </tr>
    </thead>
    <tbody>   
    </tbody>	
</table>
</div>
