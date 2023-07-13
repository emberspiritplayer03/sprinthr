<script>
$(function() {
	  var oTable = $('#e_loan_list').dataTable({   
	   "aoColumns": [
				{"bVisible":false, "bSortable": false,sWidth: '8%'},									
				{sWidth: '20%',sClass:'dt_small_font'},
				{sWidth: '15%',sClass:'dt_small_font'},
				{sWidth: '15%',sClass:'dt_small_font'},
				{sWidth: '15%',sClass:'dt_small_font'},
				{sWidth: '15%',sClass:'dt_small_font'},
				{"bVisible":false,sWidth: '3%',sClass:'dt_small_font'}												
		 ],
		"bProcessing":true,
		"bServerSide":true,
		"bAutoWidth": true,		
		"bInfo":false,
		"bJQueryUI": true,
		"aaSorting": [[ 1, "asc" ]],
		"sPaginationType": "full_numbers",
		"bPaginate": true,			
		'sAjaxSource': base_url + 'loan/_load_server_employee_loan_list_dt?eid=<?php echo $e_employee_id; ?>',
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
<table id="e_loan_list" class="display">
    <thead>
      <tr>
      	<th valign="middle" width="2%"></th>             
        <th valign="top" width="10%">Loan Type</th>
        <th valign="top" width="10%">Months to Pay</th>
        <th valign="top" width="10%">Loan Amount</th>
        <th valign="top" width="10%">Total Amount to Pay</th>
        <th valign="top" width="10%">Amount Paid</th>  
        <th valign="top" width="10%">Amount Paid</th>              
      </tr>
    </thead>
    <tbody>   
    </tbody>	
</table>
</div>
