<!--<h2 class="field_title"></h2>-->
<?php //include 'form/personal_details_edit.php';?>
<script>
	$(function() {
		load_employee_history_list_dt();
	});
</script>
<div id="personal-details-form"></div>
<div class="employee_summaryholder" id="personal_details_table_wrapper">
  <input type="hidden" id="h_employee_id" name="h_employee_id" value="<?php echo Utilities::encrypt($details->getId()); ?>" />
	<div id="photo_frame_personal_wrapper" class="employee_profile_photo">
	<?php if($permission_action_on_photo == G_Sprint_Modules::PERMISSION_02) { ?>
    	<img onClick="javascript:loadPhotoDialog();" src="<?php echo $filename; ?>?<?php echo $filemtime; ?>" width="140" border="1"  />
        <a class="action_change_photo" href="javascript:void(0);" onClick="javascript:loadPhotoDialog();">Change Picture</a>
    <?php } else { ?>
    	<img src="<?php echo $filename; ?>?<?php echo $filemtime; ?>" width="140" border="1"  />
    <?php } ?>
    </div>
    <div class="employeesummary_details">
         
    <div id="form_main" class="employee_form">
    <div id="form_default">
    	<h3 class="section_title"><?php echo $title_personal_details; ?></h3>
        <table>
          <tr>
            <td class="field_label">Employee Code:</td>
            <td><?php echo $details->employee_code; ?></td>
          </tr>
          <tr>
            <td class="field_label">Employee Device ID:</td>
            <td><?php echo $details->employee_device_id; ?></td>
          </tr>
          <tr>
            <td class="field_label">Salutation:</td>
            <td class="bold"><div id="salutation_label"><?php echo  ucfirst($details->salutation); ?></div></td>
          </tr>
          <tr>
            <td class="field_label">Firstname:</td>
            <td class="bold"><div id="firstname_label"><?php echo ucfirst($details->firstname); ?></div></td>
          </tr>
          <tr>
            <td class="field_label">Lastname:</td>
            <td class="bold"><div id="lastname_label"><?php echo  ucfirst($details->lastname); ?></div></td>
          </tr>
          <tr>
            <td class="field_label">Middlename:</td>
            <td class="bold"><div id="middlename_label"><?php echo  ucfirst($details->middlename); ?></div></td>
          </tr>
          <tr>
            <td class="field_label">Extension Name:</td>
            <td><?php echo  ucfirst($details->extension_name); ?></td>
          </tr>
          <tr>
            <td class="field_label">Nickname:</td>
            <td><?php echo  ucfirst($details->nickname); ?></td>
          </tr>
          <tr>
            <td class="field_label">Gender:</td>
            <td><?php echo ucfirst($details->gender); ?></td>
          </tr>
          <tr>
            <td class="field_label">Birthdate:</td>
             <?php 
             $birthdate = ($details->birthdate=='0000-00-00')? '' : Date::convertDateIntIntoDateString($details->birthdate); ?>
            <td><?php echo $birthdate; ?></td>
          </tr>
          <tr>
            <td class="field_label">Age:</td>
            <td><?php
			if($birthdate){
				$age = Date::get_day_diff($birthdate,date("Y-m-d")); 
				echo $age['years'] . ' yrs old';				
			}
			
			?></td>
          </tr>
          <tr>
            <td class="field_label">Marital Status:</td>
            <td><?php echo ucfirst($details->marital_status); ?></td>
          </tr>
          <tr>
            <td class="field_label">Nationality</td>
            <td><?php echo ucfirst($details->nationality); ?></td>
          </tr>
          <tr>
            <td class="field_label">Confidential:</td>
            <td><?php echo ($details->is_confidential == 1 ? G_Employee::YES : G_Employee::NO ); ?></td>
          </tr>
          <tr>
            <td class="field_label">No. of Dependent(s):</td>
            <td><?php echo $details->number_dependent; ?></td>
          </tr>
          <tr>
            <td class="field_label">Working Days:</td>
            <td><?php echo $details->week_working_days; ?></td>
          </tr>
        </table>
  </div><!-- #form_default -->
  <div class="form_separator"></div>
  <div id="form_default">
  		<h3 class="section_title">Government Contributions</h3>
      <table>          
          <tr>
            <td class="field_label">SSS Number:</td>
            <td><?php echo $details->sss_number; ?></td>
          </tr>
          <tr>
            <td class="field_label">Tin Number:</td>
            <td><?php echo $details->tin_number; ?></td>
          </tr>
           <tr>
             <td class="field_label">Philhealth Number</td>
             <td><?php echo $details->philhealth_number; ?></td>
           </tr>
           <tr>
            <td class="field_label">Pagibig Number</td>
            <td><?php echo $details->pagibig_number; ?></td>
          </tr>         
        </table>
    </div><!-- #form_default -->
    <div class="form_separator"></div>
      <?php if( !empty($dynamic_fields) ){ ?>
        <h3 class="section_title">Other Details</h3>   
          <table>                    
            <?php foreach( $dynamic_fields as $field ){ ?>
              <tr>
                <td class="field_label"><?php echo $field['title']; ?></td>
                <td><?php echo $field['value']; ?></td>
              </tr>  
            <?php } ?>
          </table>
        <div class="form_separator"></div>
      <?php } ?>
    <div id="form_default">
  		<!--<h3 class="section_title">Tags</h3>-->
      <table>          
          <tr>
            <td class="field_label"><i class="icon-tag icon-fade"></i> Tags:</td>
            <td>
			<?php 
				//echo $t ? $t->getTags() : ''; 				
				if($t) {
					$arrtags 	= explode(',',$t->getTags());					
					foreach($arrtags as $t):
					if($t) {
						echo '<span class="label label-info">' . $t . '</span>&nbsp;';
					}						
					endforeach;
				}
			?></td>
          </tr>                      
        </table>
        <div class="form_separator"></div>
         <table>          
        <tr>
          <td class="field_label"><!-- Cost Center: --> Project Site:</td>
           <td><?php echo $project_tag; //$details->cost_center; ?></td>
        </tr>
      </table>
    </div><!-- #form_default -->

    <div class="form_action_section" id="form_default">
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
    </div><!-- .form_action_section -->
    </div><!-- #form_main.inner_form -->
    
    <br />
    <br />
    <div>
        <?php echo $btn_add_history;?>
    </div>
    <div id="employee_history_list_dt_wrapper"></div>
    <!-- HERE -->
	</div>
</div>