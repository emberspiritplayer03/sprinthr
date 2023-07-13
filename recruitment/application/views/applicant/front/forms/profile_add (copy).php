<script type="text/javascript">
$(function() {
	$("#birthdate").datepicker({dateFormat: 'yy-mm-dd',changeMonth: true,
		changeYear: true,maxDate: '-16Y'});
		
	$("#date_applied").datepicker({dateFormat: 'yy-mm-dd',changeMonth: true,
		changeYear: true});
	
	
	//$("#update_profile_form").validationEngine({scroll:true});
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
<div id="photo_wrapper"></div>
<form action="<?php echo url('applicant/update_profile'); ?>" method="post" enctype="multipart/form-data" name="update_profile_form" id="update_profile_form" >
<input type="hidden" id="company_structure_id" name="company_structure_id" value="<?php echo $company_structure_id; ?>"  />
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>"  />
<div id="formwrap">
	
    <div id="form_main">    	
        <div id="contact_information_form_wrapper" >    
				<div id="photo_frame_wrapper" class="applicant_profile_photo">
				<?php
					if($profile){						
						if($profile->getPhoto() == ""){
							$profile_photo = BASE_FOLDER . '/images/profile_noimage.gif';
						}else{
							$profile_photo = BASE_FOLDER . 'files/photo/' . $profile->getPhoto();
						}
					}else{						
						$profile_photo = BASE_FOLDER . '/images/profile_noimage.gif';
					}
				?>
			   	<img onclick="javascript:loadPhotoDialog(<?php echo $profile ? $profile->getApplicantLogId() : Utilities::decrypt($applicant_id); ?>);" src="<?php echo $profile_photo; ?>" width="233" alt="Profile Photo"  />			   	
			   </div>                    
        	 <h3 class="section_title"><span>Personal Information</span></h3>
        	 <div id="form_default">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="24%" valign="top" class="field_label"><font color="#33CC00" size="2px">*</font>Lastname:</td>
                <td width="76%"><input name="lastname" type="text" class="validate[required] text-input text" id="lastname" value="" /></td>
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
                <td align="left" valign="top" class="field_label">Extension Name</td>
                <td align="left" valign="top"><input type="text" value="" name="extension_name" class="text-input text" id="extension_name" /> 
                  (I,II,III, Jr. Sr.)</td>
              </tr>
              <tr>
                <td align="left" valign="top" class="field_label">Birthdate:</td>
                <td align="left" valign="top"><input name="birthdate" type="text" class="validate[required] text-input text" id="birthdate" value="" /></td>
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
                		//foreach($GLOBALS['hr']['marital_status'] as $key=>$value){
                		//echo "<option value=".$value.">".$value."</option>";
                		//}
                	?>
                	<?php foreach($GLOBALS['hr']['marital_status'] as $key=>$value){ ?>
                			<option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                	<?php } ?>
                </select></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="field_label">Home Telephone:</td>
                <td align="left" valign="top"><input type="text" value="" name="home_telephone" class="text-input text" id="home_telephone" /></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="field_label">Mobile:</td>
                <td align="left" valign="top"><input type="text" value="" name="mobile" class="validate[required] text-input text" id="mobile" /></td>
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
                <td width="76%" align="left" valign="top"><input name="birth_place" type="text" class="text-input text" id="birth_place" value="" /></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="field_label">Address:</td>
                <td align="left" valign="top"><textarea class="validate[required]" name="address" id="address" cols="45" rows="5"></textarea></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="field_label">City:</td>
                <td align="left" valign="top"><input type="text" value="" name="city" class="validate[required] text-input text" id="city" /></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="field_label">Province:</td>
                <td align="left" valign="top"><input type="text" value="" name="province" class="validate[required] text-input text" id="province" /></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="field_label">Zip Code:</td>
                <td align="left" valign="top"><input type="text" value="" name="zip_code" class="text-input text" id="zip_code" /></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="field_label">SSS Number:</td>
                <td align="left" valign="top"><input type="text" value="" name="sss_number" class="text-input text" id="sss_number" /></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="field_label">Tin Number:</td>
                <td align="left" valign="top"><input type="text" value="" name="tin_number" class="text-input text" id="tin_number" /></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="field_label">Phil Health Number:</td>
                <td align="left" valign="top"><input type="text" value="" name="philhealth_number" class="text-input text" id="philhealth_number" /></td>
              </tr>

              <tr>
                <td align="left" valign="top" class="field_label">Pag Ibig Number:</td>
                <td align="left" valign="top"><input type="text" value="" name="pagibig_number" class="text-input text" id="pagibig_number" /></td>
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
                    	<a class="curve blue_button" href="<?php echo url("applicant_login"); ?>"><i class="icon-home icon-white"></i> Back to CPanel<a/>                        
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
</form>
</div>
