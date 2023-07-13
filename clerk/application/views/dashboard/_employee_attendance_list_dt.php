<script>
	$(function() {
		var from 	= $('#from').val();
		var to		= $('#to').val();
		  var oTable = $('#employee_attendance_list_dt').dataTable({   
		   "aoColumns": [								
					{sWidth: '10%',sClass:'dt_small_font'},					
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
			'sAjaxSource': base_url + 'dashboard/_load_server_employee_attendance_list_dt?from='+from+'&to='+to,
			"fnDrawCallback": function() {
					$('.i_container #edit').tipsy({gravity: 's'});
					$('.i_container #delete').tipsy({gravity: 's'});
					$('.i_container #view').tipsy({gravity: 's'});
				}
			}).fnSetFilteringDelay();
	});
</script>
<div class="table-container">
<table id="employee_attendance_list_dt" class="display">
    <thead>
      <tr>      	        
        <th valign="top" width="10%">Date Attendance</th>
         <th valign="top" width="8%">Time-In</th>
        <th valign="top" width="8%">Time-Out</th>
        <th valign="top" width="8%">Is Present</th>      
      </tr>
    </thead>
    <tbody>   
    </tbody>	
</table>
</div>
