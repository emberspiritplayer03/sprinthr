<script>
$(function() {
	  var oTable = $('#loan_list').dataTable({   
	   "aoColumns": [
	   			<?php if($permission_action == Sprint_Modules::PERMISSION_02)	{	?>
					{ "bSortable": false,sWidth: '1%'},	
				<?php } ?>
				{sWidth: '25%',sClass:'dt_small_font'},
				{sWidth: '20%',sClass:'dt_small_font'},
				{sWidth: '2%',sClass:'dt_small_font'},				
				{sWidth: '2%',sClass:'dt_small_font'},
				{sWidth: '2%',sClass:'dt_small_font'},
				{sWidth: '1%',sClass:'dt_small_font'},				
				{sWidth: '1%',sClass:'dt_small_font'},
				{"bVisible": false, sWidth: '1%',sClass:'dt_small_font'}											
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
				$(".dt-chkbox").click(function(){
					enableDisableWithSelected();
				})
			}
		}).fnSetFilteringDelay();
});
</script>
<div class="table-container">
<table id="loan_list" class="display">
    <thead>
      <tr>
      	<?php if($permission_action == Sprint_Modules::PERMISSION_02)	{	?>
      		<th valign="middle" width="2%"><input title="Check All" type="checkbox" id="check_uncheck" name="check_uncheck" onclick="chkUnchk();" /></th>     
      	<?php } ?>
        <th valign="top" width="10%" style="font-size:12px;">Name</th>
        <th valign="top" width="10%" style="font-size:12px;">Loan type</th>
        <th valign="top" width="10%" style="font-size:12px;">Deduction Type</th>       
        <th valign="top" width="10%" style="font-size:12px;">Loan amount</th>
        <th valign="top" width="10%" style="font-size:12px;">Total amount to pay</th>
        <th valign="top" width="10%" style="font-size:12px;">Amount Paid</th>     
        <th valign="top" width="10%" style="font-size:12px;">Balance</th>             
        <th valign="top" width="10%" style="font-size:12px;"></th>            
      </tr>
    </thead>
    <tbody>   
    </tbody>	
</table>
</div>
