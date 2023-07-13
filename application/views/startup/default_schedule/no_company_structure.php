<!--<style>
	h3.cinfo-header-form{width:99%; background-color:#666; color:#FFF; padding:10px 0 8px 5px; margin:0;}
	.text{width:250px;}
</style>-->
<script>
$(document).ready(function() {	
	$('#taskForm').validationEngine({scroll:false});	
		
	$('#taskForm').ajaxForm({
		success:function(o) {
			if (o.is_succes == 1) {								
				load_company_info();
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
});

</script>
<div id="form_main">
	<form name="taskForm" id="taskForm" method="post" action="<?php echo url('startup/add_company_structure'); ?>">
    <h3 class="section_title">Company Information</h3>
    <div id="form_default">
    <table width="100%">
    	<tr>
            <td valign="top" class="field_label">Company Name</td>
            <td>
                <input type="text" value="<?php echo($c ? $c->getTitle() : 'Undefined' ); ?>" name="title" class="validate[required] text-input text" id="title" />    
            </td>
        </tr> 
        <tr>
            <td valign="top" class="field_label">Address</td>
            <td>
                <input type="text" value="<?php echo($cinfo ? $cinfo->getAddress() : 'Undefined' ); ?>" name="address" class="validate[required] text-input text" id="address" />    
            </td>
        </tr>
        <tr>
            <td valign="top" class="field_label">Other Address</td>
            <td>
                <input type="text" value="<?php echo($cinfo ? $cinfo->getAddress1() : 'Undefined' ); ?>" name="address1" id="address1" class="text" />    
            </td>
        </tr>
        <tr>
            <td valign="top" class="field_label">City</td>
            <td>
                <input type="text" value="<?php echo($cinfo ? $cinfo->getCity() : 'Undefined' ); ?>" name="city" class="validate[required] text-input text" id="city" />
            </td>
        </tr>
        <tr>
            <td valign="top" class="field_label">State</td>
            <td>
                <input type="text" value="<?php echo($cinfo ? $cinfo->getState() : 'Undefined' ); ?>" name="state" class="validate[required] text-input text" id="state" />
            </td>
        </tr>
        <tr>
            <td valign="top" class="field_label">Zip Code</td>
            <td>
               <input type="text" value="<?php echo($cinfo ? $cinfo->getZipCode() : 'Undefined' ); ?>" name="zip_code" class="validate[required] text-input text" id="zip_code" />
            </td>
        </tr>
        <tr>
            <td valign="top" class="field_label">Remarks</td>
            <td>
                <input value="<?php echo($cinfo ? $cinfo->getRemarks() : 'Undefined' ); ?>" type="text" name="remarks" id="remarks" class="text" />
            </td>
        </tr>    
    </table>
    </div>
    <h3 class="section_title">Contact Information</h3>
    <div id="form_default">
    <table width="100%"> 
        <tr>
            <td valign="top" class="field_label">Phone Number</td>
            <td>
			<input type="text" value="<?php echo($cinfo ? $cinfo->getPhone() : 'Undefined' ); ?>" name="phone" class="validate[required] text-input text" id="phone" />
            </td>
        </tr>
        <tr>
            <td valign="top" class="field_label">Fax Number</td>
            <td>
            <input type="text" value="<?php echo($cinfo ? $cinfo->getFax() : 'Undefined' ); ?>" name="fax" id="fax" class="text" />     
            </td>
        </tr>    
    </table>
    </div>
    <div id="form_default" class="form_action_section">
    <table width="100%"> 
        <tr>
            <td valign="top" class="field_label">&nbsp;</td>
            <td><input class="blue_button" type="submit" value="Save" /></td>
        </tr>
    </table>
    </div>
    </form>
</div>