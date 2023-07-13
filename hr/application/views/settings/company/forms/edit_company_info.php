<style>
    #form_main.popup_form div#form_default.form_action_section {margin:0;}
</style>

<script>
$(document).ready(function() {	
	$('#taskForm').validationEngine({scroll:false});	
		
	$('#taskForm').ajaxForm({
		success:function(o) {
			if (o.is_succes == 1) {								
				load_company_info();
				closeDialog('#' + DIALOG_CONTENT_HANDLER);	
				dialogOkBox(o.message,{});	
				var $dialog = $('#action_form');
			    $dialog.dialog("destroy");		
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
<div id="form_main" class="inner_form popup_form wider" style="overflow:auto;">
	<form name="taskForm" id="taskForm" method="post" action="<?php echo url('settings/update_company_info'); ?>">
    <div id="form_default">
    <h3 class="section_title">Company Information</h3>
    <table width="100%">
    	<tr>
            <td class="field_label">Company Name:</td>
            <td>
                <input type="text" value="<?php echo($c ? $c->getTitle() : 'Undefined' ); ?>" name="title" class="validate[required]  text" id="title" />    
            </td>
        </tr> 
        <tr>
            <td class="field_label">Address:</td>
            <td>
                <input type="text" value="<?php echo($cinfo ? $cinfo->getAddress() : 'Undefined' ); ?>" name="address" class="validate[required]  text" id="address" />    
            </td>
        </tr>
        <tr>
            <td class="field_label">Other Address:</td>
            <td>
                <input type="text" value="<?php echo($cinfo ? $cinfo->getAddress1() : 'Undefined' ); ?>" name="address1" id="address1" class="text" />    
            </td>
        </tr>
        <tr>
            <td class="field_label">City:</td>
            <td>
                <input type="text" value="<?php echo($cinfo ? $cinfo->getCity() : 'Undefined' ); ?>" name="city" class="validate[required]  text" id="city" />
            </td>
        </tr>
        <tr>
            <td class="field_label">State:</td>
            <td>
                <input type="text" value="<?php echo($cinfo ? $cinfo->getState() : 'Undefined' ); ?>" name="state" class="validate[required]  text" id="state" />
            </td>
        </tr>
        <tr>
            <td class="field_label">Zip Code:</td>
            <td>
               <input type="text" value="<?php echo($cinfo ? $cinfo->getZipCode() : 'Undefined' ); ?>" name="zip_code" class="validate[required]  text" id="zip_code" />
            </td>
        </tr>
        <tr>
            <td class="field_label">Remarks:</td>
            <td>
                <input value="<?php echo($cinfo ? $cinfo->getRemarks() : 'Undefined' ); ?>" type="text" name="remarks" id="remarks" class="text" />
            </td>
        </tr>    
    </table>
    </div>
    <div class="form_separator"></div>

    <div id="form_default">
        <h3 class="section_title">Government Contributions</h3>
        <table width="100%" border="0" cellpadding="3" cellspacing="0"> 
            <tr>
                <td class="field_label">SSS Number:</td>
                <td>
                <input type="text" value="<?php echo($cinfo ? $cinfo->getSssNumber() : '' ); ?>" name="sss_number" id="sss_number" />
                </td>
            </tr>
            <tr>
                <td class="field_label">Pagibig Number:</td>
                <td>
                    <input type="text" value="<?php echo($cinfo ? $cinfo->getPagibigNumber() : '' ); ?>" name="pagibig_number" id="pagibig_number" class="text" />     
                </td>
            </tr>  
            <tr>
                <td class="field_label">Philhealth Number:</td>
                <td>
                <input type="text" value="<?php echo($cinfo ? $cinfo->getPhilhealthNumber() : '' ); ?>" name="philhealth_number" id="philhealth_number" class="text" />     
                </td>
            </tr>   
            <tr>
                <td class="field_label">TIN:</td>
                <td>
                <input type="text" value="<?php echo($cinfo ? $cinfo->getTinNumber() : '' ); ?>" name="tin_number" id="tin_number" class="text" />     
                </td>
            </tr>    
        </table>
    </div>        
    <div class="form_separator"></div>

    <div id="form_default">
    <h3 class="section_title">Contact Information</h3>
    <table width="100%" border="0" cellpadding="3" cellspacing="0"> 
        <tr>
            <td class="field_label">Phone Number:</td>
            <td>
			<input type="text" value="<?php echo($cinfo ? $cinfo->getPhone() : 'Undefined' ); ?>" name="phone" class="validate[required]  text" id="phone" />
            </td>
        </tr>
        <tr>
            <td class="field_label">Fax Number:</td>
            <td>
            <input type="text" value="<?php echo($cinfo ? $cinfo->getFax() : 'Undefined' ); ?>" name="fax" id="fax" class="text" />     
            </td>
        </tr>    
    </table>
    </div>
    <div id="form_default" class="form_action_section">
    <table>
    	<tr>
            <td class="field_label">&nbsp;</td>
            <td><input class="blue_button" type="submit" value="Update" /></td>
        </tr> 
    </table>
    </div>
    </form>
</div>