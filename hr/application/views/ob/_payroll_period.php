<select id="cutoff_period" name="cutoff_period">
	<?php foreach ($cutoff_periods as $c):?>

			<?php if( isset($selected_year) && $selected_year != $c->getYearTag() ) { ?>
					<?php $cutoff = $c->getStartDate().'/'.$c->getEndDate(); ?>
					<option <?php echo $cutoff == $selected_cutoff ? 'selected="selected"' : ''; ?> value="<?php echo $c->getStartDate();?>/<?php echo $c->getEndDate();?>"><?php echo $selected_year; ?> - <?php echo $c->getMonth();?> - <?php echo $c->getCutoffCharacter();?></option>
			<?php }else{ ?>
					<?php $cutoff = $c->getStartDate().'/'.$c->getEndDate(); ?>
					<option <?php echo $cutoff == $selected_cutoff ? 'selected="selected"' : ''; ?> value="<?php echo $c->getStartDate();?>/<?php echo $c->getEndDate();?>"><?php echo $c->getYearTag(); ?> - <?php echo $c->getMonth();?> - <?php echo $c->getCutoffCharacter();?></option>
			<?php } ?>
			
	<?php endforeach;?>
</select>