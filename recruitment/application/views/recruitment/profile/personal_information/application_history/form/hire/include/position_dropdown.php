<?php 
$i = count($positions); 
$c=1;
?>

<select class="select_option" name="position_id" id="position_id"  onchange="javascript:checkForAddPosition();">
    <option value="" selected="selected">-- Select Position --</option>
		<?php foreach($positions as $key=>$value) {
		$selected = ($c == $i)? 'selected' : '';	
		 ?>
         <option selected="<?php echo $selected; ?>" value="<?php echo $value->id; ?>"><?php echo $value->title; ?></option>
        <?php
		$c++;
		 } ?>

</select>