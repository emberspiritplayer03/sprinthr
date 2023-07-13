<?php 
$i = count($activities); 
$c=1;
?>
<select class="validate[required] select_option" name="activity_id" id="activity_id" onchange="javascript:checkForAddCategory();">
	<option value="" selected="selected">-- Select Activity --</option>
	<?php foreach($activities as $key=>$value) { 
		$selected = ($c == $i)? 'selected' : '';	
	?>
		<option selected="<?php echo $selected; ?>" value="<?php echo $value->id; ?>"><?php echo $value->activity_skills_name; ?></option>
	<?php 
		$c++;
	} ?>
	<option value="add">Add Activity...</option>
</select>