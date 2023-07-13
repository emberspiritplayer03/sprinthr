<script>
$(function(){  
  $('#leave_available_edit_form').validationEngine({scroll:false});   
  $('#leave_available_edit_form').ajaxForm({
      success:function(o) {        
        if( o.is_success ){            
          $("#leave_wrapper").html('');         
          loadPage("#leave");         
        }
        dialogOkBox(o.message,{});       
      },
      dataType:'json',     
      beforeSubmit: function() {        
        showLoadingDialog('Saving...');
      }
  });
});
</script>
<form id="leave_available_edit_form" name="form1" method="post" action="<?php echo url('employee/_update_leave_available'); ?>" >
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
  	   <td class="field_label">Number of Days Alloted:</td>
  	   <td><input class="text-input validate[required,custom[number]]" type="text" name="no_of_days_alloted" id="no_of_days_alloted" value="<?php echo $details->no_of_days_alloted; ?>" /></td>
    </tr>
  	 <tr>
  	   <td class="field_label">Number of Days Available:</td>
  	   <td><input class="text-input validate[required,custom[number]]" type="text" name="no_of_days_available" id="no_of_days_available" value="<?php echo  ucfirst($details->no_of_days_available); ?>" /></td>
    </tr>
  </table>
</div><!-- #form_default -->
<div class="form_action_section" id="form_default">
    <table>
    	<tr>
            <td class="field_label">&nbsp;</td>
            <td><input class="blue_button" type="submit" name="button" id="button" value="Update" />&nbsp;<a href="javascript:void(0);" onclick="javascript:loadLeaveAvailableTable();">Cancel</a></td>
    	</tr>
    </table>
</div><!-- #form_default.form_action_section -->
</div><!-- #form_main.employee_form -->
</form>
