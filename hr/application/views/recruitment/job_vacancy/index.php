<script>
	//set user access right to the global variable, this is for ajax
	can_manage = "<?php echo $can_manage ?>";
</script>
<div id="add_job_vacancy_form_wrapper" style="display:none">
<?php include 'form/add_job_vacancy_form.php'; ?>
</div>

<div class="yui-skin-sam">
	<div id="job_vacancy_datatable"></div>
</div>
<div id="job_vacancy_wrapper"></div>
<div id="confirmation"></div>
<script>
load_job_vacancy_dt();
</script>