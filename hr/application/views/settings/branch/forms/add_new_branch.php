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
<div id="form_main" class="inner_form popup_form">
	<form name="addBranch" id="addBranch" method="post" action="">
    <input type="hidden" value="<?php echo $p_id; ?>" name="parent_id" id="parent_id" /> 
    <div id="form_default">   
    <table width="100%" border="0" cellpadding="3" cellspacing="0"> 
        <tr>
            <td valign="top" class="field_label">Name:</td>
            <td valign="top">
                <input type="text" value="" name="name" class="validate[required] text" id="name" />    
            </td>
        </tr>    
        <tr>
            <td valign="top" class="field_label">Location:</td>
            <td valign="top">
                <select name="location_id" id="location_id" class="select_option_sched">
                	<?php foreach($locations as $l){ ?>
                    	<option value="<?php echo $l->getId(); ?>"><?php echo $l->getLocation(); ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>   
        <tr>
            <td valign="top" class="field_label">Province:</td>
            <td valign="top">
                <input type="text" value="" name="province" class="validate[required] text" id="province" />    
            </td>
        </tr>
         <tr>
            <td valign="top" class="field_label">City:</td>
            <td valign="top">
                <input type="text" value="" name="city" class="validate[required] text" id="city" />    
            </td>
        </tr>    
         <tr>
            <td valign="top" class="field_label">Address:</td>
            <td valign="top">
                <input type="text" value="" name="address" class="validate[required] text" id="address" />    
            </td>
        </tr>
         <tr>
            <td valign="top" class="field_label">Zip Code:</td>
            <td valign="top">
                <input type="text" value="" name="zip_code" class="validate[required] text" id="zip_code" />    
            </td>
        </tr>
        <tr>
            <td valign="top" class="field_label">Phone:</td>
            <td valign="top">
                <input type="text" value="" name="phone" class="validate[required] text" id="phone" />    
            </td>
        </tr>
        <tr>
            <td valign="top" class="field_label">Fax:</td>
            <td valign="top">
                <input type="text" value="" name="fax" class="validate[required] text" id="fax" />    
            </td>
        </tr>     
      </table>
    </div>
    <div id="form_default" class="form_action_section" align="center">
    <table width="100%" border="0" cellpadding="3" cellspacing="0">
    	<tr>
            <td valign="top" class="field_label">&nbsp;</td>
            <td valign="top"><input class="blue_button" type="submit" value="Save" /></td>
        </tr>
    </table>    
    </div>
    </form>
</div>