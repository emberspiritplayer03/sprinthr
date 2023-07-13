<script>
	$(function() {
		var h_department_id = $('#department_id').val();
		var from			= $('#from_period').val();
		var to				= $('#to_period').val();
		  var oTable = $('#archived_overtime_list_dt').dataTable({   
		   "aoColumns": [
		   		<?php if($is_period_lock == G_Cutoff_Period::NO){ ?>
		   			{ "bSortable": false,sWidth: '5%'},
				<?php } ?>
					{sWidth: '35%',sClass:'dt_small_font'},
					{sWidth: '20%',sClass:'dt_small_font'},
					{sWidth: '20%',sClass:'dt_small_font'},
					{sWidth: '20%',sClass:'dt_small_font'},
					{sWidth: '10%',sClass:'dt_small_font'},
					{sWidth: '20%',sClass:'dt_small_font'},
			 ],
			"bStateSave": true,
			'bProcessing':true,
			'bServerSide':true,
			"bAutoWidth": true,
			"bInfo":false,
			"bJQueryUI": true,
			"aaSorting": [[ 1, "asc" ]],
			"sPaginationType": "full_numbers",			
			"bPaginate": true,
			'sAjaxSource': base_url + 'overtime/_load_server_restore_overtime_list_dt?department='+h_department_id+'&from='+from+'&to='+to,
			"fnDrawCallback": function() {
					$('input#check_uncheck').tipsy({gravity: 's', live: true});	
					$('.i_container #add_request').tipsy({gravity: 's'});
					$('.i_container #delete').tipsy({gravity: 's'});
					$('.i_container #view').tipsy({gravity: 's'});
				}
			}).fnSetFilteringDelay();
	});
</script>

<div class="table-container">
<table id="archived_overtime_list_dt" class="formtable">
    <thead>
      
      <?php if($is_period_lock == G_Cutoff_Period::NO){ ?>
      	<th valign="top"><input class="text-input" title="Check All" type="checkbox" id="check_uncheck" name="check_uncheck" onclick="chkUnchk();" /></th>
      <?php } ?>
        <th>Name</th>
        <th valign="top">Position</th>
        <th valign="top">Date</th>
        <th valign="top">In</th>
        <th valign="top">Out</th>
        <th valign="top">Reason</th>
     
    </thead>
    <tbody>   
    </tbody>	
</table>
</div>
