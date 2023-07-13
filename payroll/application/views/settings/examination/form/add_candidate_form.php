<script type="text/javascript">
$(function() {
		$("#birthdate").datepicker({dateFormat: 'yy-mm-dd',changeMonth: true,
			changeYear: true,maxDate: '-16Y'});


$("#job_title_form").validationEngine({scroll:false});
$('#job_title_form').ajaxForm({
	success:function(o) {
		load_add_candidate_confirmation(); 
	},
	beforeSubmit:function() {
		showLoadingDialog('Saving...');	
	}
});

});
</script>

<div class="formwrap inner_form">
<form action="<?php echo url('recruitment/add_candidate'); ?>" method="post" enctype="multipart/form-data" name="job_title_form" id="job_title_form" >
<input type="hidden" id="company_structure_id" name="company_structure_id" value="<?php echo $company_structure_id; ?>"  />
<h3 class="form_sectiontitle"><span>Add Candidate</span></h3>
<div id="form_main">
    <div id="form_default">
        <h3 class="section_title"><span>Desired Position</span></h3>
        <input type="text"  value="" name="job_id" class="validate[required] text-input text" id="job_id" />
    (programmer, hr head)</div>
    <div id="form_default">
        <h3 class="section_title"><span>Personal Information</span></h3>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="left" valign="top" class="field_title"><font color="#33CC00" size="2px">*</font>Lastname:</td>
            <td align="left" valign="top"><input name="lastname" type="text" class="validate[required] text-input text" id="lastname" value="" /></td>
          </tr>
          <tr>
            <td align="left" valign="top" class="field_title"><font color="#33CC00" size="2px">*</font>Firstname:</td>
            <td align="left" valign="top"><input type="text" value="" name="firstname" class="validate[required] text-input text" id="firstname" /></td>
          </tr>    
          <tr>
            <td align="left" valign="top" class="field_title"><font color="#33CC00" size="2px">*</font>Middlename:</td>
            <td align="left" valign="top"><input type="text" value="" name="middlename" class="validate[required] text-input text" id="middlename" /></td>
          </tr> 
          <tr>
            <td align="left" valign="top" class="field_title"><font color="#33CC00" size="2px">*</font>Gender:</td>
            <td align="left" valign="top"><select name="gender" id="gender" class="validate[required] select_option">
              <option value="">--select gender--</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
            </select></td>
          </tr>
          <tr>
            <td align="left" valign="top" class="field_title"><font color="#33CC00" size="2px">*</font>Marital Status:</td>
            <td align="left" valign="top"><select name="marital_status" id="marital_status" class="validate[required] select_option" >
              <option value="">--select marital status--</option>
              <?php 
				foreach($GLOBALS['hr']['marital_status'] as $key=>$value){
				echo "<option value=".$value.">".$value."</option>";
				}
				?>
            </select></td>
          </tr>
          <tr>
            <td align="left" valign="top" class="field_title">Birthdate:</td>
            <td align="left" valign="top"><input name="birthdate" type="text" class="text-input text" id="birthdate" value="" /></td>
          </tr>
          <tr>
            <td align="left" valign="top" class="field_title">Birth Place:</td>
            <td align="left" valign="top"><input name="birth_place" type="text" class="text-input text" id="birth_place" value="" /></td>
          </tr>
          <tr>
            <td align="left" valign="top" class="field_title">Address:</td>
            <td align="left" valign="top"><input name="address" type="text" class="text-input text" id="address" value="" /></td>
          </tr>
          <tr>
            <td align="left" valign="top" class="field_title">City:</td>
            <td align="left" valign="top"><input type="text" value="" name="city" class="text-input text" id="city" /></td>
          </tr>
          <tr>
            <td align="left" valign="top" class="field_title">Province:</td>
            <td align="left" valign="top"><input type="text" value="" name="province" class="text-input text" id="province" /></td>
          </tr>
          <tr>
            <td align="left" valign="top" class="field_title">Zip Code:</td>
            <td align="left" valign="top"><input type="text" value="" name="zip_code" class="text-input text" id="zip_code" /></td>
          </tr>
          <tr>
            <td align="left" valign="top" class="field_title">Country:</td>
            <td align="left" valign="top"><input type="text" value="" name="country" class="text-input text" id="country" /></td>
          </tr>
        </table>    
    </div>
    <div id="form_default">
        <h3 class="section_title"><span>Contact Information</span></h3>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="left" valign="top" class="field_title">Home Telephone:</td>
            <td align="left" valign="top"><input type="text" value="" name="home_telephone" class="text-input text" id="home_telephone" /></td>
          </tr>
          <tr>
            <td align="left" valign="top" class="field_title">Mobile:</td>
            <td align="left" valign="top"><input type="text" value="" name="mobile" class="text-input text" id="mobile" /></td>
          </tr>
          <tr>
            <td align="left" valign="top" class="field_title"><font color="#33CC00" size="2px">*</font>Email Address:</td>
            <td align="left" valign="top"><input type="text" value="" name="email_address" class="validate[required,custom[email]] text-input text" id="email_address" /></td>
          </tr>
          <tr>
            <td align="left" valign="top" class="field_title">Qualifications and Experience:</td>
            <td align="left" valign="top"><textarea name="qualification" id="qualification" cols="60" rows="10"></textarea></td>
          </tr>
        </table>
    </div>
    <div id="form_default" class="form_action_section">
        <input type="submit" value="Add New Candidate" class="curve blue_button" />
        <a href="javascript:cancel_add_candidate_form();">Cancel</a>
    </div>
</div>
</form>
</div>


<script>
$('#job_id').textboxlist({unique: true,max:1, plugins: {autocomplete: {
	minLength: 3,
	onlyFromValues: true,
	queryRemote: true,
	remote: {url: base_url + 'recruitment/_autocomplete_load_job_name'}
}}});

</script>