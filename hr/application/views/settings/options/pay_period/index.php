<div class="ui-state-highlight ui-corner-all">
<span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span>
Pay Period
</div>
<br />
<div class="action_holder">
<!-- <a class="add_button" href="javascript:void(0);" onclick="javascript:load_add_new_pay_period();"><strong>+</strong><b>Add New</b></a> -->
</div>
<div class="yui-skin-sam">
	<div id="pay_period_datatable"></div>
</div>

<script>					  
load_pay_period_dt();
</script>

<?php 
	chdir(dirname(__FILE__));
	include_once('../includes/modal_forms.php'); 
?>