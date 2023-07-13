<script>
$(function(){
	load_request_approvers_dt();
	$("#add-approvers-btn").click(function(){
		showAddRequestApprovers();		
	});
});
</script>
<div class="request-approvers-form-container"></div>
<div class="data-table-container">
	<div class="ui-state-highlight ui-corner-all">
		<span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span>Request Approvers
	</div>
	<br />
	<div class="action_holder">
		<a class="add_button" id="add-approvers-btn" href="javascript:void(0);"><strong>+</strong><b>Add New Approvers</b></a>
	</div>
	<br />

	<div class="yui-skin-sam">
		<div id="request_approvers_datatable"></div>
	</div>
</div>

<?php 
	chdir(dirname(__FILE__));
	include_once('../includes/modal_forms.php'); 
?>