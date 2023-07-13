 <div class="employee_summaryholder">
	<div id="photo_frame_wrapper" class="employee_profile_photo">
            <img onclick="javascript:loadPhotoDialog();" src="<?php echo $filename;?>?<?php echo $filemtime; ?>" width="140" alt="Profile Photo"  />
            <!--<a class="action_change_photo" href="javascript:void(0);" onClick="javascript:loadPhotoDialog();">Change Picture</a>-->
    </div>
	<div class="employeesummary_details">
    	<div id="formwrap" class="employee_form_summary">
           
            <div id="form_main" class="inner_form">
                <div id="form_default">
                <div class="action_holder action_holder_right">
                    <div id="dropholder" class="dropright"><a class="gray_button dropbutton" href="javascript:void(0);"><span><span class="dark_gear"></span></span></a>
                    	<div class="dropcontent hide_option" style="display:none;">
                        	<ul><li><a href="#">Hide</a></li></ul>
                        </div>
                    </div>
                </div><!-- .action_holder -->
                <div id="employee_view_startup">
                <a href="javascript:void(0);" onclick="javascript:load_edit_employee();">Edit</a>
                <h3 class="section_title">Summary Employee Information</h3>
               
                <table>
                  <tr>
                    <td class="field_label">Employee Code:</td>
                    <td><?php echo $employee_details['employee_code']; ?></td>
                  </tr>
                  <tr>
                    <td class="field_label">Name:</td>
                    <td class="bold"><div id="employee_name_wrapper"><?php echo $employee_details['salutation']; ?> <?php echo $employee_details['employee_name']; ?></div></td>
                  </tr>
                  <tr>
                    <td class="field_label">Branch: </td>
                    <td><?php echo $employee_details['branch_name']; ?></td>
                  </tr>
                  <tr>
                    <td class="field_label">Department: </td>
                    <td><?php echo $employee_details['department']; ?></td>
                  </tr>
                  <tr>
                    <td class="field_label">Position: </td>
                    <td><?php echo $employee_details['position']; ?></td>
                  </tr>
                    <?php $hired_date = ($employee_details['hired_date']=='0000-00-00' || $employee_details['hired_date']=='') ? '' : $employee_details['hired_date']; ?>
                  <?php if($hired_date!='') { ?>  
                  <tr>
                    <td class="field_label">Employment Status:</td>
                    <td><?php echo $employee_details['employment_status']; ?></td>
                  </tr>
                  <tr>
                    <td class="field_label">Hired Date:</td>          
                    <td><?php echo Date::convertDateIntIntoDateString($hired_date); ?></td>
                  </tr>
                  <tr>
                    <td class="field_label">Tags:</td>          
                    <td><b><?php echo $t ? $t->getTags() : ''; ?></b></td>
                  </tr>
                 <?php  } ?>
                </table>
                	</div><!-- employee_view_startup -->
              
              		    <div id="editemployeesummary_details_startup">
              	 <script>
					$(document).ready(function() {
					/*jQuery("#tags").tagBox();*/
					$('#tags').tagsInput({width:'289px'});		
					$("#hired_date_add_employee").datepicker();
					
						$("#employee_form").validationEngine({scroll:false});
					
						$('#employee_form').ajaxForm({
							success:function(o) {
								if(o==0){
									 dialogOkBox('Please Fill Up the Form Completely',{})
								}else {
								//	employee_id = o;
//									$.post(base_url+"startup/_load_employee_hash",{employee_id:employee_id},
//									function(o){
//										$("#employee_hash").val(o);
//										load_add_employee_confirmation(employee_id);
//									});	
								//window.location.reload();
								}
								
							},
							beforeSubmit:function() {
								showLoadingDialog('Saving...');	
							}
						});
						var t = new $.TextboxList('#supervisor_id', {plugins: {
						autocomplete: {
							minLength: 3,
							onlyFromValues: true,
							queryRemote: true,
							remote: {url: base_url + 'startup/ajax_get_employees_autocomplete'}
						
						}
					}});
						
						
					});
					
					
					</script>
					<div id="formcontainer">
					<div class="mtshad"></div>
					<form id="employee_form"  action="<?php echo url('startup/_update_employee'); ?>" method="post"  name="employee_form" > 
					<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
					<input type="hidden" id="module" name="module" value="<?php echo $module; ?>" />
					<input type="hidden" id="company_structure_id" name="company_structure_id" value="<?php echo $company_structure_id; ?>"  />
                    <input type="hidden" name="eid" id="eid" value="<?php echo Utilities::encrypt($employee_details['id']);?>" />
					<div id="formwrap">	
						<h3 class="form_sectiontitle">Edit Employee</h3>
					<div id="form_main">
						<h3 class="section_title"><span>Employment Information</span></h3>
						<div id="form_default">      
						  <table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
							  <td align="left" valign="top" class="field_label">Branch:</td>
							  <td align="left" valign="top">
							  <div id="branch_dropdown_wrapper">
							  <select class="validate[required] select_option" name="branch_id" id="branch_id" onchange="javascript:checkForAddBranch();">
								<option value="" selected="selected">-- Select Branch --</option>
									<?php foreach($branches as $key=>$value) { ?>
										<option <?php if($employee_details['branch_id']==$value->id){?> selected="selected" <?php }?> value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
									<?php } ?>
							  </select>
							 </div> 
							 </td>
							</tr>
							<tr>
							  <td align="left" valign="top" class="field_label">Department:</td>
							  <td align="left" valign="top">
							  <div id="department_dropdown_wrapper">
							  <select class="validate[required] select_option" name="department_id" id="department_id" onchange="javascript:checkForAddDepartment();">
								  <option value="" selected="selected">-- Select Department --</option>
									<?php foreach($departments as $key=>$value) { ?>
										<option <?php if($employee_details['department_id']==$value->id){?> selected="selected" <?php }?>  value="<?php echo $value->id; ?>"><?php echo $value->title; ?></option>
									<?php } ?>
							  </select>
							 </div> 
							 </td>
							</tr>
							<tr>
							  <td align="left" valign="top" class="field_label">Position:</td>
							  <td align="left" valign="top">
							  <div id="position_dropdown_wrapper">
							  <select class="validate[required] select_option" name="position_id" id="position_id"  onchange="javascript:checkForAddPosition();">
							  <option value="" selected="selected">-- Select Position --</option>
								<?php foreach($positions as $key=>$value) { ?>
									<option  <?php if($employee_details['position']==$value->title){?> selected="selected" <?php }?>  value="<?php echo $value->id; ?>"><?php echo $value->title; ?></option>
								<?php } ?>
							 </select>
							  </div>
							  </td>
							</tr>
							<tr>
							  <td align="left" valign="top" class="field_label">Employment Status:</td>
							  <td align="left" valign="top">
							  <div id="status_dropdown_wrapper">
							  <select class="validate[required] select_option" name="employment_status_id" id="employment_status_id" onchange="javascript:checkForAddStatus();">
							  <option value="" selected="selected" >-- Select Employment Status --</option>
								<?php foreach($employement_status as $key=>$value) { ?>
								<option <?php if($employee_details['employment_status']==$value->status){?> selected="selected" <?php }?> value="<?php echo $value->id;  ?>"><?php echo $value->status; ?></option>
								<?php } ?>
							  <option value="0" >Terminated</option>
							  </select>
							  </div>
							  </td>
							</tr>
							<tr>
							  <td align="left" valign="top" class="field_label">Supervisor / Manager:</td>
							  <td align="left" valign="top"><input type="text" name="supervisor_id" id="supervisor_id" /></td>
							</tr>
							<tr>
							  <td align="left" valign="top" class="field_label">Tags:</td>
							  <td align="left" valign="top">
								<!--<label id="tag-tipsy">
								<input type="text" id="tags" name="tags" />
								</label>-->
								<input type="text" value="<?php echo $t; ?>" name="tags" id="tags" />
							  </td>
							</tr>
						  </table>
						</div>
						<div class="form_separator"></div>
						<h3 class="section_title"><span>Personal Information</span></h3>
						<div id="form_default">      
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
							  <tr>
								<td align="left" valign="top" class="field_label">*Employee ID:</td>
								<td align="left" valign="top"><input name="employee_code" type="text" class="validate[required] text-input text" id="employee_code" value="<?php echo $employee_details['employee_code']; ?>" /></td>
								</tr>
							  <tr>
								<td align="left" valign="top" class="field_label">*Firstname:</td>
								<td align="left" valign="top"><input type="text" value="<?php echo $employee_details['firstname']; ?>" name="firstname" class="validate[required] text-input text" id="firstname" /></td>
								</tr>    
							  <tr>
								<td align="left" valign="top" class="field_label">*Lastname:</td>
								<td align="left" valign="top"><input type="text" value="<?php echo $employee_details['lastname']; ?>" name="lastname" class="validate[required] text-input text" id="lastname" /></td>
								</tr>
							  <tr>
								<td align="left" valign="top" class="field_label">*Hired Date:</td>
								<td align="left" valign="top"><input type="text" value="<?php echo $employee_details['hired_date']; ?>" type="text"  name="hired_date" class="validate[required] text-input text" id="hired_date_add_employee" /> </td>
							  </tr>
							</table>
						</div>
				
						<div id="form_default" class="form_action_section">
							<table width="100%" border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td class="field_label">&nbsp;</td>
									<td>
									<input type="submit" value="Update Employee" class="curve blue_button" />
									<a href="javascript:void(0)" onclick="javascript:cancel_edit_employee_form();">Cancel</a>
									</td>
								</tr>
							</table>
						</div>
					</div><!-- #form_main -->
					</div><!-- #formwrap -->
					
					</form>
					</div>
					<div id="error_message"></div>
					<script type='text/javascript'>
					  $(function() {	 
						$('#tags_tag').tipsy({trigger: 'focus',html: true, gravity: 'e'});	 
					  });
					  cancel_edit_employee_form();
					</script>

              
              
              </div><!-- editemployeesummary_details_startup -->
              
                </div><!-- #form_default -->
                     
            </div><!-- #form_main -->
		</div><!-- #formwrap -->
    </div>
    <div class="clear"></div>
</div>