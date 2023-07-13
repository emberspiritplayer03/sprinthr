<script>
	$(function() {	
		$("#start_date_edit").datepicker({
			dateFormat	: 'yy-mm-dd',
			autoSize  	: true,
			onSelect	:function() { $("#end_date_edit").datepicker('option',{minDate:$(this).datepicker('getDate')}); },	
		});	
		$("#end_date_edit").datepicker({
			dateFormat	: 'yy-mm-dd',
			onSelect	:function() { $("#start_date_edit").datepicker('option',{maxDate:$(this).datepicker('getDate')}); },
		});	
	});
</script>

<form id="edit_request_leave_form11" name="edit_request_leave_form11" method="POST" action="<?php echo url('request/_load_update_leave_request'); ?>">
<input type="hidden" id="request_type" name="request_type" value="<?php echo Utilities::encrypt(Settings_Request::LEAVE); ?>" />
<input type="hidden" id="hid" name="hid" value="<?php echo Utilities::encrypt($leave_request->getId()); ?>" />

<div id="form_main" class="inner_form popup_form">
    <div id="form_default">
        <table width="100%" border="0" cellspacing="1" cellpadding="2">
          <tr>
            <td style="width:15%" align="left" valign="middle">Type :</td>
            <td style="width:85%" align="left" valign="middle">
                <select id="leave_type" name="leave_type" style="width:200px;" class="validate[required]">
                    <option value="" selected="selected"> - Select - </option>
                    <?php foreach($leave_types as $l): ?>
                        <option <?php echo ($l->getId() == $leave_request->getLeaveId() ? 'selected="selected"' : ''); ?> value="<?php echo Utilities::encrypt($l->getId()); ?>"><?php echo $l->getName(); ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
          </tr>
          <tr>
            <td style="width:15%" align="left" valign="middle">Date :</td>
            <td style="width:85%" align="left" valign="middle">
                <input type="text" style="width:150px;" id="start_date_edit" name="start_date_edit" class="validate[required]" placeholder="From" value="<?php echo $leave_request->getDateStart(); ?>" readonly="readonly" />
                <input type="text" style="width:150px;" id="end_date_edit" name="end_date_edit" class="validate[required]" placeholder="To" value="<?php echo $leave_request->getDateEnd(); ?>" readonly="readonly" />
            </td>
          </tr>
          <tr>
            <td style="width:15%" align="left" valign="middle">Reason :</td>
            <td style="width:85%" align="left" valign="middle"><textarea id="reason" name="reason" style="height:100px; width:250px"><?php echo $leave_request->getLeaveComments(); ?></textarea></td>
          </tr>

        </table>        
	</div><!-- #form_default -->
    <div id="form_default" class="form_action_section">
        <table width="100%">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td><input value="Save" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="closeDialogBox('#edit_request_leave_form_wrapper','#edit_request_leave_form')">Cancel</a></td>
            </tr>
		</table>
    </div>
</div><!-- #form_main.inner_form -->   
</form>

