<script>
	$(function() {
		var from	= $('#from_period').val();
		var to		= $('#to_period').val();
		  var oTable = $('#approved_leave_list').dataTable({   
		   "aoColumns": [	
		   	<?php if($permission_action == Sprint_Modules::PERMISSION_02) { ?>
							<?php if($is_period_lock == ((!isset($frequency_id) || $frequency_id != 2 ? G_Cutoff_Period::NO : G_Weekly_Cutoff_Period::NO))){ ?>   
					{ "bSortable": false,sWidth: '5%'},
				<?php } ?>	
			<?php } ?>	
					{sWidth: '15%',sClass:'dt_small_font'},
					{sWidth: '10%',sClass:'dt_small_font'},
					{sWidth: '8%',sClass:'dt_small_font'},
					{sWidth: '8%',sClass:'dt_small_font'},
					{sWidth: '8%',sClass:'dt_small_font'}					
			 ],
			"bProcessing":true,
			"bServerSide":true,
			"bAutoWidth": true,
			"bInfo":false,
			"bStateSave": true,
			"bJQueryUI": true,
			"aaSorting": [[ 1, "asc" ]],
			"sPaginationType": "full_numbers",
			"bPaginate": true,			
			'sAjaxSource': base_url + 'leave/_load_server_disapproved_leave_list_dt?dept_id=<?php echo Utilities::encrypt($dept_id); ?>&from=<?php echo $from_date; ?>&to=<?php echo $to_date; ?>',
			"fnDrawCallback": function() {
					$('.i_container #edit').tipsy({gravity: 's'});
					$('.i_container #delete').tipsy({gravity: 's'});
					$('.i_container #view').tipsy({gravity: 's'});
				}
			}).fnSetFilteringDelay();
	});
</script>
<div class="table-container">
<table id="approved_leave_list" class="display">
    <thead>
      <tr>
      	<?php if($permission_action == Sprint_Modules::PERMISSION_02) { ?>
								<?php if($is_period_lock == ((!isset($frequency_id) || $frequency_id != 2 ? G_Cutoff_Period::NO : G_Weekly_Cutoff_Period::NO))){ ?>   
	      		<th valign="middle" width="2%"></th>     
	        <?php } ?>
	    <?php } ?>	
        <th valign="top" width="10%">Employee Name</th>
        <th valign="top" width="10%">Position</th>
        <th valign="top" width="10%">Leave Type</th>
        <th valign="top" width="10%">From</th>
        <th valign="top" width="10%">To</th>        
      </tr>
    </thead>
    <tbody>   
    </tbody>	
</table>
</div>
