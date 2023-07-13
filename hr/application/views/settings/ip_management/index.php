<script>
$(function(){
	load_ip_address_list_dt();
	$("#add-ip-btn").click(function(){
		showAddIpAddress();		
	});
	jq17 = jQuery.noConflict();
});
</script>
<div class="ip-address-form-container"></div>
<div class="data-table-container">
	<div class="ui-state-highlight ui-corner-all">
		<span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span>IP Management
	</div>
	<br />
	<div class="action_holder">
		<a class="add_button" id="add-ip-btn" href="javascript:void(0);"><strong>+</strong><b>Add IP Address</b></a>
	</div>
	<br />

	<div id="ip_address_list_dt"></div>
</div>

<?php 
	chdir(dirname(__FILE__));
	include_once('../includes/modal_forms.php'); 
?>