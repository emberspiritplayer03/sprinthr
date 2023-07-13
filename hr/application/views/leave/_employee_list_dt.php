<script>
	$(function() {
		  var oTable = $('#event_people_list_dt').dataTable({   
		   "aoColumns": [
					{ "bSortable": false,sWidth: '10%'},					
					{sWidth: '25%',sClass:'dt_small_font'},
					{sWidth: '10%',sClass:'dt_small_font'},
					{sWidth: '8%',sClass:'dt_small_font'},
					{sWidth: '8%',sClass:'dt_small_font'},
					{sWidth: '8%',sClass:'dt_small_font'},
					{sWidth: '8%',sClass:'dt_small_font'},
			 ],
			'bProcessing':true,
			'bServerSide':true,
			"bAutoWidth": true,
			"bInfo":false,
			"bJQueryUI": true,
			"aaSorting": [[ 1, "asc" ]],
			"sPaginationType": "full_numbers",
			"bPaginate": true,
			'sAjaxSource': base_url + 'leave/_load_server_employee_list_dt',
			"fnDrawCallback": function() {
					$('.i_container #edit').tipsy({gravity: 's'});
					$('.i_container #delete').tipsy({gravity: 's'});
					$('.i_container #view').tipsy({gravity: 's'});
				}
			}).fnSetFilteringDelay();
	});
</script>

<div class="table-container">
<table id="event_people_list_dt" class="display">
    <thead>
      <tr>
      	<th valign="top" width="10%"></th>       
        <th valign="top" width="10%">Employee Name</th>
        <th valign="top" width="10%">Position</th>
        <th valign="top" width="10%">Leave Type</th>
        <th valign="top" width="10%">Date Start</th>
        <th valign="top" width="10%">Date End</th>
        <th valign="top" width="10%">Status</th>
      </tr>
    </thead>
    <tbody>   
    </tbody>	
</table>
</div>
