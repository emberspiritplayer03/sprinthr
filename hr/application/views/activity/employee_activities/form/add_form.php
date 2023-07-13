<style>
.leave-header{padding:4px;background-color: #198cc9;color:#ffffff;margin-top:9px;line-height: 27px;}
</style>
<div id="formcontainer">
<form id="add_employee_activity_form" name="add_employee_activity_form" action="<?php echo url('activity/_save_employee_activity'); ?>" method="post"> 
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<div id="formwrap">	
	<h3 class="form_sectiontitle">Schedule Activities</h3>
<div id="form_main">     
  
    <div id="form_default">      
        <table>        	 
             <tr>
               <td class="field_label">Type Employee Name:</td>
               <td>
               		<input class="validate[required] input-large" type="text" name="employee_id" id="employee_id" value="" />
               </td>
             </tr> 
             <tr>
               <td class="field_label">Project Site:</td>
               <td>
          			<div id="project_dropdown_wrapper">
						<select class="validate[required] select_option" name="project_site_id" id="project_site_id" >
							<option value="" selected="selected">-- Select Project Site --</option>
							
							<?php foreach($project_sites as $key=>$value) { ?>
								<option value="<?php echo $value->getId(); ?>"><?php echo $value->getName(); ?></option>
							<?php } ?>
						
						</select>
					</div>
               </td>
             </tr>   

             <tr>
               <td class="field_label">Designation:</td>
               <td>
          			<div id="category_dropdown_wrapper">
						<select class="validate[required] select_option" name="category_id" id="category_id" >
							<option value="" selected="selected">-- Select Designation --</option>
							<?php foreach($activity_categories as $key=>$value) { ?>
								<option value="<?php echo $value->id; ?>"><?php echo $value->activity_category_name; ?></option>
							<?php } ?>
							<option value="add">Add Designation...</option>
						</select>
					</div>
               </td>
             </tr>   	 
             <tr>
               <td class="field_label">Activity:</td>
               <td>
          			<div id="activity_dropdown_wrapper">
						<select class="validate[required] select_option" name="activity_id" id="activity_id" >
							<option value="" selected="selected">-- Select Activity --</option>
							<?php foreach($activity_skills as $key=>$value) { ?>
								<option value="<?php echo $value->id; ?>"><?php echo $value->activity_skills_name; ?></option>
							<?php } ?>
							<option value="add">Add Activity...</option>
						</select>
					</div>
               </td>
             </tr>
        </table>
        <h3 class="leave-header">Activity Details</h3> 
        <table>
             <tr>
               <td class="field_label">Date:</td>
               <td>
               		<input class="validate[required] input-small" type="text" name="activity_date" id="activity_date" value="" />
               </td>
             </tr>   
             <tr>
               <td class="field_label">Time:</td>
               <td>
               		<input class="validate[required] input-small" type="text" name="time_in" id="time_in" value="" placeholder="Starts on" />
               		<input class="validate[required] input-small" type="text" name="time_out" id="time_out" value="" placeholder="Ends on" />
               </td>
             </tr>        
             <tr>
               <td class="field_label">Reason:</td>
               <td>
               		<textarea class="input-large" rows="3" id="reason" name="reason"></textarea>               		
               </td>
             </tr>                                  
         </table>
    </div>
    <div id="form_default" class="form_action_section">
    	<table width="100%" border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td>
                <input type="submit" value="Save" class="curve blue_button" />
                <a href="javascript:void(0)" onclick="javascript:hide_add_activity_form();">Cancel</a>
                </td>
            </tr>
        </table>
    </div>
</div><!-- #form_main -->
</div>
</form>
</div>

<script>
	$(document).ready(function() {		
		addEmployeeActivityActionScripts();

		$('#add_employee_activity_form').validationEngine({scroll:false});	

		$('#add_employee_activity_form').submit(function(e) {
			e.preventDefault();
			
			if ($('#add_employee_activity_form').validationEngine('validate')) {
				showLoadingDialog('Saving...');

				var data = {
					employee_id:$('#add_employee_activity_form #employee_id').val(),
					activity_date:$('#add_employee_activity_form #activity_date').val(),
					time_in:$('#add_employee_activity_form #time_in').val(),
					time_out:$('#add_employee_activity_form #time_out').val()
				}

				$.ajax({
					url: base_url + 'activity/_check_employee_dtr_logs',
					type: "POST",
					data: data,
					dataType: "json",
					success: function(data){
						closeDialog('#' + DIALOG_CONTENT_HANDLER);	
						
						if (data.is_invalid) {
							dialogOkBox(data.message,{});						
						}
						else {
							if (data.message != '') {
								showYesNoDialog('<div class="confirmation-box"><div>' + data.message, {
									width: 'auto',
									height: 'auto',
									onYes: function(o) {
										submitAddEmployeeActivityForm(data.token);
									}
								});
							}
							else {
								submitAddEmployeeActivityForm(data.token);
							}
						}
					}
				})
			}
		});
		
		$("#activity_date").datepicker({
			dateFormat:'yy-mm-dd',
			changeMonth:true,
			changeYear:true,
			showOtherMonths:true
		});	 

		$('#time_in').timepicker({
			'minTime': '8:00 am',
			'maxTime': '7:30 am',
			'timeFormat': 'g:i a',           
		});

		$('#time_out').timepicker({
			'minTime': '8:00 am',
			'maxTime': '7:30 am',
			'timeFormat': 'g:i a'
		});
		
		var t = new $.TextboxList('#employee_id', {
			unique: true,
			max:1,
			plugins: {
				autocomplete: {
					minLength: 2,				
					onlyFromValues: true,
					queryRemote: true,
					remote: {url: base_url + 'ob/ajax_get_employees_autocomplete'}			
				}
		}});

		function submitAddEmployeeActivityForm(token = '') {
			if (token != '') {
				$('#add_employee_activity_form #token').val(token);
			}

			$('#add_employee_activity_form').ajaxSubmit({
				success:function(o) {
					if (o.is_saved) {
						load_employee_activities_list_dt();		
						hide_add_activity_form();
						closeDialog('#' + DIALOG_CONTENT_HANDLER);	
						dialogOkBox(o.message,{});						
					} else {
						hide_add_activity_form();
						closeDialog('#' + DIALOG_CONTENT_HANDLER);	
						dialogOkBox(o.message,{});			
					}
				},
				dataType:'json',
				beforeSubmit: function() {
					showLoadingDialog('Saving...');
				}
			});	
		}
		
	});
</script>