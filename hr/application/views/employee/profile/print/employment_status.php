<?php include('includes/employee_summary.php'); ?>

<h3 class="section_title">Employment Status</h3>
<div id="form_main" class="employee_form">
<div id="form_default">
<table>
  <tr>
    <td style="color:#777777; width:170px;">Branch Name:</td>
    <td><?php echo $d['branch_name']; ?></td>
  </tr>
  <tr>
    <td style="color:#777777; width:170px;">Department / Team:</td>
    <td><div id="salutation_label"><?php echo  ucfirst($d['department']); ?></div></td>
  </tr>
</table>
</div><!-- #form_default -->
<div class="form_separator"></div>
<div id="form_default">
<table>
  <tr>
    <td style="color:#777777; width:170px;">Position:</td>
    <td><div id="firstname_label"><?php echo  ucfirst($d['position']); ?></div></td>
  </tr>
  <tr>
    <td style="color:#777777; width:170px;">Employment Status:</td>
    <td><?php echo ucfirst($d['employment_status']); ?></td>
  </tr>
  <tr>
    <td style="color:#777777; width:170px;">Job Description:</td>
    <td><div id="lastname_label"><?php echo  ucfirst($d['job_description']); ?></div></td>
  </tr>
  <tr>
    <td style="color:#777777; width:170px;">Job Duties:</td>
    <td><div id="middlename_label"><?php echo  ucfirst($d['job_duties']); ?></div></td>
  </tr>
</table>
</div><!-- #form_default -->
<div class="form_separator"></div>
<div id="form_default">
<table>
  <tr>
    <td style="color:#777777; width:170px;">EEO Category:</td>
    <td><?php echo  ucfirst($d['job_category_name']); ?></td>
  </tr>
  <tr>
    <td style="color:#777777; width:170px;">Hired Date: </td>
    <?php  $hired_date = ($d['hired_date']=='0000-00-00')? '': Date::convertDateIntIntoDateString($d['hired_date']); ?>
    <td><?php echo $hired_date; ?></td>
  </tr>
   <?php $terminated_date = ($d['terminated_date']=='0000-00-00')? '': Date::convertDateIntIntoDateString($d['terminated_date']); ?>
   <?php if($d['terminated_date']!='0000-00-00') { ?>
  <tr>
    <td style="color:#777777; width:170px;">Terminated Date: </td>
    <td><?php echo $terminated_date; ?></td>
  </tr>
  <tr>
    <td style="color:#777777; width:170px;">Reason:</td>
    <td><?php echo $terminated_memo; ?></td>
  </tr>
  <?php } ?>
</table>
</div><!-- #form_default -->
</div>

<?php include('includes/subdivision_history.php'); ?>
<?php include('includes/job_history.php'); ?>




