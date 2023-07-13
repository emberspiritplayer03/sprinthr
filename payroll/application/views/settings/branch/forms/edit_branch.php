<style>
	h3.cinfo-header-form{width:99%; background-color:#666; color:#FFF; padding:10px 0 8px 5px; margin:0;}
	.text{width:250px;}
</style>
<script>
function callSuccessFunction(){
	load_branch_list();
};
function callFailFunction(){alert("Error on SQL")}
	$(document).ready(function() {		
		 $("#editBranch").validationEngine({						
			ajaxSubmit: true,
			scroll: false,
			ajaxSubmitFile: base_url + 'settings/update_company_branch',	
			ajaxSubmitMessage: "",		
			success : function() {load_branch_list_dt();disablePopUp();var $dialog = $('#action_form');$dialog.dialog("destroy");},
			unbindEngine:true,
			failure : function() {}

		});
	});
</script>
<div class="formWrapper">
	<form name="editBranch" id="editBranch" method="post" action="">
    <input type="hidden" value="<?php echo $b->getId(); ?>" name="branch_id" id="branch_id" />    
    <table width="100%" border="0" cellpadding="3" cellspacing="0"> 
        <tr>
            <td width="30%" valign="top" class="formControl">Name</td>
            <td width="70%" valign="top" class="formLabel">
                <input type="text" name="name" value="<?php echo $b->getName(); ?>" class="validate[required] text-input text" id="name" />    
            </td>
        </tr>    
        <tr>
            <td width="30%" valign="top" class="formControl">Location</td>
            <td width="70%" valign="top" class="formLabel">
                <select name="location_id" id="location_id">
                	<?php foreach($locations as $l){ ?>
                    	<option value="<?php echo $l->getId(); ?>" <?php echo($b->getId() == $l->getId() ? 'selected = "selected"' : ''); ?>><?php echo $l->getLocation(); ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>   
        <tr>
            <td width="30%" valign="top" class="formControl">Province</td>
            <td width="70%" valign="top" class="formLabel">
                <input type="text" name="province" value="<?php echo($b->getProvince() == "" ? 'Undefined' : $b->getProvince()); ?>" class="validate[required] text-input text" id="province" />    
            </td>
        </tr>
         <tr>
            <td width="30%" valign="top" class="formControl">City</td>
            <td width="70%" valign="top" class="formLabel">
                <input type="text" value="<?php echo($b->getCity() == "" ? 'Undefined' : $b->getCity()); ?>" name="city" class="validate[required] text-input text" id="city" />    
            </td>
        </tr>    
         <tr>
            <td width="30%" valign="top" class="formControl">Address</td>
            <td width="70%" valign="top" class="formLabel">
                <input type="text" value="<?php echo($b->getAddress() == "" ? 'Undefined' : $b->getAddress()); ?>" name="address" class="validate[required] text-input text" id="address" />    
            </td>
        </tr>
         <tr>
            <td width="30%" valign="top" class="formControl">Zip Code</td>
            <td width="70%" valign="top" class="formLabel">
                <input type="text" value="<?php echo($b->getZipCode() == "" ? 'Undefined' : $b->getZipCode()); ?>" name="zip_code" class="validate[required] text-input text" id="zip_code" />    
            </td>
        </tr>
        <tr>
            <td width="30%" valign="top" class="formControl">Phone</td>
            <td width="70%" valign="top" class="formLabel">
                <input type="text" value="<?php echo($b->getPhone() == "" ? 'Undefined' : $b->getPhone()); ?>" name="phone" class="validate[required] text-input text" id="phone" />    
            </td>
        </tr>
        <tr>
            <td width="30%" valign="top" class="formControl">Fax</td>
            <td width="70%" valign="top" class="formLabel">
                <input type="text" value="<?php echo($b->getFax() == "" ? 'Undefined' : $b->getFax()); ?>" name="fax" class="validate[required] text-input text" id="fax" />    
            </td>
        </tr>     
    </table>
    <br />
    <div align="right">
    <input type="submit" value="Update" />
    </div>
    </form>
</div>