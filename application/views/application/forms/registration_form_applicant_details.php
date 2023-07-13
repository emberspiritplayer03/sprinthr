<script type="text/javascript">
$(function() {
	$("#birthdate").datepicker({dateFormat: 'yy-mm-dd',changeMonth: true,
		changeYear: true,yearRange: "-90:-20", });
	
	$("#add_candidate_form").validationEngine({scroll:true});
	$('#add_candidate_form').ajaxForm({
		success:function(o) {
			if(o.is_saved) {				
				if(o.with_job_id == 1){
					window.location = o.url + "?eid=" + o.h_application_id  + "&hid=" + o.ehash + "&jeid=" + o.jeid;
				}else{
					window.location = o.url + "?eid=" + o.h_application_id  + "&hid=" + o.ehash;
				}				
			}else{				
				$("#token").val(o.token);
				dialogOkBox("Error : Application was not successful! Please try again",{});
			}
		},
		beforeSubmit:function() {
			showLoadingDialog('Saving...');	
		},
		dataType : 'json'
	});
	
	 $('#filename').uploadify({
		'uploader'  	  : '<?php echo BASE_FOLDER; ?>application/scripts/uploadify/uploadify.swf',
		'script'    	  : '<?php echo BASE_FOLDER; ?>application/scripts/uploadify/uploadify.php',
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
<form action="<?php echo url('applicant/_save_applicant_info'); ?>" method="post" enctype="multipart/form-data" name="add_candidate_form" id="add_candidate_form" >
<input type="hidden" name="job_id" id="job_id" value="<?php echo ($j ? Utilities::encrypt($j->getJobId()) : ""); ?>" />
<input type="hidden" name="token" id="token" value="<?php echo $token; ?>" />
<input type="hidden" name="date_applied" id="date_applied" value="<?php echo $date_appliend; ?>" />
<div id="formwrap">
	<h3 class="form_sectiontitle">Congratulation, your account has been activated.</h3>
    <div id="form_main">
    		 <h3 class="section_title"><span>To complete the registration process, kindly fill-up the below details.</span></h3>   
    		 <hr />
    	  <?php
    	  		if($with_job_application == 1){
    	  			include_once("_application_job_details.php");
    	  		}
    	  ?>
        <div id="contact_information_form_wrapper">        
        	<h3 class="section_title"><span>Personal Information</span></h3>
        	 <div id="form_default">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="24%" valign="top" class="field_label"><font color="#33CC00" size="2px">*</font>Lastname:</td>
                <td width="76%"><input name="lastname" type="text" class="validate[required] text-input text" id="lastname" value="<?php echo $a->getLastName(); ?>" /></td>
              </tr>
              <tr>
                <td valign="top" class="field_label"><font color="#33CC00" size="2px">*</font>Firstname:</td>
                <td><input type="text" name="firstname" class="validate[required] text-input text" id="firstname" value="<?php echo $a->getFirstName(); ?>" /></td>
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
			                foreach($GLOBALS['hr']['marital_status'] as $key=>$value){
			                	echo "<option value=".$value.">".$value."</option>";
			                }
                	?>
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
            </table>    
        	</div>
       </div>
       
       
        <div id="form_default" class="form_action_section">
            <table>
            	<tr>
                	<td class="field_label">&nbsp;</td>
                    <td>
                    	<input type="submit" value="Send Application" class="curve blue_button" /> | <a href="<?php echo url("job_vacancy"); ?>">Back to Job Vacancy List</a>                        
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
</form>
</div>