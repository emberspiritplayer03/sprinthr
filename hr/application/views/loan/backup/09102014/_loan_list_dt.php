<script>
$(function() {
	  var oTable = $('#loan_list').dataTable({   
	   "aoColumns": [
				{ "bSortable": false,sWidth: '70%'},					
				{sWidth: '25%',sClass:'dt_small_font'},
				{sWidth: '20%',sClass:'dt_small_font'},
				{sWidth: '2%',sClass:'dt_small_font'},
				{sWidth: '2%',sClass:'dt_small_font'},
				{sWidth: '2%',sClass:'dt_small_font'},
				{sWidth: '1%',sClass:'dt_small_font'}											
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
		'sAjaxSource': base_url + 'loan/_load_server_loan_list_dt',
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
<table id="loan_list" class="display">
    <thead>
      <tr>
      	<th valign="middle" width="2%"><input title="Check All" type="checkbox" id="check_uncheck" name="check_uncheck" onclick="chkUnchk();" /></th>     
        <th valign="top" width="10%" style="font-size:12px;">Name</th>
        <th valign="top" width="10%" style="font-size:12px;">Loan Type</th>
        <th valign="top" width="10%" style="font-size:12px;">Balance</th>
        <th valign="top" width="10%" style="font-size:12px;">Total Amount</th>
        <th valign="top" width="10%" style="font-size:12px;">Deduction Type</th>
        <th valign="top" width="10%" style="font-size:12px;">No. of Installment</th>        
      </tr>
    </thead>
    <tbody>   
    </tbody>	
</table>
</div>
