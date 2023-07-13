<script>
	$(function() {
		  var oTable = $('#overtime_list_dt').dataTable({   
		   "aoColumns": [
					{ "bSortable": false,sWidth: '9%'},
					{sWidth: '13%',sClass:'dt_small_font'},
					{sWidth: '10%', bVisible: false, sClass:'dt_small_font'},
					{sWidth: '25%',sClass:'dt_small_font'},
					{sWidth: '11%',sClass:'dt_small_font'},
					{sWidth: '4.5%',sClass:'dt_small_font'},
					{sWidth: '4.5%',sClass:'dt_small_font'},
					{sWidth: '4.5%',sClass:'dt_small_font'},
			 ],
			'bProcessing':true,
			'bServerSide':true,
			"bAutoWidth": true,
			"bInfo":false,
			"bJQueryUI": true,
			"aaSorting": [[ 1, "desc" ]],
			"sPaginationType": "full_numbers",
			"bPaginate": true,
			'sAjaxSource': base_url + 'request/_load_server_fa_overtime_list_dt',
			"fnDrawCallback": function() {
					$('.i_container #edit').tipsy({gravity: 's'});
					$('.i_container #view_approvers').tipsy({gravity: 's'});
					$('.i_container #view').tipsy({gravity: 's'});
				}
			}).fnSetFilteringDelay();
	});
</script>

<div class="table-container">
<table id="overtime_list_dt" class="display">
    <thead>
      <tr>
      	<th valign="top" width="10%"></th>
        <th valign="top" width="10%">Employee Name</th>
        <th valign="top" width="10%"></th>
        <th valign="top" width="10%">Reason</th>
        <th valign="top" width="10%">Date</th>
        <th valign="top" width="10%">Time In</th>
        <th valign="top" width="10%">Time Out</th>
        <th valign="top" width="10%">Status</th>
      </tr>
    </thead>
    <tbody>   
    </tbody>	
</table>
</div>
