<div class="ui-state-highlight ui-corner-all">
<span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span>
Request Approvers
</div>
<br />
<a class="add_button" href="javascript:void(0);" onclick="javascript:addNewRequest();"><strong>+</strong><b>Add New</b></a>
<br />
<div class="yui-skin-sam">
	<div id="request_datatable"></div>
</div>

<script>					  
load_request_dt();
</script>

<?php 
	chdir(dirname(__FILE__));
	include_once('../includes/modal_forms.php'); 
?>