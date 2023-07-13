<?php 
$i = count($branches); 
$c=1;
?>

<select class="validate[required] select_option" name="branch_id" id="branch_id" onchange="javascript:checkForAddBranch();">
    <option value="" selected="selected">-- Select Branch --</option>
        <?php foreach($branches as $key=>$value) {
			$selected = ($c == $i)? 'selected' : '';	
		 ?>
            <option selected="<?php echo $selected; ?>" value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
        <?php
			$c++;
		 } ?>
    <option value="add">Add Branch...</option>
</select>