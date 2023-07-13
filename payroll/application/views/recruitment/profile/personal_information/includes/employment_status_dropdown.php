<?php 

$i = count($status); 
$c=1;
?>
<?php if($status_type==0) { ?>

<select class="validate[required] select_option" name="employment_status_id" id="employment_status_id" onchange="javascript:checkForTermination();">
   <?php if($employment_status=='') { ?>
    <option value="" selected="selected">-- Select Employment Status --</option>
    <?php }else { ?>
	<option value="<?php echo $employment_status; ?>" selected="selected"><?php echo $employment_status; ?></option>
	<?php } ?>
		<?php foreach($status as $key=>$value) {
		$selected = ($c == $i)? 'selected' : '';	
			 ?>
        <option value="<?php echo $value->status;  ?>"><?php echo $value->status; ?></option>
        <?php
		$c++;
		 } ?>
    <option value="0" >Terminated</option>
 
</select>
<?php } ?>

<?php if($status_type==1) { ?>

<select class="validate[required] select_option" name="employment_status_id" id="employment_status_id" onchange="javascript:checkForTermination();">
	<?php if($employment_status=='') { ?>
    <option value="" selected="selected">-- Select Employment Status --</option>
    <?php }else { ?>
	<option value="<?php echo $employment_status; ?>" selected="selected"><?php echo $employment_status; ?></option>
	<?php } ?>
		<?php foreach($status as $key=>$value) {
		$selected = ($c == $i)? 'selected' : '';	
			 ?>
        <option value="<?php echo $value->employment_status;  ?>"><?php echo $value->employment_status; ?></option>
        <?php
		$c++;
		 } ?>
    <option value="0" >Terminated</option>

</select>
<?php } ?>