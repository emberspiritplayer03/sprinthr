<style>
.btn-delete-breaktime-schedule{z-index: 99999;}
.yui-dt-last{z-index: 999 !important;}
</style>
<script>
$(function(){			
	
	$("#btn-add-breaktime-schedule").click(function(){
		showAddBreaktimeSchedule();
	});

	load_breaktime_schedules_dt();	
});			  
</script>

<div class="breaktime-schedule-form-container"></div>
<div class="breaktime-schedule-container">	
	<div class="ui-state-highlight ui-corner-all">
		<span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span><?php echo $page_title; ?>		
	</div><br />	
	<a id="btn-add-breaktime-schedule" class="blue_button pull-right" href="javascript:void(0);">Add Break Time Schedule</a>	
	<div class="yui-skin-sam">
		<div id="breaktime_schedules_datatable"></div>
	</div>
</div>

<?php 
	chdir(dirname(__FILE__));
	include_once('../includes/modal_forms.php'); 
?>