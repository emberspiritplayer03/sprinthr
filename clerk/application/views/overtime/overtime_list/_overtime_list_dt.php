<script>
	$(function() {
		  var oTable = $('#event_people_list_dt').dataTable({   
		   "aoColumns": [
					{ "bSortable": false,sWidth: '4.5%'},
					{sWidth: '30%',sClass:'dt_small_font'},
					{sWidth: '8%',sClass:'dt_small_font'},
					{sWidth: '7.5%',sClass:'dt_small_font'},
					{sWidth: '7.5%',sClass:'dt_small_font'},
					{sWidth: '8%',sClass:'dt_small_font'},
					{sWidth: '8%',bVisible:false,sClass:'dt_small_font'},
			 ],
			'bProcessing':true,
			'bServerSide':true,
			"bAutoWidth": true,
			"bInfo":false,
			"bJQueryUI": true,
			"aaSorting": [[ 1, "desc" ]],
			"sPaginationType": "full_numbers",
			"bPaginate": true,
			'sAjaxSource': base_url + 'overtime/_load_server_overtime_list_dt?employee_id=<?php echo $h_employee_id; ?>',
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
        <th valign="top" width="10%">Reason</th>
        <th valign="top" width="10%">Date</th>
        <th valign="top" width="10%">Time In</th>
        <th valign="top" width="10%">Time Out</th>
        <th valign="top" width="10%">Status</th>
        <th valign="top" width="10%"></th>
      </tr>
    </thead>
    <tbody>   
    </tbody>	
</table>
</div>
