<script>
$(function(){		
	var jqAction = jQuery.noConflict();   
	load_benefits_dt();	
	$("#add-benefit-btn").click(function(){
		addBenefit();
	});

	$("#import-benefit-btn").click(function(){
		importEmployeeBenefits();
	});	
});			  
</script>

<div id="benefits-form-container"></div>
<div id="benefits-container">	
	<div class="ui-state-highlight ui-corner-all">
		<span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span><?php echo $module_title; ?>
	</div><br />
	<div class="action_holder" style="margin-bottom:10px;">
		<a id="add-benefit-btn" class="gray_button vertical-middle pull-left" href="javascript:void(0);"><strong>+</strong><b>Add New</b></a>
		<a id="import-benefit-btn" class="gray_button vertical-middle pull-right" href="javascript:void(0);"><i class="icon-excel icon-custom vertical-middle"> </i><b>Import Benefits</b></a>
		<div class="clear"></div>
	</div>
	<div class="yui-skin-sam">
		<div id="benefits_datatable"></div>
	</div>
</div>

<?php 
	chdir(dirname(__FILE__));
	include_once('../includes/modal_forms.php'); 
?>