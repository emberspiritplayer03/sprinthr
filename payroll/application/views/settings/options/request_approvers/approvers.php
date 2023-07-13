<div class="ui-state-highlight ui-corner-all">
<span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span>
<?php echo $r->getTitle(); ?> : Approvers
</div>
<br />
<a class="add_button" href="javascript:void(0);" onclick="javascript:addApprovers(<?php echo $r->getId(); ?>);"><strong>+</strong><b>Add New</b></a>
<a class="add_button" href="javascript:void(0);" onclick="javascript:sortApproversLevel(<?php echo $r->getId(); ?>);"><b>Sort Level of Approval</b></a>
<div align="right" style="float:right;">
<a class="add_button" href="<?php echo url('settings/options?sidebar=10'); ?>"><strong>&laquo;</strong><b>Back</b></a>
</div>
<br />
<br />
<div class="yui-skin-sam">
	<div id="request_datatable"></div>
</div>
<script>
load_request_approvers_dt(<?php echo $r->getId(); ?>);
</script>
<?php 
	chdir(dirname(__FILE__));
	include_once('../includes/modal_forms.php'); 
?>