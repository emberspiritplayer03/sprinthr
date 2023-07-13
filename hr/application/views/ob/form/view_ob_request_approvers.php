<style>
.approver-name{padding:8px;background-color:#198cc9;color:#ffffff;}
</style>
<script>
$(function(){
  $("#ob_request_approvers").validationEngine({scroll:false});
  $("#ob_request_approvers").ajaxForm({
    success:function(o) {        
      dialogOkBox(o.message,{});
      $('#token').val(o.token);
      
      var $dialog = $('#action_form');                    
      $dialog.dialog("destroy");     

      if( o.is_success ){ 
        location.reload();
      }
    },
    dataType:'json',     
    beforeSubmit: function() {        
      showLoadingDialog('Saving...');
    }
  });
});
</script>
<div id="form_main" class="inner_form popup_form wider">
<p>Note : Data will be lock for editing once the payroll period is locked.</p>
<form id="ob_request_approvers" name="ob_request_approvers"  action="<?php echo url('ob/_update_ob_request_approvers'); ?>" method="post" > 
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<input type="hidden" id="eid" name="eid" value="<?php echo $eid; ?>" />
    <div id="form_default">              
       <?php 
          for( $x = 1; $x <= $total_approvers; $x++ ){ 
          $needle   = $x-1;
          $eid      = Utilities::encrypt($approvers[$needle]['id']);
          $approver = $approvers[$needle]['approver_name'];
          $remarks  = $approvers[$needle]['remarks'];
          $status   = $approvers[$needle]['status'];         
          $is_disabled = ($is_lock == G_Request::YES ? 'disabled="disabled"' : '' );
       ?>
         <div>           
            <h4 class="approver-name">Approver <b><?php echo $x; ?> : <?php echo $approver; ?></h4>
            <table>
              <tr>
                <td>Status</td>
                <td> : 
                  <select name="approvers[<?php echo $eid ?>][status]" style="width:26%;" <?php echo $is_disabled; ?>>
                    <option <?php echo ($status == G_Request::PENDING ? 'selected="selected"' : ''); ?> ><?php echo G_Request::PENDING; ?></option>
                    <option <?php echo ($status == G_Request::APPROVED ? 'selected="selected"' : ''); ?> ><?php echo G_Request::APPROVED; ?></option>
                    <option <?php echo ($status == G_Request::DISAPPROVED ? 'selected="selected"' : ''); ?> ><?php echo G_Request::DISAPPROVED; ?></option>
                  </select>
                </td>
              </tr>
              <tr>
                <td>Remarks</td>
                <td> : <textarea <?php echo $is_disabled; ?> name="approvers[<?php echo $eid ?>][remarks]"><?php echo $remarks; ?></textarea></td>
              </tr>
            </table>
            
         </div>              
       <?php } ?>
    </div>
    <div id="form_default" class="form_action_section">
    	<table width="100%" border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td>
                <input type="submit" value="Update" class="curve blue_button" />
                <a href="javascript:void(0)" onclick="javascript:closeDialogBox('#_dialog-box_','#request_leave_form');">Cancel</a>
                </td>
            </tr>
        </table>
    </div>
</form>
</div><!-- #form_main -->

