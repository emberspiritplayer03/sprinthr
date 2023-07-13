<script>
$("#contact_details_form").validationEngine({scroll:false});
$('#contact_details_form').ajaxForm({
	success:function(o) {
		if(o==1) {
			dialogOkBox('Successfully Updated',{});
			$("#contact_details_wrapper").html('');
			loadPage("#contact_details");
			
		}else {
			dialogOkBox(o,{});	
		}		
	},
	beforeSubmit:function() {
		showLoadingDialog('Saving...');	
	}
});
</script>
<form id="contact_details_form" name="form1" method="post" action="<?php echo url('employee/_update_contact_details'); ?>" style="display:none;">
<div id="form_main" class="employee_form">
<input type="hidden" name="employee_id" value="<?php echo Utilities::encrypt($employee_id); ?>" />
	<h3 class="section_title">Contact Details <span class="section_note"><strong>(*)</strong> Required fields</span></h3>
	<div class="col_1_2">
    <div id="form_default" class="form_col_1_2">
      <table>
        <tr>
          <td class="field_label"><span class="required bold light-blue">*</span>Address:</td>
          <td><textarea class="validate[required]" name="address" id="address"><?php echo $details->address; ?></textarea></td>
        </tr>
        <tr>
          <td class="field_label"><span class="required bold light-blue">*</span>City:</td>
          <td>
          <input class="validate[required] text-input" type="text" name="city" id="city" value="<?php echo  ucfirst($details->city); ?>" /></td>
        </tr>
        <tr>
          <td class="field_label"><span class="required bold light-blue">*</span>Province/State:</td>
          <td>
          <input class="validate[required] text-input" type="text" name="province" id="province" value="<?php echo ucfirst($details->province); ?>" /></td>
        </tr>
        <tr>
          <td class="field_label">Zip Code:</td>
          <td>
          <input type="text" class="text-input" name="zip_code" id="zip_code" value="<?php echo  ucfirst($details->zip_code); ?>" /></td>
        </tr>
        <tr>
          <td class="field_label">Country:</td>
          <td>
          <input type="text" class="text-input" name="country" id="country" value="<?php echo  ucfirst($details->country); ?>" /></td>
        </tr>
        <tr>
          <td class="field_label">Home Telephone:</td>
          <td> <input type="text" class="text-input" name="home_telephone" id="home_telephone" value="<?php echo  ucfirst($details->home_telephone); ?>" /></td>
        </tr>
       </table>
    </div><!-- #form_default -->
    </div><!-- .col_1_2 -->
    <div class="col_1_2">
    <div id="form_default" class="form_col_1_2">
    	<table>
        <tr>
          <td class="field_label">Mobile:</td>
          <td><input type="text" class="text-input" name="mobile" id="mobile" value="<?php echo $details->mobile; ?>"></td>
        </tr>
        <tr>
          <td class="field_label">Work Telephone:</td>
          <td>
          <input type="text" class="text-input" name="work_telephone" id="work_telephone" value="<?php echo $details->work_telephone; ?>" /></td>
        </tr>
        <tr>
          <td class="field_label"><span class="required bold light-blue">*</span>Work Email:</td>
          <td>
          <input type="text" class="validate[required,custom[email]] text-input" name="work_email" id="work_email" value="<?php echo $details->work_email; ?>" /></td>
        </tr>
        <tr>
          <td class="field_label">Other Email:</td>
          <td>
          <input type="text" class="text-input" name="other_email" id="other_email" value="<?php echo $details->other_email; ?>" /></td>
        </tr>
            <?php foreach($field as $key=>$value) { 
        $employee_id = $details->employee_id;
        $e = G_Employee_Finder::findById($employee_id);
        $f =  G_Employee_Dynamic_Field_Finder::findBySettingsEmployeeFieldId($value->getId(),$e);
        if($f) {
            $title = $f->title;		 
            $val = $f->value;
            $name = 'e_'.$f->id;
        }else {
            $title = $value->title;		 
            $val = '';
            $name = 's_'.$value->id;
        }
          ?>
          
      <tr>
        <td class="field_label"><?php echo ucfirst($title); ?>:</td>
        <td><input type="text" class="text-input" name="<?php echo $name; ?>" value="<?php echo $val; ?>" /></td>
      </tr>
      <?php } ?>
        
      </table>
    </div><!-- #form_default -->
    </div><!-- .col_1_2 -->
    <div class="clearleft"></div>
    <div id="form_default" class="form_action_section form_col_1_2">    	
      <table>
      	<tr>
          <td class="field_label">&nbsp;</td>
          <td>
          	<button class="blue_button" type="submit" name="button" id="button">Update</button>
            <a href="javascript:void(0);" onclick="javascript:loadContactDetailsTable();">Cancel</a></td>
        </tr>
      </table>
	</div><!-- #form_default -->
</div><!-- #form_main.employee_form -->
</form>
