<style>
div.action-buttons{ padding-right:10px;padding-top:10px; width:50%;}
div#tabs{min-height:450px;}
</style>
<script type="text/javascript">
	$(function() {
		$("#tabs").tabs();
	});
</script>
<div class="ui-state-highlight ui-corner-all">
<span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span><?php echo $module_title; ?>
</div><br />
<div id="tabs">
	<ul>
		<li><a href="#tabs-1">Company Information</a></li>
        <li><a href="#tabs-2" onclick="javascript:load_company_structure();">Company Structure</a></li>
		
	</ul>    
	<div id="tabs-1">		
       <div id="c-info"></div>
	</div>
    
    <div id="tabs-2">
		<div id="c-structure"></div>
	</div>
</div>
<?php include_once('includes/modal_forms.php'); ?>
<script>
load_company_info();
</script>