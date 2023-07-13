         3<style>
	h3.cinfo-header-form{width:99%; background-color:#666; color:#FFF; padding:10px 0 8px 5px; margin:0;}
	.text{width:250px;}
</style>
<script>
function callSuccessFunction(){
	load_branch_list();
};
function callFailFunction(){alert("Error on SQL")}
	$(document).ready(function() {		
		 $("#addBranch").validationEngine({						
			ajaxSubmit: true,
			scroll: false,
			ajaxSubmitFile: base_url + 'settings/add_company_branch',	
			ajaxSubmitMessage: "",		
			success : function() {load_branch_list_dt();disablePopUp();var $dialog = $('#action_form');$dialog.dialog("destroy");},
			unbindEngine:true,
			failure : function() {disablePopUp();var $dialog = $('#action_form');$dialog.dialog("destroy");}

		});
	});
</script>
<div class="formWrapper">
	<form name="addBranch" id="addBranch" method="post" action="">
    <input type="hidden" value="<?php echo $p_id; ?>" name="parent_id" id="parent_id" />    
    <table width="100%" border="0" cellpadding="3" cellspacing="0"> 
        <tr>
            <td width="30%" valign="top" class="formControl">Name</td>
            <td width="70%" valign="top" class="formLabel">
                <input type="text" value="" name="name" class="validate[required] text-input text" id="name" />    
            </td>
        </tr>    
        <tr>
            <td width="30%" valign="top" class="formControl">Location</td>
            <td width="70%" valign="top" class="formLabel">
                <select name="location_id" id="location_id">
                	<?php foreach($locations as $l){ ?>
                    	<option value="<?php echo $l->getId(); ?>"><?php echo $l->getLocation(); ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>   
        <tr>
            <td width="30%" valign="top" class="formControl">Province</td>
            <td width="70%" valign="top" class="formLabel">
                <input type="text" value="" name="province" class="validate[required] text-input text" id="province" />    
            </td>
        </tr>
         <tr>
            <td width="30%" valign="top" class="formControl">City</td>
            <td width="70%" valign="top" class="formLabel">
                <input type="text" value="" name="city" class="validate[required] text-input text" id="city" />    
            </td>
        </tr>    
         <tr>
            <td width="30%" valign="top" class="formControl">Address</td>
            <td width="70%" valign="top" class="formLabel">
                <input type="text" value="" name="address" class="validate[required] text-input text" id="address" />    
            </td>
        </tr>
         <tr>
            <td width="30%" valign="top" class="formControl">Zip Code</td>
            <td width="70%" valign="top" class="formLabel">
                <input type="text" value="" name="zip_code" class="validate[required] text-input text" id="zip_code" />    
            </td>
        </tr>
        <tr>
            <td width="30%" valign="top" class="formControl">Phone</td>
            <td width="70%" valign="top" class="formLabel">
                <input type="text" value="" name="phone" class="validate[required] text-input text" id="phone" />    
            </td>
        </tr>
        <tr>
            <td width="30%" valign="top" class="formControl">Fax</td>
            <td width="70%" valign="top" class="formLabel">
                <input type="text" value="" name="fax" class="validate[required] text-input text" id="fax" />    
            </td>
        </tr>     
    </table>
    <br />
    <div align="right">
    <input type="submit" value="Save" />
    </div>
    </form>
</div>