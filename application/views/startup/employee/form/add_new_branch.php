 <script>
$("#addBranch").validationEngine({scroll:false});

$('#addBranch').ajaxForm({
			success:function(o) {				
				$("#branch_wrapper_form").dialog("destroy");
				disablePopUp();
				$dialog.dialog('destroy');
				load_branch_dropdown();				
				$("#branch_wrapper_form").html('');				
			}, 
			beforeSubmit:function() {
				showLoadingDialog('Saving...');	
			}
		});

</script>
<div id="form_main" class="inner_form popup_form">
	<form name="addBranch" id="addBranch" method="post" action="<?php echo $add_new_branch_action; ?>">
    <div id="form_default">
    <table width="100%"> 
        <tr>
            <td class="field_label">Name</td>
            <td><input type="text" value="" name="q_branch_name" class="validate[required] text-input text" id="q_branch_name" /></td>
        </tr>    
        <tr>
            <td class="field_label">Location</td>
            <td><select class="validate[required] select_option" name="q_location_id" id="q_location_id">
                	<option value="">-- Select Location --</option>
                	<?php foreach($locations as $l){ ?>
                    	<option value="<?php echo $l->getId(); ?>"><?php echo $l->getLocation(); ?></option>
                    <?php } ?>
                </select></td>
        </tr>   
        <tr>
            <td class="field_label">Province</td>
            <td><input type="text" value="" name="q_province" class="validate[optional] text-input text" id="q_province" /></td>
        </tr>
         <tr>
            <td class="field_label">City</td>
            <td><input type="text" value="" name="q_city" class="validate[optional] text-input text" id="q_city" /></td>
        </tr>    
         <tr>
            <td class="field_label">Address</td>
            <td><input type="text" value="" name="q_address" class="validate[optional] text-input text" id="q_address" /></td>
        </tr>
         <tr>
            <td class="field_label">Zip Code</td>
            <td><input type="text" value="" name="q_zip_code" class="validate[optional] text-input text" id="q_zip_code" /></td>
        </tr>
        <tr>
            <td class="field_label">Phone</td>
            <td><input type="text" value="" name="q_phone" class="validate[optional] text-input text" id="q_phone" /></td>
        </tr>
        <tr>
            <td class="field_label">Fax</td>
            <td><input type="text" value="" name="q_fax" class="validate[optional] text-input text" id="q_fax" /></td>
        </tr>     
    </table>
    </div><!-- #form_default -->
    <div id="form_default" class="form_action_section">
   	  <table width="100%">
          <tr>
            <td class="field_label">&nbsp;</td>
            <td><input class="blue_button" type="submit" value="Save" />&nbsp;<a href="#" onclick="javascript:closeBranchPopUp('#branch_wrapper_form');">Cancel</a></td>
          </tr>
        </table>    
    </div><!-- #form_default.form_action_section -->
    </form>
</div>