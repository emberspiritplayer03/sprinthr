<script type="text/javascript">
$(function() {
	$("#birthdate").datepicker({dateFormat: 'yy-mm-dd',changeMonth: true,
		changeYear: true,maxDate: '-16Y'});
		
	$("#date_applied").datepicker({dateFormat: 'yy-mm-dd',changeMonth: true,
		changeYear: true});
	
	
	$("#update_profile_form").validationEngine({scroll:true});
	$('#update_profile_form').ajaxForm({
		success:function(o) {
			if(o.is_saved) {	
				dialogOkBox("Succesfully Update Profile!",{ok_url: 'applicant/profile'});
			} else {
				dialogOkBox("Error : Profile was not successful! Please try again",{});
			}
		},
		beforeSubmit:function() {
			showLoadingDialog('Saving...');	
		},
		dataType : 'json'
	});
	
	 $('#filename').uploadify({
		'uploader'  	  : '<?php echo MAIN_FOLDER; ?>application/scripts/uploadify/uploadify.swf',
		'script'    	  : '<?php echo MAIN_FOLDER; ?>application/scripts/uploadify/uploadify.php',
		//'script'    	  : base_url +'registration/ajax_upload_resume',
		//'script'    	  : '<?php //echo url('registration/ajax_upload_resume'); ?>',
		'cancelImg' 	  : '<?php echo MAIN_FOLDER; ?>application/scripts/uploadify/cancel.png',
		'folder'          : '<?php echo MAIN_FOLDER; ?>hr/files/applicant/resume',
		//'folder'          : '<?php echo MAIN_FOLDER; ?>files',
		'auto'      	  : true,
		'scriptAccess'    : 'always',
		'removeCompleted' : true,
		'sizeLimit'       : 2097152, //2mb in bytes
		'buttonText'  	  : 'Browse ...',
		'displayData' 	  : 'speed',
		'fileExt'         : '*.doc;*.docx;*.pdf;',
		'fileDesc'        : 'Document File',
		'multi'			  : false,
		'onComplete': function(event, queueID, fileObj, response, data){
			if(response) {
				$('#uploaded_file_wrapper').show();
				$('#filename_wrapper').html(fileObj.name);
				$('#directory_name').val(response);
				$('#upload_filename').val(fileObj.name);
			}
			//alert(response);
			/*var file_name = fileObj.name;
			$.post(base_url + 'client/_json_encode_get_image',{file_name:file_name},function(o) {
				if(o.image) {
					document.test.src = o.image;
				}
			},'json');*/
		}
	  });
});

function remove_file() {
	$('#uploaded_file_wrapper').hide();
	$('#directory_name').val('');
	$('#upload_filename').val('');
}
</script>
<div id="formcontainer">
<div class="mtshad"></div>
<form action="<?php echo url('applicant/update_profile'); ?>" method="post" enctype="multipart/form-data" name="update_profile_form" id="update_profile_form" >
<input type="hidden" id="company_structure_id" name="company_structure_id" value="<?php echo $company_structure_id; ?>"  />
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>"  />
<div id="formwrap">
	
    <div id="form_main">
    	<!-- <h3 class="section_title"><span>Application Information</span></h3> -->
        <!-- <div id="form_default">            
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
            		<td valign="top" class="field_label">Email Address:</td>
            		<td><input type="text" class="validate[required,custom[email]]" name="email_address" id="email_address"></td>
              </tr>
          <tr>
            <td valign="top" class="field_label">Resume:</td>
            <td>
            	<input type="file" name="filename" id="filename" />
                <div id="uploaded_file_wrapper" style="display:none;">
                <br />
                	<strong><i><span id="filename_wrapper"></span></i></strong> <a href="javascript:void(0);" onclick="javascript:remove_file();">Remove</a>
                    <input type="hidden" id="directory_name" name="directory_name" value="" />
                    <input type="hidden" id="upload_filename" name="upload_filename" value="" />
                </div>
            </td>
          </tr>
          <tr>
            <td colspan="2" valign="top" class="field_label">&nbsp;</td>
          </tr>
          </table>
        </div> -->
        
        <!-- <div class="form_separator"></div> -->
        <div id="contact_information_form_wrapper" >            
        	 <h3 class="section_title"><span>Personal Information</span></h3>
        	 <div id="form_default">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="24%" valign="top" class="field_label"><font color="#33CC00" size="2px">*</font>Lastname:</td>
                <td width="76%"><input name="lastname" type="text" class="validate[required] text-input text" id="lastname" value="<?php echo $profile->getLastName(); ?>" /></td>
              </tr>
              <tr>
                <td valign="top" class="field_label"><font color="#33CC00" size="2px">*</font>Firstname:</td>
                <td><input type="text" value="<?php echo $profile->getFirstName(); ?>" name="firstname" class="validate[required] text-input text" id="firstname" /></td>
              </tr>
              <tr>
                <td valign="top" class="field_label"><font color="#33CC00" size="2px">*</font>Middlename:</td>
                <td><input type="text" value="<?php echo $profile->getMiddleName(); ?>" name="middlename" class="validate[required] text-input text" id="middlename" /></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="field_label">Extension Name</td>
                <td align="left" valign="top"><input type="text" value="<?php echo $profile->getExtensionName(); ?>" name="extension_name" class="text-input text" id="extension_name" /> 
                  (I,II,III, Jr. Sr.)</td>
              </tr>
              <tr>
                <td align="left" valign="top" class="field_label">Birthdate:</td>
                <td align="left" valign="top"><input name="birthdate" type="text" class="validate[required] text-input text" id="birthdate" value="<?php echo $profile->getBirthDate(); ?>" /></td>
              </tr>
              <tr>
                <td valign="top" class="field_label"><font color="#33CC00" size="2px">*</font>Gender:</td>
                <td><select name="gender" id="gender" class="validate[required] select_option">
                  <option value="">--select gender--</option>
                  <option <?php echo $profile->getGender() == 'male' ? 'selected="selected"' : ''; ?> value="male">Male</option>
                  <option <?php echo $profile->getGender() == 'female' ? 'selected="selected"' : ''; ?> value="female">Female</option>
                </select></td>
              </tr>
              <tr>
                <td valign="top" class="field_label"><font color="#33CC00" size="2px">*</font>Marital Status:</td>
                <td><select name="marital_status" id="marital_status" class="validate[required] select_option" >
                  <option value="">--select marital status--</option>
                  <?php 
                		//foreach($GLOBALS['hr']['marital_status'] as $key=>$value){
                		//echo "<option value=".$value.">".$value."</option>";
                		//}
                	?>
                	<?php foreach($GLOBALS['hr']['marital_status'] as $key=>$value){ ?>
                			<option <?php echo $profile->getMaritalStatus() == $value ? 'selected="selected"' : ''; ?> value="<?php echo $value; ?>"><?php echo $value; ?></option>
                	<?php } ?>
                </select></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="field_label">Home Telephone:</td>
                <td align="left" valign="top"><input type="text" value="<?php echo $profile->getHomeTelephone(); ?>" name="home_telephone" class="text-input text" id="home_telephone" /></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="field_label">Mobile:</td>
                <td align="left" valign="top"><input type="text" value="<?php echo $profile->getMobile(); ?>" name="mobile" class="validate[required] text-input text" id="mobile" /></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="field_label">&nbsp;</td>
                <td align="left" valign="top">&nbsp;</td>
              </tr>
            </table>
           	</div>
        </div>        
         <div class="form_separator"></div>
        <div id="other_personal_information_form_wrapper" >     
         <h3 class="section_title"><span>Other Personal Information</span></h3>
         	<div id="form_default">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="24%" align="left" valign="top" class="field_label">Birth Place:</td>
                <td width="76%" align="left" valign="top"><input name="birth_place" type="text" class="text-input text" id="birth_place" value="<?php echo $profile->getBirthPlace(); ?>" /></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="field_label">Address:</td>
                <td align="left" valign="top"><textarea class="validate[required]" name="address" id="address" cols="45" rows="5"><?php echo $profile->getAddress(); ?></textarea></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="field_label">City:</td>
                <td align="left" valign="top"><input type="text" value="<?php echo $profile->getCity(); ?>" name="city" class="validate[required] text-input text" id="city" /></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="field_label">Province:</td>
                <td align="left" valign="top"><input type="text" value="<?php echo $profile->getProvince(); ?>" name="province" class="validate[required] text-input text" id="province" /></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="field_label">Zip Code:</td>
                <td align="left" valign="top"><input type="text" value="<?php echo $profile->getZipCode(); ?>" name="zip_code" class="text-input text" id="zip_code" /></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="field_label">SSS Number:</td>
                <td align="left" valign="top"><input type="text" value="<?php echo $profile->getSssNumber(); ?>" name="sss_number" class="text-input text" id="sss_number" /></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="field_label">Tin Number:</td>
                <td align="left" valign="top"><input type="text" value="<?php echo $profile->getTinNumber(); ?>" name="tin_number" class="text-input text" id="tin_number" /></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="field_label">Phil Health Number:</td>
                <td align="left" valign="top"><input type="text" value="<?php echo $profile->getPhilhealthNumber(); ?>" name="philhealth_number" class="text-input text" id="philhealth_number" /></td>
              </tr>

              <tr>
                <td align="left" valign="top" class="field_label">Pag Ibig Number:</td>
                <td align="left" valign="top"><input type="text" value="<?php echo $profile->getPagibigNumber(); ?>" name="pagibig_number" class="text-input text" id="pagibig_number" /></td>
              </tr>
            </table>    
        	</div>
       </div>
       
       
        <div id="form_default" class="form_action_section">
            <table>
            	<tr>
                	<td class="field_label">&nbsp;</td>
                    <td>
                    	<input type="submit" value="Update Profile" class="curve blue_button" />                        
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
</form>
</div>
