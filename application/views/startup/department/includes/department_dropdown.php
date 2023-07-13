 <?php 
$i = count($departments); 
$c=1;
?>
<select class="select_option" name="department_id_startup" id="department_id_startup" onchange="javascript:checkForAddDepartmentStartup();">
    <option value="" selected="selected">-- Select Department --</option>
		<?php foreach($departments as $key=>$value) { 
		$selected = ($c == $i)? 'selected' : '';	
		?>
            <option selected="<?php echo $selected; ?>" value="<?php echo $value->id; ?>"><?php echo $value->title; ?></option>
        <?php 
		$c++;
		} ?>

</select>