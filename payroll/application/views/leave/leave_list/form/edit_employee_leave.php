<script>
$(document).ready(function() {
$("#edit_date_applied").datepicker({
	dateFormat:'yy-mm-dd',
	changeMonth:true,
	changeYear:true,
	showOtherMonths:true
});

$("#edit_date_start").datepicker({
	dateFormat:'yy-mm-dd',
	changeMonth:true,
	changeYear:true,
	showOtherMonths:true,
	onSelect	:function() { 
		$("#edit_date_end").datepicker('option',{minDate:$(this).datepicker('getDate')});
		start = $("#edit_date_start").val();
		end = $("#edit_date_end").val();
		output = computeDays(start, end);
		$("#edit_number_of_days").val(output);
	}
});

$("#edit_date_end").datepicker({
	dateFormat:'yy-mm-dd',
	changeMonth:true,
	changeYear:true,
	showOtherMonths:true,
	onSelect	:function() { 
		$("#edit_date_start").datepicker('option',{maxDate:$(this).datepicker('getDate')}); 
		start = $("#edit_date_start").val();
		end = $("#edit_date_end").val();
		output = computeDays(start, end);
		$("#edit_number_of_days").val(output);	

	}
});

$("#leave_request_edit_form").validationEngine({scroll:false});
$('#leave_request_edit_form').ajaxForm({
	success:function(o) {
		if(o==1) {
			dialogOkBox('Successfully Save',{ok_url: 'leave'});
			$("#leave_wrapper").html('');
			loadPage("#leave");
			
		}else {
			dialogOkBox(o,{});	
		}		
	},
	beforeSubmit:function() {
		showLoadingDialog('Saving...');	
	}
});

});


</script>
<div id="employee_view">
	<div class="employee_viewholder">
    	<div class="employee_view_photo"><img src="<?php echo $filename; ?>" alt="Employee Photo" ></div>
        <form id="leave_request_edit_form" name="form1" method="post" action="<?php echo url('leave/_update_leave_request'); ?>" >
           <input type="hidden" name="id" value="<?php echo $details->id ?>" />
           <input type="hidden" name="leave_id" value="<?php echo $details->leave_id ?>" />
           <input type="hidden" name="employee_id" value="<?php echo Utilities::encrypt($details->employee_id); ?>" />
        	<div class="sectionarea">
                <div id="formwrap" class="employee_form_summary">
             
                    <div id="leave_available_table_wrapper">
                      <div id="form_main" class="inner_form">
                        <div id="form_default">
                          <h3 class="section_title">Employee Information</h3>
                          <table width="100%">
                            <tr>
                              	<td class="field_label">Name:</td>                                
                                <td><strong><?php echo $employee['employee_name']; ?></strong></td>
                                <td class="field_label">Position:</td>
                                <td><?php echo $employee['position']; ?></td>
                              </tr>
                              <tr>
                                <td class="field_label">Employee Code:</td>
                                <td><?php echo $employee['employee_code']; ?></td>
                                <td class="field_label">Employment Status:</td>
                                <td><?php echo $employee['employment_status']; ?></td>
                              </tr>
                              <tr>
                                <td class="field_label">Department:</td>
                                <td><?php echo $employee['department']; ?></td>
                                <td class="field_label">Hired Date:</td>
                                <td><?php echo $employee['hired_date']; ?></td>
                              </tr>
                            </table>                          
                        </div><!-- #form_default -->
                      </div><!-- #form_main.inner-form -->
                    </div><!-- #leave_available_table_wrapper -->
                </div><!-- #formwrap.employee_form_summary -->
            </div><!-- .sectionarea -->
            <div class="sectionarea" >
            	<div class="container_12">
                <div class="col_1_2">
                    <div class="inner">
                        <h3 class="section_title">Leave Available</h3>
                            <table width="20" class="formtable">
                              <thead>
                                <tr>
                                  <th width="46" scope="col">Leave Type</th>
                                  <th width="52" scope="col">Alloted <br />
                                  Days</th>
                                  <th width="67" scope="col">Available <br />
                                  Days</th>
                                </tr>
                            </thead>
                              <tbody>
                              <?php 
                              $ctr = 0;
                               foreach($availables as $key=>$e) { ?>
                                <tr>
                                  <td><a href="javascript:void(0);" onclick="javascript:loadLeaveAvailableEditForm('<?php echo $e->id; ?>');">
                                  <?php 
                                  $l = G_Leave_Finder::findById($e->leave_id);
                                  echo $l->name; ?>
                                  </a></td>
                                  <td><?php echo $e->no_of_days_alloted; ?></td>
                                  <td><?php echo $e->no_of_days_available; ?></td>
                                </tr>
                               <?php 
                               $ctr++;
                               }
                        
                              if($ctr==0) { ?>
                                  <tr>
                                  <td colspan="3"><center><i>No Record(s) Found</i></center></td>
                                </tr> 
                                <?php }  ?>
                              </tbody>
                          </table>
                    </div>
                </div>
                <div class="col_1_2">
                    <div class="inner">
                        <h3 class="section_title">Leave Request</h3>
                            <table width="647" class="formtable">
                              <thead>
                                <tr>
                                  <th width="32%" scope="col">Leave Request Id</th>
                                  <th width="32%" scope="col">Leave Type</th>
                                  
                                  <th width="24%" scope="col">Date Start</th>
                                
                                  <th width="10%" scope="col">Is Paid</th>
                                  <th width="5%" scope="col">Status</th>
                                </tr>
                            </thead>
                              <tbody>
                              <?php 
                              $ctr = 0;
                               foreach($all_request as $key=>$e) { ?>
                                <tr>
                                  <td><?php echo $e->id; ?></td>
                                  <td><a href="javascript:void(0);" onclick="javascript:loadLeaveAvailableEditForm('<?php echo $e->id; ?>');">
                                  <?php 
								  $l = G_Leave_Finder::findById($e->leave_id);
                                  echo $l->name; ?>
                                  </a></td>
                                  
                                  <td><?php echo $e->date_start; ?></td>
                                
                                  <td><?php echo $e->is_paid; ?></td>
                                  <td><?php echo $e->is_approved; ?></td>
                                </tr>
                               <?php 
                               $ctr++;
                               }
                        
                              if($ctr==0) { ?>
                                  <tr>
                                  <td colspan="7"><center><i>No Record(s) Found</i></center></td>
                                </tr> 
                                <?php }  ?>
                              </tbody>
                          </table>
                    </div>
                </div>
                <div class="clear"></div>
                </div>
            </div><!-- .sectionarea -->
          <div id="form_main" class="employee_form">
          	<h3 class="section_title">Form Title</h3>
            <div id="form_default">              
              <table>
                 <tr>
                   <td class="field_label">Leave Request Id</td>
                   <td><?php echo $details->id; ?></td>
                 </tr>
                 <tr>
                   <td class="field_label">Leave Type:</td>
                   <td><?php 
                    $l = G_Leave_Finder::findById($details->leave_id);
                      echo $l->name; ?></td>
                </tr>
                 <tr>
                   <td class="field_label">Date Applied:</td>
                   <td><input class="validate[required] text-input" type="text" name="edit_date_applied" id="edit_date_applied" value="<?php echo $details->date_applied; ?>" /></td>
                </tr>
                 <tr>
                   <td class="field_label">Date Start:</td>
                   <td><input onChange="javascript:computeDaysLeaveProfile();" type="text" class="validate[required] text-input" name="edit_date_start" id="edit_date_start" value="<?php echo $details->date_start; ?>" /></td>
                </tr>
                 <tr>
                   <td class="field_label">Date End:</td>
                   <td><input onChange="javascript:computeDaysLeaveProfile();" type="text" class="validate[required] text-input" name="edit_date_end" id="edit_date_end" value="<?php echo $details->date_end; ?>" /></td>
                </tr>
                 <tr>
                   <td class="field_label">Days</td>
                   <td>
                   <!--<div id="edit_number_of_days"></div>-->
                   	<input name="edit_number_of_days" type="text" class="validate[required,custom[integer],min[1]]" id="edit_number_of_days" readonly="readonly" />
                   </td>
                   </tr>
                 <tr>
                   <td class="field_label">Is Paid</td>
                   <td>
                    <?php 
				   if($details->is_paid=='yes') { 
				   	$yes="selected='selected'";
				   }else { 
				   	$no="selected='selected'";
				   }
				    ?>
                   <select class="validate[required]" name="is_paid" id="is_paid">
                     <option <?php echo $yes; ?> value="yes">Yes</option>
                     <option <?php echo $no; ?> value="no">No</option>
                
				</select></td>
                 </tr>
                 <tr>
                   <td class="field_label">Leave Comments:</td>
                   <td><textarea name="leave_comments" id="leave_comments"><?php echo $details->leave_comments; ?></textarea></td>
                </tr>
                 <tr>
                   <td class="field_label">&nbsp;</td>
                   <td><select class="select_option" name="is_approved" id="is_approved">
                   <?php if($details->is_approved==G_Employee_Leave_Request::PENDING) {
                        $pending = 'selected="selected"'; 
                      }else if($details->is_approved==G_Employee_Leave_Request::APPROVED) {
                        $approved = 'selected="selected"';
                      }else if($details->is_approved==G_Employee_Leave_Request::DISAPPROVED) {
                        $disapproved = 'selected="selected"';
                        } ?>
            	<option <?php echo $pending; ?> value="<?php echo G_Employee_Leave_Request::PENDING; ?>"><?php echo G_Employee_Leave_Request::PENDING; ?></option>
                <option <?php echo $approved; ?> value="<?php echo G_Employee_Leave_Request::APPROVED; ?>"><?php echo G_Employee_Leave_Request::APPROVED; ?></option>
                <option <?php echo $disapproved; ?> value="<?php echo G_Employee_Leave_Request::DISAPPROVED; ?>"><?php echo G_Employee_Leave_Request::DISAPPROVED; ?></option>
                    
                     </select></td>
                </tr>
              </table>
            </div><!-- #form_default -->
            <div class="form_action_section" id="form_default">
                <table>
                    <tr>
                        <td class="field_label">&nbsp;</td>
                        <td><input class="blue_button" type="submit" name="button" id="button" value="Update" />&nbsp;<a href="#" onclick="javascript:loadLeaveListDatatable();">Cancel</a></td>
                    </tr>
                </table>
            </div><!-- #form_default.form_action_section -->
          </div><!-- #form_main.employee_form -->
        </form>
        <div class="clear"></div>
    </div>
</div><!-- #employee_view -->
<script>
//computeDaysLeaveProfile();
start = $("#edit_date_start").val();
end = $("#edit_date_end").val();
output = computeDays(start, end);
$("#edit_number_of_days").val(output);
</script>