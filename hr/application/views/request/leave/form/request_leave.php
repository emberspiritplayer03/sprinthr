<script>
$(document).ready(function() {	
	$('#employee_leave_form').validationEngine({scroll:false});	
		
	$('#employee_leave_form').ajaxForm({
		success:function(o) {
			if (o.is_saved == 1) {			
				//load_leave_list_dt(o.es_id);				
				load_leave_list_dt();				
				$('#request_leave_button').show();
				$('#request_leave_form_wrapper').hide();
				closeDialog('#' + DIALOG_CONTENT_HANDLER);	
				dialogOkBox(o.message,{});						
			} else {
				closeDialog('#' + DIALOG_CONTENT_HANDLER);	
				dialogOkBox(o.message,{});	
			}
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
		onSelect	:function() { 
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
		onSelect	:function() { 
			var output  = computeDaysWithHalfDay($("#date_start").val(),$("#date_end").val(),"start_halfday","end_halfday");				
			$("#number_of_days").val(output);
			load_show_specific_schedule();
		}
	});
	
	/*$("#date_applied").datepicker({
		dateFormat:'yy-mm-dd',
		changeMonth:true,
		changeYear:true,
		showOtherMonths:true
	});*/
	
});
</script>
<div id="formcontainer">
<form id="employee_leave_form" name="employee_leave_form"  action="<?php echo url('leave/_insert_new_employee_leave'); ?>" method="post"  name="employee_form" > 
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<div id="formwrap">	
	<h3 class="form_sectiontitle">Request Leave</h3>
<div id="form_main">     
  
    <div id="form_default">      
        <table>                          
             <tr>
              <td class="field_label">Leave Type:</td>
              <td>
              <select onchange="javascript:load_show_employee_leave_available();" class="validate[required] select_option_sched" name="leave_id" id="leave_id">              
                  <option value="">-- Select --</option>
                <?php foreach($leaves as $l) { ?>
                <option value="<?php echo Utilities::encrypt($l->getId()); ?>"><?php echo $l->getName(); ?></option>
                <?php } ?>
               </select>
              </td>
            </tr>  
            <tr>
              <td class="field_label"></td>
              <td>
              	<div id="show_leave_available_wrapper"></div>
              </td>
             </tr>
           <!-- <tr>
              <td class="field_label">Date Filed:</td>
              <td>
              	<input class="validate[required]" type="text" name="date_applied" id="date_applied" value="" />               
              </td>
            </tr>    -->     
            <tr>
              <td class="field_label">Date from:</td>
              <td>
              	<input type="text" class="validate[required]" name="date_start" id="date_start" value="" />
                <label class="checkbox inline">
                <input style="margin:0 5px 0 0;" value="1" type="checkbox" name="start_halfday" id="start_halfday" onclick="javascript:wrapperComputeDaysWithHalfDay('start_halfday','end_halfday','number_of_days');" />Apply Halfday                                
                </label>
              </td>
            </tr>
            <tr>        
              <td class="field_label">Date to:</td>
              <td>
              	<input type="text" class="validate[required]" name="date_end" id="date_end" value="" />
                <label class="checkbox inline">
                <input style="margin:0 5px 0 0;" value="1" type="checkbox" name="end_halfday" id="end_halfday" onclick="javascript:wrapperComputeDaysWithHalfDay('start_halfday','end_halfday','number_of_days');" />Apply Halfday                     
                </label>
              </td>
            </tr>
            <tr>
              <td class="field_label">Days:</td>
              <td><input name="number_of_days" type="text" id="number_of_days" readonly="readonly" /></td>
            </tr>
            <tr>
              <td class="field_label"></td>
              <td>
              	<div id="show_specific_schedule_wrapper"></div>
              </td>
            </tr>
             <tr>
              <td class="field_label">Is paid:</td>
              <td>
              <select class="validate[required] select_option_sched" name="is_paid" id="is_paid">              
              	<option value="<?php echo G_Employee_Leave_Request::YES; ?>"><?php echo G_Employee_Leave_Request::YES; ?></option> 
                <option value="<?php echo G_Employee_Leave_Request::NO; ?>"><?php echo G_Employee_Leave_Request::NO; ?></option>
              </select>
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

