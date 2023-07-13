Select cutoff<br />
<?php if($is_government): ?>
	<select style="width:100%;" name="government_start_date[cutoff]">
<?php else: ?>
	<select style="width:100%;" name="start_date[cutoff]">
<?php endif; ?>
	<?php foreach ($cutoff_periods as $c):?>
	<option value="<?php echo strtolower($c->getCutoffCharacter());?>"><?php echo $c->getCutoffCharacter();?></option>
	<?php endforeach;?>
</select>