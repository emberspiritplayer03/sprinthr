<select id="cutoff_period" name="cutoff_period">
	<?php foreach ($cutoff_periods as $c):?>
	<option value="<?php echo $c->getStartDate();?>/<?php echo $c->getEndDate();?>"><?php echo $c->getYearTag();?> - <?php echo $c->getMonth();?> - <?php echo $c->getCutoffCharacter();?></option>
	<?php endforeach;?>
</select>