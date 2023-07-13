<script>
$(function() { 
	  var oTable = $('#dtCompany').dataTable({   
	   "aoColumns": [		   		
				{"bSortable": false,sWidth: '5%','bVisible':false },
				{sWidth: '20%',sClass:'dt_small_font'},
				{sWidth: '20%',sClass:'dt_small_font'},
				{sWidth: '10%',sClass:'dt_small_font'},
				{sWidth: '10%',sClass:'dt_small_font'},			        
				{'bVisible':true,"bSortable": false,sWidth: '15%',sClass:'dt_small_font action_button'}							
		 ],
		'bProcessing':true,
		'bServerSide':true,
		"bAutoWidth": true,
		//"bStateSave": true,
		"bInfo":false,
		"bJQueryUI": true,
		"aaSorting": [[ 1, "asc" ]],
		"sPaginationType": "two_button",
		"bPaginate": true,
		'sAjaxSource': base_url + 'notifications/ajax_load_tardiness_list_dt?from=<?php echo $from; ?>&to=<?php echo $to; ?>',
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
<table id="dtCompany" class="mws-table mws-datatable thead-title-black">
    <thead>
      <tr>   
      	<th>  
        <th>Employee Name</th>
        <th>Attnd. Date</th>
        <th>Department</th>
        <th>Hours Late</th>
        <th></th>
      </tr>
    </thead>
    <tbody>   
    </tbody>	
</table>
</div>
