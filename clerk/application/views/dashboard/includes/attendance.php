<script>
	$(function() {
		load_employee_attendance_list_dt();
		$("#from").datepicker({
			dateFormat:'yy-mm-dd',
			changeMonth:true,
			changeYear:true,
			showOtherMonths:true,
			onSelect	:function() {
				load_employee_attendance_filter_by_date();
				$("#to").datepicker('option',{minDate:$(this).datepicker('getDate')});
			}
		});
		
		$("#to").datepicker({
			dateFormat:'yy-mm-dd',
			changeMonth:true,
			changeYear:true,
			showOtherMonths:true,
			onSelect	:function() {
				load_employee_attendance_filter_by_date();
				$("#to").datepicker('option',{minDate:$(this).datepicker('getDate')});
			}
		});
	});
</script>
<div id="employee_attendance_list_dt_wrapper"></div>


