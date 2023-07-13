<script>
$(function() { 
	  var oTable = $('#dtCompany').dataTable({   
	   "aoColumns": [		   		
				{"bSortable": false,sWidth: '6%','bVisible':false },
				{sWidth: '50%',sClass:'dt_small_font'},
				{sWidth: '50%',sClass:'dt_small_font'},			        
				{'bVisible':false,"bSortable": false,sWidth: '5%',sClass:'dt_small_font action_button'}							
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
		'sAjaxSource': base_url + 'notifications/ajax_load_no_department_list_dt',
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
        <th>Date Hired</th>
        <th>
      </tr>
    </thead>
    <tbody>   
    </tbody>	
</table>
</div>
