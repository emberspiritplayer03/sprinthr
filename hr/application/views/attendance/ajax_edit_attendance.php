<form method="post" id="edit_attendance" name="edit_attendance" action="<?php echo $action;?>">
<input type="hidden" name="date" value="<?php echo $date;?>" />
<input type="hidden" name="employee_id" value="<?php echo $employee_id;?>" />
<div id="form_main" class="inner_form popup_form">
    <div id="form_default">
        <table width="100%">
          <tr>
            <td>
                <label class="col_1_2 radio"><input <?php echo $present;?> name="attendance_type" value="present" type="radio" />Present</label>
                <label class="col_1_2 radio"><input <?php echo $absent;?> name="attendance_type" value="absent" type="radio" /><?php echo $not_present;?></label>
                <!--<label class="col_1_2"><input <?php echo $restday_present;?> name="attendance_type" value="restday_present" type="radio" />Restday OT</label>-->
                <label class="col_1_2 radio"><input <?php echo $restday;?> name="attendance_type" value="restday" type="radio" />Restday</label>
                <?php foreach ($leaves as $leave):?>
                    <label class="col_1_2 radio"><input <?php echo ($leave->getId() == $leave_id) ? 'checked="checked"' : '' ;?> name="attendance_type" value="<?php echo $leave->getId();?>" type="radio" /><?php echo $leave->getName();?></label>
                <?php endforeach;?>                
                <div class="clear"></div><br />
                <div class="form_separator"></div>
                <div><label class="checkbox"><input <?php echo ($is_paid) ? 'checked="checked"' : '' ;?> type="checkbox" name="is_paid" value="1" /> with Pay</label></div>
            </td>
          </tr>
        </table>        
	</div><!-- #form_default -->
    <div id="form_default" class="form_action_section">
        <table width="100%">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td><input value="Save" id="edit_schedule_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="closeTheDialog()">Cancel</a></td>
            </tr>
		</table>
    </div>
</div><!-- #form_main.inner_form -->   
</form>