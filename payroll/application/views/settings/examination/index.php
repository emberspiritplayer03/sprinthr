<h2 class="field_title"><?php echo $title; ?></h2>
<?php if($_GET['add_examination']=='true') { ?>
		<div id="examination_form_wrapper" >
<?php }else { ?>
		<div id="examination_form_wrapper" style="display:none" >
<?php } ?>

<?php include 'form/add_examination_form.php'; ?>
</div>
<div class="actions_holder">
<a class="add_button" id="add_examination_button_wrapper" href="#" onClick="javascript:load_add_examination();" ><strong>+</strong><b>Add Examination</b></a>
</div>
<div class="yui-skin-sam">
	<div id="examination_datatable"></div>
</div>
<div id="examination_wrapper"></div>
<div id="confirmation"></div>
<script>
load_examination_datatable();
</script>
<input type="hidden" name="examination_id" />
