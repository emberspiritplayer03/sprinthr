<h2 class="field_title"><?php echo $title_employment_status; ?></h2>
<?php  include 'form/employment_status_edit.php'; ?>
<div id="employment_status_table_wrapper">
<div id="form_main" class="employee_form">
<div id="form_default">
<table>
  <tr>
    <td class="field_label">Branch Name:</td>
    <td><?php echo $d['branch_name']; ?></td>
  </tr>
  <tr>
    <td class="field_label">Department / Team:</td>
    <td><div id="salutation_label"><?php echo  ucfirst($d['department']); ?></div></td>
  </tr>
</table>
</div><!-- #form_default -->
<div class="form_separator"></div>
<div id="form_default">
<table>
  <tr>
    <td class="field_label">Position:</td>
    <td><div id="firstname_label"><?php echo  ucfirst($d['position']); ?></div></td>
  </tr>
  <tr>
    <td class="field_label">Employment Status:</td>
    <td><?php echo ucfirst($d['employment_status']); ?></td>
  </tr>
  <tr>
    <td class="field_label">Job Description:</td>
    <td><div id="lastname_label"><?php echo  ucfirst($d['job_description']); ?></div></td>
  </tr>
  <tr>
    <td class="field_label">Job Duties:</td>
    <td><div id="middlename_label"><?php echo  ucfirst($d['job_duties']); ?></div></td>
  </tr>
</table>
</div><!-- #form_default -->
<div class="form_separator"></div>
<div id="form_default">
<table>
  <tr>
    <td class="field_label">EEO Category:</td>
    <td><?php echo  ucfirst($d['job_category_name']); ?></td>
  </tr>
  <tr>
    <td class="field_label">Hired Date: </td>
    <?php  $hired_date = ($d['hired_date']=='0000-00-00')? '': Date::convertDateIntIntoDateString($d['hired_date']); ?>
    <td><?php echo $hired_date; ?></td>
  </tr>
   <?php $terminated_date = ($d['terminated_date']=='0000-00-00')? '': Date::convertDateIntIntoDateString($d['terminated_date']); ?>
   <?php if($d['terminated_date']!='0000-00-00') { ?>
  <tr>
    <td class="field_label">Terminated Date: </td>
    <td><?php echo $terminated_date; ?></td>
  </tr>
  <tr>
    <td class="field_label">Reason:</td>
    <td><?php echo $terminated_memo; ?></td>
  </tr>
  <?php } ?>
</table>
</div><!-- #form_default -->
<div class="form_action_section" id="form_default">
    <table>
    	<tr>
            <td class="field_label">&nbsp;</td>
            <td><a onclick="javascript:loadEmploymentStatusEditForm();" class="edit_button" href="#employment_status"><strong></strong>Edit Details</a></td>
    	</tr>
    </table>
</div><!-- #form_default.form_action_section -->
</div><!-- #form_main.employee_form -->
</div>