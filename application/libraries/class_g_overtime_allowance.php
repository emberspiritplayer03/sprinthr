<?php

class G_Overtime_Allowance extends Overtime_Allowance {
	
	const EMPLOYEE_TYPE 	= "e";
	const DEPARTMENT_TYPE 	= "d";
	const EMPLOYMENT_STATUS_TYPE 	= "es";
	const ALL_TYPE 			= "a";

	const DAY_TYPE_REGULAR = "day_type1";
	const DAY_TYPE_LEGAL   = "day_type2";
	const DAY_TYPE_SPECIAL = "day_type3";
	const DAY_TYPE_RD      = "day_type4";

	protected $applied_day_type = '';
	protected $description_day_type = '';
	protected $continue = false;
	protected $data_day_types = array();

	public function __construct() {
		
	}

	public function setAppliedDayType( $value = '' ){
		$this->applied_day_type = $value;
	}

	public function getAppliedDayType(){
		return $this->applied_day_type;
	}

	public function setDescriptionDayType( $value = '' ){
		$this->description_day_type = $value;
	}

	public function getDescriptionDayType() {
		return $this->description_day_type;
	}

	public function validAppliedDayType(){
		$valid_day_types = array(self::DAY_TYPE_REGULAR => "Regular Days", self::DAY_TYPE_LEGAL => "Legal Holidays", self::DAY_TYPE_SPECIAL => "Special Holidays", self::DAY_TYPE_RD => "Rest Days");
		return $valid_day_types;
	}

	public function createValidDayTypeArray( $data = array() ){
		$valid_day_types = self::validAppliedDayType();
		$valid_entries   = array();
		$counter_valid   = 0;

		if( !empty($data) ){						
			foreach( $data as $key => $value ){								
				if( array_key_exists(trim($key), $valid_day_types) ){									
					$valid_entries['valid_day_types'][] = $key;
					$a_day_types[] = $valid_day_types[$key];
					$counter_valid++;
				}
			}

			$valid_entries['valid_day_string'] = implode(",", $a_day_types);
			$valid_entries['valid_day_types']  = serialize($valid_entries['valid_day_types']);			

			if( $counter_valid > 0 ){
				$this->continue        = true;
				$this->data_day_types = $valid_entries;
			}			
		}

		return $this;
	}

	public function setDayType( $key = '' ){
		if( $key != '' ){
			$valid_day_types = self::validAppliedDayType();
			if( array_key_exists(trim($key), $valid_day_types) ){			
				$valid_entries['valid_day_types'][] = $key;														
				$valid_entries['valid_day_string']  = $valid_day_types[$key];
				$valid_entries['valid_day_types']   = serialize($valid_entries['valid_day_types']);
				$this->continue        = true;
				$this->data_day_types = $valid_entries;
			}				
		}
		return $this;
	}

	public function addOtAllowance($data = array()) {
		if(!empty($data)) {
			$has_duplicate = false;
	        if($data['all_employee'] == 1) {
	        	$v 	 = array();
	        	$v[] = 0;
	        	$v[] = Model::safeSql(G_Overtime_Allowance::ALL_TYPE);
	        	$v[] = Model::safeSql($data['ot_allowance']);
	        	$v[] = Model::safeSql($data['multiplier']);
	        	$v[] = Model::safeSql($data['max_ot_allowance']);
	        	$v[] = Model::safeSql($data['date_start']);
	        	$v[] = Model::safeSql('All Employees');
	        	$v[] = Model::safeSql(date("Y-m-d"));
	        	$v[] = Model::safeSql($this->data_day_types['valid_day_types']);
	        	$v[] = Model::safeSql($this->data_day_types['valid_day_string']);

	        	$values = "(".implode(",",$v).")";

	        	/*$count_oa = G_Overtime_Allowance_Helper::countOtAllowanceByObjectAndDateStart(0,G_Overtime_Allowance::ALL_TYPE,$data['date_start']);
	        	if($count_oa == 0) {
		        	$v[] = 0;
		        	$v[] = Model::safeSql(G_Overtime_Allowance::ALL_TYPE);
		        	$v[] = Model::safeSql($data['ot_allowance']);
		        	$v[] = Model::safeSql($data['multiplier']);
		        	$v[] = Model::safeSql($data['max_ot_allowance']);
		        	$v[] = Model::safeSql($data['date_start']);
		        	$v[] = Model::safeSql('All Employees');
		        	$v[] = Model::safeSql(date("Y-m-d"));
		        	$v[] = Model::safeSql($this->data_day_types['valid_day_types']);
		        	$v[] = Model::safeSql($this->data_day_types['valid_day_string']);

		        	$values = "(".implode(",",$v).")";
		        }else{
		        	$has_duplicate = true;
		        	$duplicate_message = "Duplicate entry : <b>All Employees</b> with start date of <b>".$data['date_start']."</b> already exist." ;
		        }*/
			}else{
				$object_id_arr = explode(",", $data['applied_to_ids']);
				foreach($object_id_arr as $key => $value) {
					$v 	 = array();
					$obj_id = explode(":", $value);
					$object_type = strtolower($obj_id[1]);
					$object_id   = Utilities::decrypt($obj_id[0]);

					switch ($object_type) {
						case G_Overtime_Allowance::EMPLOYEE_TYPE :						
							$sql_fields = array("id","CONCAT(lastname, ', ', firstname, ' ', middlename)AS title");
							$details    = G_Employee_Helper::sqlEmployeeDetailsById($object_id, $sql_fields);
							break;
						case G_Overtime_Allowance::DEPARTMENT_TYPE :						
							$sql_fields = array("id","title");
							$details    = G_Company_Structure_Helper::sqlDepartmentDetailsById($object_id, $sql_fields);
							break;
						case G_Overtime_Allowance::EMPLOYMENT_STATUS_TYPE :
							$sql_fields = array("id","status AS title");
							$details    = G_Settings_Employment_Status_Helper::sqlDataById($object_id, $sql_fields);
							break;	
						default:																			
							break;
					}

					$v[] = $object_id;
		        	$v[] = Model::safeSql($object_type);
		        	$v[] = Model::safeSql($data['ot_allowance']);
		        	$v[] = Model::safeSql($data['multiplier']);
		        	$v[] = Model::safeSql($data['max_ot_allowance']);
		        	$v[] = Model::safeSql($data['date_start']);
		        	$v[] = Model::safeSql($details['title']);
		        	$v[] = Model::safeSql(date("Y-m-d"));
		        	$v[] = Model::safeSql($this->data_day_types['valid_day_types']);
	        		$v[] = Model::safeSql($this->data_day_types['valid_day_string']);
	        		$values_arr[] = "(".implode(",",$v).")";

					/*$count_oa = G_Overtime_Allowance_Helper::countOtAllowanceByObjectAndDateStart($object_id,$object_type,$data['date_start']);
	        		if($count_oa == 0) {		
			        	$v[] = $object_id;
			        	$v[] = Model::safeSql($object_type);
			        	$v[] = Model::safeSql($data['ot_allowance']);
			        	$v[] = Model::safeSql($data['multiplier']);
			        	$v[] = Model::safeSql($data['max_ot_allowance']);
			        	$v[] = Model::safeSql($data['date_start']);
			        	$v[] = Model::safeSql($details['title']);
			        	$v[] = Model::safeSql(date("Y-m-d"));
			        	$v[] = Model::safeSql($this->data_day_types['valid_day_types']);
		        		$v[] = Model::safeSql($this->data_day_types['valid_day_string']);

						$values_arr[] = "(".implode(",",$v).")";
					}else{
						$has_duplicate = true;
						$duplicate_message .= "Duplicate entry : <b>".$details['title']."</b> with start date of <b>".$data['date_start']."</b> already exist. <br/>" ;
					}*/
				}

				$values = implode(",",$values_arr);
				$applied_to = implode("/",$names_arr);
			}

			if(!empty($values)) {

				$oa = G_Overtime_Allowance_Manager::bulkInsert($values);

				$return['message'] = "<div style='margin-left:0px;' class='alert alert-success'>Record successfully saved.</div> <span style='font-size:11px;'>" .$duplicate_message. "</span>";
	    		$return['is_success'] = true;
			}else{

				if($has_duplicate) {
					$return['message'] = "<div style='margin-left:0px;' class='alert alert-error'>Unabled to save data.</div> <span style='font-size:11px;'>" .$duplicate_message. "</span>";
				}else{
					$return['message'] = "<div style='margin-left:0px;' class='alert alert-error'>Please select at least 1 employee/department.</div>";
				}
				
	    		$return['is_success'] = false;
			}
			
	    }else{
	    	$return['message'] = "<div style='margin-left:0px;' class='alert alert-error'>Invalid form entry.</div>";
	    	$return['is_success'] = false;
	    }

	    return $return;
	}

	public function updateOtAllowance($data = array()) {
		if(!empty($data)) {
			$has_duplicate = false;
			$oa = G_Overtime_Allowance_Finder::findById(Utilities::decrypt($data['eid']));
			if($oa) {
				switch ($oa->getObjectType()) {
					case G_Overtime_Allowance::EMPLOYEE_TYPE :						
						$sql_fields     = array("id,CONCAT(lastname, ', ', firstname, ' ', middlename)AS title");
						$details = G_Employee_Helper::sqlEmployeeDetailsById($oa->getObjectId(), $sql_fields);
						break;
					case G_Overtime_Allowance::DEPARTMENT_TYPE :						
						$sql_fields     = array("id,title");
						$details = G_Company_Structure_Helper::sqlDepartmentDetailsById($oa->getObjectId(), $sql_fields);
						break;
					case G_Overtime_Allowance::EMPLOYMENT_STATUS_TYPE :
						$sql_fields = array("id","status AS title");
						$details    = G_Settings_Employment_Status_Helper::sqlDataById($oa->getObjectId(), $sql_fields);
						break;
					case G_Overtime_Allowance::ALL_TYPE :						
						$details['title'] = "All Employees";
						break;
					default:																			
						break;
				}

				$count_oa = G_Overtime_Allowance_Helper::countOtAllowanceByObjectAndDateStart($oa->getObjectId(),$oa->getObjectType(),$data['date_start']);

				if($data['date_start'] == $oa->getDateStart()) {
					$count_oa = 0;
				}

				$oa->setOtAllowance($data['ot_allowance']);
		        $oa->setMultiplier($data['multiplier']);        
		        $oa->setMaxOtAllowance($data['max_ot_allowance']);   
		        $oa->setDateStart($data['date_start']);   
		        $oa->setDescription($details['title']);  
		        $oa->setAppliedDayType($this->data_day_types['valid_day_types']);
		        $oa->setDescriptionDayType($this->data_day_types['valid_day_string']);
		        $oa->save(); 

		        $return['message'] = "<div style='margin-left:0px;' class='alert alert-success'>Record successfully updated.</div>" ;
    			$return['is_success'] = true;

        		/*if($count_oa == 0) {	
			        $oa->setOtAllowance($data['ot_allowance']);
			        $oa->setMultiplier($data['multiplier']);        
			        $oa->setMaxOtAllowance($data['max_ot_allowance']);   
			        $oa->setDateStart($data['date_start']);   
			        $oa->setDescription($details['title']);  
			        $oa->setAppliedDayType($this->data_day_types['valid_day_types']);
			        $oa->setDescriptionDayType($this->data_day_types['valid_day_string']);
			        $oa->save(); 

			        $return['message'] = "<div style='margin-left:0px;' class='alert alert-success'>Record successfully updated.</div>" ;
	    			$return['is_success'] = true;
			    }else{
			    	$duplicate_message .= "Duplicate entry : <b>".$details['title']."</b> with start date of <b>".$data['date_start']."</b> already exist. <br/>" ;
			    	$return['message'] = "<div style='margin-left:0px;' class='alert alert-error'>Unabled to save data.</div> <span style='font-size:11px;'>" .$duplicate_message. "</span>";
			    	$return['is_success'] = false;
			    }*/

			}else{
				$return['message'] = "<div style='margin-left:0px;' class='alert alert-error'>No record found.</div> ";
			    $return['is_success'] = false;
			}
		}else{
			$return['message'] = "<div style='margin-left:0px;' class='alert alert-error'>No record found.</div> ";
		    $return['is_success'] = false;
		}

		return $return;
	}

	public function computeOvertimeAllowance($attendance, $oa) {
		$total_overtime_allowance = 0;
		$a_day_types     = unserialize($oa['applied_day_type']);		

		if( !empty($a_day_types) ){			
			$inc_26_date = 0;
			foreach ($attendance as $a) {	
				$is_valid_day = false;
				if(strtotime($oa['date_start']) <= strtotime($a->getDate()) && $a->isPresent()) {
					$t = $a->getTimesheet();
					$holiday_type = $a->getHoliday();
				
					if( !empty($t) ){
						if( in_array(self::DAY_TYPE_REGULAR, $a_day_types) && !$a->isHoliday() && !$a->isRestday() && !$a->isOfficialBusiness() ){							
							$is_special   = false;
							$is_valid_day = true;
						}elseif( in_array(self::DAY_TYPE_LEGAL, $a_day_types) && $a->isHoliday() &&  !empty($holiday_type) && $holiday_type->getType() == Holiday::LEGAL && !$a->isOfficialBusiness()){																					
							$is_special   = true;
							$is_valid_day = true;			
						}elseif( in_array(self::DAY_TYPE_SPECIAL, $a_day_types) && !empty($holiday_type) && $holiday_type->getType() == Holiday::SPECIAL && !$a->isOfficialBusiness()){		
							$is_special   = true;
							$is_valid_day = true;					
						}elseif( in_array(self::DAY_TYPE_RD, $a_day_types) && $a->isRestday() && !$a->isOfficialBusiness()){							
							$is_special   = true;
							$is_valid_day = true;
						}

						if( $is_valid_day ){
							if( $is_special ){		

								if($oa['description_day_type'] == 'Special Holidays' && $a->getDate() == '2016-12-26' ) { //Note: this is to remove the special holiday dated 12/26/2016 OT Allowance, this is not permanent i'm still working on it
									$multiplier = 0;
								} else {
									if($t->getScheduledTimeIn() != '' && $t->getScheduledTimeIn() != '') {
										$multiplier = $t->totalHrsWorkedBaseOnSchedule() / $oa['multiplier'];
									}									
								}
								
							}else{			
								if($t->getScheduledTimeIn() != '' && $t->getScheduledTimeIn() != '') {				
									$multiplier = $t->getTotalOvertimeHours() / $oa['multiplier'];
								}
							}							
							$multiplier = floor($multiplier);
							//if($multiplier > 0 && $t->getTotalOvertimeHours() > 0) {
								$overtime_allowance = $multiplier * $oa['ot_allowance'];
								if($overtime_allowance >= $oa['max_ot_allowance']) {
									$overtime_allowance = $oa['max_ot_allowance'];
								}

								//echo "Date : " . $a->getDate() . " / Overtime Allowance : {$overtime_allowance} / Multiplier : {$multiplier}<br />";
								$total_overtime_allowance += $overtime_allowance;
							//}
						}						
					}
				}		
			}	

		}
		
		return $total_overtime_allowance;
	}

	public function save() {
		return G_Overtime_Allowance_Manager::save($this);
	}
		
	public function delete() {
		G_Overtime_Allowance_Manager::delete($this);
	}

}
?>