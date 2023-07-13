<script>
	$(document).ready(function() {
		$('#withSelectedAction').validationEngine({
			scroll: false
		});
	});

	function countChecked() {
		var inputs = document.withSelectedAction.elements['dtChk[]'];
		var is_checked = false;
		var cnt = 0;
		var theForm = document.withSelectedAction;
		for (i = 0; i < theForm.elements.length; i++) {
			if (theForm.elements[i].name == 'dtChk[]')
				is_checked = theForm.elements[i].checked;
			if (is_checked) {
				cnt++;
			}
		}

		return cnt;

	}

	function chkUnchk() {
		var check_uncheck = document.withSelectedAction.elements['check_uncheck'];
		if (check_uncheck.checked == 1) {
			$('#check_uncheck').attr('title', 'Uncheck All');
			$("#chkAction").removeAttr('disabled');
			var status = 1;
		} else {
			$('#check_uncheck').attr('title', 'Check All');
			$("#chkAction").attr('disabled', true);
			var status = 0;
		}

		var theForm = document.withSelectedAction;
		for (i = 0; i < theForm.elements.length; i++) {
			if (theForm.elements[i].name == 'dtChk[]')
				theForm.elements[i].checked = status;
		}
	}
</script>
<?php include('includes/_wrappers.php'); ?>

<br>
<form name="withSelectedAction" id="withSelectedAction">
	<div class="break-bottom inner_top_option">
		<a id="request_leave_button" class="gray_button" href="javascript:addActivityType();"><i class="icon-plus"></i> <b>Add Activity</b></a>
		<!--   <div class="pull-right">
    	<a title="View All" id="btn_viewall" class="btn btn-small" href="javascript:load_leave_type_list_dt();">&nbsp;&nbsp;<i class="icon-th-list"></i>&nbsp;&nbsp;</a>
    <a title="View Archives" id="btn_viewallarchives" class="btn btn-small" href="javascript:load_archive_leave_type_list_dt();">&nbsp;&nbsp;<i class="icon-trash"></i>&nbsp;&nbsp;</a>
    </div> -->
		<div class="clear"></div>
	</div>
	<div id="activity_list_dt_wrapper" class="dtContainer"></div>
	<div id="activity_wrapper" class="dtContainer"></div>
</form>
<div id="dialog-confirm" style="display:none;">
	<div class="confirmation-box">
		<div>Are you sure you want to delete?</div>
	</div>
</div>
<div id="dialog-confirm-delete" style="display:none;">
	<div>
		<div>Activity Successfully Deleted "</div>
	</div>
</div>

</div>
<script>
	$(function() {
		load_activity_dt();
		$('#btn_viewall').tipsy({
			trigger: 'focus',
			html: true,
			gravity: 's'
		});
		$('#btn_viewallarchives').tipsy({
			trigger: 'focus',
			html: true,
			gravity: 's'
		});
	});
</script>