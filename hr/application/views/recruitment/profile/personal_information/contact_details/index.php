<?php 
include 'form/contact_details_edit.php';
?>
<div id="contact_details_table_wrapper">
<div id="form_main" class="employee_form">
<div id="form_default">
  <h3 class="section_title"><?php echo $title; ?></h3>
  <table>
  <tr>
    <td class="field_label">Home Telephone:</td>
    <td><?php echo $details->home_telephone; ?></td>
  </tr>
  <tr>
    <td class="field_label">Mobile:</td>
    <td><?php echo $details->mobile; ?></td>
  </tr>
  <tr>
    <td class="field_label">Email Address:</td>
    <td><?php echo $details->email_address; ?></td>
  </tr>
  <tr>
    <td class="field_label">Qualification:</td>
    <td rowspan="3"><?php echo $details->qualification; ?></td>
  </tr>
</table>
</div><!-- #form_default -->
<div class="form_action_section" id="form_default">
    <table>
    	<tr>
            <td class="field_label">&nbsp;</td>
            <td><a onclick="javascript:loadContactDetailsForm();" class="edit_button" href="#contact_details_wrapper"><strong></strong>Edit Details</a></td>
    	</tr>
    </table>
</div><!-- #form_default.form_action_section -->
</div><!-- #form_main.employee_form -->
</div>