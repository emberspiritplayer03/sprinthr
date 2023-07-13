<?php ob_start();?>
<style type="text/css">
.font-size {
	font-size: 8pt!important;
}
</style>
<table class="tbl-border" width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr>           
    <td bgcolor="#CCCCCC"><b>Employee ID</b></td>
    <td bgcolor="#CCCCCC"><b>Firstname</b></td>
    <td bgcolor="#CCCCCC"><b>Lastname</b></td>
    <td bgcolor="#CCCCCC"><b>Middlename</b></td>

    <td bgcolor="#CCCCCC"><b>Hired Date</b></td>
    <td bgcolor="#CCCCCC"><b>Salary Amount</b></td>
    <td bgcolor="#CCCCCC"><b>Salary Type (Daily, Monthly)</b></td>
    <td bgcolor="#CCCCCC"><b>Number of Dependent</b></td>
    <td bgcolor="#CCCCCC"><b>Department</b></td>

     <td bgcolor="#CCCCCC"><b>Project Site</b></td>

    <td bgcolor="#CCCCCC"><b>Position</b></td>
    <td bgcolor="#CCCCCC"><b>Employment Status</b></td>
    <td bgcolor="#CCCCCC"><b>Birthdate</b></td>
    <td bgcolor="#CCCCCC"><b>Marital Status</b></td>
    <td bgcolor="#CCCCCC"><b>Gender</b></td>
    <td bgcolor="#CCCCCC"><b>SSS Number</b></td>
    <td bgcolor="#CCCCCC"><b>Pagibig Number</b></td>
    <td bgcolor="#CCCCCC"><b>Philhealth Number</b></td>
    <td bgcolor="#CCCCCC"><b>Tin Number</b></td>
    <td bgcolor="#CCCCCC"><b>Address</b></td>
    <td bgcolor="#CCCCCC"><b>City</b></td>
    <td bgcolor="#CCCCCC"><b>Province</b></td>
    <td bgcolor="#CCCCCC"><b>Zipcode</b></td>
    <td bgcolor="#CCCCCC"><b>Home Telephone</b></td>
    <td bgcolor="#CCCCCC"><b>Mobile</b></td>
    <td bgcolor="#CCCCCC"><b>Personal Email</b></td>
    <td bgcolor="#CCCCCC"><b>Work Telephone</b></td>
    <td bgcolor="#CCCCCC"><b>Work Email</b></td>
    <td bgcolor="#CCCCCC"><b>Bank Name</b></td>
    <td bgcolor="#CCCCCC"><b>Account Number</b></td>
    <td bgcolor="#CCCCCC"><b>Extension Name</b></td>
    <td bgcolor="#CCCCCC"><b>Nickname</b></td>
    <td bgcolor="#CCCCCC"><b>Section</b></td>
    <td bgcolor="#CCCCCC"><b>Confidential( Yes, No)</b></td>
    <td bgcolor="#CCCCCC"><b>Working Days (5DW / 6DW / 7DW / 7DWS)</b></td>
    <td bgcolor="#CCCCCC"><b>Other Details</b></td>
    <td bgcolor="#CCCCCC"><b>Employee Status</b></td>
    <td bgcolor="#CCCCCC"><b>Nationality</b></td>    
    <td bgcolor="#CCCCCC"><b>Tags</b></td>    
    <td bgcolor="#CCCCCC"><b>Emergency Contact</b></td>    
    <td bgcolor="#CCCCCC"><b>Education</b></td>       
    <td bgcolor="#CCCCCC"><b>Tags</b></td>       
  </tr>
	<?php 
	foreach ($emp as $employee) {
		$employee_id = $employee->getId();


		$personal_details 		= G_Employee_Finder::findById($employee_id);
		$contact_details 		= G_Employee_Contact_Details_Finder::findByEmployeeId($employee_id);
		$contacts 				= G_Employee_Emergency_Contact_Finder::findByEmployeeId($employee_id);

        $a_contacts = array();
        $s_contacts = "";
        foreach($contacts as $key => $e) {
            $a_contacts[] = $e->getPerson() . "|" . $e->relationship . "|" . $e->address . "|" . "Landline : " . $e->home_telephone . ", Mobile : " . $e->mobile . ", Work Telephone : " . $e->work_telephone;
        }
        $s_contacts = implode(",", $a_contacts);

		$dependents 			= G_Employee_Dependent_Finder::findByEmployeeId($employee_id);
		$banks 					= G_Employee_Direct_Deposit_Finder::findByEmployeeId($employee_id);

		$employee_salary 		= G_Employee_Basic_Salary_History_Finder::findCurrentSalary($employee);
		$employee_rate 			= G_Job_Salary_Rate_Finder::findById($employee_salary->job_salary_rate_id);
		$employee_pay_period 	= G_Settings_Pay_Period_Finder::findById($employee_salary->pay_period_id);

		$pay_period 		= G_Settings_Pay_Period_Finder::findByCompanyStructureId($this->company_structure_id);
		$rate 				= G_Job_Salary_Rate_Finder::findByCompanyStructureId($this->company_structure_id);
		$compensation_history = G_Employee_Basic_Salary_History_Finder::findByEmployeeId($employee_id);

		
		$c 					 = G_Employee_Contribution_Finder::findByEmployeeId($employee_id);
        $dynamic_fields      = G_Employee_Dynamic_Field_Helper::sqlDynamicFieldsByEmployeeId($employee_id);

        $a_dynamic_fields = array();
        $s_dynamic_fields = '';
        foreach( $dynamic_fields as $df ){
            $a_dynamic_fields[] = $df['title'] . " = " . $df['value'];
        }
        $s_dynamic_fields = implode(" ", $a_dynamic_fields);
		
		$training  			 = G_Employee_Training_Finder::findByEmployeeId($employee_id);

		$e = G_Employee_Requirements_Finder::findByEmployeeId($employee_id);
		$data[] = unserialize($e->requirements);
		$requirements = $data;

		$subordinate 		= G_Employee_Supervisor_Finder::findByEmployeeId($employee_id);
		$supervisor 		= G_Employee_Supervisor_Finder::findBySupervisorId($employee_id);

		
		
		$gcs 				= G_Company_Structure_Finder::findById($this->company_structure_id);
		$leaves 			= G_Leave_Finder::findByCompanyStructureId($gcs);

		$work_experience	= G_Employee_Work_Experience_Finder::findByEmployeeId($employee_id);
		$education 			= G_Employee_Education_Finder::findByEmployeeId($employee_id);

        $a_educations = array();
        $s_educations = '';
		foreach($education as $key => $e) {
            if( trim($e->institute) != '' ){ 
                $a_educations[] = $e->institute . "|" . $e->course . "|" . $e->year. "|" . $e->gpa_score;
            }
		}
        $s_educations = implode(",", $a_educations);

		$skills 			= G_Employee_Skills_Finder::findByEmployeeId($employee_id);
		$languages 			= G_Employee_Language_Finder::findByEmployeeId($employee_id);
		$license 			= G_Employee_License_Finder::findByEmployeeId($employee_id);

		$subdivision_history = G_Employee_Subdivision_History_Finder::findByEmployeeId($employee_id);
		$department 		 = $department = G_Company_Structure_Finder::findParentChildByBranchId($this->company_structure_id);
		$job_history 		 = G_Employee_Job_History_Finder::findByEmployeeId($employee_id);
		$job 				 = G_Job_Finder::findByCompanyStructureId($this->company_structure_id);
		$status 			 = G_Settings_Employment_Status_Finder::findByCompanyStructureId($this->company_structure_id);
		$branch 			 = G_Company_Branch_Finder::findByCompanyStructureId($this->company_structure_id);
		$d = G_Employee_Helper::findByEmployeeId($employee_id);
        $employee_status = G_Settings_Employee_Status_Finder::findById($d['employee_status_id']);

		$tags = G_Employee_Tags_Helper::getEmployeeTags($employee_id);
		$a_tags = array();
		$string_tags = '';
		foreach( $tags as $tag ){
			$a_tags[] = $tag['tags'];
		}
		$string_tags = implode(",", $a_tags);

		//$lastname   = strtr(utf8_decode($employee->getFirstname()), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
        //$firstname  = strtr(utf8_decode($employee->getLastname()), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
       // $middlename  = strtr(utf8_decode($employee->getMiddlename()), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
        $department = strtr(utf8_decode($d['department']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
        $section    = strtr(utf8_decode($d['section_name']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY'); 

        $project_site_id = $employee->getProjectSiteId();

        $project_details = G_Project_Site_Finder::findById($project_site_id);
        $project_name = "";
        if($project_details){
            $project_name = $project_details->getprojectname();
        }

        $firstname = mb_convert_encoding($employee->getFirstname() , "HTML-ENTITIES", "UTF-8");
         $lastname = mb_convert_encoding($employee->getLastname() , "HTML-ENTITIES", "UTF-8");
          $middlename = mb_convert_encoding($employee->getMiddlename() , "HTML-ENTITIES", "UTF-8");

	?>	
		<tr>                
        	<td style="mso-number-format:'\@';"><?php echo $personal_details->employee_code; ?></td>
        	<td style="mso-number-format:'\@';"><?php echo $firstname; ?></td>
        	<td style="mso-number-format:'\@';"><?php echo $lastname; ?></td>
        	<td style="mso-number-format:'\@';"><?php echo $middlename; ?></td>
        	<td style="mso-number-format:'\@';">
        		<?php  
        			$hired_date = ($d['hired_date']=='0000-00-00')? '': Date::convertDateIntIntoDateString($d['hired_date']); 
        			echo $hired_date;
        		?>        		
        	</td>
            <td style="mso-number-format:'\@';"><?php echo number_format($employee_salary->basic_salary,2); ?></td>
        	<td style="mso-number-format:'\@';"><?php echo Tools::friendlyTitle($employee_salary->type); ?></td>
        	<td style="mso-number-format:'\@';"><?php echo $personal_details->number_dependent; ?></td>
        	<td style="mso-number-format:'\@';"><?php echo $department; ?></td>

            <td style="mso-number-format:'\@';"><?php echo $project_name; ?></td>

        	<td style="mso-number-format:'\@';"><?php echo  ucfirst($d['position']); ?></td>
        	<td style="mso-number-format:'\@';"><?php echo ucfirst($d['employment_status']); ?></td>
        	<td style="mso-number-format:'\@';">
        			<?php 
        				$birthdate = ($personal_details->birthdate=='0000-00-00')? '' : Date::convertDateIntIntoDateString($personal_details->birthdate); 
        				echo $birthdate;
        			?>
        	</td>
        	<td style="mso-number-format:'\@';"><?php echo ucfirst($personal_details->marital_status); ?></td>
        	<td style="mso-number-format:'\@';"><?php echo ucfirst($personal_details->gender); ?></td>
        	<td style="mso-number-format:'\@';"><?php echo $personal_details->sss_number; ?></td>
        	<td style="mso-number-format:'\@';"><?php echo $personal_details->pagibig_number; ?></td>
        	<td style="mso-number-format:'\@';"><?php echo $personal_details->philhealth_number; ?></td>
        	<td style="mso-number-format:'\@';"><?php echo $personal_details->tin_number; ?></td>
        	<td style="mso-number-format:'\@';"><?php echo $contact_details->address; ?></td>
        	<td style="mso-number-format:'\@';"><?php echo  ucfirst($contact_details->city); ?></td>
        	<td style="mso-number-format:'\@';"><?php echo ucfirst($contact_details->province); ?></td>
        	<td style="mso-number-format:'\@';"><?php echo  ucfirst($contact_details->zip_code); ?></td>
        	<td style="mso-number-format:'\@';"><?php echo  ucfirst($contact_details->home_telephone); ?></td>
        	<td style="mso-number-format:'\@';"><?php echo ucfirst($contact_details->mobile); ?></td>
        	<td style="mso-number-format:'\@';"><?php echo $contact_details->other_email; ?></td>
        	<td style="mso-number-format:'\@';"><?php echo $contact_details->work_telephone; ?></td>
        	<td style="mso-number-format:'\@';"><?php echo $contact_details->work_email; ?></td>
        	<td style="mso-number-format:'\@';">
        		<?php 
        			$a_bank    = array();
        			$a_account = array();
        			$string_bank    = '';
        			$string_account = '';
        			foreach($banks as $b) { 
        				$a_bank[]    = $b->getBankName();
        				$a_account[] = $b->getAccount();
        			}
        			$string_bank = implode(",", $a_bank);
        			echo $string_bank;
        		?>
        	</td>
        	<td style="mso-number-format:'\@';">
        		<?php 
        			$string_account = implode(",", $a_account);
        			echo $string_account;
        		?>
        	</td>
        	<td style="mso-number-format:'\@';"><?php echo  ucfirst($personal_details->extension_name); ?></td>
        	<td style="mso-number-format:'\@';"><?php echo  ucfirst($personal_details->nickname); ?></td>
        	<td style="mso-number-format:'\@';"><?php echo $section; ?></td>
        	<td style="mso-number-format:'\@';">
                <?php 
                    if( $employee->getIsConfidential() == 1 ){
                        echo "Yes";
                    }else{
                        echo "No";
                    }
                ?>
                is confi
            </td>
        	<td style="mso-number-format:'\@';"><?php echo $employee->getWeekWorkingDays(); ?></td>
        	<td style="mso-number-format:'\@';"><?php echo $s_dynamic_fields; ?></td>
        	<!--<td style="mso-number-format:'\@';"><?php echo ucfirst($d['employment_status']); ?></td>-->
            <td style="mso-number-format:'\@';"><?php echo ucfirst($employee_status->getName()); ?></td>
        	<td style="mso-number-format:'\@';"><?php echo ucfirst($personal_details->nationality); ?></td>
        	<td style="mso-number-format:'\@';"><?php echo $string_tags; ?></td>
        	<td style="mso-number-format:'\@';"><?php echo mb_convert_encoding($s_contacts, "HTML-ENTITIES", "UTF-8"); ?></td>
        	<td style="mso-number-format:'\@';"><?php echo $s_educations; ?></td>        	
            <td style="mso-number-format:'\@';"><?php echo $employee->getTags(); ?></td>           
        </td>
	<?php } ?>
</table>


<?php
header("Content-type: application/x-msexcel;charset=UTF-8"); //tried adding  charset='utf-8' into header
header("Content-Disposition: attachment; filename=$filename");
header("Content-Disposition: attachment;filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
?>