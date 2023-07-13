<script>
$("#date_applied").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#date_start").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#date_end").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#leave_request_edit_form").validationEngine({scroll:false});
$('#leave_request_edit_form').ajaxForm({
	success:function(o) {
		if(o==1) {
			dialogOkBox('Successfully Updated',{});
			$("#leave_wrapper").html('');
			loadPage("#leave");
			
		}else {
			dialogOkBox(o,{});	
		}		
	},
	beforeSubmit:function() {
		showLoadingDialog('Saving...');	
	}
});
</script>
<form id="leave_request_edit_form" name="form1" method="post" action="<?php echo url('employee/_update_leave_request'); ?>" >
<div id="form_main" class="employee_form">
<input type="hidden" name="id" value="<?php echo $details->id ?>" />
<input type="hidden" name="leave_id" value="<?php echo $details->leave_id ?>" />
<input type="hidden" name="employee_id" value="<?php echo Utilities::encrypt($details->employee_id); ?>" />
<div id="form_default">
  <table>
  	 <tr>
  	   <td class="field_label">Leave Type:</td>
  	   <td><?php 
	    $l = G_Leave_Finder::findById($details->leave_id);
		  echo $l->name; ?></td>
    </tr>
  	 <tr>
  	   <td class="field_label">Date Applied:</td>
  	   <td><input class="validate[required] text-input" type="text" name="date_applied" id="date_applied" value="<?php echo $details->date_applied; ?>" /></td>
    </tr>
  	 <tr>
  	   <td class="field_label">Date Start:</td>
  	   <td><input type="text" class="validate[required] text-input" name="date_start" id="date_start" value="<?php echo $details->date_start; ?>" /></td>
    </tr>
  	 <tr>
  	   <td class="field_label">Date End:</td>
  	   <td><input type="text" class="validate[required] text-input" name="date_end" id="date_end" value="<?php echo $details->date_end; ?>" /></td>
    </tr>
  	 <tr>
  	   <td class="field_label">Leave Comments:</td>
  	   <td><textarea name="leave_comments" id="leave_comments"><?php echo $details->leave_comments; ?></textarea></td>
    </tr>
  	 <tr>
  	   <td class="field_label">&nbsp;</td>
  	   <td><select class="select_option" name="is_approved" id="is_approved">
       <?php if($details->is_approved==0) {
		  	$pending = 'selected="selected"'; 
		  }else if($details->is_approved==1) {
			$approved = 'selected="selected"';
		  }else if($details->is_approved=='-1') {
			$disapproved = 'selected="selected"';
			} ?>

  	     <option <?php echo $pending; ?> value="0">Pending</option>
  	     <option <?php echo $approved; ?> value="1">Approved</option>
  	     <option <?php echo $disapproved; ?> value="-1">Disapproved</option>
	     </select></td>
    </tr>
  </table>
</div><!-- #form_default -->
<div class="form_action_section" id="form_default">
    <table>
    	<tr>
            <td class="field_label">&nbsp;</td>
            <td><a class="delete_link red float-right" href="javascript:void(0);" onclick="javascript:loadLeaveRequestDeleteDialog('<?php echo $details->id; ?>')"><span class="delete"></span>Delete Leave Request</a><input class="blue_button" type="submit" name="button" id="button" value="Update" />&nbsp;<a href="javascript:void(0);" onclick="javascript:loadLeaveRequestTable();">Cancel</a></td>
    	</tr>
    </table>
</div><!-- #form_default.form_action_section -->
</div><!-- #form_main.employee_form -->
</form>
