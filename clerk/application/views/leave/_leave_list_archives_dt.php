<script>
	$(function() {
		  var oTable = $('#leave_list_archives').dataTable({   
		   "aoColumns": [
					{ "bSortable": false,sWidth: '10%'},					
					{sWidth: '25%',sClass:'dt_small_font'},
					{sWidth: '10%',sClass:'dt_small_font'},
					{sWidth: '8%',sClass:'dt_small_font'},
					{sWidth: '10%',sClass:'dt_small_font'},
					{sWidth: '10%',sClass:'dt_small_font'},
					{sWidth: '8%',sClass:'dt_small_font'},
			 ],
			"bProcessing":true,
			"bServerSide":true,
			"bStateSave": true,
			"bAutoWidth": true,
			"bInfo":false,
			"bJQueryUI": true,
			"aaSorting": [[ 1, "asc" ]],
			"sPaginationType": "full_numbers",
			"bPaginate": true,			
			'sAjaxSource': base_url + 'leave/_load_server_leave_list_archives_dt?dept_id=<?php echo Utilities::encrypt($dept_id); ?>',
			"fnDrawCallback": function() {
					$('input#check_uncheck_sub').tipsy({gravity: 's', live: true});	
					$('.i_container #edit').tipsy({gravity: 's'});
					$('.i_container #delete').tipsy({gravity: 's'});
					$('.i_container #view').tipsy({gravity: 's'});
				}
			}).fnSetFilteringDelay();
	});
</script>
<div class="table-container">
<table id="leave_list_archives" class="display">
    <thead>
      <tr>
      	<th valign="top" width="10%"><input title="Check All" type="checkbox" id="check_uncheck_sub" name="check_uncheck_sub" onclick="chkUnchk(2);" /></th>       
        <th valign="top" width="10%">Name</th>
        <th valign="top" width="10%">Position</th>
        <th valign="top" width="10%">Leave Type</th>
        <th valign="top" width="10%">From</th>
        <th valign="top" width="10%">To</th>
        <th valign="top" width="10%">Status</th>
      </tr>
    </thead>
    <tbody>   
    </tbody>	
</table>
</div>
