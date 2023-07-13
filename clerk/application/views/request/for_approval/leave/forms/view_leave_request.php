<form id="view_leave_request_form" name="view_leave_request_form" autocomplete="off" method="POST" action="<?php echo url('request/_load_update_fa_leave_request'); ?>">
<input type="hidden" id="h_approvers_id" name="h_approvers_id" value="<?php echo Utilities::encrypt($request_approver->getId()); ?>" />

<?php $l = G_Leave_Finder::findById($leave_request->getLeaveId()); ?>
<div id="form_main" class="inner_form popup_form">
    <div id="form_default">
        <table width="100%" border="0" cellspacing="1" cellpadding="2">
         <tr>
            <td style="width:15%" align="left" valign="middle">Type :</td>
            <td style="width:85%" align="left" valign="middle"><?php echo $l->getName(); ?></td>
          </tr>
          <tr>
            <td style="width:15%" align="left" valign="middle">Date :</td>
            <td style="width:85%" align="left" valign="middle">  
               <?php 
					if($leave_request->getDateStart() == $leave_request->getDateEnd()) {
						echo date('F j, Y',strtotime($leave_request->getDateStart()));
					} else {
						if(date('F',strtotime($leave_request->getDateStart())) == date('F',strtotime($leave_request->getDateEnd()))) {
							echo date('F j',strtotime($leave_request->getDateStart())) . ' - ' . date('j, Y',strtotime($leave_request->getDateEnd())); 
						} else {
							echo date('F j',strtotime($leave_request->getDateStart())) . ' - ' . date('F j, Y',strtotime($leave_request->getDateEnd())); 	
						}
					}
				?>
            </td>
          </tr>
          <!--<tr>
            <td style="width:15%" align="left" valign="middle">Time :</td>
            <td style="width:85%" align="left" valign="middle">
                <input type="text" style="width:70px;" id="start_time_edit" name="start_time_edit" class="" onchange="javascript:onStartTimeChanged();" placeholder="Starts on" value="<?php //echo Tools::convert12To24Hour($leave_request->getTimeIn()); ?>" readonly="readonly" />
                <input type="text" style="width:70px;" id="end_time_edit" name="end_time_edit" class="" placeholder="Ends on" value="<?php //echo Tools::convert12To24Hour($leave_request->getTimeOut()); ?>" readonly="readonly" />
            </td>
          </tr>-->
          <tr>
            <td style="width:15%" align="left" valign="middle">Reason :</td>
            <td style="width:85%" align="left" valign="middle"><?php echo $leave_request->getLeaveComments(); ?></td>
          </tr>
          <tr>
            <td style="width:15%" align="left" valign="middle">Date/Time Filed :</td>
            <td style="width:85%" align="left" valign="middle"><?php echo Date::convertDateIntIntoDateString($leave_request->getDateApplied()); ?></td>
          </tr>
          <tr>
            <td style="width:15%" align="left" valign="middle">Status :</td>
            <td style="width:85%" align="left" valign="middle">
            	<select id="status" name="status" style="width:150px;">
                	<option <?php echo ($request_approver->getStatus() == 0 ? 'selected="selected"' : ''); ?> value="0">PENDING</option>
                    <option <?php echo ($request_approver->getStatus() == 1 ? 'selected="selected"' : ''); ?> value="1">APPROVE</option>
                    <option <?php echo ($request_approver->getStatus() == -1 ? 'selected="selected"' : ''); ?> value="-1">DISAPPROVE</option>
                </select>
            </td>
          </tr>
          <tr>
            <td style="width:15%" align="left" valign="middle">Approver's Remarks :</td>
            <td style="width:85%" align="left" valign="middle"><textarea id="remarks" name="remarks" style="height:75px; width:250px"></textarea></td>
          </tr>
        </table>
    </div>
    <div id="form_default" class="form_action_section">
        <table width="100%">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td><input value="Save" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="closeDialogBox('#view_fa_request_form_wrapper','#view_leave_request_form')">Cancel</a></td>
            </tr>
		</table>
    </div>
</div><!-- #form_main.inner_form -->   
</form>