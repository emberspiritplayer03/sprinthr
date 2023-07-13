<style>
.leave-header{padding:4px;background-color: #198cc9;color:#ffffff;margin-top:9px;line-height: 27px;}
</style>
<script>
$(function(){
  $("#employee_leave_form").validationEngine({scroll:false});
  $('#employee_leave_form').ajaxForm({
      success:function(o) {
          if (o.is_success) {        
              dialogOkBox(o.message,{});   
              load_leave_list_dt($("#cmb_dept_id").val());   
              $('#request_leave_button').show();
              $('#request_leave_form_wrapper').hide();                                  

              var $dialog = $('#action_form');                    
              $dialog.dialog("destroy");                    

          } else {                            
              dialogOkBox(o.message,{});          
          } 
          $("#token").val(o.token);                  
      },
      dataType:'json',
      beforeSubmit: function() {
              showLoadingDialog('Saving...');
      }
  });

  $("#date_start").datepicker({
    dateFormat:'yy-mm-dd',
    changeMonth:true,
    changeYear:true,
    showOtherMonths:true,
    onSelect  :function() {       
      $("#date_end").datepicker('option',{minDate:$(this).datepicker('getDate')});
      var output  = computeDaysWithHalfDay($("#date_start").val(),$("#date_end").val(),"start_halfday","end_halfday");
      $("#number_of_days").val(output);
      load_show_specific_schedule();      
    }
  });
    
  $("#date_end").datepicker({
    dateFormat:'yy-mm-dd',
    changeMonth:true,
    changeYear:true,
    showOtherMonths:true,
    onSelect  :function() { 
      var output  = computeDaysWithHalfDay($("#date_start").val(),$("#date_end").val(),"start_halfday","end_halfday");
      $("#number_of_days").val(output);
      load_show_specific_schedule();
    }
  });

  var t = new $.TextboxList('#employee_id', {max:1,plugins: {
      autocomplete: {
        minLength: 2,
        onlyFromValues: true,
        queryRemote: true,
        remote: {url: base_url + 'leave/ajax_get_employees_autocomplete'}
      
      }
  }});
  
    t.addEvent('blur',function(o) {
    load_show_specific_schedule();
    load_show_employee_leave_available();
    load_show_employee_request_approvers();

  });
});

function hideShowIsPaid() {
  var value = $('#leave_id :selected').text();
  if (value == 'Sick Leave' || value == 'Vacation Leave') {
    $('#is_paid_auto').show();
    $('#is_paid').hide();
  } else {
    $('#is_paid_auto').hide();
    $('#is_paid').show();
  }
}

</script>
<div id="formcontainer">
<form id="employee_leave_form" name="employee_leave_form"  action="<?php echo $form_action ?>" method="post"  name="employee_form" >
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<div id="formwrap">	
	<h3 class="form_sectiontitle">Request Leave</h3>
<div id="form_main">     
  
    <div id="form_default">      
        <table>
             <tr>
               <td class="field_label">Type Employee Name:</td>
               <td><input class="validate[required]" type="text" name="employee_id" id="employee_id" value="" /></td>
             </tr>
        </table>       
        <div id="show_request_approvers_wrapper"></div>
        <div id="show_leave_available_wrapper"></div>
        <h3 class="leave-header">Leave Request Details</h3>
        <table>             
             <tr>
              <td class="field_label">Leave Type:</td>
              <td>
              <select class="validate[required] select_option_sched" name="leave_id" id="leave_id">
                  <option value="">-- Select --</option>
                <?php foreach($leaves as $l) { ?>
                <option value="<?php echo Utilities::encrypt($l->getId()); ?>"><?php echo $l->getName(); ?></option>
                <?php } ?>
               </select>
              </td>
            </tr>  
           <!-- <tr>
              <td class="field_label">Date Applied:</td>
              <td>
              	<input class="validate[required]" type="text" name="date_applied" id="date_applied" value="" />               
              </td>
            </tr> -->        
            <tr>
              <td class="field_label">Date Start:</td>
              <td>
              	<input type="text" class="validate[required]" name="date_start" id="date_start" value="" />
                <br />
                <label class="checkbox">
                <input value="1" type="checkbox" name="start_halfday" id="start_halfday" onclick="javascript:wrapperComputeDaysWithHalfDay('start_halfday','end_halfday','number_of_days');" />Halfday
                </label>
              </td>
            </tr>
            <tr>
              <td class="field_label">Date End (optional):</td>
              <td>
              	<input type="text" class="" name="date_end" id="date_end" value="" />
                <!--<label class="checkbox inline">
                <input style="margin:0 5px 0 0;" value="1" type="checkbox" name="end_halfday" id="end_halfday" onclick="javascript:wrapperComputeDaysWithHalfDay('start_halfday','end_halfday','number_of_days');" />Apply Halfday                </label>-->
              </td>
            </tr>
          <!--   <tr>
              <td class="field_label">Days:</td>
              <td><input name="number_of_days" type="text" id="number_of_days" readonly="readonly" /></td>
            </tr> -->
            <tr>
              <td class="field_label"></td>
              <td>
              	<div id="show_specific_schedule_wrapper"></div>
              </td>
            </tr>             
            <!-- <tr>
              <td class="field_label">Status:</td>
              <td>
              <select class="select_option" name="is_approved" id="is_approved" style="width:30%;">
                <option value="<?php //echo G_Employee_Leave_Request::PENDING; ?>"><?php //echo G_Employee_Leave_Request::PENDING; ?></option>
                <option value="<?php //echo G_Employee_Leave_Request::APPROVED; ?>"><?php //echo G_Employee_Leave_Request::APPROVED; ?></option>                
              </select></td>
            </tr> -->
            <tr>
              <td class="field_label">Deduct to leave credit(s):</td>
              <td>
              <select class="validate[required] select_option_sched" name="is_paid" id="is_paid" style="width:30%;">
                <option value="<?php echo G_Employee_Leave_Request::YES; ?>"><?php echo G_Employee_Leave_Request::YES; ?></option>
                <option value="<?php echo G_Employee_Leave_Request::NO; ?>"><?php echo G_Employee_Leave_Request::NO; ?></option>
              </select>
              <!-- <select class="" name="is_paid_auto" id="is_paid_auto" style="display:none">
                <option value="">Depends on leave credit</option>
              </select> -->
              <?php if($is_have_general_rule) { ?>
                        <br /><strong>Note:</strong> All leave credits will be deducted on Leave General Rule
              <?php } ?>
              </td>
            </tr>    
            <tr>
              <td class="field_label">Leave Comments:</td>
              <td><textarea name="leave_comments" id="leave_comments"></textarea></td>
            </tr>                   
         </table>
    </div>
    <div id="form_default" class="form_action_section">
    	<table width="100%" border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td>
                <input type="submit" value="Save" class="curve blue_button" />
                <a href="javascript:void(0)" onclick="javascript:hide_request_leave_form();">Cancel</a>
                </td>
            </tr>
        </table>
    </div>
</div><!-- #form_main -->
</div>
</form>
</div>

