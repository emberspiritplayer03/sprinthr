<h2 class="field_title"><?php echo $title; ?></h2>
<?php if($_GET['add_performance']=='true') { ?>
		<div id="performance_form_wrapper" >
<?php }else { ?>
		<div id="performance_form_wrapper" style="display:none" >
<?php } ?>

<?php include 'form/add_performance_form.php'; ?>
</div>
<div class="actions_holder">
<a class="add_button" id="add_performance_button_wrapper" href="#" onClick="javascript:load_add_performance();" ><strong>+</strong><b>Add Performance</b></a>
</div>
<div class="yui-skin-sam">
	<div id="performance_datatable"></div>
</div>
<div id="performance_wrapper"></div>
<div id="confirmation"></div>
<script>
load_performance_datatable();
</script>
<input type="hidden" name="performance_id" />
