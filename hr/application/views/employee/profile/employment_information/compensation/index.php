<h2 class="field_title"><?php echo $title_compensation; ?></h2>
<div class="section_container">
	<?php include 'form/compensation_edit.php'; ?>
    <div id="compensation_table_wrapper">
    <div id="form_main" class="employee_form">
    <div id="form_default">
    <table>
      <tr>
        <td class="field_label">Pay Rate:</td>
        <td><?php echo $employee_rate->job_level; ?></td>
      </tr>
      <tr>
        <td class="field_label">Minimum Salary:</td>
        <td><?php echo $employee_rate->minimum_salary; ?></td>
      </tr>
      <tr>
        <td class="field_label">Maximum Salary:</td>
        <td><?php echo $employee_rate->maximum_salary; ?></td>
      </tr>
      <tr>
        <td class="field_label">Type:</td>
        <td><?php echo Tools::friendlyTitle($employee_salary->type); ?></td>
      </tr>
      <tr>
        <td class="field_label">Basic Salary:</td>
        <td><?php echo number_format($employee_salary->basic_salary,2); ?></td>
      </tr>
      <tr>
        <td class="field_label">Pay Frequency:</td>
        <td><?php echo $employee_pay_period->pay_period_name; ?></td>
      </tr>
      <tr>
        <td class="field_label">Cut Off</td>
        <td><?php echo $employee_pay_period->cut_off; ?></td>
      </tr>
    </table>
    </div><!-- #form_default -->
    <div class="form_action_section" id="form_default">
    <?php if($can_manage) { ?>
        <table>
            <tr>
                <td class="field_label">&nbsp;</td>
                <td><?php echo $btn_edit_details; ?></td>
            </tr>
        </table>
    <?php } ?>
    </div><!-- #form_default.form_action_section -->
    </div><!-- #form_main.employee_form -->
    </div><!-- #compensation_table_wrapper -->
</div><!-- .section_container -->