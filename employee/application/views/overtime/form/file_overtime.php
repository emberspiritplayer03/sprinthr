<form id="file_overtime_form" method="post" action="<?php echo url('overtime/_file_overtime'); ?>">
	<input type="hidden" id="token" name="token" value="<?php echo $token; ?>">
	<input type="hidden" id="no_of_level" name="no_of_level" value="<?php echo count($approvers);?>" >
	<div id="form_main" class="inner_form popup_form wider">
	    <div id="form_default">
		    <table class="no_border" width="100%">
		    	<tbody>
			    	<tr>
			        	<td width="24%" class="field_label">Date of Overtime</td>
			            <td width="76%"><input class="validate[required] " type="text" name="date_of_overtime" id="date_of_overtime" value=""></td>
			        </tr>      
			        <tr>
						<td align="right"  class="field_label">Overtime In</td>
						<td><input class="validate[required]" type="text" name="start_time" id="start_time" value=""></td>
			        </tr> 
			        <tr>
						<td align="right" class="field_label">Overtime Out</td>
						<td><input class="validate[required]" type="text" name="end_time" id="end_time" value=""></td>
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
		                    <input value="Save" id="add_schedule_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="closeTheDialog()">Cancel</a>
		                </td>
		            </tr>
	        	</tbody>
	        </table>            
	    </div>
	</div>
</form>