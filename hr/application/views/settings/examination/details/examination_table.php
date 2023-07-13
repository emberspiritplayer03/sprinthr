<div class="section_container">
<div class="employee_form" id="form_main">
  <div id="form_default">
      <table>          
          <tbody><tr>
            <td class="field_label">Title:</td>
            <td><strong><?php echo $details->title; ?></strong></td>
          </tr>
          <tr>
            <td class="field_label">Applicable to job(s):</td>
            <td><b><?php echo $applicable_to_jobs; ?></b></td>
          </tr>
          <tr>
            <td class="field_label">Description:</td>
            <td><?php echo $details->description; ?></td>
          </tr>
           <tr>
             <td class="field_label">Passing Percentage:</td>
             <td><?php echo $details->passing_percentage; ?>%</td>
           </tr>
           <tr>
            <td class="field_label">Time Duration:</td>
            <td><?php echo $details->time_duration; ?> <i class="icon-time icon-fade"></i></td>
          </tr>
                   
          <tr>
            <td class="field_label">Created by:</td>
            <td><?php echo $details->created_by; ?></td>
          </tr>
                   
          <tr>
            <td class="field_label">Date Created:</td>
            <td><?php echo Date::convertDateIntIntoDateString($details->date_created); ?> <i class="icon-calendar icon-fade"></i></td>
          </tr>
                    
        </tbody></table>
    </div><!-- #form_default -->
    <div id="form_default" class="form_action_section">
    	<table width="100%" cellspacing="0" cellpadding="0" border="0">
        	<tbody>
            <tr>
            	<td class="field_label">&nbsp;</td>
                <td><a onclick="javascript:loadExaminationDetailsForm();" class="edit_button" href="#personal_details"><strong></strong>Edit Details</a></td>
            </tr>
        </tbody></table>
    </div><!-- .form_action_section -->
</div>
</div>