<script>
$(function(){
	$('#tags').tagsInput({width:'289px'});		
  $(".btn-add-more-details").click(function(){
    var total_rows = $('.other-details-container tr').length;
     $(".other-details-container").append('<tr><td><input type="text" name="other_details[' + (total_rows+1) + '][other_details_label]" placeholder="Label" /></td><td><input type="text" name="other_details[' + (total_rows+1) + '][other_details_value]" placeholder="Value" />&nbsp;<a class="btn btn-small btn-remove-other-detail" href="javascript:void(0);"><i class="icon-remove-sign"></i></a></td></tr>');
    removeOtherDetail();
  });  

  function removeOtherDetail(){
    $(".btn-remove-other-detail").click(function(){        
      $(this).closest("tr").remove();
    });
  }
  removeOtherDetail();
});
$("#birthdate").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,maxDate:'-17Y',showOtherMonths:true});
$("#employee_personal_details_form").validationEngine({scroll:false});
$('#employee_personal_details_form').ajaxForm({
	success:function(o) {
		if(o==1) {
			loadPhoto();
			dialogOkBox('Successfully Updated',{});
			$("#personal_details_wrapper").html('');
			loadPage("#personal_details");
			loadEmployeeSummary();
		}else {
			dialogOkBox(o,{});

		}		
	},
	beforeSubmit:function() {
		showLoadingDialog('Saving...');	
	}
});
</script>
<form name="form1" id="employee_personal_details_form" method="post" action="<?php echo url('employee/_update_personal_details'); ?>">
<input type="hidden" name="employee_id" value="<?php echo Utilities::encrypt($details->id); ?>" />
<input name="photo" type="hidden" id="photo" value="<?php echo $details->getPhoto(); ?>"  />
<div class="employee_summaryholder">
	<div id="photo_frame_personal_edit_wrapper" class="employee_profile_photo">
    	<img onClick="javascript:loadPhotoDialog();" src="<?php echo $filename; ?>?<?php echo $filemtime; ?>" width="140" border="1"  />
        <a class="action_change_photo" href="javascript:void(0);" onClick="javascript:loadPhotoDialog();">Change Picture</a>
    </div>
    <div class="employeesummary_details">
    <div id="form_main" class="employee_form edit_employee_form">
    <div id="form_default">
    	<h3 class="section_title"><?php echo $title_personal_details; ?></h3>
          <table id="personal_details_table">
            <tr>
              <td align="right" class="field_label" valign="top">Employee Code:</td>
              <td valign="top"><input class="validate[required] text-input" type="text" name="employee_code" id="employee_code" value="<?php echo $details->employee_code; ?>" /></td>
            </tr>
            <tr>
              <td align="right" class="field_label" valign="top">Employee Device ID:</td>
              <td valign="top"><input class="validate[optional] text-input" type="text" name="employee_device_id" id="employee_device_id" value="<?php echo $details->employee_device_id; ?>" /></td>
            </tr>
            <tr>
              <td align="right" class="field_label" valign="top">Salutation:</td>
              <td width="241" valign="top"><input type="text" class="text-input" name="salutation" id="salutation" value="<?php echo  ucfirst($details->salutation); ?>" /></td>
            </tr>
            <tr>
              <td align="right" class="field_label" valign="top">Firstname:</td>
              <td valign="top"><input type="text" class="validate[required] text-input"  name="firstname" id="firstname" value="<?php echo ucfirst($details->firstname); ?>" /></td>
            </tr>
            <tr>
              <td align="right" class="field_label" valign="top">Lastname:</td>
              <td valign="top"><input type="text" class="validate[required] text-input"  name="lastname" id="lastname" value="<?php echo  ucfirst($details->lastname); ?>" /></td>
            </tr>
            <tr>
              <td align="right" class="field_label" valign="top">Middlename:</td>
              <td valign="top"><input type="text" class="text-input" name="middlename" id="middlename" value="<?php echo  ucfirst($details->middlename); ?>" /></td>
            </tr>
            <tr>
              <td align="right" class="field_label" valign="top">Extension Name:</td>
              <td valign="top"><input type="text" class="text-input" name="extension_name" id="extension_name" value="<?php echo  ucfirst($details->extension_name); ?>" /> 
                <small><em>(Jr, I, II, III)</em></small></td>
            </tr>
            <tr>
              <td align="right" class="field_label" valign="top">Nickname:</td>
              <td valign="top"><input type="text" class="text-input" name="nickname" id="nickname" value="<?php echo  ucfirst($details->nickname); ?>" /></td>
            </tr>
            <tr>
              <td align="right" class="field_label" valign="top">Gender:</td>
              <td valign="top">
                <select  class="validate[required] select_option"  name="gender" id="gender">
                  <option value="" >-- Select Option --</option>
                  <?php if($details->gender!='') {
                ?><option selected="selected" value="<?php echo $details->gender ?>"><?php echo ucfirst($details->gender); ?></option>
                  <?php	
                } ?>
                  <option value="male">Male</option>
                  <option value="female">Female</option>
              </select></td>
            </tr>
            <tr>
              <td align="right" class="field_label" valign="top">Birthdate:</td>
              <td valign="top">
                <?php $birthdate = ($details->birthdate=='0000-00-00')? '' : $details->birthdate; ?>
              <input type="text" name="birthdate" class="text-input" id="birthdate" value="<?php echo $birthdate; ?>" /></td>
            </tr>
            <tr>
              <td align="right" class="field_label" valign="top">Marital Status:</td>
              <td valign="top">
               <select  class="validate[required] select_option"  name="marital_status" id="marital_status">
                <option value="" >-- Select Option --</option>                
                <?php foreach($GLOBALS['hr']['marital_status'] as $key=>$marital_status) { ?>
                <option <?php echo($details->marital_status == $marital_status ? 'selected="selected"' : ''); ?> value="<?php echo $marital_status; ?>"><?php echo $marital_status; ?></option>
                <?php } ?>
              </select>
             </td>
            </tr>
            <tr>
              <td align="right" class="field_label" valign="top">Nationality</td>
              <td valign="top"><input type="text" name="nationality" id="nationality" /></td>
            </tr>
            <tr>
              <td align="right" class="field_label" valign="top">Confidential</td>
              <td valign="top">
                <select name="is_confidential" id="is_confidential">
                  <option <?php echo ($details->is_confidential == 1 ? "selected='selected'" : "" );?> value="1"><?php echo G_Employee::YES;?></option>
                  <option <?php echo ($details->is_confidential == 0 ? "selected='selected'" : "" );?> value="0"><?php echo G_Employee::NO;?></option>
                </select>

              </td>
            </tr>
            <tr>
              <td align="right" class="field_label" valign="top">No. of Dependent(s):</td>
              <td valign="top"><input type="text" readonly="readonly" class="text-input" name="number_dependent" id="number_dependent" value="<?php echo  $details->number_dependent; ?>" /></td>
            </tr>
            <tr>
              <td align="right" class="field_label" valign="top">Working days in a week:</td>
              <td valign="top">              
                <select name="week_working_days" id="week_working_days">
                  <?php foreach($working_days_options as $option){ ?>
                    <option <?php echo($details->week_working_days == $option['description'] ? 'selected="selected"' : '') ?> value="<?php echo $option['description']; ?>"><?php echo $option['description']; ?></option>                  
                  <?php } ?>
                </select>                
              </td>
            </tr>
          </table>
    </div><!-- #form_default -->
    <div class="form_separator"></div>
    <div id="form_default">
        <h3 class="section_title">Government Contributions</h3>
        <table>            
            <tr>
              <td align="right" class="field_label" valign="top">SSS Number:</td>
              <td valign="top">
              <input type="text" name="sss_number" class="text-input" id="sss_number" placeholder="Format: 00-0000000-0" value="<?php echo $details->sss_number; ?>" />
              ex: 00-0000000-0
              </td>
            </tr>
            <tr>
              <td align="right" class="field_label" valign="top">Tin Number:</td>
              <td valign="top">
              <input type="text" name="tin_number" class="text-input" id="tin_number" placeholder="Format: 000-000-000" value="<?php echo $details->tin_number; ?>"  />
              ex: 000-000-000
              </td>
            </tr>
            <tr>
              <td align="right" class="field_label" valign="top">Pagibig Number</td>
              <td valign="top"><input type="text" class="text-input" name="pagibig_number" id="pagibig_number" placeholder="Format: 0000-0000-0000" value="<?php echo $details->pagibig_number; ?>"  />
              ex: 0000-0000-0000
              </td>
            </tr>
            <tr>
              <td align="right" class="field_label" valign="top">Philhealth Number</td>
              <td valign="top"><input type="text" class="text-input" name="philhealth_number" id="philhealth_number" placeholder="Format: 00-000000000-00" value="<?php echo $details->philhealth_number; ?>"  />
              ex: 00-000000000-00
              </td>
            </tr>
            <?php foreach($field as $key=>$value) { 
            $eid = $details->id;
            $e = G_Employee_Finder::findById($eid);
            $f =  G_Employee_Dynamic_Field_Finder::findBySettingsEmployeeFieldId($value->getId(),$e);
            
            if($f) {
                $title = $f->title;		 
                $val = $f->value;
                $name = 'e_'.$f->id;
            }else {
                $title = $value->title;		 
                $val = '';
                $name = 's_'.$value->id;
            }
              ?>
          
            <tr>
                <td align="right" class="field_label" valign="top"><?php echo ucfirst($title); ?>:</td>
              <td valign="top"><input type="text" class="text-input" name="<?php echo $name; ?>" value="<?php echo $val; ?>" /></td>
          </tr>
          <?php } ?>       
          </table>
    </div><!-- #form_default -->    
    <div class="form_separator"></div>
    <div id="form_default">
        <h3 class="section_title pull-left">Other Details</h3>   
        <a class="btn btn-small pull-right btn-add-more-details" href="javascript:void(0);">Add More Details</a>
        <div class="clear"></div>
        <table class="other-details-container">         
        <?php if( empty($dynamic_fields) ){ ?>
          <?php for($x = 1; $x<=$other_details_counter; $x++){ ?>
            <tr>
              <td>
                <input type="text" name="other_details[<?php echo $x; ?>][other_details_label]" placeholder="Label" />      
              </td>
              <td>
                <input type="text" name="other_details[<?php echo $x; ?>][other_details_value]" placeholder="Value" />                 
              </td>
            </tr>  
          <?php } ?>
        <?php }else{ ?>
          <?php 
            foreach( $dynamic_fields as $key => $field ){ 
              $other_label = $field['title'];
              $other_value = $field['value'];
              $index_key   = $key + 1;
          ?>
            <tr>
              <td>
                <input type="text" name="other_details[<?php echo $index_key; ?>][other_details_label]" placeholder="Label" value="<?php echo $other_label; ?>" />
              </td>
              <td>
                <input type="text" name="other_details[<?php echo $index_key; ?>][other_details_value]" placeholder="Value" value="<?php echo $other_value; ?>" />      
                <?php if($index_key > 1){ ?>     
                  <a class="btn btn-small btn-remove-other-detail" href="javascript:void(0);"><i class="icon-remove-sign"></i></a>
                <?php } ?>
              </td>
            </tr>  
          <?php } ?>
        <?php } ?>

        </table>
        <div class="form_separator"></div>
        <h3 class="section_title">Tags</h3>
        <table>            
            <tr>
              <td align="right" class="field_label" valign="top">Tags:</td>
              <td valign="top">
              <input type="text" value="<?php echo $t ? $t->getTags() : ''; ?>" name="tags" id="tags" />
              </td>
            </tr>               
          </table>
          <div class="form_separator"></div>
        <table>            
            <tr>
              <td align="right" class="field_label" valign="top"><strong><!-- Cost Center: -->Project Site:</strong></td>
              <!-- <td valign="top" colspan="2">
              <input type="text" name="cost_center" class="text-input" id="cost_center" value="<?php echo $details->cost_center; ?>" />
              </td>-->

               <td valign="top" colspan="2">
                  <select name="project_site" id="project_site">
                    <option value="">--Select Project Site--</option>
                        <?php for($i = 0 ; $i < count($project_site); $i++){?>
                            <option value="<?php echo $project_site[$i]['id']; ?>"  <?=($project_site[$i]['id'] == $project_tag) ? 'selected' : '' ?>><?php echo $project_site[$i]['name']; ?></option>
                        <?php }?>               
                  </select>                            
                </td>

            </tr>
        </table>  
    </div><!-- #form_default -->
    
    <div class="form_action_section" id="form_default">
    	<table width="100%" cellspacing="0" cellpadding="0" border="0">
        	<tbody>
            <tr>
            	<td class="field_label">&nbsp;</td>
                <td>
                <input type="submit" class="curve blue_button" name="button" id="button" value="Update" />
                <a href="javascript:void(0);" onclick="javascript:hideEditPersonalDetailsForm();">Cancel</a>
                </td>
            </tr>
        </tbody></table>
    </div><!-- .form_action_section -->
    </div><!-- #form_main.inner_form -->
	</div>
</div>
</form>
<script type='text/javascript'>
  $(function() {	 
 	$('#tags_tag').tipsy({trigger: 'focus',html: true, gravity: 'e'});	 
  });
</script>
