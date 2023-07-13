<style>
table#pending_overtime_list_dt thead tr th{padding:0 !important;}
</style>
<script>
	$(function() {
		var h_department_id = $('#department_id').val();
		  var oTable = $('#pending_overtime_list_dt').dataTable({   
		   "aoColumns": [
					{ "bSortable": false,sWidth: '6%'},
					{sWidth: '15%',sClass:'dt_small_font'},
					{sWidth: '10%',sClass:'dt_small_font'},
					{sWidth: '7%',sClass:'dt_small_font'},
					{sWidth: '7%',sClass:'dt_small_font'},
					{sWidth: '7%',sClass:'dt_small_font'},
					{sWidth: '20%',sClass:'dt_small_font'},
					{sWidth: '7%',sClass:'dt_small_font'},
			 ],
			'bProcessing':true,
			'bServerSide':true,
			"bAutoWidth": true,
			"bInfo":false,
			"bJQueryUI": true,
			"aaSorting": [[ 1, "asc" ]],
			"sPaginationType": "full_numbers",
			"sScrollX": "100%",
			"sScrollXInner": "150%",
			"bPaginate": true,
			'sAjaxSource': base_url + 'reconstructed_overtime/_load_server_pending_overtime_list_dt?department='+h_department_id,
			"fnDrawCallback": function() {
					$('.i_container #add_request').tipsy({gravity: 's'});
					$('.i_container #delete').tipsy({gravity: 's'});
					$('.i_container #view').tipsy({gravity: 's'});
				}
			}).fnSetFilteringDelay();
	});
</script>

<div class="table-container">
<table id="pending_overtime_list_dt" class="display">
    <thead>
      <tr>
      	<th valign="top" width="10%"></th>
        <th valign="top" width="10%">Employee Name</th>
        <th valign="top" width="10%">Position</th>
        <th valign="top" width="10%">Date of Overtime</th>
        <th valign="top" width="10%">Time In</th>
        <th valign="top" width="10%">Time Out</th>
        <th valign="top" width="10%">Reason</th>
        <th valign="top" width="10%">Status</th>
        
      </tr>
    </thead>
    <tbody>   
    </tbody>	
</table>
</div>
