<script>
	$(function() {
		var h_department_id = $('#department_id').val();
		  var oTable = $('#archived_overtime_list_dt').dataTable({   
		   "aoColumns": [
		   			{ "bSortable": false,sWidth: '8%'},
					{sWidth: '15%',sClass:'dt_small_font'},
					{sWidth: '10%',sClass:'dt_small_font'},
					{sWidth: '7%',sClass:'dt_small_font'},
					{sWidth: '7%',sClass:'dt_small_font'},
					{sWidth: '7%',sClass:'dt_small_font'},
					{sWidth: '20%',sClass:'dt_small_font'},
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
			'sAjaxSource': base_url + 'overtime/_load_server_restore_overtime_list_dt?department='+h_department_id,
			"fnDrawCallback": function() {
					$('input#check_uncheck').tipsy({gravity: 's', live: true});	
					$('.i_container #add_request').tipsy({gravity: 's'});
					$('.i_container #delete').tipsy({gravity: 's'});
					$('.i_container #view').tipsy({gravity: 's'});
				}
			}).fnSetFilteringDelay();
	});
</script>

<div class="table-container">
<table id="archived_overtime_list_dt" class="display">
    <thead>
      <tr>
      	<th valign="top" width="10%"><input title="Check All" type="checkbox" id="check_uncheck" name="check_uncheck" onclick="chkUnchk();" /></th>
        <th valign="top" width="10%">Name</th>
        <th valign="top" width="10%">Position</th>
        <th valign="top" width="10%">Date</th>
        <th valign="top" width="10%">In</th>
        <th valign="top" width="10%">Out</th>
        <th valign="top" width="10%">Reason</th>
      </tr>
    </thead>
    <tbody>   
    </tbody>	
</table>
</div>
