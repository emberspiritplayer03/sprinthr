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
<div id="form_main" class="inner_form popup_form wider">
	<form name="addBranch" id="addBranch" method="post" action="<?php echo $add_new_branch_action; ?>">
    <div id="form_default">
    <table width="100%"> 
        <tr>
            <td class="field_label">Name</td>
            <td><input type="text" value="" name="branch_name" class="validate[required]  text" id="branch_name" /></td>
        </tr>    
        <tr>
            <td class="field_label">Location</td>
            <td><select class="validate[required] select_option" name="location_id" id="location_id">
                	<option value="">-- Select Location --</option>
                	<?php foreach($locations as $l){ ?>
                    	<option value="<?php echo $l->getId(); ?>"><?php echo $l->getLocation(); ?></option>
                    <?php } ?>
                </select></td>
        </tr>   
        <tr>
            <td class="field_label">Province</td>
            <td><input type="text" value="" name="province" class="validate[required]  text" id="province" /></td>
        </tr>
         <tr>
            <td class="field_label">City</td>
            <td><input type="text" value="" name="city" class="validate[required]  text" id="city" /></td>
        </tr>    
         <tr>
            <td class="field_label">Address</td>
            <td><input type="text" value="" name="address" class="validate[required]  text" id="address" /></td>
        </tr>
         <tr>
            <td class="field_label">Zip Code</td>
            <td><input type="text" value="" name="zip_code" class="validate[required]  text" id="zip_code" /></td>
        </tr>
        <tr>
            <td class="field_label">Phone</td>
            <td><input type="text" value="" name="phone" class="validate[required]  text" id="phone" /></td>
        </tr>
        <tr>
            <td class="field_label">Fax</td>
            <td><input type="text" value="" name="fax" class="validate[required]  text" id="fax" /></td>
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