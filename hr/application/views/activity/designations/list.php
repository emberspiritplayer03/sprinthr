<script>
	$(function() {
		var oTable = $('#designations_dt').dataTable({   
			"aoColumns": [
			<?php if($permission_action == Sprint_Modules::PERMISSION_02) { ?>
				{ "bSortable": false,sWidth: '10%'},					
			<?php } ?>

					{sWidth: '45%', sClass:'dt_small_font'},					
					{sWidth: '45%', sClass:'dt_small_font'}
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
			'sAjaxSource': base_url + 'activity/_load_server_designations_list_dt',
			"fnDrawCallback": function() {
					$('.i_container #edit').tipsy({gravity: 's'});
					$('.i_container #delete').tipsy({gravity: 's'});
				}
			})//.fnSetFilteringDelay();
	});
</script>

<div class="table-container">
<table id="designations_dt" class="display">
    <thead>
      <tr>
      	<?php if($permission_action == Sprint_Modules::PERMISSION_02) { ?>
			<th valign="middle" width="2%"></th>     
	    <?php } ?>
        <th valign="top" width="10%">Name</th>
        <th valign="top" width="10%">Reason</th>       
      </tr>
    </thead>
    <tbody>   
    </tbody>	
</table>
</div>
