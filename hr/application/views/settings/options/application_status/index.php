<div class="ui-state-highlight ui-corner-all">
<span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span>
Application Status:
</div>
<br />
<a class="add_button" href="javascript:void(0);" onclick="javascript:load_add_new_application_status();"><strong>+</strong><b>Add New</b></a>
<br />
<div class="yui-skin-sam">
	<div id="application_status_datatable"></div>
</div>

<script>					  
load_application_status_dt();
</script>

<?php 
	chdir(dirname(__FILE__));
	include_once('../includes/modal_forms.php'); 
?>