<script type="text/javascript">
	$(function() {
		$("#tabs").tabs();
	});
	</script>

<div class="ui-state-highlight ui-corner-all">
<span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span><?php echo $module_title; ?>
</div><br />
<div id="tabs">
	<ul>
		<li><a href="#tabs-1">Job Title</a></li>
        <li><a href="#tabs-2">Job Specification</a></li>
        <!--<li><a href="#tabs-3">Job Employment Status</a></li>-->
        <!-- <li><a href="#tabs-4">EEO Job Category</a></li>
		<li><a href="#tabs-5">Job Salary Rate</a></li> -->
	</ul>
	<div id="tabs-1">
		<?php include_once('job_title/job_list.php'); ?>       
	</div>
    <div id="tabs-2">
		<?php include_once('job_specification/job_specification_list.php'); ?> 
	</div>
   <!-- <div id="tabs-3">
		<?php include_once('job_employment_status/job_employment_status_list.php'); ?>			
	</div>-->
    <!-- <div id="tabs-4">
		<?php include_once('eeo_job_category/eeo_job_category_list.php'); ?> 
	</div> -->
   
   <!--  <div id="tabs-5">
		<?php include_once('job_salaray_rate/job_salary_rate_list.php'); ?> 
	</div> -->
</div>

<?php include_once('includes/modal_forms.php'); ?>
