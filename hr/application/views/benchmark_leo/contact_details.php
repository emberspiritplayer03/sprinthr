<?php include('includes/employee_summary.php'); ?>

<h3 class="section_title">Contact Details</h3>
	<div class="col_1_2">
    <div id="form_default" class="form_col_1_2">    	
        <table>
          <tr>
            <td style="color:#777777; width:170px;">Address:</td>
            <td><?php echo $details->address; ?></td>
          </tr>
          <tr>
            <td style="color:#777777; width:170px;">City:</td>
            <td><?php echo  ucfirst($details->city); ?></td>
          </tr>
          <tr>
            <td style="color:#777777; width:170px;">Province:</td>
            <td><?php echo ucfirst($details->province); ?></td>
          </tr>
          <tr>
            <td style="color:#777777; width:170px;">Zip Code:</td>
            <td><?php echo  ucfirst($details->zip_code); ?></td>
          </tr>
          <tr>
            <td style="color:#777777; width:170px;">Country:</td>
            <td><?php echo  ucfirst($details->country); ?></td>
          </tr>
          <tr>
            <td style="color:#777777; width:170px;">Home Telephone:</td>
            <td><?php echo  ucfirst($details->home_telephone); ?></td>
          </tr>
        </table>
    </div><!-- #form_default -->
    </div><!-- .col_1_2 -->
    <div class="col_1_2">
        <div id="form_default" class="form_col_1_2">
            <table>
                <tr>
                <td style="color:#777777; width:170px;">Mobile:</td>
                <td><?php echo ucfirst($details->mobile); ?></td>
              </tr>
              <tr>
                <td style="color:#777777; width:170px;">Work Telephone:</td>
                <td><?php echo $details->work_telephone; ?></td>
              </tr>
              <tr>
                <td style="color:#777777; width:170px;">Work Email:</td>
                <td><?php echo $details->work_email; ?></td>
              </tr>
              <tr>
                <td style="color:#777777; width:170px;">Other Email:</td>
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
                <td style="color:#777777; width:170px;"><?php echo ucfirst($title); ?>:</td>
                <td><?php echo $value; ?></td>
                <td valign="top">&nbsp;</td>
              </tr>
              <?php } ?>
            </table>
        </div><!-- #form_default -->
    </div><!-- .col_1_2 -->