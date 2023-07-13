<script type="text/javascript">
	$(function() {
		employeeOfficialBusinessRequestScripts();
	});
	var jq17 = jQuery.noConflict();
</script>

<div id="tabs">
	<ul>
		<li><a id="pending" class="load-data" href="#pending-tab" >For Approval</a></li>
		<li><a id="approved" class="load-data" href="#approved-tab" >Approved</a></li>   
		<li><a id="disapproved" class="load-data" href="#disapproved-tab" >Disapproved</a></li>
	</ul>   

	<div id="pending-tab">		
       <div class="ob-pending-list-dt"></div>
	</div>

	<div id="approved-tab">
		<div class="ob-approved-list-dt"></div>
	</div>

	<div id="disapproved-tab">
		<div class="ob-disapproved-list-dt"></div>
	</div>    
</div>