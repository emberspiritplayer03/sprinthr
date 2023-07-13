<script>
$(function() {
	  var oTable = $('#ob_dt').dataTable({   
	   "aoColumns": [
	   <?php if($permission_action == Sprint_Modules::PERMISSION_02) { ?>
					<?php if($is_period_lock == ((!isset($frequency_id) || $frequency_id != 2 ? G_Cutoff_Period::NO : G_Weekly_Cutoff_Period::NO))){ ?>   
					{ "bSortable": false,sWidth: '5%'},		
			<?php } ?>	
		<?php } ?>		
				{sWidth: '30%',sClass:'dt_small_font'},	
				{sWidth: '30%',sClass:'dt_small_font'},	
				{sWidth: '10%',sClass:'dt_small_font'},	
				{sWidth: '10%',sClass:'dt_small_font'},
				{sWidth: '5%',sClass:'dt_small_font'},	
				{sWidth: '5%',sClass:'dt_small_font'}				
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
		'sAjaxSource': base_url + 'ob/_load_server_ob_disapproved_list_dt?from=<?php echo $from; ?>&to=<?php echo $to; ?>&frequency_id=<?php echo $frequency_id; ?>',
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
<input type="hidden" id="cp_date_from" value="<?php echo $from; ?>" />
<input type="hidden" id="cp_date_to" value="<?php echo $to; ?>" />
<input type="hidden" id="cp_frequency_id" value="<?php echo $frequency_id; ?>" />
<table id="ob_dt" class="display">
    <thead>
      <tr>
      <?php if($permission_action == Sprint_Modules::PERMISSION_02) { ?>
							<?php if($is_period_lock == ((!isset($frequency_id) || $frequency_id != 2 ? G_Cutoff_Period::NO : G_Weekly_Cutoff_Period::NO))){ ?>   
		      	<th valign="middle" width="2%"><input title="Check All" type="checkbox" id="check_uncheck" name="check_uncheck" onclick="chkUnchk();" /></th>     
	      <?php } ?>
	  <?php } ?>
        <th valign="top" width="10%">Name</th>
        <th valign="top" width="10%">Position</th>
        <th valign="top" width="10%">Date From</th>       
        <th valign="top" width="10%">Date To</th>
        <th valign="top" width="10%">Time Start</th>       
        <th valign="top" width="10%">Time End</th>          
      </tr>
    </thead>
    <tbody>   
    </tbody>	
</table>
</div>
