<script>
	$(function() {
		  var oTable = $('#employee_list_dt').dataTable({   
		   "aoColumns": [
		 	  	<?php if($is_period_lock == G_Cutoff_Period::NO){ ?>
					{ "bSortable": false,sWidth: '15%'},
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
			"bInfo":false,
			"bJQueryUI": true,
			"aaSorting": [[ 1, "asc" ]],
			"sPaginationType": "full_numbers",
			"bPaginate": true,
			'sAjaxSource': base_url + 'overtime/_load_server_overtime_list_dt',
			"fnDrawCallback": function() {
					$('.i_container #add_request').tipsy({gravity: 's'});
					$('.i_container #delete').tipsy({gravity: 's'});
					$('.i_container #view').tipsy({gravity: 's'});
				}
			}).fnSetFilteringDelay();
	});
</script>

<div id="error_notifs" style="float:left; clear:left;"> 
<?php if($total_errors) { ?>
<span class="red">There are <?php echo $total_errors; ?> error(s) while importing.</span> <a href="<?php echo url('overtime/download_ot_error_log'); ?>">Download Error Log.</a>
<a href="javascript:void(0);" onclick="javascript:clear_import_error_notifs();">Clear Errors.</a>
<?php } ?>
</div>

<div class="table-container">
<form id="overtime_form_dt" name="overtime_form_dt">
<textarea id="h_ckdt_id" name="h_ckdt_id" style="display:none;""></textarea>
<table id="employee_list_dt" class="display">
    <thead>
      <tr>
      <?php if($is_period_lock == G_Cutoff_Period::NO){ ?>
      	<th valign="top" width="10%"><input type="checkbox" id="check_all_overtime" name="check_all_overtime" onclick="javascript:chkUnchk();" /></th>
      <?php } ?>
        <th valign="top" width="10%">Employee Name</th>
        <th valign="top" width="10%">Position</th>
        <th valign="top" width="10%">Date</th>
        <th valign="top" width="10%">In</th>
        <th valign="top" width="10%">Out</th>
        <th valign="top" width="10%">Comments</th>
      </tr>
    </thead>
    <tbody>   
    </tbody>	
</table>
</form>
</div>
