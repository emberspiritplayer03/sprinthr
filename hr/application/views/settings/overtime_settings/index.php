<script type="text/javascript">
	$(function() {
		var jqAction = jQuery.noConflict();   
		$("#tabs").tabs({});
		load_overtime_allowance_list_dt();

		$("#btn-add-ot-allowance").click(function(){
			showAddOtAllowance();
		});

		$(".ot-allowance-tab").click(function(){
			load_overtime_allowance_list_dt();
		});

		$("#btn-add-ot-rate").click(function(){
			showAddOtRate();
		});

		$(".ot-rate-tab").click(function(){
			load_overtime_rate_list_dt();
		});

	});
</script>
<div id="overtime-allowance-edit-container"></div>
<div id="overtime-rate-edit-container"></div>
<div id="overtime-settings-form-container"></div>
<div id="overtime-settings-container">
	<div class="ui-state-highlight ui-corner-all">
		<span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span>Overtime Settings
	</div>
	<div id="tabs" class="">
		<ul>
			<li><a class="ot-allowance-tab" href="#tabs-1">Overtime Allowance</a></li>
			<li><a class="ot-rate-tab" href="#tabs-2">Employee Overtime Rate</a></li>
		</ul>
		<div id="tabs-1" style="min-height:100px;">
			<div >
				<a id="btn-add-ot-allowance" class="blue_button " href="javascript:void(0);">Add OT Allowance</a>		
			</div><br/>	
			<div id="overtime-allowance-wrapper"></div>
		</div>
		<div id="tabs-2" style="min-height:100px;">
			<div >
				<a id="btn-add-ot-rate" class="blue_button " href="javascript:void(0);">Add Employee OT Rate</a>		
			</div><br/>	
			<div id="overtime-rate-wrapper"></div>
		</div>

	</div>
</div>