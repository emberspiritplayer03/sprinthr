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
		<li><a href="#tabs-1" onclick="javascript:load_sss_table();">SSS Table</a></li>
        <li><a href="#tabs-2" onclick="javascript:load_philhealth_table();">Phil Health Table</a></li>
		<li><a href="#tabs-3" onclick="javascript:load_pagibig_table();">Pagibig Table</a></li>
        <li><a href="#tabs-4" onclick="javascript:load_tax_table();">Tax Table</a></li>	
        <li><a href="#tabs-5" onclick="javascript:load_deduction_breakdown_list();">Contribution Settings</a></li>		
	</ul>
	<div id="tabs-1">
		<div id="sss_table"></div>
	</div>
    <div id="tabs-2">
		<div id="philhealth_table"></div>
	</div>
     <div id="tabs-3">
		<div id="pagibig_table"></div>
	</div>
    <div id="tabs-4">
		<div id="tax_table"></div>
	</div>
	<div id="tabs-5">
		<div id="deduction_breakdown_list_wrapper"></div>
	</div>
</div>

<script>
	$(function() {load_sss_table();});
</script>
