<script>
	$(function() { 
		load_overtime_list_dt(); 
		$('.overtime_action_link').hide();
	});
</script>
<div id="request_overtime_form_wrapper" style="display:none;"><?php include_once('form/add_overtime_hideshow.php'); ?></div>

<div id="action_link" style="float:left">
<a id="import_ot" class="gray_button" href="javascript:void(0)" onclick="javascript:importOvertimePending('<?php echo $eid; ?>');">Import OT</a>

<div style="display:inline;" class="overtime_action_link">
	<select id="change_status_ck" name="change_status_ck" style="width:150px;" onchange="javascript:change_overtime_request_status();">
    	<option value=""> -- Select Action -- </option>
        <option value="Pending">Pending</option>
        <option value="Approve">Approve</option>
        <option value="Disapproved">Disapproved</option>
    </select>
</div>
<br /><br />
</div>

<div id="overtime_list_dt"></div>


<div id="employee_list_dt_wrapper"></div>
<div id="overtime_details_wrapper"></div>
<div id="edit_request_overtime_form_modal_wrapper"></div>