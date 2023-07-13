<table>
  <h3 class="section_title">Personal Details</h3>
  <tr>
    <td style="color:#777777; width:170px;">Employee Code:</td>
    <td><?php echo $employee->getEmployeeCode(); ?></td>
  </tr>
  <tr>
    <td style="color:#777777; width:170px;">Salutation:</td>
    <td class="bold"><div id="salutation_label"><?php echo  ucfirst($employee->getSalutation()); ?></div></td>
  </tr>
  <tr>
    <td style="color:#777777; width:170px;">Firstname:</td>
    <td class="bold"><div id="firstname_label"><strong><?php echo ucfirst($employee->getFirstname()); ?></strong></div></td>
  </tr>
  <tr>
    <td style="color:#777777; width:170px;">Lastname:</td>
    <td class="bold"><div id="lastname_label"><strong><?php echo  ucfirst($employee->getLastname()); ?></strong></div></td>
  </tr>
  <tr>
    <td style="color:#777777; width:170px;">Middlename:</td>
    <td class="bold"><div id="middlename_label"><strong><?php echo  ucfirst($employee->getMiddlename()); ?></strong></div></td>
  </tr>
  <tr>
    <td style="color:#777777; width:170px;">Extension Name:</td>
    <td><strong><?php echo  ucfirst($employee->getExtensionName()); ?></strong></td>
  </tr>
  <tr>
    <td style="color:#777777; width:170px;">Nickname:</td>
    <td><?php echo  ucfirst($employee->getNickname()); ?></td>
  </tr>
  <tr>
    <td style="color:#777777; width:170px;">Gender:</td>
    <td><?php echo ucfirst($employee->getGender()); ?></td>
  </tr>
  <tr>
    <td style="color:#777777; width:170px;">Birthdate:</td>
     <?php 
     $birthdate = ($employee->getBirthdate() =='0000-00-00')? '' : Date::convertDateIntIntoDateString($employee->getBirthdate()); ?>
    <td><?php echo $birthdate; ?></td>
  </tr>
  <tr>
    <td style="color:#777777; width:170px;">Age:</td>
    <td><?php
    if($birthdate){
        $age = Date::get_day_diff($birthdate,date("Y-m-d")); 
        echo $age['years'] . ' yrs old';                
    }
    
    ?>
    </td>
  </tr>
  <tr>
    <td style="color:#777777; width:170px;">Marital Status:</td>
    <td><?php echo ucfirst($employee->getMaritalStatus()); ?></td>
  </tr>
  <tr>
    <td style="color:#777777; width:170px;">Nationality</td>
    <td><?php echo ucfirst($employee->getNationality()); ?></td>
  </tr>
  <tr>
    <td style="color:#777777; width:170px;">No. of Dependent(s):</td>
    <td><?php echo $employee->getNumberDependent(); ?></td>
  </tr>
</table>