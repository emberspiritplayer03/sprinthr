<script>
$(function() {
	var from			= $('#from_period').val();
	var to				= $('#to_period').val();
	  var oTable = $('#leave_list').dataTable({   
	   "aoColumns": [
	   		<?php if($permission_action == Sprint_Modules::PERMISSION_02) { ?>
		   		<?php if($is_period_lock == G_Cutoff_Period::NO){ ?>
					{ "bSortable": false,sWidth: '14%'},
				<?php } ?>		
			<?php }?>			
				{sWidth: '15%',sClass:'dt_small_font'},
				{sWidth: '8%',sClass:'dt_small_font'},
				{sWidth: '8%',sClass:'dt_small_font'},
				{sWidth: '8%',sClass:'dt_small_font'},
				{sWidth: '8%',sClass:'dt_small_font'}					
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
		'sAjaxSource': base_url + 'leave/_load_server_leave_list_dt?frequency_id=<?php echo $frequency_id; ?>&dept_id=<?php echo Utilities::encrypt($dept_id); ?>'+'&from='+from+'&to='+to,
		"fnDrawCallback": function() {
				$('input#check_uncheck').tipsy({gravity: 's', live: true});	
				$('.i_container #edit').tipsy({gravity: 's'});
				$('.i_container #delete').tipsy({gravity: 's'});
				$('.i_container #view').tipsy({gravity: 's'});
			}
		}).fnSetFilteringDelay();
});
</script>

<div id="error_notifs" style="float:left; clear:left;"> 
<?php if($total_errors) { ?>
<div class="alert alert-danger">
<span>There are <strong><?php echo $total_errors; ?></strong> error(s) while importing.</span> <a class="btn btn-mini" href="<?php echo url('leave/download_leave_error_log'); ?>"><i class="icon-circle-arrow-down"></i> Download Error Log.</a>
<a class="btn btn-mini" href="javascript:void(0);" onclick="javascript:clear_import_error_notifs();">Clear Errors.</a>
</div>
<?php } ?>
</div>


<div class="table-container">
<table id="leave_list" class="display">
    <thead>
      <tr>
      	<?php if($permission_action == Sprint_Modules::PERMISSION_02) { ?>
								<?php if($is_period_lock == ((!isset($frequency_id) || $frequency_id != 2 ? G_Cutoff_Period::NO : G_Weekly_Cutoff_Period::NO))){ ?>   
	      		<th valign="middle" width="2%"><input title="Check All" type="checkbox" id="check_uncheck" name="check_uncheck" onclick="chkUnchk();" /></th>     
	        <?php } ?>
	    <?php } ?>
        <th valign="top" width="10%">Name</th>
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
