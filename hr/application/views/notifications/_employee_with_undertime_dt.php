<script>
$(function() { 
	  var oTable = $('#dtNotifications').dataTable({   
	   "aoColumns": [		
	   			{'bVisible':false,"bSortable": false,sWidth: '5%'},   						
				{sWidth: '25%',sClass:'dt_small_font'},
				{sWidth: '15%',sClass:'dt_small_font'},
				{sWidth: '10%',sClass:'dt_small_font'},			
				{sWidth: '10%',sClass:'dt_small_font'},			
				{sWidth: '10%',sClass:'dt_small_font'},			
				{sWidth: '10%',sClass:'dt_small_font'},			
				{sWidth: '10%',sClass:'dt_small_font'},
				{'bVisible':true,"bSortable": false,sWidth: '10%',sClass:'dt_small_font action_button'}		        				
		 ],
		'bProcessing':true,
		'bServerSide':true,
		"bAutoWidth": true,
		"bInfo":false,
		"bJQueryUI": true,
		"aaSorting": [[ 1, "asc" ]],
		"sPaginationType": "two_button",
		"bPaginate": true,
		'sAjaxSource': base_url + 'notifications/ajax_load_employee_with_undertime_list_dt?from=<?php echo $from; ?>&to=<?php echo $to; ?>',
		"fnDrawCallback": function() {

			}
		});		
});
</script>

<div style="position:relative; top:-6px;" class="ui-state-highlight ui-corner-all">
	<span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span>
	<div id="total_result_wrapper">
		Total Record(s): <?php echo ($n ? $n->getItem() : 0);?>
	</div>
</div>

<div class="table-container">
<table id="dtNotifications" class="mws-table mws-datatable thead-title-black">
    <thead>
      <tr>   
      	<th></th>         	        
        <th>Employee</th>
        <th>Attnd. Date</th>
        <th>Sched. In</th>
        <th>Sched. Out</th>
        <th>Actual In</th>
        <th>Actual Out</th>
        <th>Undertime</th> 
        <th></th>        
      </tr>
    </thead>
    <tbody></tbody>	
</table>
</div>