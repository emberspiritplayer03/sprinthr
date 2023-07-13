<script>
$(function() { 
	  var oTable = $('#dtCompany').dataTable({   
	   "aoColumns": [		   		
		{sWidth: '25%',sClass:'dt_small_font'},
        {sWidth: '25%',sClass:'dt_small_font'},
        {sWidth: '25%',sClass:'dt_small_font'},
        {sWidth: '25%',sClass:'dt_small_font'}
		 ],
		'bProcessing':false,
		'bServerSide':false,
		"bAutoWidth": true,
		"bInfo":false,
		"bJQueryUI": true,
		"aaSorting": [[ 1, "asc" ]],
		"sPaginationType": "full_numbers", //two_button, full_numbers
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
		<th>Employee Code</th> 
        <th>Employee Name</th>
        <th>Date Hired</th>
        <th>Date End of Contract</th>
      </tr>
    </thead>
    <tbody>   
	<?php foreach($endo_employees as $endo_employee) { ?>
	
				<tr>
					<td><?=$endo_employee['employee_code']?></td>
					<td><?=$endo_employee['employee_name']?></td>
					<td><?=$endo_employee['hired_date']?></td>
					<td><?=$endo_employee['end_date']?></td>
				</tr>
		<?php } ?>
    </tbody>	
</table>
</div>
