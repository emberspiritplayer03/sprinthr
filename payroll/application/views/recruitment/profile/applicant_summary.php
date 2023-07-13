<h2 class="field_title">Summary Applicant Information [<a href="javascript:void(0);" onclick="javascript:hideApplicantSummary()">hide</a>]</h2>
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left" valign="top">
<table class="table_form" width="400" border="0" cellspacing="3" cellpadding="3">
          <tr>
            <td width="170" align="right" valign="top">Date Applied:</td>
            <td align="left" valign="top"><?php echo Date::convertDateIntIntoDateString($applicant_details['applied_date_time']); ?></td>
          </tr>
          <tr>
            <td width="170" align="right" valign="top">Name:</td>
            <td align="left" valign="top"><div id="employee_name_wrapper"><?php echo $applicant_details['applicant_name']; ?></div></td>
          </tr>
          <tr>
            <td width="170" align="right" valign="top">Applied Position: </td>
            <td align="left" valign="top"><?php echo $applicant_details['job_name']; ?></td>
          </tr>
          <tr>
            <td align="right" valign="top">Status</td>
            <td align="left" valign="top"><?php echo $GLOBALS['hr']['application_status'][$applicant_details['application_status_id']]; ?></td>
          </tr>
          <?php if($requirements!='') { ?>
          <tr>
            <td width="170" align="right" valign="top">Requirements</td>
            <td align="left" valign="top"><?php echo $requirements; ?></td>
          </tr>
          <?php } ?>
            <?php $hired_date = ($applicant_details['hired_date']=='0000-00-00' || $applicant_details['hired_date']=='') ? '' : $applicant_details['hired_date'];
			 ?>
          <?php if($hired_date!='') { ?>
            <tr>
            <td width="170" align="right" valign="top">Hired Date</td>
            <td align="left" valign="top"><?php echo Date::convertDateIntIntoDateString($hired_date); ?></td>
          </tr>
         <?php  } ?>
          <?php if($applicant_details['employee_id']!=0) { 
		  	$hash = Utilities::createHash($applicant_details['employee_id']);
			$employee_id = Utilities::encrypt($applicant_details['employee_id']);
		  ?>
            <tr>
            <td width="170" align="right" valign="top">&nbsp;</td>
            <td align="left" valign="top"><a href="<?php echo url('employee/profile?eid='.$employee_id.'&hash='.$hash.'#personal_details'); ?>">View Employee Profile</a></td>
          </tr>
         <?php  } ?>
        </table>
    </td>
    <td width="150" align="center" valign="top">
    <div id="photo_frame_wrapper"><img onclick="javascript:loadPhotoDialog();" src="<?php echo $filename;?>?<?php echo $filemtime; ?>" width="140" border="1"  /></div></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="center" valign="top">&nbsp;</td>
  </tr>
</table>
