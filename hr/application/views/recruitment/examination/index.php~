<script>
	//set user access right to the global variable, this is for ajax
	can_manage = "<?php echo $can_manage ?>";
</script>

<!--<h2 class="field_title"><?php echo $title; ?></h2>-->
<?php if($_GET['add']=='show') { ?>
<div id="applicant_examination_add_form_wrapper"  >
<?php } else { ?>
<div id="applicant_examination_add_form_wrapper"  style="display:none">
<?php 	
}?>
<?php include 'form/examination_add.php'; ?>
</div>
<div id="employee_search_container" class="buttons_holder">
Filter:&nbsp;
<a class="small_button blue_button" href="javascript:void(0);" onclick="javascript:load_applicant_examination_datatable('today');">Today</a>
<a class="small_button blue_button" href="javascript:void(0);" onclick="javascript:load_applicant_examination_datatable('next_week');">Next Week</a>
<a class="small_button blue_button" href="javascript:void(0);" onclick="javascript:load_applicant_examination_datatable('last_week');">Last Week</a>
<a class="small_button blue_button" href="javascript:void(0);" onclick="javascript:load_applicant_examination_datatable('all');">All</a>
</div>
<div class="yui-skin-sam">
  <div id="applicant_examination_datatable"></div>
</div>

<script>
load_applicant_examination_datatable('all');
</script>