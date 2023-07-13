<script>
	$(function() {
		$('input:checkbox').attr('checked','checked');
		$('.ckoptions, .cksectionoption').change(function() {
			$('#all_modules').attr('checked',false)
		});
	});
	
	function check_uncheck_options() {
		var a = $('#all_modules').attr('checked');
		var attr = (a=="checked" ? 'checked' : false);
		$('.ckoptions').attr('checked',attr);
		$('.cksectionoption').attr('checked',attr);
	}
	
	function check_uncheck_section(section) {
		
		if(section == 'personal_information') {
			var a = $('#personal_information_section').attr('checked');
			var attr = (a=="checked" ? 'checked' : false);
			$('.ckpersonal_information').attr('checked',attr);
			
		} else if(section == 'employment_information') {
			var a = $('#employment_information_section').attr('checked');
			var attr = (a=="checked" ? 'checked' : false);
			$('.ckemployment_information').attr('checked',attr);
		} else if(section == 'qualification') {
			var a = $('#qualification_section').attr('checked');
			var attr = (a=="checked" ? 'checked' : false);
			$('.ckqualification').attr('checked',attr);
		}
	}
	
	function uncheck_section(id) {
		$(id).attr('checked',false);
	}
</script>
<div class="popup_form popup_form_wcont" id="form_main">
    <form class="form_checkbox_group" id="print_employee_details_option_form" name="print_employee_details_option_form" method="post" action="<?php echo url('employee/download_employee_details'); ?>">
	<input type="hidden" id="h_employee_id" name="h_employee_id" value="<?php echo $h_employee_id; ?>" />
	<h3><label class="checkbox"><input type="checkbox" id="all_modules" name="all_modules" onchange="javascript:check_uncheck_options();" />Select All Modules</label></h3>
    <div id="form_default">
      <table width="100%" cellspacing="3" cellpadding="3" border="0" class="">
        <tbody>
         <tr>
          <td valign="top">
          	<h4><label class="checkbox"><input type="checkbox" id="personal_information_section" name="personal_information_section" class="cksectionoption" onchange="javascript:check_uncheck_section('personal_information');" /> Personal Information</label></h4>
            <ul>
                <li><label class="checkbox"><input type="checkbox" id="module[personal_details]" onclick="javascript:uncheck_section('#personal_information_section');" name="module[personal_details]" class="ckoptions ckpersonal_information" /> Personal Details</label></li>
                <li><label class="checkbox"><input type="checkbox" id="module[contact_details]" onclick="javascript:uncheck_section('#personal_information_section');" name="module[contact_details]" class="ckoptions ckpersonal_information" /> Contact Details</label></li>
                <li><label class="checkbox"><input type="checkbox" id="module[emergency_contacts]" onclick="javascript:uncheck_section('#personal_information_section');" name="module[emergency_contacts]" class="ckoptions ckpersonal_information" /> Emergency Contacts</label></li>
                <li><label class="checkbox"><input type="checkbox" id="module[dependents]" onclick="javascript:uncheck_section('#personal_information_section');" name="module[dependents]" class="ckoptions ckpersonal_information" /> Dependents</label></li>
                <li><label class="checkbox"><input type="checkbox" id="module[bank]" onclick="javascript:uncheck_section('#personal_information_section');" name="module[bank]" class="ckoptions ckpersonal_information" /> Bank</label></li>
                <li><label class="checkbox"><input type="checkbox" id="module[employment_status]" onclick="javascript:uncheck_section('#personal_information_section');" name="module[employment_status]" class="ckoptions ckpersonal_information" /> Employment Status</label></li>
            </ul>
          </td>
          <td valign="top">
          	<h4><label class="checkbox"><input type="checkbox" id="employment_information_section" name="employment_information_section" onchange="javascript:check_uncheck_section('employment_information');" class="cksectionoption" /> Employment Information</label></h4>
            <ul>
                <li><label class="checkbox"><input type="checkbox" id="module[employment_status]" name="module[employment_status]" onclick="javascript:uncheck_section('#employment_information_section');" class="ckoptions ckemployment_information" /> Employment Status</label></li>
                <li><label class="checkbox"><input type="checkbox" id="module[compensation]" name="module[compensation]" onclick="javascript:uncheck_section('#employment_information_section');" class="ckoptions ckemployment_information" /> Compensation</label></li>
                <li><label class="checkbox"><input type="checkbox" id="module[contract]" name="module[contract]" onclick="javascript:uncheck_section('#employment_information_section');" class="ckoptions ckemployment_information" /> Contract</label></li>
                <li><label class="checkbox"><input type="checkbox" id="module[contribution]" name="module[contribution]" onclick="javascript:uncheck_section('#employment_information_section');" class="ckoptions ckemployment_information" /> Contribution</label></li>
                <li><label class="checkbox"><input type="checkbox" id="module[performance]" name="module[performance]" onclick="javascript:uncheck_section('#employment_information_section');" class="ckoptions ckemployment_information" /> Performance</label></li>
                <li><label class="checkbox"><input type="checkbox" id="module[training]" name="module[training]" onclick="javascript:uncheck_section('#employment_information_section');" class="ckoptions ckemployment_information" /> Training</label></li>
                <li><label class="checkbox"><input type="checkbox" id="module[memo]" name="module[memo]" onclick="javascript:uncheck_section('#employment_information_section');" class="ckoptions ckemployment_information" /> Memo</label></li>
                <li><label class="checkbox"><input type="checkbox" id="module[requirement]" name="module[requirement]" onclick="javascript:uncheck_section('#employment_information_section');" class="ckoptions ckemployment_information" /> Requirement</label></li>
                <li><label class="checkbox"><input type="checkbox" id="module[supervisor]" name="module[supervisor]" onclick="javascript:uncheck_section('#employment_information_section');" class="ckoptions ckemployment_information" /> Supervisor</label></li>
                <li><label class="checkbox"><input type="checkbox" id="module[leave]" name="module[leave]" onclick="javascript:uncheck_section('#employment_information_section');" class="ckoptions ckemployment_information" /> Leave</label></li>
                <li><label class="checkbox"><input type="checkbox" id="module[deduction]" name="module[deduction]" onclick="javascript:uncheck_section('#employment_information_section');" class="ckoptions ckemployment_information" /> Deduction</label></li>
            </ul>
          </td>
          <td valign="top">
          	<h4><label class="checkbox"><input type="checkbox" id="qualification_section" name="qualification_section" onchange="javascript:check_uncheck_section('qualification');" class="cksectionoption" /> Qualification</label></h4>
            <ul>
                <li><label class="checkbox"><input type="checkbox" id="module[work_experience]" name="module[work_experience]" onclick="javascript:uncheck_section('#qualification_section');" class="ckoptions ckqualification" /> Work Experience</label></li>
                <li><label class="checkbox"><input type="checkbox" id="module[education]" name="module[education]" onclick="javascript:uncheck_section('#qualification_section');" class="ckoptions ckqualification" /> Education</label></li>
                <li><label class="checkbox"><input type="checkbox" id="module[skills]" name="module[skills]" onclick="javascript:uncheck_section('#qualification_section');" class="ckoptions ckqualification" /> Skills</label></li>
                <li><label class="checkbox"><input type="checkbox" id="module[language]" name="module[language]" onclick="javascript:uncheck_section('#qualification_section');" class="ckoptions ckqualification" /> Language</label></li>
                <li><label class="checkbox"><input type="checkbox" id="module[license]" name="module[license]" onclick="javascript:uncheck_section('#qualification_section');" class="ckoptions ckqualification" /> License</label></li>
            </ul>
          </td>
        </tr>
        </tbody>
        </table>
    </div><!-- #form_default.form_action_section -->
    <div class="form_action_section" id="form_default">
    	<table width="100%" cellspacing="0" cellpadding="0" border="0">
        	<tbody>
            <tr>
                <td><button class="blue_button" type="submit"><i class="icon-download-alt icon-white"></i> Download</button>&nbsp;<a onclick="javascript:closeDialogBox('#_dialog-box_','#print_employee_details_option_form');" href="javascript:void(0)">Cancel</a></td>
            </tr>
        </tbody></table>
    </div>
    </form>
</div>

<!-- <td width="20%">
    Schedule
    <table width="100%" border="1">
      <tr>
        <td><input type="checkbox" id="module[work_experience]" name="module[work_experience]" class="ckoptions" /></td>
        <td>Work Schedule</td>
      </tr>
    </table>
</td>
<td width="20%">
    Attachment
    <table width="100%" border="1">
      <tr>
        <td><input type="checkbox" id="module[attachment]" name="module[attachment]" class="ckoptions" /></td>
        <td>Attachment</td>
      </tr>
    </table>
</td>-->