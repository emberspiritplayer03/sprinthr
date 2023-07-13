  <?php if($check_structure){ ?> 
 
<div id="branch_wrapper_form_department_startup" style="display:none" >
<?php include 'forms/add_new_branch.php'; ?>
</div>
<div id="department_wrapper_form_startup" style="display:none" >
<?php // include 'forms/add_department.php'; ?>
</div>
<div id="form_main" class="inner_form">
<div id="form_default">
<h3 class="section_title">Branch / Department</h3>
	<div class="alert alert-info"> Note: if there is no branch in your company add <strong>"Main"</strong> in branch to continue adding department.</div>
    <table width="50%" border="0" cellpadding="3" cellspacing="0"> 
        <tr>
            <td class="field_label">Branch:</td>
            <td valign="top">  
            <div id="branch_dropdown_wrapper_startup">
              <select class="validate[required] select_option" name="branch_id_startup" id="branch_id_startup" onchange="javascript:load_department_startup_dt();">
                <option value="" selected="selected">-- Select Branch --</option>
                    <?php foreach($branches as $key=>$value) { ?>
                        <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                    <?php } ?>
                <!--<option value="add">Add Branch...</option>-->
              </select><br />
              <small>Select a branch to manage department.</small>             
         	</div> 
            <div class="inline" style="padding-top:5px;"><a href="javascript:void(0);" class="btn btn-mini" onclick="javascript:checkForAddBranchDepartmentStartup('add');"><i class="icon-plus"></i> Add Branch</a></div>
         </td>
        </tr>       
    </table>
    <br />
    <div id="c-depatment_dt"></div>
    
</div>
<!--<div class="form_separator"></div>
<a class="btn" href="javascript:void(0);"><strong>Save</strong></a>-->
</div>
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
            <td class="field_label">Company Name</td>
            <td>
                <input type="text" value="<?php echo($c ? $c->getTitle() : 'Undefined' ); ?>" name="title" class="validate[required] text-input text" id="title" />    
            </td>
        </tr> 
        <tr>
            <td class="field_label">Address</td>
            <td>
                <input type="text" value="<?php echo($cinfo ? $cinfo->getAddress() : 'Undefined' ); ?>" name="address" class="validate[required] text-input text" id="address" />    
            </td>
        </tr>
        <tr>
            <td class="field_label">Other Address</td>
            <td>
                <input type="text" value="<?php echo($cinfo ? $cinfo->getAddress1() : 'Undefined' ); ?>" name="address1" id="address1" class="text" />    
            </td>
        </tr>
        <tr>
            <td class="field_label">City</td>
            <td>
                <input type="text" value="<?php echo($cinfo ? $cinfo->getCity() : 'Undefined' ); ?>" name="city" class="validate[required] text-input text" id="city" />
            </td>
        </tr>
        <tr>
            <td class="field_label">State</td>
            <td>
                <input type="text" value="<?php echo($cinfo ? $cinfo->getState() : 'Undefined' ); ?>" name="state" class="validate[required] text-input text" id="state" />
            </td>
        </tr>
        <tr>
            <td class="field_label">Zip Code</td>
            <td>
               <input type="text" value="<?php echo($cinfo ? $cinfo->getZipCode() : 'Undefined' ); ?>" name="zip_code" class="validate[required] text-input text" id="zip_code" />
            </td>
        </tr>
        <tr>
            <td class="field_label">Remarks</td>
            <td>
                <input value="<?php echo($cinfo ? $cinfo->getRemarks() : 'Undefined' ); ?>" type="text" name="remarks" id="remarks" class="text" />
            </td>
        </tr>    
    </table>
    <h3 class="cinfo-header-form">Contact Information</h3>
    <table width="100%" border="0" cellpadding="3" cellspacing="0"> 
        <tr>
            <td class="field_label">Phone Number</td>
            <td>
			<input type="text" value="<?php echo($cinfo ? $cinfo->getPhone() : 'Undefined' ); ?>" name="phone" class="validate[required] text-input text" id="phone" />
            </td>
        </tr>
        <tr>
            <td class="field_label">Fax Number</td>
            <td>
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


