<script>
	$(function() {
		var sidebar			= '<?php echo $sidebar; ?>';
		var h_department_id = $('#department_id').val();
		var from			= $('#from_period').val();
		var to				= $('#to_period').val();
		  var oTable = $('#pending_overtime_list_dt').dataTable({   
		   "aoColumns": [
		    <?php if($is_period_lock == G_Cutoff_Period::NO){ ?>		
					{ "bSortable": false,sWidth: '10%'},
			<?php } ?>
					{sWidth: '15%',sClass:'dt_small_font'},
					{sWidth: '10%',sClass:'dt_small_font'},
					{sWidth: '7%',sClass:'dt_small_font'},
					{sWidth: '7%',sClass:'dt_small_font'},
					{sWidth: '7%',sClass:'dt_small_font'},
					{sWidth: '20%',sClass:'dt_small_font'},
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
			'sAjaxSource': base_url + 'overtime/_load_server_generic_overtime_list_dt?department='+h_department_id+'&sidebar='+sidebar+'&from='+from+'&to='+to,
			"fnDrawCallback": function() {
					$('.i_container #add_request').tipsy({gravity: 's'});
					$('.i_container #delete').tipsy({gravity: 's'});
					$('.i_container #view').tipsy({gravity: 's'});
				}
			}).fnSetFilteringDelay();
	});
</script>

<div class="table-container">
<table id="pending_overtime_list_dt" class="formtable">
    <thead>
      <tr>
      	<?php if($is_period_lock == G_Cutoff_Period::NO){ ?>		
      		<th valign="top" width="10%"></th>
        <?php } ?>
        <th valign="top" width="10%">Name</th>
        <th valign="top" width="10%">Position</th>
        <th valign="top" width="10%">Date</th>
        <th valign="top" width="10%">In</th>
        <th valign="top" width="10%">Out</th>
        <th valign="top" width="10%">Reason</th>
      </tr>
    </thead>
    <tbody>   
    </tbody>	
</table>
</div>
