<script>
$(function() {
	  var oTable = $('#loan_details_list').dataTable({   
	   "aoColumns": [
				{ "bSortable": false,sWidth: '10%'},					
				{sWidth: '20%',sClass:'dt_small_font'},
				{sWidth: '20%',sClass:'dt_small_font'},
				{sWidth: '5%',sClass:'dt_small_font'},
				{sWidth: '5%',sClass:'dt_small_font'},													
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
		'sAjaxSource': base_url + 'loan/_load_server_loan_details_dt?eid=<?php echo $e_loan_id; ?>',
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
<table id="loan_details_list" class="formtable">
    <thead>
      <tr>
      	<th valign="middle" width="2%"><input title="Check All" type="checkbox" id="check_uncheck" name="check_uncheck" onclick="chkUnchk();" /></th>     
        <th valign="top" width="10%">Date of Payment</th>
        <th valign="top" width="10%">Amount</th>
        <th valign="top" width="10%">Is Paid</th>
        <th valign="top" width="10%">Remarks</th>          
      </tr>
    </thead>
    <tbody>   
    </tbody>	
</table>
</div>
