<h2 class="field_title"><?php echo $title; ?></h2>

<?php 
include 'form/employment_status_edit.php';
?>
<table id="employment_status_table_wrapper" class="table_form" width="418" border="0" cellpadding="3" cellspacing="3">
  <tr>
    <td width="156" align="right" valign="top">Branch Name:</td>
    <td valign="top"><?php echo $d['branch_name']; ?> <a href="#">view history</a></td>
  </tr>
  <tr>
    <td width="156" align="right" valign="top">Department / Team:</td>
    <td width="241" valign="top"><div id="salutation_label"><?php echo  ucfirst($d['department']); ?> <a href="#">view history</a></div></td>
  </tr>
  <tr>
    <td align="right" valign="top">&nbsp;</td>
    <td valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td width="156" align="right" valign="top">Position:</td>
    <td valign="top"><div id="firstname_label"><?php echo  ucfirst($d['position']); ?></div></td>
  </tr>
  <tr>
    <td align="right" valign="top">Employment Status:</td>
    <td valign="top"><?php echo ucfirst($d['employment_status']); ?></td>
  </tr>
  <tr>
    <td width="156" align="right" valign="top">Job Description:</td>
    <td valign="top"><div id="lastname_label"><?php echo  ucfirst($d['job_description']); ?></div></td>
  </tr>
  <tr>
    <td width="156" align="right" valign="top">Job Duties:</td>
    <td valign="top"><div id="middlename_label"><?php echo  ucfirst($d['job_duties']); ?></div></td>
  </tr>
  <tr>
    <td align="right" valign="top">&nbsp;</td>
    <td valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td width="156" align="right" valign="top">EEO Category:</td>
    <td valign="top"><?php echo  ucfirst($d['job_category_name']); ?></td>
  </tr>
  <tr>
    <td align="right" valign="top">&nbsp;</td>
    <td valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="right" valign="top">Hired Date: </td>
    <?php  $hired_date = ($d['hired_date']=='0000-00-00')? '': Date::convertDateIntIntoDateString($d['hired_date']); ?>
    <td valign="top"><?php echo $hired_date; ?></td>
  </tr>
   <?php $terminated_date = ($d['terminated_date']=='0000-00-00')? '': Date::convertDateIntIntoDateString($d['terminated_date']); ?>
  <?php if($d['terminated_date']!='0000-00-00') { ?>
  <tr>
    <td align="right" valign="top">Terminated Date: </td>
    <td valign="top"><?php echo $terminated_date; ?></td>
  </tr>
  <?php } ?>
  <tr>
    <td align="right" valign="top">&nbsp;</td>
    <td valign="top"><input type="submit" name="button" id="button" value="Edit" onclick="javascript:loadEmploymentStatusEditForm();"  /></td>
  </tr>
</table>