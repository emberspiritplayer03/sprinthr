<div class="ui-state-highlight ui-corner-all">
<span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span><?php echo $module_title; ?>
</div><br />
<div class="action_holder">
<a class="add_button" href="javascript:void(0);" onclick="javascript:addNewSubDivisionType();"><strong>+</strong><b>Add New</b></a>
</div>
<div class="yui-skin-sam">
	<div id="subdivision_type_datatable"></div>
</div>

<script>					  
load_subdivision_type_dt();
</script>

<?php 
	chdir(dirname(__FILE__));
	include_once('../includes/modal_forms.php'); 
?>