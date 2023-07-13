<script>
$(function() {
	  var oTable = $('#loan_details_list').dataTable({   
	   "aoColumns": [								
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
		'sAjaxSource': base_url + 'loan/_load_server_employee_loan_details_dt?eid=<?php echo $e_loan_id; ?>',
		"fnDrawCallback": function() {				
				$('.i_container #edit').tipsy({gravity: 's'});
				$('.i_container #delete').tipsy({gravity: 's'});
				$('.i_container #view').tipsy({gravity: 's'});
			}
		}).fnSetFilteringDelay();
});
</script>
<br />
<div id="form_main" class="employee_form">
	<h2>Payment(s)</h2>
    <div class="table-container">
    <table id="loan_details_list" class="display">
        <thead>
          <tr>            
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
</div>
