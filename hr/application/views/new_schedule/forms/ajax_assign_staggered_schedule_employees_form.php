<script>
$(function(){
	$(".all-employees").click(function(){
		if($(this).is(':checked')){
	    	$(".autocomplete-employees").hide(); 
		}
		else{
	    	$(".autocomplete-employees").show(); 
		}   
	});
		
});
</script>
<form method="post" action="<?php echo url('new_schedule/_assign_staggered_schedule');?>">
<div class="autocomplete-employees">
	<input type="hidden" name="schedule_id" value="<?php echo $schedule_id;?>" />
	Type employees: <input type="text" name="employees_autocomplete" id="employees_autocomplete" />
</div>
<label class="checkbox"><input class="all-employees"  name="apply_to_all" type="checkbox" />All Employees</label>
</form>