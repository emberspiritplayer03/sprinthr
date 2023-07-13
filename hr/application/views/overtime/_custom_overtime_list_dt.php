<script>
$(function() {
	  jq17('.dropdown-toggle_multi').dropdown();	
	  var oTable = $('#custom_overtime_dt').dataTable({   
	   "aoColumns": [
	   <?php //if($permission_action == Sprint_Modules::PERMISSION_02) { ?>
		   <?php //if($is_period_lock == G_Cutoff_Period::NO){ ?>	
					{ "bSortable": false,sWidth: '5%'},		
			<?php //} ?>	
		<?php //} ?>		
				{sWidth: '30%',sClass:'dt_small_font'},	
				{sWidth: '30%',sClass:'dt_small_font'},	
				{sWidth: '10%',sClass:'dt_small_font'},	
				{sWidth: '10%',sClass:'dt_small_font'},	
				{sWidth: '10%',sClass:'dt_small_font'},	
				{sWidth: '10%',sClass:'dt_small_font'},	
				{sWidth: '10%',sClass:'dt_small_font'}
				<?php //if($permission_action == Sprint_Modules::PERMISSION_02) { ?>
				   <?php //if($is_period_lock == G_Cutoff_Period::NO){ ?>	
							,{ "bSortable": false,sWidth: '10%'},		
					<?php //} ?>	
				<?php //} ?>					
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
		'sAjaxSource': base_url + 'overtime/_load_server_custom_overtime_list_dt?from=<?php echo $date_from; ?>&to=<?php echo $date_to; ?>&frequency_id=<?php echo $frequency_id; ?>',
		"fnDrawCallback": function() {
				jq17('.dropdown-toggle').dropdown();

				$(".btn-edit-custom-overtime").click(function(){
					var eid = $(this).attr("id");
					showEditCustomOvertime(eid);
				});

				$(".btn-approve-custom-overtime").click(function(){
					var eid = $(this).attr("id");
					approveCustomOvertime(eid);
				});

				$(".btn-disapprove-custom-overtime").click(function(){
					var eid = $(this).attr("id");
					disApproveCustomOvertime(eid);
				});
			}
		}).fnSetFilteringDelay();
});
</script>
<div class="table-container">
<input type="hidden" id="cp_date_from" value="<?php echo $from; ?>" />
<input type="hidden" id="cp_date_to" value="<?php echo $to; ?>" />
<table id="custom_overtime_dt" class="display">
    <thead>
      <tr>
      <?php //if($permission_action == Sprint_Modules::PERMISSION_02) { ?>
	      <?php //if($is_period_lock == G_Cutoff_Period::NO){ ?>	
		      	<th valign="middle" width="2%"><input title="Check All" type="checkbox" id="check_uncheck" name="check_uncheck" onclick="chkUnchk();" /></th>     
	      <?php //} ?>
	  <?php //} ?>
        <th valign="top" width="10%">Name</th>
        <th valign="top" width="10%">Position</th>
        <th valign="top" width="10%">Date</th>       
        <th valign="top" width="10%">Time In</th>        
        <th valign="top" width="10%">Time Out</th>        
        <th valign="top" width="10%">Day Type</th>        
        <th valign="top" width="10%">Status</th>  
        <?php //if($is_period_lock == G_Cutoff_Period::NO){ ?>	      
        		<th valign="top" width="10%">&nbsp;</th>        
        <?php //} ?>
      </tr>
    </thead>
    <tbody>   
    </tbody>	
</table>
</div>
