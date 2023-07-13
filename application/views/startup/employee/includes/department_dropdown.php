 <?php 
$i = count($departments); 
$c=1;
?>
<select class="select_option" name="department_id" id="department_id" onchange="javascript:checkForAddDepartment();">
    <option value="" selected="selected">-- Select Department --</option>
		<?php foreach($departments as $key=>$value) { 
		$selected = ($c == $i)? 'selected' : '';	
		?>
            <option selected="<?php echo $selected; ?>" value="<?php echo $value->id; ?>"><?php echo $value->title; ?></option>
        <?php 
		$c++;
		} ?>
<option value="add">Add Department...</option>
</select>