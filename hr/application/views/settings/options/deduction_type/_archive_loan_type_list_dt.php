<script>
$(function() {
	  var oTable = $('#loan_type_list').dataTable({   
	   "aoColumns": [
				{ "bSortable": false,sWidth: '5%'},						
				{sWidth: '95%',sClass:'dt_small_font'}						
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
		'sAjaxSource': base_url + 'settings/_load_server_archive_loan_type_list_dt',
		"fnDrawCallback": function() {
				$('input#check_uncheck').tipsy({gravity: 's', live: true});	
				$('.i_container #edit').tipsy({gravity: 's'});
				$('.i_container #delete').tipsy({gravity: 's'});
				$('.i_container #view').tipsy({gravity: 's'});
			}
		}).fnSetFilteringDelay();
});
</script>
<div class="table-container">
<table id="loan_type_list" class="formtable">
    <thead>
      <tr>
      	<th valign="middle" width="2%"></th>           
        <th valign="top" width="10%">Loan Type</th>       
      </tr>
    </thead>
    <tbody>   
    </tbody>	
</table>
</div>