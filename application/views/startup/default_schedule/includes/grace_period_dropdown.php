<?php 
$i = count($grace_period); 
$c=1;
?>
<select class="select_option" name="grace_period_id" id="grace_period_id" onchange="javascript:checkForAddGraceStartup();">
    <option value="" selected="selected">-- Select Grace Period --</option>
		<?php  foreach($grace_period as $value) { 
		$selected = ($c == $i)? 'selected' : '';	
		?>
            <option <?php if($value->getIsDefault()==1){?> selected="selected" <?php }?> value="<?php echo $value->getId(); ?>"><?php echo $value->getTitle()." ". $value->getNumberMinuteDefault()." mins."; ?></option>
        <?php 
		$c++;
		} ?>
</select>