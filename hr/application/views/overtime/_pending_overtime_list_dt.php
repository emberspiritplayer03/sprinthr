<script>
	$(function() {
		var h_department_id = $('#department_id').val();
		var from			= $('#from_period').val();
		var to				= $('#to_period').val();
		  var oTable = $('#pending_overtime_list_dt').dataTable({   
		   "aoColumns": [
		   	<?php if($is_period_lock == G_Cutoff_Period::NO){ ?>		
					{ "bSortable": false,sWidth: '13%'},
			<?php } ?>
					{sWidth: '17%',sClass:'dt_small_font'},
					{sWidth: '12%',sClass:'dt_small_font'},
					{sWidth: '7%',sClass:'dt_small_font'},
					{sWidth: '7%',sClass:'dt_small_font'},
					{sWidth: '7%',sClass:'dt_small_font'},
					{sWidth: '15%',sClass:'dt_small_font'}					
			 ],
			'bProcessing':true,
			'bServerSide':true,
			"bAutoWidth": true,
			"bStateSave": true,
			"bInfo":false,
			"bJQueryUI": true,
			"aaSorting": [[ 2, "asc" ]],
			"sPaginationType": "full_numbers",			
			"bPaginate": true,
			'sAjaxSource': base_url + 'overtime/_load_server_pending_overtime_list_dt?department='+h_department_id+'&from='+from+'&to='+to,
			"fnDrawCallback": function() {
					$('input#check_uncheck').tipsy({gravity: 's', live: true});	
					$('.i_container #add_request').tipsy({gravity: 's'});
					$('.i_container #delete').tipsy({gravity: 's'});
					$('.i_container #view').tipsy({gravity: 's'});
				}
			}).fnSetFilteringDelay();
	});
</script>

<div id="error_notifs" style="float:left; clear:left;"> 
<?php if($total_errors) { ?>
<div class="alert alert-danger">
<span>There are <strong><?php echo $total_errors; ?></strong> error(s) while importing.</span> <a class="btn btn-mini" href="<?php echo url('overtime/download_ot_error_log'); ?>"><i class="icon-circle-arrow-down"></i> Download Error Log.</a>
<a class="btn btn-mini" href="javascript:void(0);" onclick="javascript:clear_import_error_notifs();">Clear Errors.</a>
</div>
<?php } ?>
</div>

<div class="table-container">
<textarea id="h_ckdt_id" name="h_ckdt_id" style="display:none;"></textarea>
<table id="pending_overtime_list_dt" class="formtable">
    <thead>
      <tr>      
      	<?php if($is_period_lock == G_Cutoff_Period::NO){ ?>		
      		<th valign="top" width="10%"><input title="Check All" type="checkbox" id="check_uncheck" name="check_uncheck" onclick="chkUnchk();" /></th>
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
