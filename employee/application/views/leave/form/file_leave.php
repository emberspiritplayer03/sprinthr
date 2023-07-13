
<form id="file_leave_form" method="post" action="<?php echo url('leave/_file_leave'); ?>">
	<input type="hidden" id="token" name="token" value="<?php echo $token; ?>">
	<input type="hidden" id="no_of_level" name="no_of_level" value="<?php echo count($approvers);?>" >
	<div id="form_main" class="inner_form popup_form wider">
	    <div id="form_default">
		    <table class="no_border" width="100%">
		    	<tbody> 		    		
			        <tr>
						<td align="right"  class="field_label">Leave Type</td>
						<td>
							<select class="validate[required] select_option_sched" name="leave_id" id="leave_id">
								<option value="">-- Select --</option>
								<?php foreach($leaves as $l) { ?>
									<option value="<?php echo Utilities::encrypt($l->getId()); ?>"><?php echo $l->getName(); ?></option>
								<?php } ?>
			                </select>
						</td>
			        </tr> 
			         <tr>
						<td align="right" class="field_label">From</td>
						<td><input class="validate[required]" type="text" name="date_start" id="date_start" value=""></td>
			        </tr>
			        <tr>
						<td align="right" class="field_label">To</td>
						<td>
							<input class="validate[required]" type="text" name="date_end" id="date_end" value="">
							<br />
			                <label class="checkbox">
			                	<input value="1" type="checkbox" name="start_halfday" id="start_halfday" onclick="javascript:wrapperComputeDaysWithHalfDay('start_halfday','end_halfday','number_of_days');" />Halfday
			                </label>
						</td>
			        </tr>			       
			        <tr>
						<td class="field_label">Days:</td>
						<td><input name="number_of_days" type="text" id="number_of_days" readonly="readonly" /></td>
		            </tr>
		            <tr>
						<td class="field_label">Deduct to leave credit(s):</td>
						<td>
							<div id="is_paid_wrapper">
								<input name="is_paid" type="text" id="is_paid" readonly="readonly" value="No" />
							</div>
						</td>
		            </tr>
		            <?php if($approvers) { ?>
				        <?php foreach($approvers as $level => $approver) { ?>
				        <tr>
							<td align="right" class="field_label">Approver #<?php echo $level;?></td>
							<td>
								<select name="approvers[<?php echo $level; ?>]" id="approver_id_<?php echo $level;?>" class="select-approver validate[required]" style="width:219px;">
								<?php foreach($approver as $key => $value) { ?>
									<option value="<?php echo Utilities::encrypt($value['employee_id']); ?>"><?php echo $value['employee_name']; ?></option>
								<?php } ?>
								</select>
							</td>
				        </tr>
				        <?php } ?>
				    <?php }else{ ?>
				    	<tr>
							<td align="right" class="field_label">Approver</td>
							<td>
								<select name="approver_id" id="approver_id" class="select-approver validate[required]" style="width:219px;">
									<option value="">- No assigned approver -</option>
								</select>
							</td>
				        </tr>
				    <?php } ?>
			        <tr>
						<td align="right" class="field_label">Reason</td>
						<td><textarea class="validate[required]"  id="reason" name="reason" style="min-width:290px !important;"></textarea></td>
			        </tr>  
		        </tbody>
		    </table>
	    </div>

	    <span id="schedule_message"></span>
	    <div id="form_default" class="form_action_section">
	        <table class="no_border" width="100%">
	            <tbody>
		            <tr>
		                <td class="field_label">&nbsp;</td>
		                <td>
		                    <input value="Save" id="" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="closeTheDialog()">Cancel</a>
		                </td>
		            </tr>
	        	</tbody>
	        </table>            
	    </div>
	</div>
</form>