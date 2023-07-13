<script>
$(function() {
	load_dtr_list_dt();
	$("#from").datepicker({
		dateFormat:'yy-mm-dd',
		changeMonth:true,
		changeYear:true,
		showOtherMonths:true,
		onSelect	:function() {
			load_dtr_filter_by_date();
			$("#to").datepicker('option',{minDate:$(this).datepicker('getDate')});
		}
	});
	
	$("#to").datepicker({
		dateFormat:'yy-mm-dd',
		changeMonth:true,
		changeYear:true,
		showOtherMonths:true,
		onSelect	:function() {
			load_dtr_filter_by_date();
			$("#to").datepicker('option',{minDate:$(this).datepicker('getDate')});
		}
	});
});
</script>

<div style="position:relative;">
<div align="right" class="float-right" style="position:absolute; right:0; top:2px; z-index:100;">
<form id="print_dtr" name="print_dtr" method="post" action="<?php echo url('dtr/print_dtr_report'); ?>">
	From : <input type="text" id="from" name="from" style="width:120px;" /> To : <input type="text" id="to" name="to" style="width:120px;" />&nbsp;<button class="curve blue_button" type="submit"><i class="icon-print icon-white"></i> Print</button>
</form>
</div>

<div id="dtr_list_dt_wrapper"></div>
</div>
<?php include('includes/_wrappers.php'); ?>