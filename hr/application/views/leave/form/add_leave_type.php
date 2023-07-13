<script>
$(document).ready(function() {	
	$('#add_leave_type_form').validationEngine({scroll:false});	
		
	$('#add_leave_type_form').ajaxForm({
		success:function(o) {
			if (o.is_success == 1) {
				//load_leave_type_list_dt();
				$('#request_leave_button').show();
				$('#request_leave_form_wrapper').hide();
				closeDialog('#' + DIALOG_CONTENT_HANDLER);	
				dialogOkBox(o.message,{});

                var query = window.location.search;
                $.get(base_url + 'leave/type'+ query, {ajax:1}, function(html_data){
                    $('#main').html(html_data)
                });

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
});
</script>
<div id="formcontainer">
<form id="add_leave_type_form" name="add_leave_type_form"  action="<?php echo url('leave/_insert_leave_type'); ?>" method="post"  name="employee_form" > 
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<div id="formwrap">	
	<h3 class="form_sectiontitle">Add Leave Type</h3>
<div id="form_main">     
  
    <div id="form_default">      
        <table>
             <tr>
               <td class="field_label">Title:</td>
               <td><input class="validate[required] text-input" type="text" name="leave_title" id="leave_title" value="" /></td>
             </tr>
             <tr>
               <td class="field_label">Default Credit:</td>
               <td><input class="validate[required] text-input" type="text" name="default_credit" id="default_credit" value="0" /></td>
             </tr>
             <tr>
              <td class="field_label">Is paid:</td>
              <td>
              <select class="validate[required] select_option" name="is_paid" id="is_paid">              
              	<option selected="selected" value="<?php echo G_Leave::YES; ?>"><?php echo G_Leave::YES; ?></option> 
                <option value="<?php echo G_Leave::NO; ?>"><?php echo G_Leave::NO; ?></option>
              </select>
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
                <a href="javascript:void(0)" onclick="javascript:hide_request_leave_form();">Cancel</a>
                </td>
            </tr>
        </table>
    </div>
</div><!-- #form_main -->
</div>
</form>
</div>

