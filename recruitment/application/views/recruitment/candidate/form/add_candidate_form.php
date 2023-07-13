<script type="text/javascript">
$(function() {
$("#birthdate").datepicker({dateFormat: 'yy-mm-dd',changeMonth: true,
	changeYear: true,maxDate: '-16Y'});
	
$("#date_applied").datepicker({dateFormat: 'yy-mm-dd',changeMonth: true,
	changeYear: true});


$("#add_candidate_form").validationEngine({scroll:true});
$('#add_candidate_form').ajaxForm({
	success:function(o) {
		applicant_id = o;
			$.post(base_url+"recruitment/_load_applicant_hash",{applicant_id:applicant_id},
			function(o){
				$("#applicant_hash").val(o);
				load_add_candidate_confirmation(applicant_id);

			});
	},
	beforeSubmit:function() {
		showLoadingDialog('Saving...');	
	}
});

});
</script>
<div id="formcontainer">
<div class="mtshad"></div>
<form action="<?php echo url('recruitment/add_candidate'); ?>" method="post" enctype="multipart/form-data" name="add_candidate_form" id="add_candidate_form" >
<input type="hidden" id="company_structure_id" name="company_structure_id" value="<?php echo $company_structure_id; ?>"  />

<div id="formwrap">
	<h3 class="form_sectiontitle" id="candidate_form_wrapper">Add Candidate</h3>
    <div id="form_main">
    	<h3 class="section_title"><span>Application Information</span></h3>
        <div id="form_default">            
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="field_label">Desired Position: </td>
            <td><select class="validate[required]" name="job_id" id="job_id">
              <option value="">- Select Desired Position - </option>
             
              <?php foreach ($positions as $key=>$value) { ?>
               <option value="<?php echo $value->id; ?>"><?php echo $value->title; ?></option>
              <?php } ?>
            </select>              
            <em class="note label">(programmer, hr head)</em></td>
          </tr>
          <tr>
            <td class="field_label">Date Applied: </td>
            <td><input type="text" class="validate[required] text-input text" name="date_applied" id="date_applied" /></td>
          </tr>
          <tr>
            <td valign="top" class="field_label"><font color="#33CC00" size="2px">*</font>Lastname:</td>
            <td><input name="lastname" type="text" class="validate[required] text-input text" id="lastname" value="" /></td>
          </tr>
          <tr>
            <td valign="top" class="field_label"><font color="#33CC00" size="2px">*</font>Firstname:</td>
            <td><input type="text" value="" name="firstname" class="validate[required] text-input text" id="firstname" /></td>
          </tr>
          <tr>
            <td valign="top" class="field_label"><font color="#33CC00" size="2px">*</font>Middlename:</td>
            <td><input type="text" value="" name="middlename" class="validate[required] text-input text" id="middlename" /></td>
          </tr>
          <tr>
            <td valign="top" class="field_label"><font color="#33CC00" size="2px">*</font>Gender:</td>
            <td><select name="gender" id="gender" class="validate[required] select_option">
              <option value="">--select gender--</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
            </select></td>
          </tr>
          <tr>
            <td valign="top" class="field_label"><font color="#33CC00" size="2px">*</font>Marital Status:</td>
            <td><select name="marital_status" id="marital_status" class="validate[required] select_option" >
              <option value="">--select marital status--</option>
              <?php 
                foreach($GLOBALS['hr']['marital_status'] as $key=>$value){
                echo "<option value=".$value.">".$value."</option>";
                }
                ?>
            </select></td>
          </tr>
          <tr>
            <td valign="top" class="field_label">Email Address:</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td valign="top" class="field_label">Resume:</td>
            <td><input type="file" name="filename" id="filename" /></td>
          </tr>
          <tr>
            <td valign="top" class="field_label">Notes:</td>
            <td><textarea name="notes" id="notes" cols="45" rows="5"></textarea></td>
          </tr>
          <tr>
            <td colspan="2" valign="top" class="field_label">
            <a id="view_other_personal_information_button_wrapper" href="#other_personal_information_form_wrapper" onclick="javascript:view_other_personal_information();" >View Other Personal Information</a>
            <a style="display:none" id="hide_other_personal_information_button_wrapper" onclick="javascript:hide_other_personal_information();" href="javascript:void(0);">Hide Other Personal Information</a>
            
            <br />
              <a id="view_contact_details_button_wrapper" onclick="javascript:view_contact_details();" href="javascript:void(0);">View Contact Information </a>
              <a style="display:none" id="hide_contact_details_button_wrapper" onclick="javascript:hide_contact_details();" href="javascript:void(0);">Hide Contact Information </a>
              </td>
            </tr>
          </table>
        </div>
        <div class="form_separator"></div>
       
        <div id="other_personal_information_form_wrapper" style="display:none">     
         <h3 class="section_title"><span>Other Personal Information</span></h3>
         	 <div id="form_default">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="left" valign="top" class="field_label">Birthdate:</td>
                <td align="left" valign="top"><input name="birthdate" type="text" class="text-input text" id="birthdate" value="" /></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="field_label">Birth Place:</td>
                <td align="left" valign="top"><input name="birth_place" type="text" class="text-input text" id="birth_place" value="" /></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="field_label">Address:</td>
                <td align="left" valign="top"><input name="address" type="text" class="text-input text" id="address" value="" /></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="field_label">City:</td>
                <td align="left" valign="top"><input type="text" value="" name="city" class="text-input text" id="city" /></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="field_label">Province:</td>
                <td align="left" valign="top"><input type="text" value="" name="province" class="text-input text" id="province" /></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="field_label">Zip Code:</td>
                <td align="left" valign="top"><input type="text" value="" name="zip_code" class="text-input text" id="zip_code" /></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="field_label">Country:</td>
                <td align="left" valign="top"><input type="text" value="" name="country" class="text-input text" id="country" /></td>
              </tr>
            </table>    
            </div>
        </div>
        <div class="form_separator"></div>
       
        <div id="contact_information_form_wrapper" style="display:none">            
         <h3 class="section_title"><span>Contact Information</span></h3>
         	<div id="form_default">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="left" valign="top" class="field_label">Home Telephone:</td>
                <td align="left" valign="top"><input type="text" value="" name="home_telephone" class="text-input text" id="home_telephone" /></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="field_label">Mobile:</td>
                <td align="left" valign="top"><input type="text" value="" name="mobile" class="text-input text" id="mobile" /></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="field_label">Qualifications and Experience:</td>
                <td align="left" valign="top"><textarea name="qualification" id="qualification" cols="60" rows="5"></textarea></td>
              </tr>
            </table>
            </div>
        </div>        
        <div id="form_default" class="form_action_section">
            <table>
            	<tr>
                	<td class="field_label">&nbsp;</td>
                    <td>
                    	<input type="submit" value="Add New Candidate" class="curve blue_button" />
                    	<a href="javascript:cancel_add_candidate_form();">Cancel</a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
</form>
</div>
