<script type="text/javascript">
	$(function() {
		requestForApprovalScripts();
	});
	var jq17 = jQuery.noConflict();
</script>

<div id="tabs">
	<ul>
		<li><a id="overtime" class=" load-data" href="#overtime-tab" >Overtime <span id="ot-counter"><?php echo ($overtime_for_approval > 0 ? "({$overtime_for_approval})" : ""); ?></span></a></li>
		<li><a id="leave" class=" load-data" href="#leave-tab" >Leave <span id="leave-counter"><?php echo ($leave_for_approval > 0 ? "({$leave_for_approval})" : ""); ?></span></a></li>   
		<li><a id="ob" class=" load-data" href="#ob-tab" >Official Business <span id="ob-counter"><?php echo ($ob_for_approval > 0 ? "({$ob_for_approval})" : ""); ?></span></a></li>
	</ul>   

	<div id="overtime-tab">		
       <div class="overtime-pending-list-dt"></div>
	</div>

	<div id="leave-tab">
		<div class="leave-pending-list-dt"></div>
	</div>

	<div id="ob-tab">
		<div class="ob-pending-list-dt"></div>
	</div>    
</div>