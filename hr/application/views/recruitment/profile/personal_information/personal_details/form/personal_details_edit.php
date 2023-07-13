<script>
$("#birthdate").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,maxDate:'-17Y',showOtherMonths:true});
$("#applied_date_time").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#personal_details_form").validationEngine({scroll:false});
$('#personal_details_form').ajaxForm({
	success:function(o) {
		if(o==1) {
			loadPhoto();
			dialogOkBox('Successfully Updated',{});
			$("#personal_details_wrapper").html('');
			loadPage("#personal_details");
			loadApplicantSummary();
		}else {
			dialogOkBox(o,{});

		}		
	},
	beforeSubmit:function() {
		showLoadingDialog('Saving...');	
	}
});
</script>

<form id="personal_details_form" name="form1" method="post" action="<?php echo url('recruitment/_update_personal_details'); ?>"  style="display:none;">
<input type="hidden" name="applicant_id" value="<?php echo Utilities::encrypt($details->id); ?>" />
<input name="photo" type="hidden" id="photo" value="<?php echo $details->getPhoto(); ?>"  />
<input type="hidden" name="home_telephone" id="home_telephone" value="<?php echo $details->home_telephone; ?>"  />
<input type="hidden" name="mobile" id="mobile" value="<?php echo $details->mobile; ?>"  />
<input type="hidden" name="email_address" id="email_address" value="<?php echo $details->email_address; ?>"  />
<input type="hidden" name="qualification" id="qualification" value="<?php echo $details->qualification; ?>"  />
<div class="employee_summaryholder">
	<div id="photo_frame_personal_edit_wrapper" class="employee_profile_photo">
    	<img  onclick="javascript:loadPhotoDialog();" src="<?php echo $filename; ?>?<?php echo $filemtime; ?>" width="140" border="1"  />
        <a class="action_change_photo" href="javascript:void(0);" onClick="javascript:loadPhotoDialog();">Change Picture</a>
    </div>
    <div class="employeesummary_details">
    <div id="form_main" class="employee_form edit_employee_form">
    <div id="form_default">
    	<h3 class="section_title"><?php echo $title_personal_details; ?></h3>
          <table id="personal_details_table">
            <tr>
              <td class="field_label">Firstname:</td>
              <td><input type="text" class="validate[required] text-input"  name="firstname" id="firstname" value="<?php echo ucfirst($details->firstname); ?>" /></td>
            </tr>
            <tr>
              <td class="field_label">Lastname:</td>
              <td><input type="text" class="validate[required] text-input"  name="lastname" id="lastname" value="<?php echo  ucfirst($details->lastname); ?>" /></td>
            </tr>
            <tr>
              <td class="field_label">Middlename:</td>
              <td><input class="text-input" type="text" name="middlename" id="middlename" value="<?php echo  ucfirst($details->middlename); ?>" /></td>
            </tr>
            <tr>
              <td class="field_label">Extension Name:</td>
              <td><input class="text-input" type="text" name="extension_name" id="extension_name" value="<?php echo  ucfirst($details->extension_name); ?>" />&nbsp;&nbsp;<small><em>(Jr, I, II, III)</em></small></td>
            </tr>
            <tr>
              <td class="field_label">Gender:</td>
              <td><select  class="validate[required] select_option"  name="gender" id="gender">
                <option value="" >-- Select Option --</option>                            
                <option <?php echo($details->gender == "male" ? 'selected="selected"' : ''); ?> value="male">Male</option>
                <option <?php echo($details->gender == "female" ? 'selected="selected"' : ''); ?> value="female">Female</option>
              </select></td>
            </tr>
            <tr>
              <td class="field_label">Marital Status:</td>
              <td><select  class="validate[required] select_option"  name="marital_status" id="marital_status">
                <option value="" >-- Select Option --</option>                
                <?php foreach($GLOBALS['hr']['marital_status'] as $key=>$marital_status) { ?>
                <option <?php echo(ucfirst($details->marital_status) == $marital_status ? 'selected="selected"' : ''); ?> value="<?php echo $marital_status; ?>"><?php echo $marital_status; ?></option>
                <?php } ?>
              </select></td>
            </tr>
            <tr>
              <td class="field_label">Birthdate:</td>
              <td><?php $birthdate = ($details->birthdate=='0000-00-00')? '' : $details->birthdate; ?>
              <input class="text-input" type="text" name="birthdate" id="birthdate" value="<?php echo $birthdate; ?>" /></td>
            </tr>
            <tr>
              <td class="field_label">Birth Place:</td>
              <td><input class="text-input" type="text" name="birth_place" id="birth_place" value="<?php echo $details->birth_place; ?>"  /></td>
            </tr>
            <tr>
              <td class="field_label">Address:</td>
              <td><input class="text-input" type="text" name="address" id="address" value="<?php echo $details->address; ?>" /></td>
            </tr>
            <tr>
              <td class="field_label">City:</td>
              <td><input class="text-input" type="text" name="city" id="city" value="<?php echo $details->city; ?>"  /></td>
            </tr>
            <tr>
              <td class="field_label">State:</td>
              <td><input class="text-input" type="text" name="province" id="province" value="<?php echo $details->province; ?>"  /></td>
            </tr>
            <tr>
              <td class="field_label">Zip Code:</td>
              <td><input class="text-input" type="text" name="zip_code" id="zip_code" value="<?php echo $details->zip_code; ?>"  /></td>
            </tr>
            <tr>
              <td class="field_label">Country:</td>
              <td>
              	<select id="country" name="country">
					<?php foreach($locations as $l){ ?>
                    <option <?php echo($l->getLocation() == $details->country ? 'selected="selected"' : '' ); ?> value="<?php echo $l->getLocation(); ?>"><?php echo $l->getLocation(); ?></option>
                    <?php } ?>
                </select>              	
              </td>
            </tr>
        </table>
    </div><!-- #form_default -->
    <div class="form_separator"></div>
    <div id="form_default">
        <h3 class="section_title">Other Details</h3>
        <table>            
            <tr>
              <td class="field_label">SSS Number:</td>
              <td><input class="text-input" type="text" name="sss_number" id="sss_number" value="<?php echo $details->sss_number; ?>"  /></td>
            </tr>
            <tr>
              <td class="field_label">Pagibig Number:</td>
              <td><input class="text-input" type="text" name="pagibig_number" id="pagibig_number" value="<?php echo $details->pagibig_number; ?>"  /></td>
            </tr>
            <tr>
              <td class="field_label">TIN Number:</td>
              <td><input class="text-input" type="text" name="tin_number" id="tin_number" value="<?php echo $details->tin_number; ?>"  /></td>
            </tr>
             <tr>
              <td class="field_label">Philhealth Number:</td>
              <td><input class="text-input" type="text" name="philhealth_number" id="philhealth_number" value="<?php echo $details->philhealth_number; ?>"  /></td>
            </tr>
            <tr>
              <td class="field_label">Date Applied:</td>
              <td><input class="text-input" type="text" name="applied_date_time" id="applied_date_time" value="<?php echo $details->applied_date_time; ?>"  /></td>
            </tr>
            <tr>
              <td class="field_label">Applied Position:</td>
              <td><select class="select_option" name="job_id" id="job_id">
                <?php foreach($job as $key=>$val) {  ?>
                <option <?php echo ($details->job_id==$val->id) ? 'selected' : '';   ?> value="<?php echo $val->getId(); ?>"><?php echo $val->getTitle(); ?></option>
                <?php } ?>
              </select></td>
            </tr>
            <!--<tr>
              <td class="field_label">Resume:</td>
              <td><input class="text-input" type="text" name="resume_name" id="resume_name" value="<?php //echo $details->resume_name; ?>"  /></td>
            </tr>-->
          </table>
    </div><!-- #form_default -->
    <div class="form_action_section" id="form_default">
    	<table width="100%" cellspacing="0" cellpadding="0" border="0">
        	<tbody>
            <tr>
            	<td class="field_label">&nbsp;</td>
                <td><input class="blue_button" type="submit" name="button" id="button" value="Update" />&nbsp;<a href="javascript:void(0);" onclick="javascript:loadPersonalDetailsTable();">Cancel</a></td>
            </tr>
        </tbody></table>
    </div><!-- .form_action_section -->
    </div><!-- #form_main.inner_form -->
	</div>
</div>
</form>


<script>
/*$('#job_id').textboxlist({unique: true,max:1, plugins: {autocomplete: {
	minLength: 3,
	onlyFromValues: true,
	queryRemote: true,
	remote: {url: base_url + 'recruitment/_autocomplete_load_job_name'}
}}});*/



</script>