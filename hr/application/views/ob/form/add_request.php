<style>
.leave-header{padding:4px;background-color: #198cc9;color:#ffffff;margin-top:9px;line-height: 27px;}
</style>
<script>
var date_from_str = $("#date_from").val();
var date_to_str   = $("#date_to").val();

$(document).ready(function() {		
	$('#add_request_form').validationEngine({scroll:false});	
		
	$('#add_request_form').ajaxForm({
		success:function(o) {
			if (o.is_success == 1) {
				load_ob_list_dt(o.from,o.to);		
				hide_add_ob_form();
				closeDialog('#' + DIALOG_CONTENT_HANDLER);	
				dialogOkBox(o.message,{});						
			} else {
				hide_add_ob_form();
				closeDialog('#' + DIALOG_CONTENT_HANDLER);	
				dialogOkBox(o.message,{});			
			}
		},
		dataType:'json',
		beforeSubmit: function() {
			showLoadingDialog('Saving...');
		}
	});	
	
	$("#ob_date_from").datepicker({
		minDate: date_from_str,
    	maxDate: date_to_str,
		dateFormat:'yy-mm-dd',
		changeMonth:true,
		changeYear:true,
		showOtherMonths:true,
		onSelect	:function() { 
			$("#ob_date_to").datepicker('option',{minDate:$(this).datepicker('getDate')});
			//load_show_specific_schedule2();
		}
	});	
	
	$("#ob_date_to").datepicker({
		minDate: date_from_str,
    	maxDate: date_to_str,
		dateFormat:'yy-mm-dd',
		changeMonth:true,
		changeYear:true,
		showOtherMonths:true,
		onSelect	:function() { 
		   //load_show_specific_schedule2();
		}
	});	
	
	var t = new $.TextboxList('#employee_id', {
		unique: true,
		max:1,
		plugins: {
			autocomplete: {
				minLength: 2,				
				onlyFromValues: true,
				queryRemote: true,
				remote: {url: base_url + 'ob/ajax_get_employees_autocomplete'}			
			}
	}});

	t.addEvent('blur',function(o) {		
		load_show_employee_request_approvers();
	});


	  $('#ob_time_start').timepicker({
        'minTime': '8:00 am',
        'maxTime': '7:30 am',
        'timeFormat': 'g:i a'
    });


	  $('#ob_time_end').timepicker({
        'minTime': '8:00 am',
        'maxTime': '7:30 am',
        'timeFormat': 'g:i a'
    });

	
});

//for oblogs
$(".ob_logs_wrapper").hide();

function showLogsInput(){
	if($('#has_time_logs').is(':checked')){
		$(".ob_logs_wrapper").show();
		$('#ob_time_start').addClass("validate[required]");
		$('#ob_time_end').addClass("validate[required]");
	}
	else{
		$(".ob_logs_wrapper").hide();
		$('#ob_time_start').removeClass("validate[required]");
		$('#ob_time_end').removeClass("validate[required]");
	}
}



</script>
<div id="formcontainer">
<form id="add_request_form" name="add_request_form" action="<?php echo url('ob/_save_ob_request'); ?>" method="post"> 
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<input type="hidden" id="date_from" name="date_from" value="<?php echo $from; ?>" />
<input type="hidden" id="date_to" name="date_to" value="<?php echo $to; ?>" />
<div id="formwrap">	
	<h3 class="form_sectiontitle">Add New Request</h3>
<div id="form_main">     
  
    <div id="form_default">      
        <table>        	 
             <tr>
               <td class="field_label">Type Employee Name:</td>
               <td>
               		<input class="validate[required] input-large" type="text" name="employee_id" id="employee_id" value="" />
               </td>
             </tr>
        </table>
        <div id="show_request_approvers_wrapper"></div>
        <h3 class="leave-header">Official Business Details</h3> 
        <table>
             <tr>
               <td class="field_label">From:</td>
               <td>
               		<input class="validate[required] input-small" type="text" name="ob_date_from" id="ob_date_from" value="" />
               </td>
             </tr>  
             <tr>
               <td class="field_label">To:</td>
               <td>
               		<input class="validate[required] input-small" type="text" name="ob_date_to" id="ob_date_to" value="" />
               </td>
             </tr>                                                                   
             <!-- <tr>
               <td class="field_label">Is Approved:</td>
               <td>
               		<select class="validate[required] select_option" name="is_approved" id="is_approved">        
               		<option value="<?php //echo Employee_Official_Business_Request::YES; ?>" selected="selected"><?php //echo Employee_Official_Business_Request::YES; ?></option>  
                    <option value="<?php //echo Employee_Official_Business_Request::NO; ?>"><?php //echo Employee_Official_Business_Request::NO; ?></option>                                  
                    </select>
               </td>
             </tr> -->

             <tr>
                <td class="field_label"></td>
               <td>
               		<label class="checkbox">
	                <input value="1" type="checkbox" name="has_time_logs" id="has_time_logs"  onchange="showLogsInput()" />Insert Time Range 
	                </label>
               </td>
             </tr>

              
               <tr class="ob_logs_wrapper">
               <td class="field_label">Time Start:</td>
               <td>
               		<input class="input-small" type="text" name="ob_time_start" id="ob_time_start" value="" />
               </td>
               	 </tr>
               	 <tr class="ob_logs_wrapper">
	               <td class="field_label">Time End:</td>
	               <td>
	               		<input class="input-small" type="text" name="ob_time_end" id="ob_time_end" value="" />
	               </td>
	             </tr>     
	      	    
            	<!--
            	 <tr>
		              <td class="field_label"></td>
		              <td>
		              	<div id="show_specific_schedule_wrapper2"></div>
		              </td>
		            </tr> -->


             <tr>
               <td class="field_label">Comments:</td>
               <td>
               		<textarea class="input-large" rows="3" id="comments" name="comments"></textarea>               		
               </td>
             </tr>                                  
         </table>
    </div>
    <div id="form_default" class="form_action_section">
    	<table width="100%" border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td>
                <input type="submit" value="Save" class="curve blue_button" />
                <a href="javascript:void(0)" onclick="javascript:hide_add_ob_form();">Cancel</a>
                </td>
            </tr>
        </table>
    </div>
</div><!-- #form_main -->
</div>
</form>
</div>

