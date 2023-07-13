<img src="<?php echo $filename; ?>?<?php echo $filemtime; ?>" width="140" border="1"  />

<div id="form_default">
    	<h3 class="section_title"><?php echo $title_personal_details; ?></h3>
        <table>
          <tr>
            <td style="color:#777777; width:170px;">Employee Code:</td>
            <td><?php echo $details->employee_code; ?></td>
          </tr>
          <tr>
            <td style="color:#777777; width:170px;">Salutation:</td>
            <td class="bold"><div id="salutation_label"><?php echo  ucfirst($details->salutation); ?></div></td>
          </tr>
          <tr>
            <td style="color:#777777; width:170px;">Firstname:</td>
            <td class="bold"><div id="firstname_label"><strong><?php echo ucfirst($details->firstname); ?></strong></div></td>
          </tr>
          <tr>
            <td style="color:#777777; width:170px;">Lastname:</td>
            <td class="bold"><div id="lastname_label"><strong><?php echo  ucfirst($details->lastname); ?></strong></div></td>
          </tr>
          <tr>
            <td style="color:#777777; width:170px;">Middlename:</td>
            <td class="bold"><div id="middlename_label"><strong><?php echo  ucfirst($details->middlename); ?></strong></div></td>
          </tr>
          <tr>
            <td style="color:#777777; width:170px;">Extension Name:</td>
            <td><strong><?php echo  ucfirst($details->extension_name); ?></strong></td>
          </tr>
          <tr>
            <td style="color:#777777; width:170px;">Nickname:</td>
            <td><?php echo  ucfirst($details->nickname); ?></td>
          </tr>
          <tr>
            <td style="color:#777777; width:170px;">Gender:</td>
            <td><?php echo ucfirst($details->gender); ?></td>
          </tr>
          <tr>
            <td style="color:#777777; width:170px;">Birthdate:</td>
             <?php 
             $birthdate = ($details->birthdate=='0000-00-00')? '' : Date::convertDateIntIntoDateString($details->birthdate); ?>
            <td><?php echo $birthdate; ?></td>
          </tr>
          <tr>
            <td style="color:#777777; width:170px;">Age:</td>
            <td><?php
			if($birthdate){
				$age = Date::get_day_diff($birthdate,date("Y-m-d")); 
				echo $age['years'] . ' yrs old';				
			}
			
			?></td>
          </tr>
          <tr>
            <td style="color:#777777; width:170px;">Marital Status:</td>
            <td><?php echo ucfirst($details->marital_status); ?></td>
          </tr>
          <tr>
            <td style="color:#777777; width:170px;">Nationality</td>
            <td><?php echo ucfirst($details->nationality); ?></td>
          </tr>
          <tr>
            <td style="color:#777777; width:170px;">No. of Dependent(s):</td>
            <td><?php echo $details->number_dependent; ?></td>
          </tr>
        </table>
  </div>
  
  <div id="form_default">
  		<h3 class="section_title">Other Details</h3>
      <table>          
          <tr>
            <td style="color:#777777; width:170px;">SSS Number:</td>
            <td><?php echo $details->sss_number; ?></td>
          </tr>
          <tr>
            <td style="color:#777777; width:170px;">Tin Number:</td>
            <td><?php echo $details->tin_number; ?></td>
          </tr>
           <tr>
             <td style="color:#777777; width:170px;">Philhealth Number</td>
             <td>&nbsp;<?php echo $details->philhealth_number; ?></td>
           </tr>
           <tr>
            <td style="color:#777777; width:170px;">Pagibig Number</td>
            <td><?php echo $details->pagibig_number; ?></td>
          </tr>
          <?php foreach($field as $key=>$value) { 
            $eid = $details->id;
            $e = G_Employee_Finder::findById($eid);
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
          </tr>
          <?php } ?>          
        </table>
    </div>