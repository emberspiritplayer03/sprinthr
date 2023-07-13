<script>
$(function() { 
	  var oTable = $('#dtCompany').dataTable({   
	   "aoColumns": [		   		
				{"bSortable": false,sWidth: '5%','bVisible':false },
				{sWidth: '20%',sClass:'dt_small_font'},
				{sWidth: '20%',sClass:'dt_small_font'},
				{sWidth: '10%',sClass:'dt_small_font'},
				{sWidth: '10%',sClass:'dt_small_font'},			        
				{'bVisible':true,"bSortable": false,sWidth: '15%',sClass:'dt_small_font action_button'}							
		 ],
		'bProcessing':false,
		'bServerSide':false,
		"bAutoWidth": true,
		//"bStateSave": true,
		"bInfo":false,
		"bJQueryUI": true,
		"aaSorting": [[ 1, "asc" ]],
		"sPaginationType": "two_button",
		"bPaginate": true,
		"fnDrawCallback": function() {

			}
		});		
});
</script>

<div style="position:relative; top:-6px;" class="ui-state-highlight ui-corner-all">
	<span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span>
	<div id="total_result_wrapper">
		Total Record(s): <?php echo ($n ? $n->getItem() : 0);?>
	</div>
</div>

<div class="table-container">
<table id="dtCompany" class="mws-table mws-datatable thead-title-black">
    <thead>
      <tr>   
      	<th>  
        <th>Employee Name</th>
        <th>Attnd. Date</th>
        <th>Type</th>
        <th>Total in/out</th>
        <th></th>
      </tr>
    </thead>
    <tbody>  
    	<?php foreach($multi_in_out_data as $miod) { ?> 
	    	<tr>
	    		<td>-</td>
	    		<td><?php echo $miod['employee_name']; ?></td>
	    		<td><?php echo $miod['attendance_date']; ?></td>
	    		<td><?php echo $miod['type']; ?></td>
	    		<?php if(isset($miod['total_in'])) { ?>
	    			<td><?php echo $miod['total_in']; ?></td>
	    		<?php }else{ ?>
	    			<td><?php echo $miod['total_out']; ?></td>
	    		<?php } ?>
	    		<td>
                    <a target="_blank" href="<?php echo url("notifications/get_link?module=incomplete_dtr&emp_code=".$miod['employee_code']) . "&attendance_date=" . $miod['attendance_date']; ?> "  >
                        <i class=\"icon-edit\"></i> DTR
                    </a>	    			
	    		</td>
	    	</tr>
    <?php } ?>
    </tbody>	
</table>
</div>