<script>
	$(function() {
		  var oTable = $('#approved_leave_list').dataTable({   
		   "aoColumns": [										
					{sWidth: '8%',sClass:'dt_small_font'},
					{sWidth: '8%',sClass:'dt_small_font'},
					{sWidth: '8%',sClass:'dt_small_font'}					
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
			'sAjaxSource': base_url + 'leave/_load_server_approved_leave_list_dt',
			"fnDrawCallback": function() {
					$('.i_container #edit').tipsy({gravity: 's'});
					$('.i_container #delete').tipsy({gravity: 's'});
					$('.i_container #view').tipsy({gravity: 's'});
				}
			}).fnSetFilteringDelay();
	});
</script>
<div class="table-container">
<table id="approved_leave_list" class="display">
    <thead>
      <tr>      	                
        <th valign="top" width="10%">Leave Type</th>
        <th valign="top" width="10%">From</th>
        <th valign="top" width="10%">To</th>
      </tr>
    </thead>
    <tbody>   
    </tbody>	
</table>
</div>
