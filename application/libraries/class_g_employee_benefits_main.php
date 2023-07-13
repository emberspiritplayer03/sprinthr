<?php
class G_Employee_Benefits_Main extends Employee_Benefits_Main {
	
	protected $fields;
	protected $description;
	protected $criteria = '';
	protected $custom_criteria = '';
	protected $employee_custom_criteria = array();
	protected $bulk_enrollees  = array();
	protected $b_bulk_save     = false;
	protected $a_bulk_insert   = array();		
	protected $cutoff_period;
	protected $cutoff_end_date;
	protected $cutoff_start_date;
	protected $date_created = '';
	protected $excluded_emplooyee_id = [];

	protected $attendance;

	protected $file_to_import;
	protected $obj_reader;
	public $a_employee_benefits = array();

	const CRITERIA_NO_ABSENT    = "No Absent";
	const CRITERIA_NO_LATE      = "No Late";
	const CRITERIA_NO_UNDERTIME = "No Undertime";
	const CRITERIA_NO_LEAVE     = "No Leave";

	//Custom criteria with inputs
	const CUSTOM_CRITERIA_ABSENT_DAYS = "Days Absent";
	const CUSTOM_CRITERIA_LEAVE_DAYS  = "Days Leave";
	const CUSTOM_CRITERIA_ABSENT_LEAVE_DAYS = "Days Absent and Leave";

	const NO_CRITERIA = "All";

	public function __construct() {
		
	}

	public function setDateCreated( $value ) {
		$this->date_created = $value;
	}

	public function setImportFile($file){
		$this->file_to_import = $file;
		$inputFileType    = PHPExcel_IOFactory::identify($this->file_to_import);
		$objReader        = PHPExcel_IOFactory::createReader($inputFileType);
		$this->obj_reader = $objReader->load($this->file_to_import);
		return $this;
	}

	public function setCutoffStartDate($value){
		$this->cutoff_start_date = $value;
		return $this;
	}
	
	public function setCutoffEndDate($value){
		$this->cutoff_end_date = $value;
		return $this;
	}

	public function setAttendance( G_Attendance $a ){
		$this->attendance = $a;
		return $this;
	}

	public function validAppliedToOptions() {
		$applied_to = array(Employee_Benefits_Main::EMPLOYEE,Employee_Benefits_Main::DEPARTMENT,Employee_Benefits_Main::EMPLOYMENT_STATUS,Employee_Benefits_Main::ALL_EMPLOYEE);
		return $applied_to;
	}

	public function getCriteriaOptions(){
		$a_criteria = array(self::CRITERIA_NO_UNDERTIME, self::CRITERIA_NO_LATE, self::CRITERIA_NO_ABSENT, self::CRITERIA_NO_LEAVE);
		return $a_criteria;		
	}

	public function getCustomCriteriaOptions() {
		$a_custom_criteria = array(self::CUSTOM_CRITERIA_ABSENT_DAYS, self::CUSTOM_CRITERIA_LEAVE_DAYS, self::CUSTOM_CRITERIA_ABSENT_LEAVE_DAYS);
		return $a_custom_criteria;
	}

	public function setCustomCriteria( $value ) {
		$this->custom_criteria = $value;
		return $this;
	} 

	public function setEmployeeCustomCriteria( $values = array() ) {
		$this->employee_custom_criteria = $values;
		return $this;
	}

	public function getCustomCriteria() {
		return $this->custom_criteria;
	}

	public function setDescription( $value = '' ) {
		$this->description = $value;
	}

	public function setCutoffPeriod( $value = '' ) {
		$this->cutoff_period = $value;
		return $this;
	}

	public function setCriteria( $value = '' ){
		$this->criteria = $value;
		return $this;
	}

	public function getCriteria(){
		return $this->criteria;
	}

	public function getDescription() {
		return $this->description;
	}

	public function setBulkEnrollees( $a_data = array() ) {
		$this->bulk_enrollees = $a_data;
		return $this;
	}

	public function setExcludedEmployeeId($value){
		$this->excluded_emplooyee_id = $value;
	}

	public function getExcludedEmployeeId(){
		return $this->excluded_emplooyee_id;
	}

	public function removeDuplicateBulkEnrollees() {		
		if( !empty( $this->bulk_enrollees ) ){
			$bulk_data     = $this->bulk_enrollees;
			$new_bulk_data = array();
			foreach( $bulk_data as $key => $data ){
				$a_filtered = array_unique($data, SORT_REGULAR);
				$new_bulk_data[$key] = $a_filtered;
			}
			$this->bulk_enrollees = $new_bulk_data;
		}

		return $this;
	}

	public function sanitizeBulkEnrollees( $b_decrypt_id = false ) {
		if( !empty( $this->bulk_enrollees ) ){
			$bulk_data 	   = $this->bulk_enrollees;
			$new_bulk_data = array();
			foreach( $bulk_data as $key => $data ){
				foreach( $data as $value ){
					$s_value = trim($value);
					$s_value = filter_var($s_value, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
					if( $b_decrypt_id ){
						$s_value = Utilities::decrypt($value);
					}
					$new_bulk_data[$key][] = $s_value;
				}
			}
			$this->bulk_enrollees = $new_bulk_data;
		}		
		return $this;
	}

	public function sanitizeString( $string = '', $string_case = '' ){		
		$s_string   = filter_var($string, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
		$new_string = trim($s_string);
		switch ($string_case) {
			case 'toupper':				
				$new_string = strtoupper($s_string);
				break;
			case 'tolower':
				$new_string = strtolower($s_string);
				break;
			case 'towords':
				$new_string = ucwords($s_string);
				break;
			default:				
				break;
		}

		return $new_string;
	}

	public function deleteDuplicateData() {		
		$is_with_existing = false;
		if( !empty($this->bulk_enrollees) && $this->benefit_id > 0 ){
			$a_ids 		 = array();
			$bulk_data   = $this->bulk_enrollees;
			foreach( $bulk_data as $key => $value ){
				foreach( $value as $subValue ){			
					$object_id = $subValue;
					switch ($key) {
						case 'employees':
							$type = Employee_Benefits_Main::EMPLOYEE;						
							break;
						case 'dept_section':
							$type = Employee_Benefits_Main::DEPARTMENT;
							break;
						case 'employment_status':
							$type = Employee_Benefits_Main::EMPLOYMENT_STATUS;
							break;
						default:						
							break;
					}	
					$fields = array("id");
					$data   = G_Employee_Benefits_Main_Helper::sqlFindByEmployeeDepartmentIdAndBenefitIdAndAppliedTo($object_id, $this->benefit_id, $type, $fields);
					if( !empty($data) ){
						$a_ids[] 		  = $data['id'];
						$is_with_existing = true;
					} 
				}
			}

			if( $is_with_existing ){
				//Delete existing data
				$s_ids = implode(",", $a_ids);
				self::deleteAllByIds($s_ids);
			}						
		}	
		return $this;
	}

	private function deleteAllByIds($ids = '') {
		G_Employee_Benefits_Main_Manager::deleteAllByIds($ids);
	}

	public function createBulkSaveArray() {	
		$this->b_bulk_save =false;			
		if( !empty($this->bulk_enrollees) && $this->benefit_id > 0 ){			
			$bulk_data   = $this->bulk_enrollees;
			$bulk_insert = array();
			foreach( $bulk_data as $key => $data ){	
				switch ($key) {
					case 'employees':	
						foreach( $data as $value ){							
							$id     = $value;
							$fields = array("CONCAT(firstname, ' ', lastname)AS employee_name"); 
							$data   = G_Employee_Helper::sqlGetEmployeeDetailsById($id, $fields);
							if( !empty($data) ){												
								$type          = Employee_Benefits_Main::EMPLOYEE;
								$description   = $data['employee_name'];
								$bulk_insert[] = "(" . Model::safeSql($this->company_structure_id) . "," . Model::safeSql($id) . "," . Model::safeSql($this->benefit_id) . "," . Model::safeSql($description) . "," . Model::safeSql($type) . "," . Model::safeSql($this->criteria) . "," . Model::safeSql($this->custom_criteria) . ", '')"; 
							} 
						}					
						break;
					case 'dept_section':
						foreach( $data as $value ){
							$id     = $value;
							$fields = array("title"); 
							$data   = G_Company_Structure_Helper::sqlDataById($id, $fields);
							if( !empty($data) ){
								$type          = Employee_Benefits_Main::DEPARTMENT;
								$description   = $data['title'];
								$bulk_insert[] = "(" . Model::safeSql($this->company_structure_id) . "," . Model::safeSql($id) . "," . Model::safeSql($this->benefit_id) . "," . Model::safeSql($description) . "," . Model::safeSql($type) . "," . Model::safeSql($this->criteria) . "," . Model::safeSql(join(',', $this->excluded_emplooyee_id)) . ", '')"; 
							} 
						}	
						break;
					case 'employment_status':
						foreach( $data as $value ){
							$id     = $value;
							$fields = array("status"); 
							$data   = G_Settings_Employment_Status_Helper::sqlDataById($id, $fields);
							if( !empty($data) ){
								$type          = Employee_Benefits_Main::EMPLOYMENT_STATUS;
								$description   = $data['status'];
								$bulk_insert[] = "(" . Model::safeSql($this->company_structure_id) . "," . Model::safeSql($id) . "," . Model::safeSql($this->benefit_id) . "," . Model::safeSql($description) . "," . Model::safeSql($type) . "," . Model::safeSql($this->criteria) . "," . Model::safeSql($this->custom_criteria) . "," . Model::safeSql(join(',', $this->excluded_emplooyee_id)) . ")"; 
							} 
						}
						break;
					default:						
						break;
				}
			}
			
			$total_records = count($bulk_insert);
			if( $total_records > 0 ){
				$this->b_bulk_save = true;
				$this->a_bulk_insert = $bulk_insert;
			}
		}

		return $this;
	}

	public function setFields($values = array()) {
		$this->fields = $values;
	}

	public function getEmployeeBenefitsWithCustomCriteria(){

	}

	public function convertToArrayCustomCriteria( $custom_criteria = array() ){
		if( $custom_criteria != '' ){
			$a_custom_criteria = explode(",", $custom_criteria);
			foreach( $a_custom_criteria as $criteria ){
				$a_sub_custom_criteria = explode("/", trim($criteria));
				if( !empty($a_sub_custom_criteria) ){
					$days_criteria  = $a_sub_custom_criteria[0];
					$range_criteria = $a_sub_custom_criteria[1];

					$a_days_criteria  = explode(":", $days_criteria);

					$new_custom_criteria[trim($a_days_criteria[0])]['days_need'] = $a_days_criteria[1];
					$new_custom_criteria[trim($a_days_criteria[0])]['from_to']   = $range_criteria;
				}
			}
		}

		return $new_custom_criteria;
	}

	public function getEmployeeBenefitsWithCriteria( IEmployee $e, $applied_to = array(), $occurence = array() ){
		$benefits = array();		
		if( !empty($e) && !empty($applied_to) ){			
			$new_occurence = implode(",", $occurence);
			foreach( $applied_to as $value ){
				switch ($value) {
					case Employee_Benefits_Main::EMPLOYEE:
						$data = G_Employee_Benefits_Main_Helper::sqlAllEmployeeBenefitsByObjectIdAndAppliedToAndCriteria($e->getId(), $value, $new_occurence, $this->criteria);						
						break;
					case Employee_Benefits_Main::DEPARTMENT:
						$data = G_Employee_Benefits_Main_Helper::sqlAllEmployeeBenefitsByObjectIdAndAppliedToAndCriteria($e->getDepartmentCompanyStructureId(), $value, $new_occurence, $this->criteria);						
						break;
					case Employee_Benefits_Main::EMPLOYMENT_STATUS:
						$data = G_Employee_Benefits_Main_Helper::sqlAllEmployeeBenefitsByObjectIdAndAppliedToAndCriteria($e->getEmploymentStatusId(), $value, $new_occurence, $this->criteria);									
						break;
					case Employee_Benefits_Main::ALL_EMPLOYEE:
						$data = G_Employee_Benefits_Main_Helper::sqlAllEmployeeBenefitsByObjectIdAndAppliedToAndCriteria(0, $value, $new_occurence, $this->criteria);
						break;
					default:						
						break;
				}

				foreach( $data as $value ){					
					$custom_criteria   = trim($value['custom_criteria']);
					$a_custom_criteria = self::convertToArrayCustomCriteria($custom_criteria);					
					
					foreach( $value as $key => $subValue ){
						if( $key == 'criteria' && $subValue != "" ){
							if( !empty($this->criteria) ){								
								$a_result_criteria = explode(",", $subValue);
								$a_criteria        = explode(",", $this->criteria);
								$match_need        = count($a_result_criteria);
								$count_match       = 0;

								foreach( $a_criteria as $s_criteria ){							
									if( in_array($s_criteria, $a_result_criteria) ){								
										$count_match++;
									}
								}

								if( $count_match === $match_need ){									
									if( !empty( $a_custom_criteria ) ){										
										$cc_count_match   = 0;
										$cc_match_need = count($a_custom_criteria);
										foreach( $a_custom_criteria as $key => $a_criteria ){
											$days_needed = trim($a_criteria['days_need']);
											$from_to     = explode("to", $a_criteria['from_to']);
											$i_from      = trim($from_to[0]);
											$i_to        = trim($from_to[1]);
											
											if( $i_from > $i_to ){
												$month_year = strtotime(date("Y-m-1",strtotime("-1 month", strtotime($this->cutoff_end_date))));
												$new_month_year_from = date("Y-m-d",$month_year);
												$new_month_year_to   = date("Y-m-d",strtotime("+1 month", $month_year));
											}else{
												$month_year = strtotime(date("Y-m-1",strtotime($this->cutoff_end_date)));
												$new_month_year_from = date("Y-m-d",$month_year);
												$new_month_year_to   = date("Y-m-d",$month_year);
											}

											$i_month_from = date("m",strtotime($new_month_year_from));
											$i_year_from  = date("Y",strtotime($new_month_year_from));

											$i_month_to = date("m",strtotime($new_month_year_to));
											$i_year_to  = date("Y",strtotime($new_month_year_to));

											if( ($i_month_from == 2 && $i_from > 28) || $i_from > 30 ){
												$i_from_date = $i_year_from . "-" . $i_month_from . "-" . 01;
												$last_day    = date("Y-m-t",strtotime($i_from_date));
												$from_date   = $i_year_from . "-" . $i_month_from . "-" . $last_day;
											}else{
												$from_date   = $i_year_from . "-" . $i_month_from . "-" . $i_from;
											}

											if( ($i_month_to == 2 && $i_to > 28) || $i_to > 30 ){
												$i_to_date = $i_year_to . "-" . $i_month_to . "-" . 01;
												$last_day    = date("Y-m-t",strtotime($i_to_date));
												$to_date   = $i_year_to . "-" . $i_month_to . "-" . $last_day;
											}else{
												$to_date   = $i_year_to . "-" . $i_month_to . "-" . $i_to;
											}

											$to_date   = date("Y-m-d",strtotime($to_date));
											$from_date = date("Y-m-d",strtotime($from_date)); 

											//echo "From Date : {$from_date} / To Date : {$to_date}<Br />";

											switch (trim($key)) {
												case self::CUSTOM_CRITERIA_ABSENT_DAYS:
													$days_absent = G_Attendance_Helper::sqlCountAbsentDaysByEmployeeIdAndFromAndToDate($e->getId(), $from_date, $to_date);
													//if( $days_absent <= $days_needed || $days_absent == 0 ){	
													if( $days_absent == $days_needed ){	
														$cc_count_match++;
													}
													break;
												case self::CUSTOM_CRITERIA_LEAVE_DAYS:
													$days_leave = G_Attendance_Helper::sqlCountLeaveDaysByEmployeeIdAndFromAndToDate($e->getId(), $from_date, $to_date);
													//if( $days_leave <= $days_needed || $days_leave == 0 ){	
													if( $days_leave == $days_needed ){	
														$cc_count_match++;
													}
													break;
												case self::CUSTOM_CRITERIA_ABSENT_LEAVE_DAYS:
													if( $days_absent <= 0 ){
														$days_absent = G_Attendance_Helper::sqlCountAbsentDaysByEmployeeIdAndFromAndToDate($e->getId(), $from_date, $to_date);
													}

													if( $days_leave <= 0 ){
														$days_leave = G_Attendance_Helper::sqlCountLeaveDaysByEmployeeIdAndFromAndToDate($e->getId(), $from_date, $to_date);
													}

													$total_leave_absences = $days_absent + $days_leave;
													if( $total_leave_absences == $days_needed ){	
														$cc_count_match++;
													}
													break;
												default:													
													break;
											}
										}

										if( $count_match === $match_need && $cc_count_match === $cc_match_need ){
											if(!in_array($e->getId(),explode(',', $value['excluded_emplooyee_id'])))
											$benefits[$value['code'] . "_" . $value['id']] = $value;	
										}
									}else{
										if(!in_array($e->getId(),explode(',', $value['excluded_emplooyee_id'])))
										$benefits[$value['code'] . "_" . $value['id']] = $value;	
									}
								}
							}else{				
								if( !empty( $a_custom_criteria ) ){	

									$cc_count_match   = 0;
									$cc_match_need = count($a_custom_criteria);									
									foreach( $a_custom_criteria as $key => $a_criteria ){									
										$days_needed = trim($a_criteria['days_need']);
										$from_to     = explode("to", $a_criteria['from_to']);
										$i_from      = trim($from_to[0]);
										$i_to        = trim($from_to[1]);

										if( $i_from > $i_to ){
											$month_year = strtotime(date("Y-m-1",strtotime("-1 month", strtotime($this->cutoff_end_date))));
											$new_month_year_from = date("Y-m-d",$month_year);
											$new_month_year_to   = date("Y-m-d",strtotime("+1 month", $month_year));
										}else{
											$month_year = strtotime(date("Y-m-1",strtotime($this->cutoff_end_date)));
											$new_month_year_from = date("Y-m-d",$month_year);
											$new_month_year_to   = date("Y-m-d",$month_year);
										}

										$i_month_from = date("m",strtotime($new_month_year_from));
										$i_year_from  = date("Y",strtotime($new_month_year_from));

										$i_month_to = date("m",strtotime($new_month_year_to));
										$i_year_to  = date("Y",strtotime($new_month_year_to));

										if( ($i_month_from == 2 && $i_from > 28) || $i_from > 30 ){
											$i_from_date = $i_year_from . "-" . $i_month_from . "-" . 01;
											$last_day    = date("Y-m-t",strtotime($i_from_date));
											$from_date   = $i_year_from . "-" . $i_month_from . "-" . $last_day;
										}else{
											$from_date   = $i_year_from . "-" . $i_month_from . "-" . $i_from;
										}

										if( ($i_month_to == 2 && $i_to > 28) || $i_to > 30 ){
											$i_to_date = $i_year_to . "-" . $i_month_to . "-" . 01;
											$last_day    = date("Y-m-t",strtotime($i_to_date));
											$to_date   = $i_year_to . "-" . $i_month_to . "-" . $last_day;
										}else{
											$to_date   = $i_year_to . "-" . $i_month_to . "-" . $i_to;
										}
										
										$to_date   = date("Y-m-d",strtotime($to_date));
										$from_date = date("Y-m-d",strtotime($from_date)); 

										switch ($key) {											
											case self::CUSTOM_CRITERIA_ABSENT_DAYS:											
												$days_absent = G_Attendance_Helper::sqlCountAbsentDaysByEmployeeIdAndFromAndToDate($e->getId(), $from_date, $to_date);														
												//if( $days_absent <= $days_needed || $days_absent == 0 ){													
												if( $days_absent == $days_needed ){													
													$cc_count_match++;																					
												}
												break;
											case self::CUSTOM_CRITERIA_LEAVE_DAYS:
												$days_leave = G_Attendance_Helper::sqlCountLeaveDaysByEmployeeIdAndFromAndToDate($e->getId(), $from_date, $to_date);
												//if( $days_leave <= $days_needed || $days_leave == 0 ){													
												if( $days_leave == $days_needed ){
													$cc_count_match++;
												}
												break;
											default:													
												break;
										}
									}

									if( $count_match === $match_need && $cc_count_match === $cc_match_need ){
										if(!in_array($e->getId(),explode(',', $value['excluded_emplooyee_id'])))
										$benefits[$value['code'] . "_" . $value['id']] = $value;	
									}

								}else{
									if(!in_array($e->getId(),explode(',', $value['excluded_emplooyee_id'])))
									$benefits[$value['code'] . "_" . $value['id']] = $value;	
								}
							}							
						}elseif( $key == 'custom_criteria' && $subValue != "" ){
							if( !empty( $a_custom_criteria ) ){
								$cc_count_match   = 0;
								$cc_match_need = count($a_custom_criteria);
								foreach( $a_custom_criteria as $key => $a_criteria ){									
									$days_needed = trim($a_criteria['days_need']);
									$from_to     = explode("to", $a_criteria['from_to']);
									$i_from      = trim($from_to[0]);
									$i_to        = trim($from_to[1]);

									if( $i_from > $i_to ){
										$month_year = strtotime(date("Y-m-1",strtotime("-1 month", strtotime($this->cutoff_end_date))));
										$new_month_year_from = date("Y-m-d",$month_year);
										$new_month_year_to   = date("Y-m-d",strtotime("+1 month", $month_year));
									}else{
										$month_year = strtotime(date("Y-m-1",strtotime($this->cutoff_end_date)));
										$new_month_year_from = date("Y-m-d",$month_year);
										$new_month_year_to   = date("Y-m-d",$month_year);
									}

									$i_month_from = date("m",strtotime($new_month_year_from));
									$i_year_from  = date("Y",strtotime($new_month_year_from));

									$i_month_to = date("m",strtotime($new_month_year_to));
									$i_year_to  = date("Y",strtotime($new_month_year_to));

									if( ($i_month_from == 2 && $i_from > 28) || $i_from > 30 ){
										$i_from_date = $i_year_from . "-" . $i_month_from . "-" . 01;
										$last_day    = date("Y-m-t",strtotime($i_from_date));
										$from_date   = $i_year_from . "-" . $i_month_from . "-" . $last_day;
									}else{
										$from_date   = $i_year_from . "-" . $i_month_from . "-" . $i_from;
									}

									if( ($i_month_to == 2 && $i_to > 28) || $i_to > 30 ){
										$i_to_date = $i_year_to . "-" . $i_month_to . "-" . 01;
										$last_day    = date("Y-m-t",strtotime($i_to_date));
										$to_date   = $i_year_to . "-" . $i_month_to . "-" . $last_day;
									}else{
										$to_date   = $i_year_to . "-" . $i_month_to . "-" . $i_to;
									}
									
									$to_date   = date("Y-m-d",strtotime($to_date));
									$from_date = date("Y-m-d",strtotime($from_date)); 
									
									switch ($key) {											
										case self::CUSTOM_CRITERIA_ABSENT_DAYS:											
											$days_absent = G_Attendance_Helper::sqlCountAbsentDaysByEmployeeIdAndFromAndToDate($e->getId(), $from_date, $to_date);														
											//if( $days_absent <= $days_needed || $days_absent == 0 ){													
											if( $days_absent == $days_needed ){													
												$cc_count_match++;																					
											}
											break;
										case self::CUSTOM_CRITERIA_LEAVE_DAYS:
											$days_leave = G_Attendance_Helper::sqlCountLeaveDaysByEmployeeIdAndFromAndToDate($e->getId(), $from_date, $to_date);
											//if( $days_leave <= $days_needed || $days_leave == 0 ){													
											if( $days_leave == $days_needed ){
												$cc_count_match++;
											}
											break;
										case self::CUSTOM_CRITERIA_ABSENT_LEAVE_DAYS:
											$days_absent = G_Attendance_Helper::sqlCountAbsentDaysByEmployeeIdAndFromAndToDate($e->getId(), $from_date, $to_date);
											$days_leave = G_Attendance_Helper::sqlCountLeaveDaysByEmployeeIdAndFromAndToDate($e->getId(), $from_date, $to_date);
											if( $days_absent == $days_needed ){													
												$cc_count_match++;																					
											}
											if( $days_leave == $days_needed ){
												$cc_count_match++;
											}
										default:													
											break;
									}
								}

								if( $count_match === $match_need && $cc_count_match === $cc_match_need ){
									if(!in_array($e->getId(),explode(',', $value['excluded_emplooyee_id'])))
									$benefits[$value['code'] . "_" . $value['id']] = $value;	
								}
							}else{
								if(!in_array($e->getId(),explode(',', $value['excluded_emplooyee_id'])))
								$benefits[$value['code'] . "_" . $value['id']] = $value;	
							}
						}elseif( $value['criteria'] == '' && $value['custom_criteria'] == '' ){
							if(!in_array($e->getId(),explode(',', $value['excluded_emplooyee_id'])))
							$benefits[$value['code'] . "_" . $value['id']] = $value;
						}
					}					
				}

			}	

			$this->a_employee_benefits = $benefits;
		}

		return $this;
	}

	private function removeDuplicateBenefits( $benefits = array() ){
		if( !empty( $benefits ) ){

		}
	}

	public function convertBenefitsToEarningsArray(){
		$earnings = array();
		if( !empty($this->a_employee_benefits) ){			
			$benefits = $this->a_employee_benefits;				
			foreach( $benefits as $key => $value ){
				if ( $value['is_taxable'] == Employee_Benefits_Main::YES ) {
                    $is_taxable = Earning::TAXABLE;
                } else {
                    $is_taxable = Earning::NON_TAXABLE;
                }	                
                $title  = $value['name'];
                if( !empty($value['multiplied_by']) ){
                	$amount = $value['amount'] . "/" . $value['multiplied_by'];
                }else{
                	$amount = $value['amount'];
                }
                $earn   = new Earning($title, $amount, $is_taxable);
                $earnings[] = $earn;                
			}
		}		
		return $earnings;
	}

	public static function getEmployeeBenefits(IEmployee $e) {
		$benefits = array();

		if( !empty($e) ){
			$data = G_Employee_Benefits_Main_Helper::sqlAllEmployeeBenefitsByEmployeeId($e->getId());
			if( $data ){
				foreach( $data as $d ){
					if ( $d['is_taxable'] == Employee_Benefits_Main::YES ) {
	                    $is_taxable = Earning::TAXABLE;
	                } else {
	                    $is_taxable = Earning::NON_TAXABLE;
	                }	                
	                $title = $d['name'];
	                $earn = new Earning($title, $d['amount'], $is_taxable);
	                $benefits[] = $earn;
				}
			}
		}
		return $benefits;
    }

    public static function getEmployeeEnrolledBenefits(IEmployee $e) {
		$data = array();

		if( !empty($e) ){
			$data = G_Employee_Benefits_Main_Helper::sqlAllEmployeeBenefitsByEmployeeId($e->getId());			
		}
		return $data;
    }

	public function getAllEmployeesEnrolledToBenefit($order_by = "", $limit = "") {
		$data = array();

		if( !empty($this->benefit_id) ){
			$data = G_Employee_Benefits_Main_Helper::sqlAllEmployeesEnrolledToBenefit($this->benefit_id, $order_by, $limit);	
		} 
		return $data;
	}

	public function getAllEnrolledToBenefits($order_by = "", $limit = "") {
		$data = array();

		if( !empty($this->benefit_id) ){
			$data = G_Employee_Benefits_Main_Helper::sqlAllDataByBenefitId($this->benefit_id, $order_by, $limit);	
		} 
		return $data;
	}

	public function countTotalEnrolledToBenefit() {
		$total_records = 0;
		
		if( !empty($this->benefit_id) ){
			$total_records = G_Employee_Benefits_Main_Helper::sqlCountTotalEnrolledToBenefit($this->benefit_id);		
		}

		return $total_records;
	}

	public function countTotalEmployeesEnrolledToBenefit() {
		$total_records = 0;
		
		if( !empty($this->benefit_id) ){
			$total_records = G_Employee_Benefits_Main_Helper::sqlCountTotalEmployeesEnrolledToBenefit($this->benefit_id);		
		}

		return $total_records;
	}

	/*
		Usage :
		$benefit_id 			= 1;
		$company_structure_id   = 1;
		$apply_to 				= Employee_Benefits_Main::EMPLOYEE; 
		//$apply_to 				= Employee_Benefits_Main::ALL_EMPLOYEE;
		$encrypted_employee_ids = "wP4dkK9Ud-6p2NLXkg4M80I-vv0Zaal9dv88kdYYo8s,NltI_8hiiaR7gMKq87Dzo27f53HGpQJQqmpuo-gOcJg";

		$b = new G_Employee_Benefits_Main();
		$b->setCompanyStructureid($company_structure_id);
		$b->setBenefitId($benefit_id);
		$b->setAppliedTo($apply_to);
		$data = $b->enrollToBenefit($encrypted_employee_ids); //Returns array
	*/

	public function enrollToBenefit( $encrypted_employee_ids = '' ){
		$return = array();

		if( !empty($this->benefit_id) && !empty($this->applied_to) ){

			$is_benefit_assigned_to_all_employee = G_Employee_Benefits_Main_Helper::sqlIsBenfitIdAssignedToAllEmployee($this->benefit_id); //Check if benefit set is already applied to all employees

			if( $is_benefit_assigned_to_all_employee ){
				$return['is_success'] = true;
				$return['message']    = 'Selected benefit already applied to all employees';
			}else{
				if( $this->applied_to == self::EMPLOYEE && !empty($encrypted_employee_ids) ){
					$employee_ids = explode(",", $encrypted_employee_ids);				

					foreach($employee_ids as $key => $value){
						$employee_ids[$key] = Utilities::decrypt($value);
					}

					$is_success   = G_Employee_Benefits_Main_Manager::bulkEnrollToBenefit($this, $employee_ids);

					if( $is_success ){
						$return['is_success'] = true;
						$return['message']    = 'Record Saved';
					}else{
						$return['is_success'] = false;
						$return['message']    = 'Cannot save record';
					}

				}elseif( $this->applied_to == self::ALL_EMPLOYEE ){
					$this->employee_department_id = 0;
					self::deleteAllEnrolledEmployeesByBenefitId($this->benefit_id); //Delete all currently enrolled employees. Will replace with Apply to All
					$is_success = self::save();

					if( $is_success > 0 ){
						$return['is_success'] = true;
						$return['message']    = 'Record Saved';
					}else{
						$return['is_success'] = false;
						$return['message']    = 'Cannot save record';
					}

				}else{
					$return['is_success'] = false;
					$return['message']    = 'Cannot save record';
				}
			}
			
		}else{
			$return['is_success'] = false;
			$return['message']    = 'Cannot save record';
		}

		return $return;
	}

	/*
		Usage :
		$keyword = "Bryan";
		$b = new G_Employee_Benefits_Main();
		$b->setId($beid);
		$employees = $b->searchEmployeesByKeywordNotEnrolledToBenefit($keyword); //Will return array - employee id, employee name
	*/

	public function searchEmployeesByKeywordNotEnrolledToBenefit($keyword = '') {
		$data = array();

		if( !empty($this->id) ){
			$data = G_Employee_Benefits_Main_Helper::sqlSearchEmployeesByKeywordNotEnrolledToBenefit($this->id, $keyword);
		}

		return $data;
	}

	public function searchDepartmentSectionByKeywordNotEnrolledToBenefit($keyword = '') {
		$data = array();

		if( !empty($this->id) ){
			$data = G_Employee_Benefits_Main_Helper::sqlSearchDepartmentSectionByKeywordNotEnrolledToBenefit($this->id, $keyword);
		}

		return $data;
	}

	public function searchEmploymentStatusByKeywordNotEnrolledToBenefit($keyword = '') {
		$data = array();

		if( !empty($this->id) ){
			$data = G_Employee_Benefits_Main_Helper::sqlSearchEmploymentStatusByKeywordNotEnrolledToBenefit($this->id, $keyword);
		}

		return $data;
	}

	public function getAllEmployeeBenefits() {
		$data = array();

		if( $this->employee_department_id > 0  && $this->company_structure_id > 0 ){
			$order_by = "b.code, b.name ASC";
			$data     = G_Employee_Benefits_Main_Helper::sqlAllEmployeeBenefitsByEmployeeIdAndCompanyStructureId($this->employee_department_id, $this->company_structure_id, $order_by);
		}

		return $data;
	}
							
	public function save() {
		$return['is_success'] = false;
		$return['message']    = 'Cannot save data';

		$id =  G_Employee_Benefits_Main_Manager::save($this);

		if( $id > 0 ){
			$return['is_success'] = true;
			$return['message']    = 'Record saved';
		}

		return $return;
	}

	/*
		Usage :
		$id = 1;
		$b = G_Employee_Benefits_Main_Finder::findById($id);
		$return = $b->deleteEnrollee(); //Returns array		
	*/

	public function deleteEnrollee(){
		$return = array();
		
		$return['is_success'] = false;
		$return['message']    = 'Record not found';
		$return['eid']        = Utilities::encrypt($this->benefit_id);

		if( $this->benefit_id > 0 && $this->id > 0 ){
			self::delete();			
			$return['is_success'] = true;
			$return['message']    = 'Record deleted';
		}

		return $return;
	}

	public function createImportBulkDataCustom(){						
		$read_sheet  = $this->obj_reader->getActiveSheet();
		$a_benefits  = array();
		$s_emp_code  = "";
		foreach ($read_sheet->getRowIterator() as $row) {          
			$cellIterator = $row->getCellIterator();
		   	foreach ($cellIterator as $cell) {				
				$current_row    = $cell->getRow();
				$cell_value     = $cell->getFormattedValue();
				$column         = $cell->getColumn();
				$current_column = PHPExcel_Cell::columnIndexFromString($cell->getColumn());               
                if ($current_row == 1) {                    
                    $column_header[$column] = strtolower(trim($cell_value));                    
                }else{                    
                    $column_header_value = strtolower(trim($column_header[$column]));
                    $cell_value          = trim($cell_value);
                    if( $cell_value != "" && $cell_value > 0 ){
                    	switch ($column_header_value) {
                    		case 'emp id':
                    			$s_emp_code = $cell_value;
                    			$fields     = array("id","CONCAT(firstname, ' ', lastname)AS employee_name");
                    			$data       = G_Employee_Helper::sqlGetEmployeeDetailsByEmployeeCode($s_emp_code,$fields);
                    			$i_eid      = 0;
                    			if( !empty( $data ) ){
                    				$i_eid 		     = $data['id'];
                    				$s_employee_name = $data['employee_name'];
                    			}  
                    			break;
	                        case 'position allowance':
	                       		if( $cell_value > 0 ){
	                       			$s_code = "POSITION_ALLOWANCE_{$cell_value}";
	                       			$fields = array("id");
	                       			$benefit_data = G_Settings_Employee_Benefit_Helper::sqlGetBenefitDetailsByBenefitCode($s_code,$fields);
	                       			if( !empty($benefit_data) && $i_eid > 0 ){
	                       				$i_company_structure_id = 1;
		                       			$i_benefit_id           = $benefit_data['id'];
		                       			$s_applied_to           = Employee_Benefits_Main::EMPLOYEE;
		                       			$s_description			= $s_employee_name;		                       			
		                       			$a_benefits[]	        = "({$i_company_structure_id},{$i_eid},{$i_benefit_id},'{$s_description}','{$s_applied_to}','','')";
	                       			}
	                       		}			                        	
	                            break;
	                        case 'ctpa/sea':
	                        	if( $cell_value > 0 ){
	                       			$s_code          = "CTPA/SEA_{$cell_value}";
	                       			$fields = array("id");
	                       			$benefit_data = G_Settings_Employee_Benefit_Helper::sqlGetBenefitDetailsByBenefitCode($s_code,$fields);
	                       			if( !empty($benefit_data) && $i_eid > 0 ){
	                       				$i_company_structure_id = 1;
		                       			$i_benefit_id           = $benefit_data['id'];
		                       			$s_applied_to           = Employee_Benefits_Main::EMPLOYEE;
		                       			$s_description			= $s_employee_name;
		                       			$a_benefits[]	        = "({$i_company_structure_id},{$i_eid},{$i_benefit_id},'{$s_description}','{$s_applied_to}','','')";
	                       			}
	                       		}
	                            break;
	                        case 'other allowance':	    
	                        	if( $cell_value > 0 ){
	                       			$s_code          = "OTHER ALLOWANCE_{$cell_value}";
	                       			$fields = array("id");
	                       			$benefit_data = G_Settings_Employee_Benefit_Helper::sqlGetBenefitDetailsByBenefitCode($s_code,$fields);
	                       			if( !empty($benefit_data) && $i_eid > 0 ){
	                       				$i_company_structure_id = 1;
		                       			$i_benefit_id           = $benefit_data['id'];
		                       			$s_applied_to           = Employee_Benefits_Main::EMPLOYEE;
		                       			$s_description			= $s_employee_name;
		                       			$a_benefits[]	        = "({$i_company_structure_id},{$i_eid},{$i_benefit_id},'{$s_description}','{$s_applied_to}','','')";
	                       			}
	                       		}
	                            break;                                            
	                        default:                           
	                            break;
	                    }	
                    }
                }
			}
		}
		
		if( count($a_benefits) > 0 ){
			$this->b_bulk_save = true;
			$this->a_bulk_insert = $a_benefits;			
		}		
		return $this;
	}

	public function createImportBulkData(){		
		$read_sheet  = $this->obj_reader->getActiveSheet();
		$a_benefits  = array();
		$s_emp_code  = "";
		$a_valid_applied_type = array("employee id","all","department","employment status");
		$a_valid_cutoff       = array(1,2,3);
		$a_valid_taxable      = array(Employee_Benefits_Main::YES, Employee_Benefits_Main::NO);

		$b_row_data_valid     = false;
		$total_columns        = 8;
		$i_column_count       = 0;
		$i_row_count          = 0;

		foreach ($read_sheet->getRowIterator() as $row) {          
			$cellIterator = $row->getCellIterator();	
			$i_row_count++;		
		   	foreach ($cellIterator as $cell) {				
				$current_row    = $cell->getRow();
				$cell_value     = $cell->getFormattedValue();
				$column         = $cell->getColumn();
				$current_column = PHPExcel_Cell::columnIndexFromString($cell->getColumn());               
                if ($current_row == 1) {                    
                    $column_header[$column] = strtolower(trim($cell_value));                    
                }else{                    
                    $column_header_value = strtolower(trim($column_header[$column]));
                    $cell_value          = trim($cell_value);
                    //if( $cell_value != "" && $cell_value > 0 ){                    	
                    	if( strpos($column_header_value, 'applied type') !== false ){                         		
                    		if( in_array(strtolower($cell_value), $a_valid_applied_type) ){
                    			$b_row_data_valid = true;   
                    			$applied_type     = trim(strtolower($cell_value));                 			
                    		}else{                    			
                    			$b_row_data_valid = false;
                    			$applied_type     = "";
                    		}
                    		$i_column_count++;
                    	}elseif( strpos($column_header_value, 'applied to') !== false && $b_row_data_valid ){                    		
                    		switch ($applied_type) {
                    			case 'employee id':
                    				$a_employee_code = explode(",", $cell_value);
                    				$a_new_employee_code = array();
									foreach( $a_employee_code as $code ){
										$a_new_employee_code[] = "'{$code}'";
									}
									$new_column_string  = implode(",", $a_new_employee_code);    

                    				$fields = array("id","CONCAT(firstname, ' ', lastname)AS description");                    				
                    				$data   = G_Employee_Helper::sqlGetAllEmployeesByEmployeeCode($new_column_string, $fields);    
                    				$s_applied_to = Employee_Benefits_Main::EMPLOYEE;                				
                    				break;
                    			case 'department':                            				
									$a_dept = explode(",", $cell_value);
									$a_new_dept = array();
									foreach( $a_dept as $dept ){
										$a_new_dept[] = Model::safeSql(trim($dept));
									}
									$new_column_string  = implode(",", $a_new_dept);            				
                    				$fields = array("id","title AS description");
                    				$data   = G_Company_Structure_Helper::sqlGetDepartmentDataByTitle($new_column_string,$fields);                    			
                    				$s_applied_to = Employee_Benefits_Main::DEPARTMENT;        
                    				break;
                    			case 'employment status':
                    				$a_employment = explode(",", $cell_value);
                    				$a_new_employment_status = array();
									foreach( $a_employment as $status ){
										$a_new_employment_status[] = Model::safeSql(trim($status));
									}
									$new_column_string = implode(",", $a_new_employment_status);
									$fields = array("id","status AS description");
									$data   = G_Settings_Employment_Status_Helper::sqlGetEmploymentStatusDataByTitle($new_column_string,$fields);
									$s_applied_to = Employee_Benefits_Main::EMPLOYMENT_STATUS;      
                    				break;
                    			case 'all':
                    				$data[0]['id'] = 0;
                    				$data[0]['description'] = Employee_Benefits_Main::ALL_EMPLOYEE;                    				
                    				$s_applied_to = Employee_Benefits_Main::ALL_EMPLOYEE;
                    				break;
                    			default:
                    				$applied_to 	  = '';
                    				$b_row_data_valid = false;
                    				break;
                    		}

                    		if( !empty($data) && $b_row_data_valid ){                    					
            					foreach( $data as $d ){
            						$a_benefits[$i_row_count]['applied_to'][] = array("applied_to" => $s_applied_to, "employee_department_id" => $d['id'], "description" => $d['description']);
            					}
            					$data = array();
            					$b_row_data_valid = true;            					
            				}else{                    				            					
            					$b_row_data_valid = false;
            				}
            				$data = array();
                    		$i_column_count++;

                    	}elseif( strpos($column_header_value, 'title') !== false && $b_row_data_valid ){
                    		$a_benefits[$i_row_count]['code']  = mb_strtoupper($cell_value);                    		
                    		$a_benefits[$i_row_count]['title'] = $cell_value;                    		
                    		$i_column_count++;
                    	}elseif( strpos($column_header_value, 'given every') !== false && $b_row_data_valid ){                     				
                    		if( in_array($cell_value, $a_valid_cutoff) && strlen($cell_value) == 1 ){                    			   
                    			$a_benefits[$i_row_count]['cutoff'] = $cell_value;
                    		}else{
                    			$b_row_data_valid = false;
                    		}
                    		$i_column_count++;
                    	}elseif( strpos($column_header_value, 'is taxable') !== false && $b_row_data_valid ){
                    		if( in_array($cell_value, $a_valid_taxable) ){
                    			$a_benefits[$i_row_count]['is_taxable'] = $cell_value;
                    		}else{
                    			$b_row_data_valid = false;
                    		}
                    		$i_column_count++;
                    	}elseif( strpos($column_header_value, 'amount') !== false && $b_row_data_valid ){
                    		if( $cell_value > 0 ){
                    			$a_benefits[$i_row_count]['amount'] = $cell_value;	
                    		}else{
                    			$b_row_data_valid = false;
                    		}
                    		$i_column_count++;
                    	}elseif( strpos($column_header_value, 'multiply by') !== false && $b_row_data_valid ){
                    		switch ($cell_value) {
                    			case 1:
                    				$a_benefits[$i_row_count]['multiplied_by'] = G_Settings_Employee_Benefit::MULTIPLIED_BY_PRESENT_DAYS;	
                    				break;
                    			default:     
                    				$a_benefits[$i_row_count]['multiplied_by'] = '';               				
                    				break;
                    		}
                    		$i_column_count++;
                    	}elseif( strpos($column_header_value, 'remarks') !== false && $b_row_data_valid ){
                    		$a_benefits[$i_row_count]['remarks'] = $cell_value;	
                    		$i_column_count++;
                    	}
                    //}
                }
			}

			if( !$b_row_data_valid ){
				unset($a_benefits[$i_row_count]);				
			}else{
				$a_benefits[$i_row_count]['date_created'] = $this->date_created;
			}
		}
		
		if( count($a_benefits) > 0 ){		
			$this->a_bulk_insert = $a_benefits;			
		}				
		return $this;
	}

	public function importBulkSettingsBenefits() {
		if( !empty($this->a_bulk_insert) ){

			$a_benefits = $this->a_bulk_insert;			
			foreach( $a_benefits as $benefit ){
				$bulk_benefits[] = "('" . $benefit['code'] . "'," . Model::safeSql($benefit['title']) . "," . Model::safeSql($benefit['cutoff']) . "," . Model::safeSql($benefit['is_taxable']) . "," . Model::safeSql($benefit['amount']) . "," . Model::safeSql($benefit['multiplied_by']) . "," . Model::safeSql(G_Settings_Employee_Benefit::NO) . "," . Model::safeSql($benefit['date_created'])  . ")";
			}

			$benefits_last_id = G_Settings_Employee_Benefit_Helper::sqlBenefitLastId();			
			$fields = array("code","name","cutoff","is_taxable","amount","multiplied_by","is_archive","date_created");
			
			$b 	    = new G_Settings_Employee_Benefit();
			$result = $b->importBulkSave($bulk_benefits,$fields);		

			if( $result['is_success'] ){				
				$last_inserted_id = G_Settings_Employee_Benefit_Helper::sqlBenefitLastId();			
				$start_benefit_id = $benefits_last_id + 1;
			
				$employees_bulk_insert = array();
				foreach($a_benefits as $benefit){
					$applied_to = $benefit['applied_to'];
					foreach( $applied_to as $at ){
						$employees_bulk_insert[] = "(" . Model::safeSql($this->company_structure_id) . "," . Model::safeSql($start_benefit_id) . "," . Model::safeSql($at['applied_to']) . "," . Model::safeSql($at['employee_department_id']) . "," . Model::safeSql($at['description']) . ")";
					}
					$start_benefit_id++;
					if( $start_benefit_id > $last_inserted_id ){
						break;
					}
				}

				if( !empty($employees_bulk_insert) ){
					$this->a_bulk_insert = $employees_bulk_insert;
					$this->b_bulk_save   = true;
				}
			}
		}

		return $this;
	}

	public function bulkSave( $bulk_data = array(), $fields = array()) {
		$return['is_success'] = false;
		$return['message']    = 'Cannot save data';

		if( !empty($bulk_data) ){
			$this->a_bulk_insert = $bulk_data;
		}

		if( !empty($this->a_bulk_insert) ){			
			$is_success = G_Employee_Benefits_Main_Manager::bulkInsertData($this->a_bulk_insert, $fields);
			if( $is_success ){
				$return['is_success'] = true;
				$return['message']    = 'Record(s) was successfully saved';
			}
		}

		return $return;
	}

	/*
		Usage :
		$benefit_id = 1;
		$b  = new G_Employee_Benefits_Main();
		$b->setBenefitId($benefit_id);
		$b->deleteAllEnrolledEmployeesByBenefitId(); 
	*/		

	public function deleteAllEnrolledEmployeesByBenefitId() {
		$return = false;
		if( $this->benefit_id > 0 ){
			G_Employee_Benefits_Main_Manager::deleteAllEnrolledEmployeesByBenefitId($this->benefit_id);
			$return = true;
		}

		return $return;
	}

	public function delete() {
		G_Employee_Benefits_Main_Manager::delete($this);
	}
}
?>