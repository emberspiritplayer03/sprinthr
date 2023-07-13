<?php 
$i = count($categories); 
$c=1;
?>
<select class="validate[required] select_option" name="category_id" id="category_id" onchange="javascript:checkForAddCategory();">
	<option value="" selected="selected">-- Select Designation --</option>
	<?php foreach($categories as $key=>$value) { 
		$selected = ($c == $i)? 'selected' : '';	
	?>
		<option selected="<?php echo $selected; ?>" value="<?php echo $value->id; ?>"><?php echo $value->activity_category_name; ?></option>
	<?php 
		$c++;
	} ?>
	<option value="add">Add Designation...</option>
</select>