<script type="text/javascript">
$(function() {
	$("#birthdate").datepicker({dateFormat: 'yy-mm-dd',changeMonth: true,
		changeYear: true,maxDate: '-16Y'});
		
	$("#date_applied").datepicker({dateFormat: 'yy-mm-dd',changeMonth: true,
		changeYear: true});
	
	
	$("#add_candidate_form").validationEngine({scroll:true});
	$('#add_candidate_form').ajaxForm({
		success:function(o) {
			if(o.is_saved) {	
				dialogOkBox("Succesfully Registered! <br/><br/> Click <a href='<?php echo url('examination/choose_examination'); ?>?app_id="+o.h_application_id+"&hash="+o.hash+"'>here</a> to proceed to examination page.",{ok_url: 'registration'});
			} else {
				dialogOkBox("Error : Application was not successful! Please try again",{ok_url: 'registration'});
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
<form action="<?php echo url('registration/add_candidate'); ?>" method="post" enctype="multipart/form-data" name="add_candidate_form" id="add_candidate_form" >
<input type="hidden" id="company_structure_id" name="company_structure_id" value="<?php echo $company_structure_id; ?>"  />

<div id="formwrap">
<?php 
	//echo url('registration/ajax_upload_resume') .'<br/>';
	#echo BASE_FOLDER . '' .'<br/>';
	#echo MAIN_FOLDER;
	#echo MAIN_FOLDER .'application/scripts/uploadify/uploadify.php';
	#exit;
?>
	<h3 class="form_sectiontitle" id="candidate_form_wrapper">Add Candidate</h3>
    <div id="form_main">
    	<h3 class="section_title"><span>Application Information</span></h3>
        <div id="form_default">            
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="field_label">Applied Position: </td>
            <td><select class="validate[required]" name="job_id" id="job_id">
              <option value="">- Select Applied Position - </option>
             
              <?php foreach ($positions as $key=>$value) { ?>
               <option value="<?php echo $value->id; ?>"><?php echo $value->title; ?></option>
              <?php } ?>
            </select>              
            <em class="note label">(programmer, hr head)</em></td>
          </tr>
          <tr>
            <td class="field_label">Date Applied: </td>
            <td><input type="text" class="validate[required] text-input text" value="<?php echo date('Y-m-d'); ?>" name="date_applied" id="date_applied" /></td>
          </tr>
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
        </div>
        <div class="form_separator"></div>
        <div id="contact_information_form_wrapper" >            
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
                    	<input type="submit" value="Add New Candidate" class="curve blue_button" />                        
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
</form>
</div>
