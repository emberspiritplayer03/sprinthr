<form method="post" action="<?php echo url('schedule/_assign_schedule');?>">
<input type="hidden" name="schedule_id" value="<?php echo $schedule_id;?>" />
Type groups or department:<input type="text" class="text-input" name="groups_autocomplete" id="groups_autocomplete" />
<br /><br />Type employees:<input type="text" class="text-input" name="employees_autocomplete" id="employees_autocomplete" />
</form>