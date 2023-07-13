<select style="width:216px;" id="p_cutoff_period" name="p_cutoff_period">
	<?php foreach($cutoffs as $node){
			foreach($node as $n){
	?>
    	<?php
			$is_period_start_exists = G_Cutoff_Period_Helper::isPeriodStartExist($n['start']);
			$is_period_end_exists   = G_Cutoff_Period_Helper::isPeriodEndExist($n['end']);
			if($is_period_start_exists <= 0 && $is_period_end_exists <= 0){
				$start = date("m-d",strtotime($n['start']));
				$end   = date("m-d",strtotime($n['end']));
		?>
	      	<option value="<?php echo $n['start'] . "/" . $n['end']; ?>"><?php echo $start . " to " . $end; ?></option>
        <?php } ?>
    <?php }} ?>
</select>   
<label class="checkbox">
	<input type="checkbox" id="generate_all" name="generate_all" onchange="javascript:enableDisAbleObject('p_cutoff_period','generate_all');" /> Generate All
</label>       