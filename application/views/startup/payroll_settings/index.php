<?php if($check_structure){ ?> 
<div id="form_main" class="inner_form">
<div id="form_default">
<h3 class="section_title">Deduction</h3>
<div id="c-deduction" ></div>
<!--<div class="form_separator"></div>-->
<h3 class="section_title">Pay Period &nbsp;<a class="add_button" href="javascript:void(0);" onclick="javascript:load_add_new_pay_period();"><strong>+</strong><b>Add New</b></a></h3>
<div id="c-pay-period" class="table_compact"></div>	   
   
</div>
</div>
<script>
 $(document).ready(function() {
   load_deduction_breakdown();
   load_pay_period();
 });
</script>


<?php }else{ // if there is no company this will show?>

<style>
	h3.cinfo-header-form{width:99%; background-color:#666; color:#FFF; padding:10px 0 8px 5px; margin:0;}
	.text{width:250px;}
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
<div class="formWrapper">
	<form name="taskForm" id="taskForm" method="post" action="<?php echo url('startup/add_company_structure'); ?>">
    <h3 class="cinfo-header-form">Company Information</h3>
    <table width="100%" border="0" cellpadding="3" cellspacing="0">
    	<tr>
            <td width="30%" valign="top" class="formControl">Company Name</td>
            <td width="70%" valign="top" class="formLabel">
                <input type="text" value="<?php echo($c ? $c->getTitle() : 'Undefined' ); ?>" name="title" class="validate[required] text-input text" id="title" />    
            </td>
        </tr> 
        <tr>
            <td width="30%" valign="top" class="formControl">Address</td>
            <td width="70%" valign="top" class="formLabel">
                <input type="text" value="<?php echo($cinfo ? $cinfo->getAddress() : 'Undefined' ); ?>" name="address" class="validate[required] text-input text" id="address" />    
            </td>
        </tr>
        <tr>
            <td width="30%" valign="top" class="formControl">Other Address</td>
            <td width="70%" valign="top" class="formLabel">
                <input type="text" value="<?php echo($cinfo ? $cinfo->getAddress1() : 'Undefined' ); ?>" name="address1" id="address1" class="text" />    
            </td>
        </tr>
        <tr>
            <td width="30%" valign="top" class="formControl">City</td>
            <td width="70%" valign="top" class="formLabel">
                <input type="text" value="<?php echo($cinfo ? $cinfo->getCity() : 'Undefined' ); ?>" name="city" class="validate[required] text-input text" id="city" />
            </td>
        </tr>
        <tr>
            <td width="30%" valign="top" class="formControl">State</td>
            <td width="70%" valign="top" class="formLabel">
                <input type="text" value="<?php echo($cinfo ? $cinfo->getState() : 'Undefined' ); ?>" name="state" class="validate[required] text-input text" id="state" />
            </td>
        </tr>
        <tr>
            <td width="30%" valign="top" class="formControl">Zip Code</td>
            <td width="70%" valign="top" class="formLabel">
               <input type="text" value="<?php echo($cinfo ? $cinfo->getZipCode() : 'Undefined' ); ?>" name="zip_code" class="validate[required] text-input text" id="zip_code" />
            </td>
        </tr>
        <tr>
            <td width="30%" valign="top" class="formControl">Remarks</td>
            <td width="70%" valign="top" class="formLabel">
                <input value="<?php echo($cinfo ? $cinfo->getRemarks() : 'Undefined' ); ?>" type="text" name="remarks" id="remarks" class="text" />
            </td>
        </tr>    
    </table>
    <h3 class="cinfo-header-form">Contact Information</h3>
    <table width="100%" border="0" cellpadding="3" cellspacing="0"> 
        <tr>
            <td width="30%" valign="top" class="formControl">Phone Number</td>
            <td width="70%" valign="top" class="formLabel">
			<input type="text" value="<?php echo($cinfo ? $cinfo->getPhone() : 'Undefined' ); ?>" name="phone" class="validate[required] text-input text" id="phone" />
            </td>
        </tr>
        <tr>
            <td width="30%" valign="top" class="formControl">Fax Number</td>
            <td width="70%" valign="top" class="formLabel">
            <input type="text" value="<?php echo($cinfo ? $cinfo->getFax() : 'Undefined' ); ?>" name="fax" id="fax" class="text" />     
            </td>
        </tr>    
    </table>
    <br />
    <div align="right">
    <input type="submit" value="Save" />
    </div>
    </form>
</div>
<?php }?>