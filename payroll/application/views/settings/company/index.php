<style>
div.action-buttons{ padding-right:10px;padding-top:10px; width:50%;}
div#tabs{min-height:450px;}
</style>
<script type="text/javascript">
	$(function() {
		$("#tabs").tabs();
	});
</script>

<div id="tabs">
	<ul>
		<li><a href="#tabs-1">Company Information</a></li>
        <li><a href="#tabs-2">Company Structure</a></li>
		
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
load_company_structure();
</script>