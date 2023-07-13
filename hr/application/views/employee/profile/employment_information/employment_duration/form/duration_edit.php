<script>
$("#start_date").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#end_date").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#duration_edit_form").validationEngine({scroll:false});
$('#duration_edit_form').ajaxForm({
	success:function(o) {
		if(o==1) {
			dialogOkBox('Successfully Updated',{height:240,width:390});
			$("#duration_wrapper").html('');
			var hash = window.location.hash;
			loadPage(hash);
			
		}else {
			dialogOkBox(o,{height:240,width:390});	
		}		
	},
	beforeSubmit:function() {
		showLoadingDialog('Saving...');	
	}
});
</script>
<form action="<?php echo url('employee/_update_duration'); ?>" method="post" enctype="multipart/form-data" name="form1" id="duration_edit_form" >
<div id="form_main" class="employee_form">
<input type="hidden" name="id" value="<?php echo $details->id ?>" />
<input type="hidden" name="employee_id" value="<?php echo Utilities::encrypt($details->employee_id); ?>" />
<input type="hidden" name="attachment" value="<?php echo $details->getAttachment(); ?>" />
<div id="form_default">
  <table>
  	 <tr>
  	   <td class="field_label">From:</td>
  	   <td><input type="text" class="validate[required]" name="start_date" id="start_date" value="<?php echo $details->start_date; ?>" /></td>
    </tr>
  	 <tr>
  	   <td class="field_label">To:</td>
  	   <td><input name="end_date" class="validate[required]" type="text" id="end_date" value="<?php echo  ucfirst($details->end_date); ?>" /></td>
    </tr>
  	 <tr>
  	   <td class="field_label">Attachment:</td>
  	   <td><input type="file" name="filename" id="filename" /></td>
	   </tr>
  	 <tr>
  	   <td class="field_label">Remarks</td>
  	   <td><textarea name="remarks" id="remarks" cols="45" rows="5"><?php echo  $details->remarks; ?></textarea></td>
	   </tr>
  	 <tr>
  	   <td class="field_label">Contract Status</td>
  	   <td><?php
	   if($details->is_done==0) {$current = "selected='selected'";}else {$expired = "selected='selected'";}
	    ?>
       <select class="validate[required]" name="is_done" id="is_done">
        <option value="">-- Select Contract Status --</option>
        <option <?php echo $current; ?> value="0">Current</option>
        <option <?php echo $expired; ?> value="1">Expired</option>
      </select>
       </td>
	   </tr>
  </table>
</div><!-- #form_default -->
<div class="form_action_section" id="form_default">
    <table>
    	<tr>
            <td class="field_label">&nbsp;</td>
            <td><a class="delete_link red float-right" href="javascript:void(0);" onclick="javascript:loadDurationDeleteDialog('<?php echo $details->id; ?>')"><span class="delete"></span>Delete Duration</a><input class="blue_button" type="submit" name="button" id="button" value="Update" />&nbsp;<a href="javascript:void(0);" onclick="javascript:loadDurationTable();">Cancel</a></td>
    	</tr>
    </table>
</div><!-- #form_default.form_action_section -->
</div><!-- #form_main.employee_form -->
</form>
