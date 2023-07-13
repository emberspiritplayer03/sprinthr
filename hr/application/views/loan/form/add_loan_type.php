<script>
$(document).ready(function() {	
	$('#add_loan_type_form').validationEngine({scroll:false});	
		
	$('#add_loan_type_form').ajaxForm({
		success:function(o) {
			if (o.is_success == 1) {								
				load_loan_type_list_dt();				
				$('#request_loan_button').show();
				$('#request_loan_form_wrapper').hide();
				closeDialog('#' + DIALOG_CONTENT_HANDLER);	
				dialogOkBox(o.message,{});						
			} else {
				
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
<form id="add_loan_type_form" name="add_loan_type_form"  action="<?php echo url('loan/_insert_new_loan_type'); ?>" method="post"  name="employee_form" > 
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<div id="formwrap">	
	<h3 class="form_sectiontitle">Add Loan Type</h3>
<div id="form_main">     
  
    <div id="form_default">      
        <table>
             <tr>
               <td class="field_label">Name:</td>
               <td><input class="validate[required] text-input" type="text" name="loan_type" id="loan_type" value="" /></td>
             </tr>                              
         </table>
    </div>
    <div id="form_default" class="form_action_section">
    	<table width="100%" border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td>
                <input type="submit" value="Save" class="curve blue_button" />
                <a href="javascript:void(0)" onclick="javascript:hide_show_loan_form();">Cancel</a>
                </td>
            </tr>
        </table>
    </div>
</div><!-- #form_main -->
</div>
</form>
</div>

