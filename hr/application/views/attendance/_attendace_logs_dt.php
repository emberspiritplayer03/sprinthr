<script>
$(function() {
	  var oTable = $('#loan_list').dataTable({   
	   "aoColumns": [						
				{sWidth: '35%',sClass:'dt_small_font'},
				{sWidth: '40%',sClass:'dt_small_font'},
				{sWidth: '4%',sClass:'dt_small_font'},
				{sWidth: '4%',sClass:'dt_small_font'},
				{sWidth: '4%',sClass:'dt_small_font'}													
		 ],
		"bProcessing":true,
		"bServerSide":true,
		"bAutoWidth": true,
		"bStateSave": true,
		"bInfo":false,
		"bJQueryUI": true,
		"aaSorting": [[ 1, "asc" ]],
		"sPaginationType": "full_numbers",
		"bPaginate": true,			
		'sAjaxSource': base_url + 'attendace/_load_server_attendace_logs_dt?date_from=<?php echo $date_from; ?>&date_to=<?php echo $date_to; ?>&error_type=<?php echo $error_type;  ?>'		
		});
});
</script>
<div class="table-container">
<table id="loan_list" class="display">
    <thead>
      <tr>      	
        <th valign="top" width="10%"><strong>Employee Code</strong></th>
        <th valign="top" width="10%"><strong>Employee Name</strong></th>
        <th valign="top" width="10%"><strong>Date</strong></th>
        <th valign="top" width="10%"><strong>Time</strong></th>
        <th valign="top" width="10%"><strong>Type</strong></th>               
      </tr>
    </thead>
    <tbody>   
    </tbody>	
</table>
</div>
