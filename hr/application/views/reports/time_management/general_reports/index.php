<script type="text/javascript">
	$(function() {
		$("#tabs").tabs();
	});
</script>

<div class="ui-state-highlight ui-corner-all" >
<span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span><?php echo $title; ?>

</div><br />
<div id="tabs" >
	
	<ul>
		<li><a href="#tabs-1" onclick="javascript:load_hr_audit_log_list();">HR</a></li>
        <li><a href="#tabs-2" onclick="javascript:load_payroll_audit_log_list();">PAYROLL</a></li>
        <li><a href="#tabs-3" onclick="javascript:load_timekeeping_audit_log_list();">TIME KEEPING</a></li>
	</ul>

	<div id="tabs-1" >
		<div id="hr-audit-log-wrapper" ></div>      
	</div>
    <div id="tabs-2">
		<div id="payroll-audit-log-wrapper"></div>  
	</div>
	<div id="tabs-3">
		<div id="timekeeping-audit-log-wrapper"></div>  
	</div>
  
</div>

<script>
	$(function() {
		load_hr_audit_log_list();
	});
</script>



