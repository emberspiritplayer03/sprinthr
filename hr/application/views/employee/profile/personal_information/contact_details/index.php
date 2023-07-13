<!--<h2 class="field_title"><?php echo $title_contact_details; ?></h2>-->
<?php  include 'form/contact_details_edit.php'; ?>
<div id="contact_details_table_wrapper">
<div id="form_main" class="employee_form">
	<h3 class="section_title">Contact Details</h3>
	<div class="col_1_2">
    <div id="form_default" class="form_col_1_2">    	
        <table>
          <tr>
            <td class="field_label">Address:</td>
            <td><?php echo $details->address; ?></td>
          </tr>
          <tr>
            <td class="field_label">City:</td>
            <td><?php echo  ucfirst($details->city); ?></td>
          </tr>
          <tr>
            <td class="field_label">Province:</td>
            <td><?php echo ucfirst($details->province); ?></td>
          </tr>
          <tr>
            <td class="field_label">Zip Code:</td>
            <td><?php echo  ucfirst($details->zip_code); ?></td>
          </tr>
          <tr>
            <td class="field_label">Country:</td>
            <td><?php echo  ucfirst($details->country); ?></td>
          </tr>
          <tr>
            <td class="field_label">Home Telephone:</td>
            <td><?php echo  ucfirst($details->home_telephone); ?></td>
          </tr>
        </table>
    </div><!-- #form_default -->
    </div><!-- .col_1_2 -->
    <div class="col_1_2">
        <div id="form_default" class="form_col_1_2">
            <table>
                <tr>
                <td class="field_label">Mobile:</td>
                <td><?php echo ucfirst($details->mobile); ?></td>
              </tr>
              <tr>
                <td class="field_label">Work Telephone:</td>
                <td><?php echo $details->work_telephone; ?></td>
              </tr>
              <tr>
                <td class="field_label">Work Email:</td>
                <td><?php echo $details->work_email; ?></td>
              </tr>
              <tr>
                <td class="field_label">Other Email:</td>
                <td><?php echo $details->other_email; ?></td>
              </tr>
               <?php foreach($field as $key=>$value) { 
                $employee_id = $details->employee_id;
                $e = G_Employee_Finder::findById($employee_id);
                
                $f =  G_Employee_Dynamic_Field_Finder::findBySettingsEmployeeFieldId($value->getId(),$e);
                if($f) {
                    $title = $f->title;		 
                    $value = $f->value;
                }else {
                    $title = $value->title;		 
                    $value = '';
                }
              ?>
              <tr>
                <td class="field_label"><?php echo ucfirst($title); ?>:</td>
                <td><?php echo $value; ?></td>
                <td valign="top">&nbsp;</td>
              </tr>
              <?php } ?>
            </table>
        </div><!-- #form_default -->
    </div><!-- .col_1_2 -->
    <div class="clearleft"></div>
    <div id="form_default" class="form_action_section form_col_1_2">
    	<table width="100%" cellspacing="0" cellpadding="0" border="0">
        	<tbody>
            <tr>
            	<td class="field_label">&nbsp;</td>
            	<td>
              <?php echo $btn_edit_details; ?>
                </td>
          	</tr>
        	</tbody>
        </table>
    </div>
</div><!-- #form_main.employee_form -->
</div><!-- #contact_details_table_wrapper -->