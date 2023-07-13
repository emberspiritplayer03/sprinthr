<?php
  if($ec_emp){
    $address = $c_emp->getAddress();
    $city = $c_emp->getCity();
    $province = $c_emp->getProvince();
    $zipcode = $c_emp->getZipCode();
    $country = $c_emp->getCountry();
    $home_telephone = $c_emp->getHomeTelephone();

    $mobile =  $c_emp->getMobile();
    $work_telephone = $c_emp->getWorkTelephone();
    $work_email = $c_emp->getWorkEmail();
    $other_email =  $c_emp->getOtherEmail();

  }
?>
<h3>Contact Details</h3>
  <div class="col_1_2">
    <div id="form_default" class="form_col_1_2">      
        <table>
          <tr>
            <td style="color:#777777;">Address:</td>
            <td><?php echo $address; ?></td>
          </tr>
          <tr>
            <td style="color:#777777;">City:</td>
            <td><?php echo  ucfirst($city); ?></td>
          </tr>
          <tr>
            <td style="color:#777777;">Province:</td>
            <td><?php echo ucfirst($province); ?></td>
          </tr>
          <tr>
            <td style="color:#777777;">Zip Code:</td>
            <td><?php echo  ucfirst($zipcode); ?></td>
          </tr>
          <tr>
            <td style="color:#777777;">Country:</td>
            <td><?php echo  ucfirst($country); ?></td>
          </tr>
          <tr>
            <td style="color:#777777;">Home Telephone:</td>
            <td><?php echo  ucfirst($home_telephone); ?></td>
          </tr>
        </table>
    </div><!-- #form_default -->
    </div><!-- .col_1_2 -->
    <div class="col_1_2">
        <div id="form_default" class="form_col_1_2">
            <table>
                <tr>
                <td style="color:#777777;">Mobile:</td>
                <td><?php echo ucfirst($mobile); ?></td>
              </tr>
              <tr>
                <td style="color:#777777;">Work Telephone:</td>
                <td><?php echo $work_telephone; ?></td>
              </tr>
              <tr>
                <td style="color:#777777;">Work Email:</td>
                <td><?php echo $work_email; ?></td>
              </tr>
              <tr>
                <td style="color:#777777;">Other Email:</td>
                <td><?php echo $other_email; ?></td>
              </tr>
               <?php foreach($field as $key=>$value) { 
                $employee_id = $contact_details->employee_id;
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
                <td style="color:#777777;"><?php echo ucfirst($title); ?>:</td>
                <td><?php echo $value; ?></td>
                <td valign="top">&nbsp;</td>
              </tr>
              <?php } ?>
            </table>
        </div><!-- #form_default -->
    </div><!-- .col_1_2 -->