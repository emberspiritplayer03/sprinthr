<style>
div.action-buttons{ padding-right:10px;padding-top:10px; width:50%;}
div#tabs{min-height:450px;}
</style>
<script type="text/javascript">
	$(function() {
		$("#tabs").tabs();
		load_department_list_dt();
	});
</script>

<select id="branch_id" name="branch_id" style="width:150px;">
	<?php foreach($branch as $b): ?>
    	<option value="<?php echo Utilities::encrypt($b->getId()); ?>"><?php echo $b->getName(); ?></option>
    <?php endforeach; ?>
</select>

<br />
<br />

<div id="department_list_dt_wrapper"></div>
<?php include_once('includes/modal_forms.php'); ?>