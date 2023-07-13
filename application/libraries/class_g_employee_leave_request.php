<?php
class G_Employee_Leave_Request {
	
	public $id;
	public $company_structure_id = 1;
	public $employee_id;
	public $leave_id;
	public $date_applied;
    public $time_applied;
	public $date_start;
	public $date_end;
	public $apply_half_day_date_start;
	public $apply_half_day_date_end;
	public $leave_comments;	
	public $is_approved = self::APPROVED;
	public $is_paid = self::YES;
	public $is_archive = self::NO;
	public $created_by;

	protected $a_valid_leave_dates = array();
	protected $a_bulk_insert       = array();

	public $default_general_rule;

	const PENDING 		= 'Pending';
	const APPROVED 		= 'Approved';
	const DISAPPROVED	= 'Disapproved';
	
	const YES = 'Yes';
	const NO  = 'No';

    const IS_PAID_YES  = 'Yes';
    const IS_PAID_NO = 'No';
	
	function __construct() {
		$this->id = $id;
	}
	
	public function setId($value) {
		$this->id = $value;	
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setCompanyStructureId($value) {
		$this->company_structure_id= $value;	
	}
	
	public function getCompanyStructureId() {
		return $this->company_structure_id;
	}
	
	public function setEmployeeId($value) {
		$this->employee_id = $value;	
	}
	
	public function getEmployeeId() {
		return $this->employee_id;
	}
	
	public function setLeaveId($value) {
		$this->leave_id = $value;
	}

	public function getLeaveId() {
		return $this->leave_id;
	}
	
	public function setDateApplied($value) {
		$this->date_applied = $value;
	}

	public function getDateApplied() {
		return $this->date_applied;
	}

	public function setTimeApplied($value) {
		$this->time_applied = $value;
	}

	public function getTimeApplied() {
		return $this->time_applied;
	}
	
	public function setDateStart($value) {
		$this->date_start = $value;	
	}
	
	public function getDateStart() {
		return $this->date_start;
	}
	
	public function setDateEnd($value) {
		$this->date_end = $value;	
	}
	
	public function getDateEnd() {
		return $this->date_end;
	}
	
	public function setLeaveComments($value) {
		$this->leave_comments = $value;	
	}
	
	public function getLeaveComments() {
		return $this->leave_comments;
	}

	public function setAsPending() {
		$this->is_approved = self::PENDING;
	}

	public function setAsApproved() {
		$this->is_approved = self::APPROVED;
	}

	public function setAsDisapproved(){
		$this->is_approved = self::DISAPPROVED;
	}
	
	public function setIsApproved($value) {
		$this->is_approved = $value;	
	}
	
	public function getIsApproved() {
		return $this->is_approved;
	}

    public function isApproved() {
        if ($this->is_approved == self::APPROVED) {
            return true;
        } else {
            return false;
        }
    }
	
	public function setIsPaid($value) {
		$this->is_paid = $value;	
	}
	
	public function getIsPaid() {
		return $this->is_paid;
	}
	
	public function setCreatedBy($value) {
		$this->created_by = $value;	
	}
	
	public function getCreatedBy() {
		return $this->created_by;
	}

	public function setAsIsNotArchive() {
		$this->is_archive = self::NO;
	}

	public function setAsIsArchive() {
		$this->is_archive = self::YES;
	}
	
	public function setIsArchive($value) {
		$this->is_archive = $value;	
	}
	
	public function getIsArchive() {
		return $this->is_archive;
	}
	
	public function setApplyHalfDayDateStart($value) {
		$this->apply_half_day_date_start = $value;	
	}
	
	public function getApplyHalfDayDateStart() {
		return $this->apply_half_day_date_start;
	}
	
	public function setApplyHalfDayDateEnd($value) {
		$this->apply_half_day_date_end = $value;	
	}
	
	public function getApplyHalfDayDateEnd() {
		return $this->apply_half_day_date_end;
	}

	/*
		Usage:
		$l = new G_Employee_Leave_Request();
		$l->setDateStart("2014-02-11");
		$l->setDateEnd("2014-02-15");
		$l->setLeaveId(G_Leave::ID_SICK);
		$l->addLeaveToAttendance($e); // $e = employee object
	*/

	public function addLeaveToAttendance($e){
		$return = false;
		//Update Attendance
		if( !empty($e) && !empty($this->date_start) && !empty($this->date_end) && !empty($this->leave_id) ){
			$start_date = date("Y-m-d", strtotime($this->date_start));		
			$end_date	= date("Y-m-d", strtotime($this->date_end));		
			$dates 		= Tools::getBetweenDates($start_date, $end_date);
			foreach ($dates as $date) {
				$a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
				if (!$a) {
					$a = new G_Attendance;									
				}
				$a->setLeaveId($this->leave_id);
				$a->setDate($date);
				$a->setAsLeave();

				if( $this->is_paid == self::YES ){
					$a->setAsPaid();
				}else{
					$a->setAsNotPaid();
				}

				$a->setAsNotRestday();
				$a->recordToEmployee($e);
			}	

			G_Attendance_Helper::updateAttendanceByEmployeeIdPeriod($this->employee_id, $this->date_start, $this->date_end); //Update employee attendance

			$return = true;
		}
		//

		return $return;
	}

	public function saveRequestWithGeneralRule() {

		if(!empty($this->default_general_rule) || $this->default_general_rule > 0) {
			$default_leave_id_general_rule = $this->default_general_rule;
			$return   = array();
			$is_saved = 0;
			if( !empty($this->employee_id) && !empty($this->date_start) && !empty($this->date_end) && !empty($this->leave_id) ){
				if( $this->apply_half_day_date_start == self::YES ){
					$num_days = 0.5;
				}
										
				$date_diff 		 = strtotime($this->date_end) - strtotime($this->date_start);
				$days_difference = date("d",$date_diff); 
				$num_days  		 = $num_days + $days_difference; //Days difference of two dates

					if( $this->is_approved == self::APPROVED ){
						if( $this->is_paid == self::YES ){
							if( $num_days >= 0 ){
								$el = new G_Employee_Leave_Available();
								$el->setEmployeeId($this->employee_id);
								$el->setLeaveId($default_leave_id_general_rule);
								$is_deducted = $el->deductLeaveCredits($num_days); //Deduct leave credits

								if( $is_deducted['is_success'] ){
									$is_saved = self::save(); //Save request								
									if( $is_saved ){
										$return['is_success'] = true;
										$return['message']    = "Record saved";
									}else{
										$return['is_success'] = false;
										$return['message']    = "Cannot save record";
									}						
								}else{
									$return = $is_deducted;
								}
							}else{
								$return['is_success'] = false;
								$return['message']    = "Cannot save record";
							}

						}else{
							$is_saved = self::save(); //Save request
							if( $is_saved ){
								$return['is_success'] = true;
								$return['message']    = "Record saved";
							}else{
								$return['is_success'] = false;
								$return['message']    = "Cannot save record";
							}						
						}				
					}else{
						//If is paid, check if number of leave is sufficient
						if( $this->is_paid == self::YES ){
							$el = new G_Employee_Leave_Available();						
							$el->setEmployeeId($this->employee_id);
							$el->setLeaveId($default_leave_id_general_rule);
							$total_available_leave = $el->employeeTotalAvailableLeaveByEmployeeIdAndLeaveId();						
							if( $total_available_leave >= $num_days  ){
								$is_saved = self::save(); //Save request	
							}else{
								$return['is_success'] = false;
								$return['message']    = "Insufficient leave credits : Available leave <b>{$total_available_leave}</b>";	
								return $return;
							}
						}else{
							$is_saved = self::save(); //Save request	
						}	
						
						if( $is_saved ){
							$return['last_insert_id'] = $is_saved;
							$return['is_success'] = true;
							$return['message']    = "Record updated";
						}else{
							$return['is_success'] = false;
							$return['message']    = "Cannot save record";
						}
					}
			}else{
				$return['is_success'] = false;
				$return['message']    = "Invalid form entries";
			}

			$return['last_inserted_id'] = $is_saved;

		} else {
			$return['is_success'] = false;
			$return['message']    = "Cannot save record, no general rule selected";			
		}

		return $return;			
	}

	public function saveRequest() {
		$return   = array();
		$is_saved = 0;
		if( !empty($this->employee_id) && !empty($this->date_start) && !empty($this->date_end) && !empty($this->leave_id) ){
			if( $this->apply_half_day_date_start == self::YES ){
				$num_days = 0.5;
			}
									
			$date_diff 		 = strtotime($this->date_end) - strtotime($this->date_start);
			$days_difference = date("d",$date_diff); 
			$num_days  		 = $num_days + $days_difference; //Days difference of two dates

				if( $this->is_approved == self::APPROVED ){
					if( $this->is_paid == self::YES ){
						if( $num_days >= 0 ){
							$el = new G_Employee_Leave_Available();
							$el->setEmployeeId($this->employee_id);
							$el->setLeaveId($this->leave_id);
							$is_deducted = $el->deductLeaveCredits($num_days); //Deduct leave credits

							if( $is_deducted['is_success'] ){
								$is_saved = self::save(); //Save request								
								if( $is_saved ){
									$return['is_success'] = true;
									$return['message']    = "Record saved";
								}else{
									$return['is_success'] = false;
									$return['message']    = "Cannot save record";
								}						
							}else{
								$return = $is_deducted;
							}
						}else{
							$return['is_success'] = false;
							$return['message']    = "Cannot save record";
						}

					}else{
						$is_saved = self::save(); //Save request
						if( $is_saved ){
							$return['is_success'] = true;
							$return['message']    = "Record saved";
						}else{
							$return['is_success'] = false;
							$return['message']    = "Cannot save record";
						}						
					}				
				}else{
					//If is paid, check if number of leave is sufficient
					if( $this->is_paid == self::YES ){
						$el = new G_Employee_Leave_Available();						
						$el->setEmployeeId($this->employee_id);
						$el->setLeaveId($this->leave_id);
						$total_available_leave = $el->employeeTotalAvailableLeaveByEmployeeIdAndLeaveId();						
						if( $total_available_leave >= $num_days  ){
							$is_saved = self::save(); //Save request	
						}else{
							$return['is_success'] = false;
							$return['message']    = "Insufficient leave credits : Available leave <b>{$total_available_leave}</b>";	
							return $return;
						}
					}else{
						$is_saved = self::save(); //Save request	
					}	
					
					if( $is_saved ){
						$return['last_insert_id'] = $is_saved;
						$return['is_success'] = true;
						$return['message']    = "Record updated";
					}else{
						$return['is_success'] = false;
						$return['message']    = "Cannot save record";
					}
				}
		}else{
			$return['is_success'] = false;
			$return['message']    = "Invalid form entries";
		}

		$return['last_inserted_id'] = $is_saved;

		return $return;	
	}
	
	public function checkGeneralRule() {
		$slv = G_Settings_Leave_General_Finder::findById(1);
		$this->default_general_rule = $slv->getLeaveId();
		return $this;
	}
	
	public function save() {
		return G_Employee_Leave_Request_Manager::save($this);
	}
	
	public function delete() {
		return G_Employee_Leave_Request_Manager::delete($this);
	} 

	public function approveRequestWithGeneralRule() {

		if(!empty($this->default_general_rule) || $this->default_general_rule > 0) {
			$default_leave_id_general_rule = $this->default_general_rule;

	    	$return = array();    	
	    	$update_request = false;
	    	if( !empty($this->id) && $this->is_approved == self::PENDING){    		
	    		if( $this->is_paid == self::YES ){    		
					if( $this->apply_half_day_date_start == self::YES ){
						$num_days = 0.5;
					} else {
						$date_diff 		 = strtotime($this->date_end) - strtotime($this->date_start);
						$days_difference = date("d",$date_diff); 
						$num_days  		 = $num_days + $days_difference; //Days difference of two dates						
					}

					if( $num_days >= 0 ){
						$el = new G_Employee_Leave_Available();
						$el->setEmployeeId($this->employee_id);
						$el->setLeaveId($default_leave_id_general_rule);

						//Check day for restday - if restday do not deduct
						$start  = $this->date_start;
						$end    = $this->date_end;
						$deduct = 0; 
						while(strtotime($start) <= strtotime($end)) {
						    $check_date = date("Y-m-d");
						    $attendance = G_Attendance_Helper::sqlFindByDateAndEmployeeId($this->employee_id, $check_date);
						    if( $attendance ){
						    	if( $attendance['is_restday'] ){
						    		$deduct++;
						    	}
						    }
						    $start      = date("Y-m-d", strtotime("+1 day", strtotime($start)));
						}

						if( $deduct > $num_days ){
							$deduct = $num_days;
						}

						$num_days = $num_days - $deduct;
						$is_deducted = $el->deductLeaveCredits($num_days); //Deduct leave credits

						if( $is_deducted['is_success'] ){
							$update_request    = true;
							$this->is_approved = self::APPROVED;
							$is_saved 		   = self::save(); //Update request
							if( $is_saved ){
								$return['is_success'] = true;
								$return['message']    = "Record updated";
							}else{
								$return['is_success'] = false;
								$return['message']    = "Cannot save record";
							}						
						}else{
							$return = $is_deducted;
						}

					}else{
						$return['is_success'] = false;
						$return['message']    = "Cannot save record";
					}
				}else{				
					$update_request    = true;
					$this->is_approved = self::APPROVED;
					$is_saved 		   = self::save(); //Update request
					if( $is_saved ){
						$return['is_success'] = true;
						$return['message']    = "Record updated";
					}else{
						$return['is_success'] = false;
						$return['message']    = "Cannot save record";
					}
				}
			}else{			
				$return['is_success'] = false;
				$return['message']    = "Record not found";
			}


			if( $update_request ){
				$request = new G_Request();
				$request->setRequestId($this->id);
				$request->setRequestType(G_Request::PREFIX_LEAVE);
				$request->resetToApprovedApproversStatusByRequestIdAndRequestType(); 
			}

		} else {
			$return['is_success'] = false;
			$return['message']    = "Cannot save records, no leave general rule selected";			
		}		

    	return $return;		
	}

    public function approveRequest() {
    	$return = array();    	
    	$update_request = false;
    	if( !empty($this->id) && $this->is_approved == self::PENDING){    		
    		if( $this->is_paid == self::YES ){    		
				if( $this->apply_half_day_date_start == self::YES ){
					$num_days = 0.5;
				} else {
					$date_diff 		 = strtotime($this->date_end) - strtotime($this->date_start);
					$days_difference = date("d",$date_diff); 
					$num_days  		 = $num_days + $days_difference; //Days difference of two dates					
				}
										
				if( $num_days >= 0 ){
					$el = new G_Employee_Leave_Available();
					$el->setEmployeeId($this->employee_id);
					$el->setLeaveId($this->leave_id);
					$is_deducted = $el->deductLeaveCredits($num_days); //Deduct leave credits

					if( $is_deducted['is_success'] ){
						$update_request    = true;
						$this->is_approved = self::APPROVED;
						$is_saved 		   = self::save(); //Update request
						if( $is_saved ){
							$return['is_success'] = true;
							$return['message']    = "Record updated";
						}else{
							$return['is_success'] = false;
							$return['message']    = "Cannot save record";
						}						
					}else{
						$return = $is_deducted;
					}

				}else{
					$return['is_success'] = false;
					$return['message']    = "Cannot save record";
				}
			}else{				
				$update_request    = true;
				$this->is_approved = self::APPROVED;
				$is_saved 		   = self::save(); //Update request
				if( $is_saved ){
					$return['is_success'] = true;
					$return['message']    = "Record updated";
				}else{
					$return['is_success'] = false;
					$return['message']    = "Cannot save record";
				}
			}
		}else{			
			$return['is_success'] = false;
			$return['message']    = "Record not found";
		}


		if( $update_request ){
			$request = new G_Request();
			$request->setRequestId($this->id);
			$request->setRequestType(G_Request::PREFIX_LEAVE);
			$request->resetToApprovedApproversStatusByRequestIdAndRequestType(); 
		}

    	return $return;
    }

    public function disApproveRequest() {
    	$return = array();   
    	$return['is_success'] = false;
		$return['message']    = "Cannot update record";
    	if( !empty($this->id) ){    		
			$this->is_approved = self::DISAPPROVED;
			$is_saved 		   = self::save(); //Update request

			if( $is_saved ){

				/*$request = new G_Request();
				$request->setRequestId($this->id);
				$request->setRequestType(G_Request::PREFIX_LEAVE);
				$request->resetToDisApprovedApproversStatusByRequestIdAndRequestType(); 
*/
				$return['is_success'] = true;
				$return['message']    = "Record was successfully updated";
			}
    	}  

    	return $return;
    }

    public function hrDisApproveRequest() {
    	$return = array();   
    	$return['is_success'] = false;
		$return['message']    = "Cannot update record";
    	if( !empty($this->id) ){    		
			$this->is_approved = self::DISAPPROVED;
			$is_saved 		   = self::save(); //Update request

			if( $is_saved ){

				$request = new G_Request();
				$request->setRequestId($this->id);
				$request->setRequestType(G_Request::PREFIX_LEAVE);
				$request->resetToDisApprovedApproversStatusByRequestIdAndRequestType(); 

				$return['is_success'] = true;
				$return['message']    = "Record was successfully updated";
			}
    	}  

    	return $return;
    }

    public function resetToPendingRequest() {
    	$return = array();   
    	$update_request = false;	
    	if( !empty($this->id) && $this->is_approved == self::APPROVED ){    		
    		if( $this->is_paid == self::YES ){    		
				if( $this->apply_half_day_date_start == self::YES ){
					$num_days = 0.5;
				} else {
					$date_diff 		 = strtotime($this->date_end) - strtotime($this->date_start);
					$days_difference = date("d",$date_diff); 
					$num_days  		 = $num_days + $days_difference; //Days difference of two dates					
				}

				if( $num_days >= 0 ){
					$el = new G_Employee_Leave_Available();
					$el->setEmployeeId($this->employee_id);
					$el->setLeaveId($this->leave_id);
					$is_reverted = $el->addLeaveCredits($num_days); //Return leave credits

					if( $is_reverted['is_success'] ){						
						$this->is_approved = self::PENDING;
						$is_saved 		   = self::save(); //Update request
						if( $is_saved ){
							$update_request    = true;
							$return['is_success'] = true;
							$return['message']    = "Record updated";
						}else{
							$return['is_success'] = false;
							$return['message']    = "Cannot save record";
						}						
					}else{
						$return = $is_reverted;
					}

				}else{
					$return['is_success'] = false;
					$return['message']    = "Cannot save record";
				}
			}else{				
				$update_request    = true;
				$this->is_approved = self::PENDING;
				$is_saved 		   = self::save(); //Update request
				if( $is_saved ){
					$return['is_success'] = true;
					$return['message']    = "Record updated";
				}else{
					$return['is_success'] = false;
					$return['message']    = "Cannot save record";
				}
			}
		}elseif( !empty($this->id) && $this->is_approved == self::DISAPPROVED ){			
			$this->is_approved = self::PENDING;
			$is_saved 		   = self::save(); //Update request
			if( $is_saved ){
				$update_request       = true;
				$return['is_success'] = true;
				$return['message']    = "Record updated";
			}else{
				$return['is_success'] = false;
				$return['message']    = "Cannot save record";
			}
		}else{			
			$return['is_success'] = false;
			$return['message']    = "Record not found";
		}

		//Set all approvers status to Pending
		if( $update_request ){
			$request = new G_Request();
			$request->setRequestId($this->id);
			$request->setRequestType(G_Request::PREFIX_LEAVE);
			$request->resetToPendingApproversStatusByRequestIdAndRequestType(); 
		}

    	return $return;
    }

    public function approve() {
        if ($this->getIsApproved() != self::APPROVED) {
            // SET AS APPROVED
            $this->setIsApproved(self::APPROVED);
            $current_year = Tools::getGmtDate('Y', strtotime($this->date_start));

            // DEDUCT FROM LEAVE CREDITS
            $leave_days = G_Employee_Leave_Request_Helper::countLeaveDays($this);
            $leave_id = $this->leave_id;
            $la = G_Employee_Leave_Available_Finder::findByEmployeeIdLeaveIdYear($this->employee_id, $leave_id, $current_year);
            if ($la && $la->getNoOfDaysAvailable() > 0) {
                G_Employee_Leave_Available_Manager::subtractLeaveCredit($la, $leave_days);
            }
            $this->save();

            $employee_id = $this->getEmployeeId();
            $date_start = $this->getDateStart();
            $date_end = $this->getDateEnd();
            G_Attendance_Helper::updateAttendanceByEmployeeIdPeriod($employee_id, $date_start, $date_end);

            return true;
        } else {
            return false;
        }
    }
    /*
    *   Voids approved request. Deducted leave credits will be added again. Status will be pending
    *
    */
    public function voidRequest() {
        if ($this->getIsApproved() == self::APPROVED) {
            // SET AS PENDING
            $this->setIsApproved(self::PENDING);
            $current_year = Tools::getGmtDate('Y', strtotime($this->date_start));

            $leave_days = G_Employee_Leave_Request_Helper::countLeaveDays($this);
            $leave_id = $this->leave_id;
            $la = G_Employee_Leave_Available_Finder::findByEmployeeIdLeaveIdYear($this->employee_id, $leave_id, $current_year);
            if ($la) {
                G_Employee_Leave_Available_Manager::addLeaveCredit($la, $leave_days);
            }
            $this->save();
        }
    }

    public function filterValidDates() {

    	if( $this->employee_id > 0 && $this->leave_id > 0 && $this->date_start != '' && $this->date_end != '' && strtotime($this->date_start) <= strtotime($this->date_end) ){
 	   		$a_dates     = Tools::getBetweenDates($this->date_start, $this->date_end);
    		$a_new_dates = array();   

    		foreach( $a_dates as $date ){
    			//Check if date is holiday

    			$hol = new G_Holiday();
    			$is_date_holiday = $hol->isDateHoliday($date);

    			//Check if restday
    			$is_date_rest_day_specific = G_Employee_Helper::isDateEmployeeRestDaySpecific($this->employee_id, $date);


    			//Check attendace if rd
    			$is_date_rest_day = G_Attendance_Helper::sqlIsDateRdByEmployeeIdAndDate($this->employee_id, $date);

    			//Capture all valid dates for saving in leave request
    			if( !$is_date_holiday && !$is_date_rest_day_specific && !$is_date_rest_day ){
    				$a_new_dates['valid'][] = $date;
    			}else{
    				$a_new_dates['invalid'][] = $date;
    			}    		
    		}
    		
    		if( !empty($a_new_dates) && isset($a_new_dates['valid']) ){
    			$valid_dates  = $a_new_dates['valid'];
    			$last_date    = end($valid);
    			$counter      = 0;

    			$expected_next_date = '';
    			$reset_start_date   = true;
    			$total_dates        = count($valid_dates);    			
    			foreach( $valid_dates as $value ){
    				if( $reset_start_date ){
    					$s_start_date     = $value;
    					$reset_start_date = false;
    				}

    				$expected_next_date = date("Y-m-d",strtotime("+1 day",strtotime($value)));     				
    				if( $valid_dates[$counter+1] == $expected_next_date ){    	    					
    					$counter++; 	 				    						
    					continue;
    				}else{    										
    					$leave_data[] = array(    											
    						"from_date" => $s_start_date, 
    						"to_date" => $value    						
    					);
    					$reset_start_date = true;
    				}
    				$counter++; 				
    			}
    		} 

    		$this->a_valid_leave_dates = $leave_data;
    	}
    	return $this;
    }

    public function deductLeaveToLeaveCredits(){
    	$filtered_dates = $this->a_valid_leave_dates;
    	if( $this->apply_half_day_date_start == self::YES ){
			$num_days = 0.5;
		} elseif( !empty( $filtered_dates ) ){
    		foreach($filtered_dates as $values){    				
    			$date_diff 		  = strtotime($values['to_date']) - strtotime($values['from_date']);
				$days_difference  = date("d",$date_diff); 
				$num_days  		 += $days_difference; //Days difference of two dates
    		}
    	}else{
    		$date_diff 		 = strtotime($this->date_end) - strtotime($this->date_start);
			$days_difference = date("d",$date_diff); 
			$num_days  		 = $num_days + $days_difference; //Days difference of two dates
    	}
    	
    	$default_leave_id_general_rule = $this->default_general_rule;

		if( $this->is_approved == self::APPROVED ){
			if( $this->is_paid == self::YES ){
				if( $num_days >= 0 ){
					$el = new G_Employee_Leave_Available();
					$el->setEmployeeId($this->employee_id);
					if( $default_leave_id_general_rule > 0 ){
						$el->setLeaveId($default_leave_id_general_rule);	
					}else{
						$el->setLeaveId($this->leave_id);
					}
					$el->deductLeaveCredits($num_days); //Deduct leave credits
				}
			}			
		}/*else{
			//If is paid, check if number of leave is sufficient
			if( $this->is_paid == self::YES ){
				$el = new G_Employee_Leave_Available();						
				$el->setEmployeeId($this->employee_id);
				if( $default_leave_id_general_rule > 0 ){
					$el->setLeaveId($default_leave_id_general_rule);	
				}else{
					$el->setLeaveId($this->leave_id);
				}	
				$el->deductLeaveCredits($num_days); //Deduct leave credits				
			}
		}	*/	
		return $this;
	}

	public function createLeaveBulkRequests(){
		if( $this->employee_id > 0 && $this->leave_id > 0 && $this->date_start != '' && $this->date_end != '' && strtotime($this->date_start) <= strtotime($this->date_end) ){  			
			$filtered_dates = $this->a_valid_leave_dates;
			if( !empty($filtered_dates) ){				
				foreach($filtered_dates as $values){				
					$bulk_data[] = "(" . Model::safeSql($this->company_structure_id) . "," . Model::safeSql($this->employee_id) . "," . Model::safeSql($this->leave_id) . "," . Model::safeSql($this->date_applied) . "," . Model::safeSql($this->time_applied) . "," . Model::safeSql($values['from_date']) . "," . Model::safeSql($values['to_date']) . "," . Model::safeSql($this->leave_comments) . "," . Model::safeSql($this->is_approved) . "," . Model::safeSql($this->is_paid) . "," . Model::safeSql($this->created_by) . "," . Model::safeSql($this->is_archive) . "," . Model::safeSql($this->apply_half_day_date_start) . ")";
				}

				$this->a_bulk_insert = $bulk_data;
			}
		}	

		return $this;
	}

	public function bulkSaveRequests() {		
		$return['is_success'] = false;
		$return['message']    = 'Cannot save data';		
		if( !empty($this->a_bulk_insert) ){							
			$is_success = G_Employee_Leave_Request_Manager::bulkInsertData($this->a_bulk_insert);
			if( $is_success ){
				$update_request       = true;
				$return['is_success'] = true;
				$return['message']    = "Record updated";
			}
		}

		return $return;
	}

}

?>