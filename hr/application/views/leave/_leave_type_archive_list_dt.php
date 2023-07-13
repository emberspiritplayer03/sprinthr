<script>
	$(function() {
		  var oTable = $('#leave_type_archive_list').dataTable({   
		   "aoColumns": [
		   		<?php if($permission_action == Sprint_Modules::PERMISSION_02) {	?>
					{ "bSortable": false,sWidth: '8%'},	
				<?php } ?>				
					{sWidth: '60%',sClass:'dt_small_font'},
					{sWidth: '10%',sClass:'dt_small_font dt_center'}						
			 ],
			'bProcessing':true,
			'bServerSide':true,
			"bAutoWidth": true,
			"bInfo":false,
			"bStateSave": true,
			"bJQueryUI": true,
			"aaSorting": [[ 1, "asc" ]],
			"sPaginationType": "full_numbers",
			"bPaginate": true,
			'sAjaxSource': base_url + 'leave/_load_server_leave_type_archive_list_dt',
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
<table id="leave_type_archive_list" class="display">
    <thead>
      <tr>
      <?php if($permission_action == Sprint_Modules::PERMISSION_02) {	?>
      	<th valign="top" width="10%" style="font-weight:bold;"><input title="Check All" type="checkbox" id="check_uncheck" name="check_uncheck" onclick="chkUnchk(1);" /><!--<label id="check_uncheck_caption"> Check All</label>--></th>       
      <?php } ?>
        <th valign="top" width="10%" style="font-weight:bold;">Leave Type</th>  
        <th valign="top" width="10%" style="font-weight:bold;">Is Paid</th>       
      </tr>
    </thead>
    <tbody>   
    </tbody>	
</table>
</div>
