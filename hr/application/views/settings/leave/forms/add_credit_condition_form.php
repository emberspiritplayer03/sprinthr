<style>
.qry-options{width:20% !important;margin-left:8px;}
.qry-inputs{width:30%;height:21px;margin-left:8px;}
.btn-remove-other-detail{margin-left:10px;}
.qry-title{background-color: #e3e3e3; padding-left: 11px;margin:22px 4px 17px; width: 100%;font-size: 15px;}
.btn-add-qry{margin-top: 7px;margin-right:7px;margin-bottom: 22px;}
</style>
<script type="text/javascript">
$(function() {
	$("#credit_condition_form").validationEngine({scroll:false});
  $('#credit_condition_form').ajaxForm({
    success:function(o) {
      if (o.is_added == 1) {
        hide_leave_credits_form();
        //location.href = base_url + 'settings/leave'; 
        load_leave_credit_list()
        closeDialog('#' + DIALOG_CONTENT_HANDLER);        
        $("#message_container").html(o.message);
        $('#message_container').show();
      } else {
        closeDialog('#' + DIALOG_CONTENT_HANDLER);                    
        $("#message_container").html(o.message);
        $('#message_container').show();
      }
    },
    dataType:'json',
    beforeSubmit: function() {
      showLoadingDialog('Updating...');
      return true;
    }
  });   

  function removeOtherDetail(){
      $(".btn-remove-other-detail").click(function(){        
        $(this).closest("tr").remove();
      });
  }

  $(".btn-add-qry").click(function(){
      var total_rows  = $('.qry-container tr').length;          
      var remove_btn  = '<a class="btn btn-small btn-remove-other-detail" href="javascript:void(0);"><i class="icon-remove-sign"></i></a>';     
      $(".qry-container").append('<tr><td class="form-inline" width="100%" valign="" style="font-size:11px;">On Employee\'s <select style="width:10%" name="' + (total_rows+1) + '_[employment_years]" id="employment_years"><option value="1">1st</option><option value="2">2nd</option><option value="3">3rd</option><option value="4">4th</option><option value="5">5th</option><option value="6">6th</option><option value="7">7th</option><option value="8">8th</option><option value="9">9th</option><option value="10">10th</option></select> year onwards add <input type="text" style="width:15px;" value="" name="' + (total_rows+1) + '_[default_credit]" id="default_credit_' + (total_rows+1) + '" class="validate[required] text" /> credits in <select style="width:18%" name="' + (total_rows+1) + '_[leave_id]"><?php foreach($leave_type as $leave) { ?><option value="<?php echo $leave->getId();?>"><?php echo $leave->getName();?></option><?php } ?></select> to all <select style="width:15%" name="' + (total_rows+1) + '_[employment_status_id]"><?php foreach($employment_status as $emp_status) { ?><option value="<?php echo $emp_status->getId(); ?>"><?php echo $emp_status->getStatus(); ?></option><?php } ?></select> employee' + remove_btn + '</td></tr>');         
      removeOtherDetail();         
      $("#credit_condition_form").validationEngine('attach');
  });
});
</script>
<h2 style="color:#215175;font-size:16px;">Add Leave Credit Condition</h2>
<form action="<?php echo $action; ?>" method="post"  name="credit_condition_form" id="credit_condition_form" >
  <div id="form_main" class="employee_form">
    <a class="btn btn-small pull-right btn-add-qry" href="javascript:void(0);"><i class="icon-plus-sign"></i> Add Credit Condition</a>
    <div class="clear"></div>
    <input type="hidden" id="token" name="token" value="<?php echo $token; ?>" >    
      <div id="form_default">
          <table border="0" cellpadding="3" cellspacing="0" width="100%" class="qry-container"> 
              <tbody>
                  <tr>
                      <td class="form-inline" width="100%" valign="" style="font-size:11px;">                      
                        On Employee's 
                        <select style="width:10%" name="<?php echo $initial_count; ?>_[employment_years]" id="employment_years">
                          <option value="1">1st</option>
                          <option value="2">2nd</option>
                          <option value="3">3rd</option>
                          <option value="4">4th</option>
                          <option value="5">5th</option>
                          <option value="6">6th</option>
                          <option value="7">7th</option>
                          <option value="8">8th</option>
                          <option value="9">9th</option>
                          <option value="10">10th</option>
                        </select>
                        year onwards add <input type="text" style="width:15px;" value="" id="default_credit[<?php echo $initial_count; ?>]" name="<?php echo $initial_count; ?>_[default_credit]" class="validate[required] text" /> credits in 
                        <select style="width:18%" name="<?php echo $initial_count; ?>_[leave_id]">
                          <?php foreach($leave_type as $leave) { ?>
                          <option value="<?php echo $leave->getId();?>"><?php echo $leave->getName();?></option>
                          <?php } ?>
                        </select>                      
                        to all 
                        <select style="width:15%" name="<?php echo $initial_count; ?>_[employment_status_id]">
                          <?php foreach($employment_status as $emp_status) { ?>
                          <option value="<?php echo $emp_status->getId(); ?>"><?php echo $emp_status->getStatus(); ?></option>
                          <?php } ?>
                        </select> employee                      
                      </td>
                  </tr>
              </tbody>
          </table>
      </div>
      <div id="form_default" class="form_action_section">
      	<table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td align="left" valign="top" class="field_label">&nbsp;</td>
              <td align="left" valign="top"><input type="submit" value="Save" class="curve blue_button" />&nbsp;<a href="javascript:hide_leave_credits_form();">Cancel</a></td>
            </tr>
          </table>  
      </div>
  </div>
</form>



<!--</script>-->