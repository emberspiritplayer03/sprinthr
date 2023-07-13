<select name="schedule_public_id">
	<?php foreach($schedule as $schedule_group) { ?>
		<?php if (!$schedule_group->isDefault()) { ?>
			<option value="<?php echo $schedule_group->getPublicId();?>"><?php echo $schedule_group->getName();?></option>
		<?php } ?>			
	<?php } ?>
	<option value="all">All</option>
</select>