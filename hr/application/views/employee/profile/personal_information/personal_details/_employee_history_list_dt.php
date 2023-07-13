<script>
	$(function() {
		var h_employee_id = $('#h_employee_id').val();
		  var oTable = $('#employee_list_dt').dataTable({   
		   "aoColumns": [
		   		<?php if($permission_action == Sprint_Modules::PERMISSION_02)	{	?>
					{ "bSortable": false,sWidth: '7%'},
				<?php } ?>
					{sWidth: '50%',sClass:'dt_small_font'},
					{sWidth: '20%',sClass:'dt_small_font'},
			 ],
			'bProcessing':true,
			'bServerSide':true,
			"bAutoWidth": true,
			"bInfo":false,
			"bJQueryUI": true,
			"aaSorting": [[ 1, "asc" ]],
			"sPaginationType": "full_numbers",
			"bPaginate": true,
			'sAjaxSource': base_url + 'employee/_load_server_employee_history_list_dt?h_employee_id='+h_employee_id,
			"fnDrawCallback": function() {
					$('.i_container #edit').tipsy({gravity: 's'});
					$('.i_container #delete').tipsy({gravity: 's'});
					$('.i_container #view').tipsy({gravity: 's'});
				}
			}).fnSetFilteringDelay();
	});
</script>

<div class="table-container">
<form id="overtime_form_dt" name="overtime_form_dt">
<textarea id="h_ckdt_id" name="h_ckdt_id" style="display:none;""></textarea>
<table id="employee_list_dt" class="display">
    <thead>
      <tr>
      <?php if($permission_action == Sprint_Modules::PERMISSION_02)	{	?>
      	<th valign="top" width="10%"></th>
      <?php } ?>
        <th valign="top" width="10%">Remarks</th>
        <th valign="top" width="10%">Date</th>
      </tr>
    </thead>
    <tbody>   
    </tbody>	
</table>
</form>
</div>
