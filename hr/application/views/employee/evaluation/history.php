
<?php  $date = 0; ?>
<table class="no_border">
	<tbody>
	<tr>
		<td>
			<label>Employee Name:</label>
			<label><strong><?php echo $employee_name; ?></strong><br/></label>
		</td>
		<td align="right">
			<label>Select evaluation date:</label>
			 <input type="text" name="evaldate" id="evaldate" onchange="getSelectedDate()" />
		</td>
	</tr>
</tbody>
</table>

<div id="employee_evaluation_history_list_wrapper">

</div>

<script type="text/javascript">

	function getSelectedDate(){

		var date = document.getElementById('evaldate').value;
		getEvalHistory(<?php echo $employee_id;  ?> , date );
	}
	
	getEvalHistory(<?php echo $employee_id;  ?> , <?php echo $date; ?>);
	$("#evaldate ").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});

</script>