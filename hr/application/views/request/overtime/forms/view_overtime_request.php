<form id="view_overtime_request_form" name="view_overtime_request_form" autocomplete="off" method="POST" action="<?php echo url('request/_load_update_fa_overtime_request'); ?>">
<input type="hidden" id="h_approvers_id" name="h_approvers_id" value="<?php echo Utilities::encrypt($request_approver->getId()); ?>" />

<div id="form_main" class="inner_form popup_form">
    <div id="form_default">
        <table width="100%" border="0" cellspacing="1" cellpadding="2">
          <tr>
            <td style="width:25%" align="left" valign="middle">Date of Overtime :</td>
            <td style="width:75%" align="left" valign="middle"><?php echo $overtime_request->getDateStart(); ?></td>
          </tr>
          <tr>
            <td style="width:25%" align="left" valign="middle">Time of Overtime :</td>
            <td style="width:75%" align="left" valign="middle">
            	<?php 
					echo Tools::convert24To12Hour($overtime_request->getTimeIn()) . ' - ' .Tools::convert24To12Hour($overtime_request->getTimeOut()); 
				?>
            </td>
          </tr>
          <tr>
            <td style="width:25%" align="left" valign="middle">Reason :</td>
            <td style="width:75%" align="left" valign="middle"><?php echo $overtime_request->getOvertimeComments(); ?></td>
          </tr>
          <tr>
            <td style="width:25%" align="left" valign="middle">Date/Time Filed :</td>
            <td style="width:75%" align="left" valign="middle"><?php echo Date::convertDateIntIntoDateString($overtime_request->getDateApplied()); ?></td>
          </tr>
          <tr>
            <td style="width:25%" align="left" valign="middle">Status :</td>
            <td style="width:75%" align="left" valign="middle">
            	<select id="status" name="status" style="width:150px;">
                	<option <?php echo ($request_approver->getStatus() == G_Employee_Overtime_Request::PENDING ? 'selected="selected"' : ''); ?> value="<?php echo G_Employee_Overtime_Request::PENDING ?>">Pending</option>
                    <option <?php echo ($request_approver->getStatus() == G_Employee_Overtime_Request::APPROVED ? 'selected="selected"' : ''); ?> value="<?php echo G_Employee_Overtime_Request::APPROVED ?>">Approved</option>
                    <option <?php echo ($request_approver->getStatus() == G_Employee_Overtime_Request::DISAPPROVED ? 'selected="selected"' : ''); ?> value="<?php echo G_Employee_Overtime_Request::DISAPPROVED ?>">Disapprove</option>
                </select>
                <?php if($request_approver->getOverrideLevel() == 'Granted') { ?>
                	<div class="ui-icon ui-icon-info info" style="display:inline-block; margin-left:5px;" title="You are the main approver of this request. You will override the approval of other approvers. "></div>
                <?php } ?>
            </td>
          </tr>
          <tr>
            <td style="width:25%" align="left" valign="middle">Approver's Remarks :</td>
            <td style="width:75%" align="left" valign="middle"><textarea id="remarks" name="remarks" style="height:75px; width:250px"><?php echo $request_approver->getRemarks(); ?></textarea></td>
          </tr>
        </table>
    </div>
    <div id="form_default" class="form_action_section">
        <table width="100%">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td><input value="Save" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="closeDialogBox('#view_fa_request_form_wrapper','#view_overtime_request_form')">Cancel</a></td>
            </tr>
		</table>
    </div>
</div><!-- #form_main.inner_form -->   
</form>

<script>
	$(function() {
		$('.info').tipsy({gravity: 's'});
	});
</script>