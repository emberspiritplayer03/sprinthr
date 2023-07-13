<script>
	$(function() {		
		 var oTable = $('#employee_eval_hist_datatable').dataTable({   
		   "aoColumns": [
					{ "bSortable": false,sWidth: '3%', "bVisible": false},					
					{sWidth: '8%',sClass:'dt_small_font'},
					{sWidth: '10%',sClass:'dt_small_font'},
					{sWidth: '8%',sClass:'dt_small_font'},
					
					{ "bSortable": false,sWidth: '3%'}		
			 ],
			"bStateSave": false,
			'bProcessing':true,
			'bServerSide':true,
			"bAutoWidth": false,
			"bInfo":false,
			"bJQueryUI": true,
			"aaSorting": [[ 1, "asc" ]],
			"sPaginationType": "full_numbers",
			"bPaginate": true,
			"bFilter": false,
			
			'sAjaxSource': base_url + 'evaluation/_load_employee_eval_history_dt?eid=<?php echo $eid; ?>&date=<?php echo $date; ?>',
			"fnDrawCallback": function() {
					
					//$('.i_container #edit').tipsy({gravity: 's'});
					//$('.i_container #delete').tipsy({gravity: 's'});
					//$('.i_container #view').tipsy({gravity: 's'});

				}
			});
	});
</script>
<div class="table-container">
 <table id="employee_eval_hist_datatable" class="display">
    <thead>
      <tr>
        <th valign="top" ></th>       
        <th valign="top" >Evaluation date</th>        
        <th valign="top" >Score</th>   
        <th valign="top" >Attachments</th>
        <th valign="top"></th>    
      </tr>
    </thead>
    <tbody>   
    </tbody>  
</table>
</div>