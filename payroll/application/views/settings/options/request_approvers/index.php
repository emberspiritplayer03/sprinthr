<div class="ui-state-highlight ui-corner-all">
<span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span>
Approvers
</div>
<br />
<a class="add_button" href="javascript:void(0);" onclick="javascript:load_add_new_application_status();"><strong>+</strong><b>Add New</b></a>
<br />
<div class="yui-skin-sam">
	<div id="approvers_datatable"></div>
</div>

<script>					  
load_approvers_dt();
</script>

<?php 
	chdir(dirname(__FILE__));
	include_once('../includes/modal_forms.php'); 
?>