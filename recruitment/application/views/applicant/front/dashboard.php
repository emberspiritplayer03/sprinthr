<div class="ui-state-highlight ui-corner-all">
<span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span><?php echo $module_title; ?>
</div><br />

<div id="applicant_list_dt_wrapper" class="dtContainer"></div>    

<script>
	$(function() { load_application_list_dt(); });
</script>

<?php include('includes/modal_forms.php') ?>

<br><br><br>

<div id="applicantion_view_details_wrapper" style="display:none">
	<div id="formcontainer">
		<div class="mtshad"></div>
		<div id="formwrap">	
			<h3 class="form_sectiontitle">View Details</h3>
			<div id="form_main">
				<a onclick="javascript:hideApplicationDetails();" href="javascript:void(0);">Hide</a>
			</div>
		</div>
	</div>
</div>