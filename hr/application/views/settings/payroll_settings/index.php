<script>
$(function(){			
	load_payroll_settings();		
});			  
</script>

<div id="benefits-form-container"></div>
<div id="benefits-container">	
	<div class="ui-state-highlight ui-corner-all">
		<span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span><?php echo $page_title; ?>
	</div><br />	
	<div class="yui-skin-sam">
		<div id="payroll_settings_datatable"></div>
	</div>
</div>

<?php 
	chdir(dirname(__FILE__));
	include_once('../includes/modal_forms.php'); 
?>