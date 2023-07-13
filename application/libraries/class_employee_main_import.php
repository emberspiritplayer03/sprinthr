<?php
/*
	This is used for importing timesheet.

	Usage:
		$file = $_FILES['employee']['tmp_name'];
		//$file = BASE_PATH . 'files/files/employee.xls';
		
		$e = new Employee_Import($file);
		$return = $e->import();
*/
class Employee_Main_Import {
	protected $file_to_import;
	
	public function __construct($file) {
		
		$this->file_to_import = $file;	
	}
	
	public function import() {
		
		$this->error_count = 0;
		$this->imported_count = 0;
		
		$this->error_complete_name = 0;
		$this->error_branch_name = 0;
		$this->error_department = 0;
		$this->warning_fullname=0;
		
		$_SESSION['hr']['error']='';
		$this->data = new Excel_Main_Reader($this->file_to_import);
		$this->total_row = $this->data->countRow();

		for ($this->i = 1; $this->i <= $this->total_row; $this->i++) {
			$this->get_excel_data($this->i);	

			$is_valid = $this->is_excel_format_valid($this->i);
			if($is_valid==1 && $this->i>1) {
				
				$this->e = G_Employee_Finder::findByEmployeeCode($this->employee[$this->i]['employee_code']);
				$this->array_duplicate[] = $excel_employee_code;
				
				if($this->e){
					$this->update();	
				}else {
					
					$this->save();	
				}
			}
			
		}
		
		echo $this->result();
	}
	
	public function update()
	{
			$this->company_structure_id = $company_structure_id = $_SESSION['sprint_hr']['company_structure_id'];
			$this->is_update=0;
			if($this->i>1) { //begin of if($i>1)
			
				$this->check_if_branch_is_blank();
				
				$this->check_if_department_is_blank();
				
				$this->check_if_employee_name_has_blank();
				
			
				
				if($this->employee[$this->i]['branch']!='' && $this->employee[$this->i]['department']!='' && $this->error_fullname==0) { //IF STATEMENT
					$this->imported_update_count++;	
					
					$employee_id = ($this->e->id>0) ? $this->e->id : '' ;
					
					$this->update_employee($employee_id);
					
					$this->update_account($employee_id);
					
					$e = Employee_Factory::get($employee_id);
					
					$hash = Utilities::createHash($employee_id);
		
					$e->addHash($hash);
					//ADD INTO COMPANY
					$c = G_Company_Structure_Finder::findById($this->company_structure_id);
					$c->addEmployee($e);
					//END OF ADD INTO COMPANY
					
					//ADD OR UPDATE INTO BRANCH
					$branch = G_Company_Branch_Finder::findByName($this->employee[$this->i]['branch']);
					if(!$branch) {
						//echo "insert branch<br>";
						$b = new G_Company_Branch;
						$b->setCompanyStructureId($this->company_structure_id);
						$b->setName($this->employee[$this->i]['branch']);
						$branch_id = $b->save($c);
					}else {
					//	echo "Branch Id: " . $branch->getId();
						$branch_id = $branch->getId();
					}
						$e_branch = G_Employee_Branch_History_Finder::findCurrentBranch($e);
						
						if($e_branch) {

							//has current branch then update
							$b = G_Company_Branch_Finder::findById($branch_id);
							$e_branch->setCompanyBranchId($b->getId());
							$e_branch->setBranchName($b->getName());
							$e_branch->setStartDate($this->employee[$this->i]['hired_date']);
							$e_branch->save();
							
						}else {
	
							//do not have current branch then add
							$b = G_Company_Branch_Finder::findById($branch_id);
							$b->addEmployee($e,$this->employee[$this->i]['hired_date']);	
						}
					//END ADD OR UPDATE INTO BRANCH<br />


					
					//ADD OR UPDATE DEPARTMENT			
					$department = G_Company_Structure_Finder::findByTitle($this->employee[$this->i]['department']);
					
					if(!$department) {
						$dept = new  G_Company_Structure;
						$dept->setTitle($this->employee[$this->i]['department']);
						$dept->setCompanyBranchId($branch_id);
						$dept->setParentId($this->company_structure_id);
						$department_id = $dept->save();
					}else {
						$department_id = $department->getId();
					}
					
					$c = G_Company_Structure_Finder::findById($department_id);
					$e_subdivision = G_Employee_Subdivision_History_Finder::findCurrentSubdivision($e);
					if($e_subdivision) {

						//has current departmen
						$e_subdivision->setCompanyStructureId($c->getId());
						$e_subdivision->setName($c->getTitle());
						$e_subdivision->setStartDate($this->employee[$this->i]['hired_date']);
						$e_subdivision->save();
					}else {
						//does not have current department then add
						if($c) {
							$c->addEmployeeToSubdivision($e,$this->employee[$this->i]['hired_date']);
						}
					}
						
					//END OF ADD OR UPDATE DEPARTMENT
					
					//ADD / UPDATE JOB POSITION AND EMPLOYMENT STATUS
					$job = G_Job_Finder::findByTitle($this->employee[$this->i]['position']);
					
					if(!$job) {
						$j = new G_Job;
						$j->setCompanyStructureId($this->company_structure_id);
						$j->setTitle($this->employee[$this->i]['position']);
						$j->setIsActive(1);
						$position_id = $j->save();
		
					}else {
						$position_id = $job->getId();
					//	echo "Position Id " . $position_id;
					}
					
					if(strtolower($this->employee[$this->i]['employment_status'])!='terminated') {// do not update the settings if the status is equal terminated
						$employment_status = G_Settings_Employment_Status_Finder::findByStatus($this->employee[$this->i]['employment_status']);
						if(!$employment_status) {
							//echo "insert status<br>";
							$gcs = G_Company_Structure_Finder::findById($this->company_structure_id);
							$employment_status = new G_Settings_Employment_Status;
							$employment_status->setCompanyStructureId($this->company_structure_id);
							$employment_status->setStatus($this->employee[$this->i]['employment_status']);
							$employment_status_id = $employment_status->save($gcs);
						}else {
							$employment_status_id = $employment_status->getId();
						}
						$s = G_Settings_Employment_Status_Finder::findById($employment_status_id);
						$status = $s->getStatus();
					}else {
						$status = $this->employee[$this->i]['employment_status'];	
					}
							
					$p = G_Job_Finder::findById($position_id);
					$e_job = G_Employee_Job_History_Finder::findCurrentJob($e);
					
					$this->employee[$this->i]['employment_status'] = ($this->employee[$this->i]['terminated_date']!='') ? 'Terminated'  : $this->employee[$this->i]['employment_status'] ;
					$this->employee[$this->i]['terminated_date'] = (strtolower($this->employee[$this->i]['employment_status'])=='terminated' && $this->employee[$this->i]['terminated_date']=='') ? date("Y-m-d")  : $this->employee[$this->i]['terminated_date'];
					
					if($e_job) {
						// has current job then update
						$e_job->setJobId($p->getId());
						$e_job->setName($p->getTitle());
						$e_job->setEmploymentStatus($status);
						$e_job->setStartDate($this->employee[$this->i]['hired_date']);
						$e_job->setEndDate($this->employee[$this->i]['terminated_date']);
						$e_job->save();						
					}else {
						//if terminated update
						if($this->employee[$this->i]['terminated_date']!='') {
							$e_terminated = G_Employee_Job_History_Finder::findByTerminatedJob($e);
							if($e_terminated) {
								$e_terminated->setJobId($p->getId());
								$e_terminated->setName($p->getTitle());
								$e_terminated->setEmploymentStatus($this->employee[$this->i]['employment_status']);
								$e_terminated->setStartDate($this->employee[$this->i]['hired_date']);
								$e_terminated->setEndDate($this->employee[$this->i]['terminated_date']);
								$e_terminated->save();		
							}
							
						}else {
							//if not terminated save new job
							$employee_position = new G_Employee_Job_History;
							$employee_position->setEmployeeId($e->getId());
							$employee_position->setJobId($p->getId());
							$employee_position->setName($p->getTitle());
							$employee_position->setEmploymentStatus($this->employee[$this->i]['employment_status']);
							$employee_position->setStartDate($this->employee[$this->i]['hired_date']);
							$employee_position->setEndDate('');
							$employee_position->save();	
						}
					}
					
							// UPDATING BUT STATUS IS NOT TERMINATED
							//CHECK IF THERE IS TERMINATED HISTORY THEN REMOVED
							$terminated_history = G_Employee_Job_History_Finder::findByTerminatedJob($e);
							if($terminated_history) {
								$history_terminated = G_Employee_Job_History_Finder::findById($terminated_history->getId());
								$history_terminated->delete();
							}

					// END ADD / UPDATE JOB POSITION AND EMPLOYMENT STATUS
					
					//SALARY INSERT OR UPDATE					
					if(strtolower($excel_type)=='monthly') {
						$rate = "monthly_rate";
					}else if(strtolower($excel_type)=='hourly') {
						$rate = "hourly_rate";
	
					}else if(strtolower($excel_type)=='daily') {
						$rate = "daily_rate";
					}else {
						$rate = 'monthly_rate';	
					}
					
					$salary = G_Employee_Basic_Salary_History_Finder::findCurrentSalary($e);	
					$pp = G_Settings_Pay_Period_Finder::findByCompanyStructureId($this->company_structure_id);
					
					$frequency = G_Settings_Pay_Period_Finder::findByPayPeriodCode($this->company_structure_id, $this->employee[$this->i]['pay_frequency']);	
					$default_pay_period = G_Settings_Pay_Period_Finder::findDefault($this->company_structure_id);
				
					$frequency_id = ($frequency) ? $frequency->id : $default_pay_period->getId();
					$this->employee[$this->i]['salary'] = str_replace(",", "", $this->employee[$this->i]['salary']);
					if($salary) {
						
						$salary->setEmployeeId($employee_id);
						$salary->setJobSalaryRateId("");
						$salary->setBasicSalary($this->employee[$this->i]['salary']);
						$salary->setType($rate);
						
						$salary->setPayPeriodId($frequency_id);
						$salary->setStartDate($this->employee[$i]['hired_date']);
						$salary->save();
					}else {
						$employee_salary = new G_Employee_Basic_Salary_History;	
						$employee_salary->setId($salary->id);
						$employee_salary->setEmployeeId($this->employee_id);
						$employee_salary->setJobSalaryRateId("");
						$employee_salary->setType($rate);
						$employee_salary->setBasicSalary($this->employee[$this->i]['salary']);
						$employee_salary->setPayPeriodId($frequency_id);
						$employee_salary->setStartDate($this->employee[$i]['hired_date']);
						$employee_salary->save();
					}	
					//END SALARY INSERT OR UPDATE
					
					//CONTRIBUTION UPDATE
					$philhealth = G_Philhealth_Finder::findBySalary($this->employee[$this->i]['salary']);
					$philhealth_er = ($philhealth) ? $philhealth->getCompanyShare() : 0 ;
					$philhealth_ee = ($philhealth) ? $philhealth->getEmployeeShare() : 0 ;
					
					$sss = G_SSS_Finder::findBySalary($this->employee[$this->i]['salary']);
					$sss_er = ($sss) ? $sss->getCompanyShare() : 0 ;
					$sss_ee = ($sss) ? $sss->getEmployeeShare() : 0 ;
					
					$pagibig = G_Pagibig_Finder::findBySalary($this->employee[$this->i]['salary']);
					$pagibig_er = ($pagibig) ? $pagibig->getCompanyShare() : 0 ;
					$pagibig_ee = ($pagibig)? $pagibig->getEmployeeShare() : 0 ;
					//add sss philhealth and er	
				
					$contribution = G_Employee_Contribution_Finder::findByEmployeeId($employee_id);
					if($contribution) {
						$contribution->setId($contribution->getId());
						$contribution->setEmployeeId($employee_id);
						$contribution->setSssEe($sss_ee);
						$contribution->setPagibigEe($pagibig_ee);
						$contribution->setPhilhealthEe($philhealth_ee);
						$contribution->setSssEr($sss_er);
						$contribution->setPagibigEr($pagibig_er);
						$contribution->setPhilhealthEr($philhealth_er);
						$contribution->save();	
						
					}else {
						$cont = new G_Employee_Contribution;
						$cont->setEmployeeId($employee_id);
						$cont->setSssEe($sss_ee);
						$cont->setPagibigEe($pagibig_ee);
						$cont->setPhilhealthEe($philhealth_ee);
						$cont->setSssEr($sss_er);
						$cont->setPagibigEr($pagibig_er);
						$cont->setPhilhealthEr($philhealth_er);
						$cont->save();	
					}
					//END OF CONTRIBUTION UPDATE
					
					// UPDATE CONTRACT

					if($this->employee[$this->i]['contract_start']!='' && $this->employee[$this->i]['contract_end']!=''){
						$contract_history = G_Employee_Extend_Contract_Finder::findByCurrentContract($employee_id);
	
						if($contract_history) {

							$contract_history->setEmployeeId($e->getId());
							$contract_start_date = date('Y-m-d', strtotime($this->employee[$this->i]['contract_start']));
							$contract_end_date = date('Y-m-d', strtotime($this->employee[$this->i]['contract_end']));
							
							$contract_history->setStartDate($contract_start_date);
							$contract_history->setEndDate($contract_end_date);
							$contract_history->save();
							
						}else {
							$contract = new G_Employee_Extend_Contract;
							$contract->setEmployeeId($e->getId());
							
							$contract_start_date = date('Y-m-d', strtotime($this->employee[$this->i]['contract_start']));
							$contract_end_date = date('Y-m-d', strtotime($this->employee[$this->i]['contract_end']));
							
							$contract->setStartDate($contract_start_date);
							$contract->setEndDate($contract_end_date);
							$contract->save();
						}
						
					}else {
						$contract_history = G_Employee_Extend_Contract_Finder::findByCurrentContract($employee_id);	
						if($contract_history) {
							$contract_history->setEmployeeId($e->getId());
							$contract_start_date = date('Y-m-d', strtotime($this->employee[$this->i]['contract_start']));
							$contract_end_date = date('Y-m-d', strtotime($this->employee[$this->i]['contract_end']));
							
							$contract_history->setStartDate($contract_start_date);
							$contract_history->setEndDate($contract_end_date);
							$contract_history->save();
						}
						
					}
					// END OF UPDATE CONTRACT
					
					
					// UPDATE CONTACT DETAILS
					$contact_details = G_Employee_Contact_Details_Finder::findByEmployeeId($employee_id);
					if($contact_details) {
						
						$contact_details->setEmployeeId($e->getId());
						$contact_details->setAddress($this->employee[$this->i]['address']);
						$contact_details->setCity($this->employee[$this->i]['city']);
						$contact_details->setProvince($this->employee[$this->i]['province']);
						$contact_details->setZipCode($this->employee[$this->i]['zip_code']);
						$contact_details->setCountry();
						$contact_details->setHomeTelephone($this->employee[$this->i]['home_telephone']);
						$contact_details->setMobile($this->employee[$this->i]['mobile']);
						$contact_details->setWorkTelephone($this->employee[$this->i]['work_telephone']);
						$contact_details->setWorkEmail($this->employee[$this->i]['work_email']);
						$contact_details->setOtherEmail($this->employee[$this->i]['personal_email']);
						$contact_details->save();
					}
					// END CONTACT DETAILS
					
					// UPDATE DIRECT DEPOSIT
					$dd = G_Employee_Direct_Deposit_Finder::findRecordByEmployeeIdBankName($employee_id,$this->employee[$this->i]['bank_name']);
					if(!$dd) {
						$dd = new G_Employee_Direct_Deposit();
					}
					$dd->setEmployeeId($e->getId());
					$dd->setBankName($this->employee[$this->i]['bank_name']);
					$dd->setAccount($this->employee[$this->i]['bank_account_number']);
					$dd->save();
					
					// END DIRECT DEPOSIT
					
					$emergency = G_Employee_Emergency_Contact_Finder::findRecordByEmployeeIdPersonMobile($employee_id,$this->employee[$this->i]['emergency_contact_name'],$this->employee[$this->i]['emergency_number']);
					if(!$emergency) {
						$emergency = new G_Employee_Emergency_Contact();
					}
						$emergency->setEmployeeId($e->getId());
						$emergency->setPerson($this->employee[$this->i]['emergency_contact_name']);
						$emergency->setRelationship();
						$emergency->setHomeTelephone($this->employee[$this->i]['home_telephone']);
						$emergency->setMobile($this->employee[$this->i]['emergency_number']);
						$emergency->setWorkTelephone($this->employee[$this->i]['work_telephone']);
						$emergency->setAddress($this->employee[$this->i]['address']);
						$emergency->save();
				}
				$this->error_fullname=0;
			
			}//end of if($i>1)		
	}
	
	public function save()
	{
		$this->company_structure_id = $company_structure_id = $_SESSION['sprint_hr']['company_structure_id'];
			
			if($this->i>1) { //begin of if($i>1)

				$this->check_if_branch_is_blank();
				
				$this->check_if_department_is_blank();
				
				$this->check_if_employee_name_has_blank();
				
				if($this->employee[$this->i]['branch']!='' && $this->employee[$this->i]['department']!='' && $this->error_fullname==0) { //IF STATEMENT
					$this->imported_count++;
						
					$this->employee_id = $this->update_employee();
					
					$this->update_account($employee_id);
					
					$this->company_structure_id = $_SESSION['sprint_hr']['company_structure_id'];
					
					$e = Employee_Factory::get($this->employee_id);
					$hash = Utilities::createHash($this->employee_id);
					$e->addHash($hash);
					
					
					$c = G_Company_Structure_Finder::findById($this->company_structure_id);
					$c->addEmployee($e);
				
					$branch = G_Company_Branch_Finder::findByName($this->employee[$this->i]['branch']);

					if(!$branch) {
						//echo "insert branch<br>";
						$b = new G_Company_Branch;
						$b->setCompanyStructureId($this->company_structure_id);
						$b->setName($this->employee[$this->i]['branch']);
						$branch_id = $b->save($c);
					}else {
					//	echo "Brnach Id: " . $branch->getId();
						$branch_id = $branch->getId();
					}
						$b = G_Company_Branch_Finder::findById($branch_id);
						$b->addEmployee($e,$this->employee[$this->i]['hired_date']);
					
					$department = G_Company_Structure_Finder::findByTitle($this->employee[$this->i]['department']);
					//echo "<pre>";
				//	print_r($department);
					if(!$department) {
						//echo "insert dapartment<br>";	
						$dept = new  G_Company_Structure;
						$dept->setTitle($this->employee[$this->i]['department']);
						$dept->setCompanyBranchId($branch_id);
						$dept->setParentId($_SESSION['sprint_hr']['company_structure_id']);
						$dept->setType('Department');
						$department_id = $dept->save();
		
					}else {
						//echo "department id " . $department_id;
						$department_id = $department->getId();
					}
				
					$subdivision_history = G_Employee_Subdivision_History_Finder::findCurrentSubdivision($e);
					
					if(!$subdivision_history) {
						$c = G_Company_Structure_Finder::findById($department_id);
						$c->addEmployeeToSubdivision($e,$this->employee[$this->i]['hired_date']);
					}
						
					
					$job = G_Job_Finder::findByTitle($this->employee[$this->i]['position']);
					if(!$job) {
						$j = new G_Job;
						$j->setCompanyStructureId($company_structure_id);
						$j->setTitle($this->employee[$this->i]['position']);
						$j->setIsActive(1);
						$position_id = $j->save();
		
					}else {
						$position_id = $job->getId();
					//	echo "Position Id " . $position_id;
					}
						$p = G_Job_Finder::findById($position_id);
						$p->saveToEmployee($e, $this->employee[$this->i]['hired_date'] );

					if(strtolower($this->employee[$this->i]['employment_status'])!='terminated') {// do not update the settings if the status is equal terminated
						$employment_status = G_Settings_Employment_Status_Finder::findByStatus($this->employee[$this->i]['employment_status']);
						if(!$employment_status) {
							//echo "insert status<br>";
							$gcs = G_Company_Structure_Finder::findById($company_structure_id);
							$employment_status = new G_Settings_Employment_Status;
							$employment_status->setCompanyStructureId($company_structure_id);
							$employment_status->setStatus($this->employee[$this->i]['employment_status']);
							$employment_status_id = $employment_status->save($gcs);
							
						}else {
						//	echo "retreive status<br>";
						//retrieve the id
							$employment_status_id = $employment_status->getId();
						}
					}
					
					$employee_position = G_Employee_Job_History_Finder::findCurrentJob($e);
					$company_job = G_Job_Finder::findById($position_id);

					$this->employee[$this->i]['employment_status'] = ($this->employee[$this->i]['terminated_date']!='') ? 'Terminated'  : $this->employee[$this->i]['employment_status'] ;
					$this->employee[$this->i]['terminated_date'] = ($this->employee[$this->i]['employment_status']=='Terminated' && $this->employee[$this->i]['terminated_date']=='') ? date("Y-m-d")  : $this->employee[$this->i]['terminated_date'] ;
					if($employee_position) {
						//with job
						$employee_position->setJobId($company_job->getId());
						$employee_position->setName($company_job->getTitle());
						$employee_position->setEmploymentStatus($this->employee[$this->i]['employment_status']);
						$employee_position->setStartDate($this->employee[$this->i]['hired_date']);
						$employee_position->setEndDate($this->employee[$this->i]['terminated_date']);
						$employee_position->save();	
					}else {
						//no job
						$employee_position = new G_Employee_Job_History;
						$employee_position->setEmployeeId($e->getId());
						$employee_position->setJobId($company_job->getId());
						$employee_position->setName($company_job->getTitle());
						$employee_position->setEmploymentStatus($this->employee[$this->i]['employment_status']);
						$employee_position->setStartDate($this->employee[$this->i]['hired_date']);
						$employee_position->setEndDate($this->employee[$this->i]['terminated_date']);
						$employee_position->save();	
					}	
					
					
					//insert salary
					if(strtolower($this->employee[$this->i]['type_of_payment'])=='monthly') {
						$rate = "monthly_rate";
					}else if(strtolower($this->employee[$this->i]['type_of_payment'])=='hourly') {
						$rate = "hourly_rate";
	
					}else if(strtolower($this->employee[$this->i]['type_of_payment'])=='daily') {
						$rate = "daily_rate";
					}else {
						$rate = 'monthly_rate';	
					}
					$salary = G_Employee_Basic_Salary_History_Finder::findCurrentSalary($e);	
					$pp = G_Settings_Pay_Period_Finder::findByCompanyStructureId($this->company_structure_id);
					
					$frequency = G_Settings_Pay_Period_Finder::findByPayPeriodCode($company_structure_id, $this->employee[$this->i]['pay_frequency']);	
					$default_pay_period = G_Settings_Pay_Period_Finder::findDefault($company_structure_id);
					$frequency_id = ($frequency) ? $frequency->id : $default_pay_period->id;
					$this->employee[$this->i]['salary'] = str_replace(",", "", $this->employee[$this->i]['salary']);
					
					
					if($salary) {
						$salary->setEmployeeId($this->employee_id);
						$salary->setJobSalaryRateId("");
						$salary->setBasicSalary($this->employee[$this->i]['salary']);
						$salary->setType($rate);
						
						$salary->setPayPeriodId($frequency_id);
						$salary->setStartDate(date('Y-m-d'));
						$salary->save();
					}else {
						$employee_salary = new G_Employee_Basic_Salary_History;	
						$employee_salary->setId($salary->id);
						$employee_salary->setEmployeeId($this->employee_id);
						$employee_salary->setJobSalaryRateId("");
						$employee_salary->setType($rate);
						$employee_salary->setBasicSalary($this->employee[$this->i]['salary']);
						$employee_salary->setPayPeriodId($frequency_id);
						$employee_salary->setStartDate(date('Y-m-d'));
						$employee_salary->save();
					}		
					$philhealth = G_Philhealth_Finder::findBySalary($this->employee[$this->i]['salary']);
					$philhealth_er = ($philhealth)?$philhealth->getCompanyShare() : 0;
					$philhealth_ee = ($philhealth)?$philhealth->getEmployeeShare() : 0;
					
					$sss = G_SSS_Finder::findBySalary($this->employee[$this->i]['salary']);
					
					$sss_er = ($sss)? $sss->getCompanyShare() : 0;
					$sss_ee = ($sss)? $sss->getEmployeeShare() : 0;
					
					$pagibig = G_Pagibig_Finder::findBySalary($this->employee[$this->i]['salary']);
					$pagibig_er = ($pagibig)?$pagibig->getCompanyShare(): 0;
					$pagibig_ee = ($pagibig)?$pagibig->getEmployeeShare(): 0;
					//add sss philhealth and er	
					$contribution = new G_Employee_Contribution;
					$contribution->setEmployeeId($e->getId());
					$contribution->setSssEe($sss_ee);
					$contribution->setPagibigEe($pagibig_ee);
					$contribution->setPhilhealthEe($philhealth_ee);
					$contribution->setSssEr($sss_er);
					$contribution->setPagibigEr($pagibig_er);
					$contribution->setPhilhealthEr($philhealth_er);
					$contribution->save();
					
					//INSERT CONTRACT
					if($this->employee[$this->i]['contract_start']!='' && $this->employee[$this->i]['contract_end']!=''){
						$contract = new G_Employee_Extend_Contract;
						$contract->setEmployeeId($e->getId());
						
						$contract_start_date = date('Y-m-d', strtotime($this->employee[$this->i]['contract_start']));
						$contract_end_date = date('Y-m-d', strtotime($this->employee[$this->i]['contract_end']));
						
						$contract->setStartDate($contract_start_date);
						$contract->setEndDate($contract_end_date);
						$contract->save();
					}
					
					// UPDATE CONTACT DETAILS
					$contact_details = new G_Employee_Contact_Details();
					$contact_details->setEmployeeId($e->getId());
					$contact_details->setAddress($this->employee[$this->i]['address']);
					$contact_details->setCity($this->employee[$this->i]['city']);
					$contact_details->setProvince($this->employee[$this->i]['province']);
					$contact_details->setZipCode($this->employee[$this->i]['zip_code']);
					$contact_details->setCountry();
					$contact_details->setHomeTelephone($this->employee[$this->i]['home_telephone']);
					$contact_details->setMobile($this->employee[$this->i]['mobile']);
					$contact_details->setWorkTelephone($this->employee[$this->i]['work_telephone']);
					$contact_details->setWorkEmail($this->employee[$this->i]['work_email']);
					$contact_details->setOtherEmail($this->employee[$this->i]['personal_email']);
					$contact_details->save();
					
					// END CONTACT DETAILS
					
					// INSERT DIRECT DEPOSIT
					$dd = new G_Employee_Direct_Deposit();
					$dd->setEmployeeId($e->getId());
					$dd->setBankName($this->employee[$this->i]['bank_name']);
					$dd->setAccount($this->employee[$this->i]['bank_account_number']);
					$dd->save();
					// END DIRECT DEPOSIT
					
					$emergency = new G_Employee_Emergency_Contact();
					$emergency->setEmployeeId($e->getId());
					$emergency->setPerson($this->employee[$this->i]['emergency_contact_name']);
					$emergency->setRelationship();
					$emergency->setHomeTelephone($this->employee[$this->i]['home_telephone']);
					$emergency->setMobile($this->employee[$this->i]['mobile']);
					$emergency->setWorkTelephone($this->employee[$this->i]['work_telephone']);
					$emergency->setAddress($this->employee[$this->i]['address']);
					$emergency->save();
					
					 
				}
				$this->error_fullname=0;
			
			}//end of if($i>1)	
	}
	
	public function check_if_branch_is_blank() {
		//check branch name
		if(!$this->employee[$this->i]['branch']){
			$this->error_branch_name++;
			$this->error_count++;
			$this->report_row_no_branch[] = $i;
		}
	}
	
	public function check_if_department_is_blank()
	{
		//check department
		if(!$this->employee[$this->i]['department']) {
			$this->error_department++;
			$this->error_count++;	
			$this->report_row_no_department[] = $i;
		}
				
	}
	
	public function check_if_employee_name_has_blank()
	{
		//check blank full name
		if($this->employee[$this->i]['firstname']=='' || $this->employee[$this->i]['lastname']=='' || $this->employee[$this->i]['middlename']==''){					
			$this->error_count++;
			if($this->employee[$this->i]['firstname']=='' && $this->employee[$this->i]['lastname']=='' && $this->employee[$this->i]['middlename']=='') {
				$this->error_fullname=1;	
				$this->error_complete_name++;	
				$this->report_row_no_fullname[] = $i;
			}
			$this->warning_fullname++;
			$this->report_row_incomplete_fullname[] = $i;
		}
	}
	

	public function update_employee($employee_id='') {

		$e = new G_Employee;

		$e->setId($employee_id);
		$e->setEmployeeCode($this->employee[$this->i]['employee_code']);
		$e->setFirstname($this->employee[$this->i]['firstname']);
		$e->setLastname($this->employee[$this->i]['lastname']);
		$e->setMiddlename($this->employee[$this->i]['middlename']);
		$e->setExtensionName($this->employee[$this->i]['extension_name']);
		$e->setNickname($this->employee[$this->i]['nickname']);
		$e->setBirthdate($this->employee[$this->i]['birthdate']);
		$e->setGender($this->employee[$this->i]['gender']);
		$e->setMaritalStatus($this->employee[$this->i]['marital_status']);
		$e->setNumberDependent($this->employee[$this->i]['number_of_dependent']);
		$e->setHiredDate($this->employee[$this->i]['hired_date']);					
		$e->setSssNumber($this->employee[$this->i]['sss_number']);
		$e->setTinNumber($this->employee[$this->i]['tin_number']);
		$e->setPagibigNumber($this->employee[$this->i]['pagibig_number']);
		$e->setPhilhealthNumber($this->employee[$this->i]['philhealth_number']);
		$e->setIsArchive(G_Employee::NO);
		$e_id = $e->save();
		$this->employee_id = ($employee_id!='')? $employee_id : $e_id ;
		return $this->employee_id;
						
	}
	
	public function update_account($employee_id='') {
		if($employee_id!='') {
			$e = G_Employee_Finder::findById($employee_id);
			$j = G_Employee_Job_History_Finder::findCurrentJob($e);
			
			$user = G_User_finder::findByEmployeeId($employee_id);
			
			if($user) {
				$user->setUsername($this->employee[$this->i]['username']);
				if($this->employee[$this->i]['password']=='') {
					$password = $user->getPassword();	
				}else {
					$password = Utilities::encryptPassword($this->employee[$this->i]['password']);
					$password_update = 1;
				}
				
				
				$user->setPassword($password);
				$user->setModule($this->employee[$this->i]['module']);
				$user->setHash($e->getHash());
				$user->setDateModified(date("Y-m-d"));
				$user->setEmploymentStatus($j->getEmploymentStatus());
				$user->save();
				
			}else {
				//insert
				if($this->employee[$this->i]['username']!='' && $this->employee[$this->i]['password']!='' && $this->employee[$this->i]['module']!='') 
				{	
					$un = G_User_Finder::findByEmployeeIdUsername($this->employee[$this->i]['username']);
					if(!$un) {
						$e = G_Employee_Finder::findById($employee_id);
						$j = G_Employee_Job_History_Finder::findCurrentJob($e);
						$user = new G_User;
						$user->setCompanyStructureId($this->company_structure_id);
						$user->setEmployeeId($employee_id);
						$user->setUsername($this->employee[$this->i]['username']);
						$user->setPassword( Utilities::encryptPassword($this->employee[$this->i]['password']));
						$user->setModule($this->employee[$this->i]['module']);
						$user->setHash($e->getHash());
						$user->setDateModified(date("Y-m-d"));
						$user->setEmploymentStatus($j->getEmploymentStatus());
						$user->save();
					}
					
				}
			
					
			}
		}
		
	}

	public function is_excel_format_valid($i) {

		if($i==1) {
				if($this->employee[$i]['branch']!='Branch' ) { //|| $excel_department!='Department' || $excel_employee_code!='Employee Code' || $excel_lastname!='Lastname' || $excel_firstname!='Firstname') {
					$this->is_format_valid=0;
					$this->i=$this->total_row;
				}else {
					$this->is_format_valid=1;
				}	
			
		}else {
			$this->is_format_valid=1;
		}
		return $this->is_format_valid;
	}
	
	public function get_excel_data($i) {
				
			$this->employee[$i]['branch'] = (string) trim(utf8_encode($this->data->getValue($i, 'A')));
			$this->employee[$i]['department'] = (string) trim(utf8_encode($this->data->getValue($i, 'B')));
			$this->employee[$i]['employee_code'] = (string) trim(utf8_encode($this->data->getValue($i, 'C')));
			$this->employee[$i]['lastname'] = (string) trim(utf8_encode($this->data->getValue($i, 'D')));
			$this->employee[$i]['firstname'] = (string) trim(utf8_encode($this->data->getValue($i, 'E')));
			
			$this->employee[$i]['middlename'] = (string) trim(utf8_encode($this->data->getValue($i, 'F')));
			$this->employee[$i]['extension_name'] = (string) trim(utf8_encode($this->data->getValue($i, 'G')));
			$this->employee[$i]['nickname'] = (string) trim(utf8_encode($this->data->getValue($i, 'H')));
			$this->employee[$i]['birthdate'] = (string)(Tools::isDate(trim(utf8_encode($this->data->getValue($i, 'I'))))) ? date('Y-m-d',strtotime(trim(utf8_encode($this->data->getValue($i, 'I'))))) : '';
			$this->employee[$i]['gender'] = (string) trim(utf8_encode($this->data->getValue($i, 'J')));
			
			$this->employee[$i]['marital_status'] = (string) trim(utf8_encode($this->data->getValue($i, 'K')));
			$this->employee[$i]['number_of_dependent'] = (string) trim(utf8_encode($this->data->getValue($i, 'L')));
			$this->employee[$i]['position'] = (string) trim(utf8_encode($this->data->getValue($i, 'M')));
			$this->employee[$i]['employment_status'] = (string) trim(utf8_encode($this->data->getValue($i, 'N')));
			$this->employee[$i]['hired_date'] =  (string) (Tools::isDate(trim(utf8_encode($this->data->getValue($i, 'O'))))) ? date('Y-m-d',strtotime(trim(utf8_encode($this->data->getValue($i, 'O'))))) : '';
			
			$this->employee[$i]['terminated_date'] = (string) (Tools::isDate(trim(utf8_encode($this->data->getValue($i, 'P'))))) ? date('Y-m-d',strtotime(trim(utf8_encode($this->data->getValue($i, 'P'))))) : '';
			$this->employee[$i]['salary'] = (string) trim($this->data->getValue($i, 'Q'));
			$this->employee[$i]['type_of_payment'] = (string) trim(utf8_encode($this->data->getValue($i, 'R')));
			$this->employee[$i]['pay_frequency'] = (string) trim(utf8_encode($this->data->getValue($i, 'S')));
			$this->employee[$i]['sss_number'] = (string) trim($this->data->getValue($i, 'T'));
			
			$this->employee[$i]['pagibig_number'] = (string) trim($this->data->getValue($i, 'U'));
			$this->employee[$i]['philhealth_number'] = (string) trim($this->data->getValue($i, 'V'));
			$this->employee[$i]['tin_number'] = (string) trim($this->data->getValue($i, 'W'));
			$this->employee[$i]['contract_start'] = (string) trim(utf8_encode($this->data->getValue($i, 'X')));
			$this->employee[$i]['contract_end'] = (string) trim(utf8_encode($this->data->getValue($i, 'Y')));
			
			$this->employee[$i]['address'] = (string) trim(utf8_encode($this->data->getValue($i, 'Z')));
			$this->employee[$i]['city'] = (string) trim(utf8_encode($this->data->getValue($i, 'AA')));
			$this->employee[$i]['province'] = (string) trim(utf8_encode($this->data->getValue($i, 'AB')));
			$this->employee[$i]['zip_code'] = (string) trim(utf8_encode($this->data->getValue($i, 'AC')));
			$this->employee[$i]['home_telephone'] = (string) trim($this->data->getValue($i, 'AD'));
			
			$this->employee[$i]['mobile'] = (string) trim($this->data->getValue($i, 'AE'));
			$this->employee[$i]['personal_email'] = (string) trim(utf8_encode($this->data->getValue($i, 'AF')));
			$this->employee[$i]['work_telephone'] = (string) trim(utf8_encode($this->data->getValue($i, 'AG')));
			$this->employee[$i]['work_email'] = (string) trim(utf8_encode($this->data->getValue($i, 'AH')));
			$this->employee[$i]['bank_name'] = (string) trim(utf8_encode($this->data->getValue($i, 'AI')));
		
			$this->employee[$i]['bank_account_number'] = (string) trim($this->data->getValue($i, 'AJ'));
			$this->employee[$i]['emergency_contact_name'] = (string) trim(utf8_encode($this->data->getValue($i, 'AK')));
			$this->employee[$i]['emergency_number'] = (string) trim($this->data->getValue($i, 'AL'));
			$this->employee[$i]['username'] = (string) trim($this->data->getValue($i, 'AM'));
			$this->employee[$i]['password'] = (string) trim($this->data->getValue($i, 'AN'));
			$this->employee[$i]['module'] = (string) trim($this->data->getValue($i, 'AO'));
		
	}
	
	public function result()
	{
		$this->count_duplicate=0;
		$this->duplicate_list='';
			foreach(array_count_values($this->array_duplicate) as $key=>$val ) {
				if($val>1) {
					$this->error_count++;
					$this->count_duplicate++;
					$this->duplicate_list.="\n";
					$this->duplicate_list.="Employee Code:".$key." have ".$val." record(s)";
				}	
			}
				
			if ($this->imported_count > 0 || $this->imported_update_count>0) {
				$return['is_imported'] = true;
				
				if ($this->error_count > 0) {//HAVE ERRORS
					$this->total_row = $this->total_row - 1; // minus the excel title header
					$msg =  $this->imported_count. ' of '.$this->total_row .' records has been successfully imported.<br>';
					if($this->error_branch_name>0) {
						$_SESSION['hr']['error'].= 'Please Fix '. $this->error_branch_name.' error(s) found in Branch Column.';
						$_SESSION['hr']['error'].="\n";
						$_SESSION['hr']['error'].= 'List of row(s):';	
						foreach ($this->report_row_no_branch as $key=>$val) {
							$_SESSION['hr']['error'].= "\nrow: ". $val;	
						}
						$_SESSION['hr']['error'].="\n\n";
					}
					
					if($this->error_department>0) {
						$_SESSION['hr']['error'] .= 'Please Fix '. $this->error_department .' error(s) found in Department Column.';
						$_SESSION['hr']['error'].="\n";
						$_SESSION['hr']['error'].= 'List of row(s):';	
						foreach ($this->report_row_no_department as $key=>$val) {
							$_SESSION['hr']['error'].= "\nrow: ". $val;	
						}	
						$_SESSION['hr']['error'].="\n\n";
					}
					
					if($this->error_complete_name>0) {
						$_SESSION['hr']['error'] .= 'Please Fix '. $this->error_complete_name .' error(s) found in Employee Name Column.';
						$_SESSION['hr']['error'].="\n";
						$_SESSION['hr']['error'].= 'List of row(s):';	
						foreach($this->report_row_no_fullname as $key=>$value){
							$_SESSION['hr']['error'] .= "\nrow: ".$value;	
						}
						$_SESSION['hr']['error'].="\n\n";
					}
					
					if($this->warning_fullname>0) {
						$_SESSION['hr']['error'] .= 'Warning:  '. $this->warning_fullname .' Incomplete Employee Name.';
						$_SESSION['hr']['error'].="\n";
						$_SESSION['hr']['error'].= 'List of row(s):';
						
						foreach($this->report_row_incomplete_fullname as $key=>$value){
							$_SESSION['hr']['error'].= "\nrow: ".$value;	
						}	
						$_SESSION['hr']['error'].="\n\n";
					}
					
					
					if($this->count_duplicate>0) {
						$_SESSION['hr']['error'] .= 'Warning:  '. $this->count_duplicate .' Duplicate Employee code .';
						$_SESSION['hr']['error'].="\n";
						$_SESSION['hr']['error'].= 'List of Employee Code:';
					
						$_SESSION['hr']['error'].=$this->duplicate_list;	
					}
					
					$msg .= "<a target='_blank' href='employee/_import_error'>Download Error Report</a>";
					$return['message']= $msg;
				} else {
					//NO ERRORS
					if($this->imported_count>0) {
						$return['message'] .= $this->imported_count . ' Record(s) has been successfully imported.';
						$return['message'] .= "\n";
					}
					
					if($this->imported_update_count>0) {
						$return['message'] .= $this->imported_update_count . ' Record(s) has been successfully updated.';
						 
					}
					
				}
			}elseif($this->is_format_valid==0) {
				$return['message'] = 'Invalid Excel Format';
			} else {
				$return['message'] = 'There was a problem importing the Employee. Please contact the administrator.';
			}
			//echo json_encode($return);	
			return $return['message'];	
	}
	
}
?>