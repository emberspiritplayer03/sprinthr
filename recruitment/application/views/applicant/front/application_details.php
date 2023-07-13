<script></script>

	<div id="formcontainer">
		<div class="mtshad"></div>
		<div id="formwrap">
		  <div id="form_main">		  
		<?php foreach($application_details as $content): ?>	
        <div id="form_default">   
        		 <h3 class="form_sectiontitle">Application Details</h3>
        		  <table>
        		  	 <tr>
		            <td class="field_label">Name </td>
		            <td>: <?php echo $content['lastname'] . ", " . $content['firstname']; ?></td>
		          </tr>		                   
		          <tr>
		            <td valign="top" class="field_label">Email Address</td>
		            <td>: <?php echo $content['email_address']; ?></td>
		          </tr>
        		  	 <tr>
		            <td valign="top" class="field_label">Application Status</td>
		            <td>: <script> getApplicationStatus(<?php echo $content['application_status_id']; ?>); </script></td>
		          </tr>
        		  </table>    
        		  <hr />
        		  <h3 class="form_sectiontitle">Job Details</h3>
		        <table width="100%" border="0" cellpadding="0" cellspacing="0">
		          <tr>
		            <td class="field_label">Job Title </td>
		            <td>: <?php echo $content['job_title']; ?></td>
		          </tr>
		          <tr>
		            <td class="field_label">Job Description </td>
		            <td>: <?php echo $content['job_description']; ?></td>
		          </tr>
		          <tr>
		            <td valign="top" class="field_label">Date Applied</td>
		            <td>: <?php echo $content['applied_date_time']; ?></td>
		          </tr>
		          <tr>
		            <td valign="top" class="field_label">Hiring Manager</td>
		            <td>: <?php echo $content['hiring_manager_name']; ?></td>
		          </tr>
		          <tr>
		            <td valign="top" class="field_label">Date Posted</td>
		            <td>: <?php echo $content['publication_date']; ?></td>
		          </tr>
		          <tr>
		            <td valign="top" class="field_label">Open Until</td>
		            <td>: <?php echo $content['advertisement_end']; ?></td>
		          </tr>
		        </table>
		        <hr />
		  </div>
		<?php endforeach; ?>    
		<hr />    
			</div>
		</div>
	</div>
	
