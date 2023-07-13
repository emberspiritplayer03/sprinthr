<script>
	$(function() {
		var h_department_id = $('#department_id').val();
		var from			= $('#from_period').val();
		var to				= $('#to_period').val();
		  var oTable = $('#undertime_list').dataTable({   
		   "aoColumns": [	
		   		<?php if($is_period_lock == G_Cutoff_Period::NO){ ?>
					{ "bSortable": false,sWidth: '11.5%'},
				<?php } ?>				
		   			{sWidth: '17%',sClass:'dt_small_font'},
					{sWidth: '12%',sClass:'dt_small_font'},
					{sWidth: '7%',sClass:'dt_small_font'},
					{sWidth: '7%',sClass:'dt_small_font'},
					{sWidth: '15%',sClass:'dt_small_font'}				
			 ],
			'bProcessing':true,
			'bServerSide':true,
			"bAutoWidth": true,
			"bInfo":false,
			"bJQueryUI": true,
			"aaSorting": [[ 1, "asc" ]],
			"sPaginationType": "full_numbers",
			"bPaginate": true,			
			'sAjaxSource': base_url + 'undertime/_load_server_undertime_approved_list_dt?department='+h_department_id+'&from='+from+'&to='+to,
			"fnDrawCallback": function() {
					$('.i_container #edit').tipsy({gravity: 's'});
					$('.i_container #delete').tipsy({gravity: 's'});
					$('.i_container #view').tipsy({gravity: 's'});
				}
			}).fnSetFilteringDelay();
	});
</script>
<div class="table-container">
<table id="undertime_list" class="display">
    <thead>
      <?php if($is_period_lock == G_Cutoff_Period::NO){ ?>
      	<th valign="top" width="10%"><input title="Check All" type="checkbox" id="check_uncheck" name="check_uncheck" onclick="chkUnchk();" /></th>
      <?php } ?>
      	<th valign="top" width="10%">Employee Name</th>
        <th valign="top" width="10%">Position</th>
        <th valign="top" width="10%">Date</th>
        <th valign="top" width="10%">Out</th>
        <th valign="top" width="10%">Reason</th>  
    </thead>
    <tbody>   
    </tbody>	
</table>
</div>
