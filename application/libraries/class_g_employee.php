<?php
/*
$e = Employee_Factory::get(1);

$e->getFirstname();*/

class G_Employee extends Employee implements IEmployee {

	public $hash;
	public $employee_device_id;    
    public $department_company_structure_id;
	public $is_archive = self::NO;
    public $year_working_days;
    public $week_working_days;
    public $tags;
    protected $bulk_education = array();
    protected $bulk_emergency_contact = array();
    protected $bulk_education_is_valid = false;
    protected $bulk_emergency_contact_is_valid = false;
	
	const NO  = 'No';
	const YES = 'Yes';
	const MAX_EMPLOYEES = EMPLOYEE_LIMIT; // config_client

	public function __construct() {}
	
	public function getName() {
		//return $this->lastname .', '. $this->firstname;	
        return $this->lastname .' '. $this->firstname . ' ' . $this->middlename; 
	}
	
	public function getHash() {
		return $this->hash;
	}
	
	public function setHash($value) {
		$this->hash = $value;	
	}
	
	public function setEmployeeDeviceId($value) {
		$this->employee_device_id = $value;
	}
	
	public function getEmployeeDeviceId() {
		return $this->employee_device_id;
	}

    public function setDepartmentCompanyStructureId( $value = 0 ){
        $this->department_company_structure_id = $value;
    }

    public function getDepartmentCompanyStructureId() {
        return $this->department_company_structure_id;
    }

    public function setYearWorkingDays($value) {
        $this->year_working_days = $value;
    }

    public function getYearWorkingDays() {
        return $this->year_working_days;
    }

    public function setWeekWorkingDays($value) {
        $this->week_working_days = $value;
    }

    public function getWeekWorkingDays() {
        return $this->week_working_days;
    }
	
	public function setIsArchive($value) {
		$this->is_archive = $value;
	}
	
	public function getIsArchive() {
		return $this->is_archive;
	}

    public function setTags($value){
        $this->tags = $value;
    }

    public function getTags() {
        return $this->tags;
    }
	
	public function goToWork($date, $time_in, $time_out) {
        return G_Attendance_Helper::goToWork($this, $date, $time_in, $time_out);
	}

    /*
        Enroll employee to benefits
        Usage : 
        $employee_id          = 1;      
        $benefits             = array("wP4dkK9Ud-6p2NLXkg4M80I-vv0Zaal9dv88kdYYo8s","rPn_n_eXTu4htnooJyAx-2NRCcVhfRIF0rvjqQz3q-I","QmHY-wZzstfI65vHWXaZ0by1yeWrVD6v41kRWzmCUow");
        $e = new G_Employee();
        $e->setId($employee_id);
        $return = $e->enrollToBenefits($benefits);//Returns and accepts array
    */

    public function enrollToBenefits( $benefits = array() , $company_structure_id = 0 ) {
        $return = array();
        $errors = array();
        $errors['errors_count'] = 0;

        if( !empty($this->id) && !empty($benefits) && $company_structure_id > 0 ){
            
            foreach( $benefits as $benefit ){
                $benefit_id          = Utilities::decrypt($benefit);
                $is_benefit_id_exits = G_Settings_Employee_Benefit_Helper::sqlIsIdExists($benefit_id);
                $is_applied_to_all   = G_Employee_Benefits_Main_Helper::sqlIsBenfitIdAssignedToAllEmployee($benefit_id);
                if( $is_benefit_id_exits ){
                    if( !$is_applied_to_all ){
                        $is_already_assigned_to_employee = G_Employee_Benefits_Main_Helper::sqlIsBenfitIdAssignedToEmployee($benefit_id, $this->id);

                        if( !$is_already_assigned_to_employee ){
                            $encrypted_employee_ids = Utilities::encrypt($this->id);
                            $apply_to               = Employee_Benefits_Main::EMPLOYEE;

                            $b = new G_Employee_Benefits_Main();
                            $b->setCompanyStructureid($company_structure_id);
                            $b->setBenefitId($benefit_id);
                            $b->setAppliedTo($apply_to);
                            $b->enrollToBenefit($encrypted_employee_ids);
                        }else{
                            $errors['errors_count']  = $errors['errors_count'] + 1;
                            $errors['errors_msgs'][] = 'Benefit is already applied to employee';
                        }

                    }else{
                        $errors['errors_count']  = $errors['errors_count'] + 1;
                        $errors['errors_msgs'][] = 'Benefit is already applied to all';
                    }
                }else{
                    $errors['errors_count']  = $errors['errors_count'] + 1;
                    $errors['errors_msgs'][] = 'Record not found';
                }
            }

        }else{
            $errors['errors_count']  = $errors['errors_count'] + 1;
            $errors['errors_msgs'][] = 'Record not found';
        }   

        if( $errors['errors_count'] > 0 ){
            $total_failed_insert  = $errors['errors_count'];
            $errors_messages      = implode("<br />", $errors['errors_msgs']);
            $return['is_success'] = true;
            $return['message']    = "Total Errors : <b>{$total_failed_insert}</b> <br />{$errors_messages}";
        }else{
            $return['is_success'] = true;
            $return['message']    = 'Record Updated';
        }

        return $return; 
    }

    /*
    * Attendance Log In. This is used for manual adding of DTR
    *
    */
    public function punchIn($date, $time) {
        return G_Attendance_Helper::punchIn($this, $date, $time);
    }

    /*
    * Attendance Log Out. This is used for manual adding of DTR
    *
    */
    public function punchOut($date, $time) {
        return G_Attendance_Helper::punchOut($this, $date, $time);
    }  

    /*
    * Attendance Log In with Project Site and Activity. This is used for manual adding of staggered schedule
    *
    */
    public function punchInWithProjectSiteAndActivity($date, $time, $project_site, $activity_name) {
        return G_Attendance_Helper_V2::punchInWithProjectSiteAndActivity($this, $date, $time, $project_site, $activity_name);
    }  

    /*
    * Attendance Log Out with Project Site and Activity. This is used for manual adding of staggered schedule
    *
    */
    public function punchOutWithProjectSiteAndActivity($date, $time, $project_site, $activity_name) {
        return G_Attendance_Helper_V2::punchOutWithProjectSiteAndActivity($this, $date, $time, $project_site, $activity_name);
    }  

    public function requestOvertime($date, $time_in, $time_out, $reason = '') {
        return G_Overtime_Helper::requestOvertime($this, $date, $time_in, $time_out, $reason);
    }

    public function getOvertime($date) {
        return G_Overtime_Finder::findByEmployeeAndDate($this, $date);
    }

    public function getOvertimeRequest($date) {
        return G_Overtime_Finder::findByEmployeeAndDate($this, $date);
    }

    public function addRestDay($date) {
        return G_Restday_Helper::addRestDay($this, $date);
    }

    public function addApprovedOvertime($date, $time_in, $time_out, $reason = '') {
        return G_Overtime_Helper::addApprovedOvertime($this, $date, $time_in, $time_out, $reason);
    }

    public function addContribution($salary) {
        return G_Employee_Contribution_Helper::addContribution($this->id, $salary);
    }

    public function updateSectionId($section_id) {
        return G_Employee_Manager::updateSectionId($this->id, $section_id);
    }

    /*
     * Usage:
        $address = 'block 42 lot 4';
        $city = 'cabuyao';
        $province = 'laguna';
        $zip_code = '4025';
        $home_phone = '5024826';
        $mobile = '09175511284';
        $work_phone = '50299123';
        $work_email = 'test@gmail.com';
        $other_email = 'other@gmail.com';
        $e->addContactDetails($address, $city, $province, $zip_code, $home_phone, $mobile, $work_phone, $work_email, $other_email);
     */
    public function addContactDetails($address, $city, $province, $zip_code, $home_phone, $mobile, $work_phone, $work_email, $other_email) {
        return G_Employee_Contact_Details_Helper::addContactDetails($this->id, $address, $city, $province, $zip_code, $home_phone, $mobile, $work_phone, $work_email, $other_email);
    }

    /*
     * @return object Instance of G_Employee_Contact_Details
     */
    public function getContactDetails() {
        return G_Employee_Contact_Details_Finder::findByEmployeeId($this->id);
    }

    public function addBankAccount($bank_name, $bank_account) {
        return G_Employee_Direct_Deposit_Helper::addBankAccount($this->id, $bank_name, $bank_account);
    }

    /*
     * @param string $salary_type It's G_Employee_Basic_Salary_History::SALARY_TYPE_DAILY or G_Employee_Basic_Salary_History::SALARY_TYPE_MONTHLY
     */
    public function addNewSalary($salary_amount, $salary_type, $effectivity_date,$frequency_id) {
        return G_Employee_Basic_Salary_History_Helper::addNewSalary($this->id, $salary_amount, $salary_type, $effectivity_date,$frequency_id);
    }
	
	public function absentToWork($date) {

	}

    public function getEmployeeDailyRate(){
        $end_date = date("Y-m-d");
        $per_day  = 0;
        $s = G_Employee_Basic_Salary_History_Finder::findByEmployeeAndDate($this, $end_date);
        if( $s ){
            $salary_amount = $s->getBasicSalary();  
            $working_days  = $this->getYearWorkingDays();

            if( $working_days <= 0 ){
               $sv = new G_Sprint_Variables(G_Sprint_Variables::FIELD_DEFAULT_TOTAL_WRKNG_DAYS);
               $working_days = $sv->getVariableValue();
            }

            $salary_type = $s->getType();
            switch ($salary_type):
                case G_Employee_Basic_Salary_History::SALARY_TYPE_MONTHLY:  
                    $per_day   = ($salary_amount * 12) / $working_days;
                    break;
                case G_Employee_Basic_Salary_History::SALARY_TYPE_DAILY:    
                    $monthly_rate_daily = ($salary_amount * $working_days) / 12;
                    $per_day            = ($monthly_rate_daily * 12) / $working_days;  
                    break;
            endswitch;
        }

        return number_format($per_day,2);
    }

    /*
        Usage :        
        $request_id  = 2;
        $employee_id = 1;
        $type        = G_Request::PREFIX_LEAVE;
        $e = G_Employee_Finder::findById($employee_id);
        if( $e ){
         $request_data = $e->getRequestForApprovalDetails($request_id, $type); //returns array
        }
    */

    public function getRequestForApprovalDetails( $request_id = '', $type = '' ) {
        $data = array();
        if( !empty( $this->id ) && !empty($request_id) && !empty($type) ){
            $fields        = array("id","status","is_lock","remarks","action_date");
            $limit         = "LIMIT 1";
            $approver_data = G_Request_Helper::sqlFetchDataByApproverIdAndRequestIdAndRequestType($this->id, $request_id, $type, $fields, $limit);
            if( $approver_data ){
                $approver_data       = array_shift($approver_data);
                $approver_data['id'] = Utilities::encrypt($approver_data['id']); //encrypt id
                switch ($type) {
                    case G_Request::PREFIX_LEAVE:
                        $fields          = array("CONCAT(lastname, ', ', firstname, ' ', middlename)AS requested_by","date_applied","date_start","date_end","apply_half_day_date_start","apply_half_day_date_end","leave_comments","is_approved","name AS leave_type");                     
                        $request_details = G_Employee_Leave_Request_Helper::sqlRequestDetailsById($request_id, $fields);
                        $status = $request_details['is_approved'];
                        break;
                    case G_Request::PREFIX_OVERTIME:
                        $fields          = array("CONCAT(lastname, ', ', firstname, ' ', middlename)AS requested_by","date","time_in","time_out","reason","status");                    
                        $request_details = G_Overtime_Helper::sqlRequestDetailsById($request_id, $fields);
                        $status = $request_details['status'];
                        break;
                    case G_Request::PREFIX_OFFICIAL_BUSSINESS:
                        $fields          = array("CONCAT(lastname, ', ', firstname, ' ', middlename)AS requested_by","date_applied","date_start","date_end","comments","is_approved");
                        $request_details = G_Employee_Official_Business_Request_Helper::sqlRequestDetailsById($request_id, $fields);
                        $status = $request_details['is_approved'];
                        break;
                    default:                    
                        break;
                }                       
                $data['status']             = $status;
                $data['request_type']       = $type;
                $data['request_eid']        = Utilities::encrypt($request_id);
                $data['approver_eid']       = Utilities::encrypt($this->id);
                $data['approvers_details'] = $approver_data;   
                $data['request_details']    = $request_details;
            }
        }

        return $data;
    }

    public function resign($date) {
        G_Employee_Helper::resign($this, $date);
    }

    public function terminate($date) {
        G_Employee_Helper::terminate($this, $date);
    }

    public function endo($date) {
        G_Employee_Helper::endo($this, $date);
    }

    public function inactive($date) {
        G_Employee_Helper::inactive($this, $date);
    }  


    public function resetActive($date) {
        G_Employee_Helper::resetActive($this, $date);
    }  

    //USED IN REPORT GENERATION
    public function getManpowerCountData($query) {
       return G_Employee_Helper::getManpowerCountData($query);
    }
    
    public function getEndOfContractData($query, $add_query) {
       return G_Employee_Helper::getEndOfContractData($query, $add_query);
    }

    public function getResignedEmployeesData($query) {
       return G_Employee_Helper::getResignedEmployees($query);
    }

    public function getTerminatedEmployeesData($query) {
       return G_Employee_Helper::getTerminatedEmployees($query);
    }
    
    public function countEndOfContractData($query) {
       return G_Employee_Helper::countEndOfContractData($query);
    }
    
    public function getDailyTimeRecordData($query, $add_query) {
       return G_Employee_Helper::getDailyTimeRecordData($query, $add_query);
    }
    
    public function getDailyTimeRecordDataWithBreak($query, $add_query) {
       return G_Employee_Helper::getDailyTimeRecordDataWithBreak($query, $add_query);
    }

    public function getDailyTimeRecordSummarizedData($query, $add_query) {
        return G_Employee_Helper::getDailyTimeRecordSummarizedData($query, $add_query);
    }
    
    public function getDailyTimeRecordSummarizedDataWithBreak($query, $add_query) {
       return G_Employee_Helper::getDailyTimeRecordSummarizedDataWithBreak($query, $add_query);
    }

    public function getDailyTimeRecordIncompleteBreakLogs($query, $add_query) {
        return G_Employee_Helper::getDailyTimeRecordIncompleteBreakLogs($query, $add_query);
    }
     
    public function getDailyTimeRecordNoBreakLogs($query, $add_query) {
        return G_Employee_Helper::getDailyTimeRecordNoBreakLogs($query, $add_query);
    }
     
    public function getDailyTimeRecordEarlyBreakOut($query, $add_query) {
        return G_Employee_Helper::getDailyTimeRecordEarlyBreakOut($query, $add_query);
    }

    public function getDailyTimeRecordLateBreakIn($query, $add_query) {
        return G_Employee_Helper::getDailyTimeRecordLateBreakIn($query, $add_query);
    }
     
    
    public function getIncompleteTimeInOutData($query, $add_query) {
       return G_Employee_Helper::getIncompleteTimeInOutData($query, $add_query);
    }  
    
    public function getIncompleteTimeInOutWithBreakLogsData($query, $add_query) {
       return G_Employee_Helper::getIncompleteTimeInOutWithBreakLogsData($query, $add_query);
    }  

    public function getIncorrectShiftData($query) {
       return G_Employee_Helper::getIncorrectShiftData($query);
    }    
    
    public function getTimesheetData($query, $add_query) {
       return G_Employee_Helper::getTimesheetData($query, $add_query);
    }  
    
    public function getTimesheetDataWithBreak($query, $add_query) {
       return G_Employee_Helper::getTimesheetDataWithBreak($query, $add_query);
    }

     public function getEmployeeTimesheetData($date_from = '', $date_to = '') {
       $data = array();
       if( !empty($this->id) && !empty($date_from) && !empty($date_to) ){
           $data = G_Employee_Helper::getEmployeeTimesheetDataByEmployeeIdAndDateRange($this->id, $date_from, $date_to);
       }    
       return $data;
    }

    public function getOvertimeData($query, $add_query) {
       return G_Employee_Helper::getOvertimeData($query, $add_query);
    }

    public function countOvertimeData($query, $add_query) {
       return G_Employee_Helper::countOvertimeData($query, $add_query);
    }

    public function getUndertimeData($query, $add_query) {
       return G_Employee_Helper::getUndertimeData($query, $add_query);
    }

    public function getUndertimeWithBreakLogsData($query, $add_query) {
       return G_Employee_Helper::getUndertimeWithBreakLogsData($query, $add_query);
    }

    public function countUndertimeData($query, $add_query) {
       return G_Employee_Helper::countUndertimeData($query, $add_query);
    }

    public function countUndertimeWithBreakLogsData($query, $add_query) {
       return G_Employee_Helper::countUndertimeWithBreakLogsData($query, $add_query);
    }
    
    public function getLeaveData($query, $add_query) {
       return G_Employee_Helper::getLeaveData($query, $add_query);
    }

    public function getLeaveCreaditsData($query, $add_query) {
       return G_Employee_Helper::getLeaveCreditsData($query, $add_query);
    }

    public function getLeaveCreaditsDataGeneralIncentive($query, $add_query) {
       return G_Employee_Helper::getLeaveCreaditsDataGeneralIncentive($query, $add_query);
    }    

    public function getEmployeesTotalLeaveCredits($query, $add_query) {
       return G_Employee_Helper::getEmployeesTotalLeaveCredits($query, $add_query);
    }

    public function getEmployeesTotalLeaveCreditsGeneralIncentive($query, $add_query) {
       return G_Employee_Helper::getEmployeesTotalLeaveCreditsGeneralIncentive($query, $add_query);
    }    

    public function countLeaveData($query) {
       return G_Employee_Helper::countLeaveData($query);
    }

    public function getEmploymentStatusData($query, $add_query) {
       return G_Employee_Helper::getEmploymentStatusData($query, $add_query);
    }

    public function getEeErContributionData($query, $add_query) {
       return G_Employee_Helper::getEeErContributionData($query, $add_query);
    }

    /* END REPORT GENERATION */

    /*
     * DEPRECATED
     */
	public function activeToTerminated($data) {		
		return G_Employee_Helper::activeToTerminated($data,$this);
	}

    /*
     * DEPRECATED
     */
	public function activeToResigned($data) {		
		return G_Employee_Helper::activeToResigned($data,$this);
	}

    /*
     * DEPRECATED
     */
	public function activeToEndo($data) {		
		return G_Employee_Helper::activeToEndo($data,$this);
	}
	
	public function employeeAttachFile($attachment,$data) {		
		return G_Employee_Helper::employeeAttachFile($attachment,$data,$this);
	}
	
	public function save() {        
		return G_Employee_Manager::save($this);
	}
	
	public function updateEmployeeStatus() {
		return G_Employee_Manager::updateEmployeeStatus($this);
	}
	
	public function archive() {
		return G_Employee_Manager::archive($this);
	}
	
	public function restore() {
		return G_Employee_Manager::restore($this);
	}
	
	public function addHash($hash) {
		return G_Employee_Manager::addHash($this,$hash);
	}

	public function delete() {
		return G_Employee_Manager::delete($this);
	}

    public function addDefaultLeaveCredits($year) {
        G_Leave_Helper::addDefaultLeaveCreditsToEmployee($this, $year);
    }

    /*
     * @param object $leave Instance of G_Leave
     */
    public function requestLeave($leave, $applied_date, $start_date, $end_date, $comment = '', $is_half_day1 = '', $is_half_day2 = '') {
        $number_of_days = Tools::countDaysDifference($start_date, $end_date);
        $available_credit = $this->getAvailableLeaveCredit($leave);

        // Return true if requested number of days can be covered by available credit
        if ($available_credit >= $number_of_days || $available_credit == 0) {
            return G_Employee_Leave_Request_Helper::addNewRequest($this->getId(), $leave->getId(), $applied_date, $start_date, $end_date, $comment, $is_half_day1, $is_half_day2);
        } else {
            return false;
        }
    }

    public function requestOfficialBusiness($applied_date, $start_date, $end_date, $comment) {
        return G_Employee_Official_Business_Request_Helper::addNewRequest($this->getId(), $applied_date, $start_date, $end_date, $comment);
    }

    /*
     * Gets the leave request of this employee
     *
     * @param string $leave_date Date of actual leave example: '2014-01-20'
     * @return object Instance of G_Employee_Leave_Request class
     */
    public function getLeaveRequest($leave_date) {
        return G_Employee_Leave_Request_Finder::findByEmployeeIdAndLeaveDate($this->id, $leave_date);
    }

    public function getOfficialBusinessRequest($date) {
        return G_Employee_Official_Business_Request_Finder::findByEmployeeIdDate($this->id, $date);
    }

    //ob request timebase duplicate existing function to prevent error
     public function getOfficialBusinessRequest2($date) {
        return G_Employee_Official_Business_Request_Finder::findByEmployeeIdDate2($this->id, $date);
    }

    /*
     * Adds leave credit or days
     *
     * @param object $leave Instance of G_Leave
     * @param float $number_of_days Number of days to add in leave credit
     */
    public function addLeaveCredit($leave, $number_of_days, $year = '') {
        G_Leave_Helper::addLeaveCreditsToEmployee($this, $leave, $number_of_days, $year);
    }

    /*
     * Gets the available leave credits or days of this employee
     *
     * @param object $leave Instance of G_Leave
     * @param integer $year What year?
     * @return float Number of available leave credits or days
     */
    public function getAvailableLeaveCredit($leave, $year = '') {
        return G_Employee_Leave_Available_Helper::getAvailableLeaveCredit($this, $leave, $year);
    }

    /*
     * Gets the attendance of this employee
     *
     * @param string $date_start Format: YYYY-MM-DD
     * @param string $date_end Format: YYYY-MM-DD
     * @return mixed 1) Array of instance G_Attendance 2) Object instance of G_Attendance
     */
    public function getAttendance($date_start, $date_end = '') {
        if ($date_start != '' && $date_end != '') {
            return G_Attendance_Finder::findByEmployeeAndPeriod($this, $date_start, $date_end);
        } else if ($date_start != '') {
            return G_Attendance_Finder::findByEmployeeAndDate($this, $date_start);
        }
    }

    public function getPayslip($month, $cutoff_number, $year = '') {
        if ($year == '') {
            $year = Tools::getGmtDate('Y');
        }
        $period = G_Cutoff_Period_Finder::findByYearMonthAndCutoffNumber($year, $month, $cutoff_number);
        $start_date = $period->getStartDate();
        $end_date = $period->getEndDate();
        return G_Payslip_Finder::findByEmployeeAndPeriod($this, $start_date, $end_date);
    }

    /*
     * Gets the salary based on date
     *
     * @param string $date Date of salary covered
     * @return object Instance of G_Employee_Basic_Salary_History
     */
    public function getSalary($date) {
        return G_Employee_Basic_Salary_History_Finder::findByEmployeeAndDate($this, $date);
    }

    /*
     * DEPRECATED!
     *
     * Generates payslip based on given dates
     *
     * @param string $start_date
     * @param string $end_date
     * @return G_Payslip
     */
    public function generatePayslip($start_date, $end_date) {
        return G_Payslip_Helper::generatePayslip($this, $start_date, $end_date);
    }

    public function getValidEmployeeImage(){
        $image = MAIN_FOLDER . "hr/files/photo/profile_noimage.gif";
        if( !empty($this->photo) ){
            $image_check =  $_SERVER['DOCUMENT_ROOT'] . MAIN_FOLDER . "hr/files/photo/" . $this->photo;
            if(Tools::checkFileExist($image_check)==1) {             
                $image = MAIN_FOLDER . "hr/files/photo/" . $this->photo;
            }
        }

        return $image;
    }

    public function updateIsTaxExempted() {
        return G_Employee_Manager::updateIsTaxExempted($this);
    }

    /*
        Usage : 
        $id = 1;
        $e  = G_Employee_Finder::findById($id);
        if( !empty($e) ){
            $approvers = $e->getRequestApprovers(); //Returns approvers array
        }
    */

    public function getRequestApprovers() {
        $approvers = array();

        if( !empty($this->id) ){
            $employee_id = $this->id;
            $gra = new G_Request_Approver();
            $gra->setEmployeeId($employee_id);
            $approvers = $gra->getEmployeeRequestApprovers();
        }

        return $approvers;
    }

    public function generateCetaSea($data = array(), $salary_type = null){ 
        $return = array();
        if( $this->id > 0 && !empty($data) ){            
            $a = $data['attendance'];
            $daily_rate = $data['daily_rate'];

            $sv = new G_Sprint_Variables();
            $min_rate = $sv->setVariableName(G_Sprint_Variables::FIELD_MIN_RATE)->getVariableValue();
            $ceta     = $sv->setVariableName(G_Sprint_Variables::FIELD_CETA)->getVariableValue();
            $sea      = $sv->setVariableName(G_Sprint_Variables::FIELD_SEA)->getVariableValue();

            $ceta_custom_value_a = $sv->setVariableName(G_Sprint_Variables::FIELD_CETA)->getVariableCustomValueA();
            $sea_custom_value_a  = $sv->setVariableName(G_Sprint_Variables::FIELD_SEA)->getVariableCustomValueA();

            if($salary_type == 'Daily') {
                $total_valid_days    = G_Attendance_Helper::getTotalValidCetaSeaNumberOfDaysDailyEmployee($a);            
            } else {
                $total_valid_days    = G_Attendance_Helper::getTotalValidCetaSeaNumberOfDays($a);            
            }
            
            if( $ceta_custom_value_a ){
                if( round($daily_rate,0, PHP_ROUND_HALF_DOWN) <= round($min_rate,0, PHP_ROUND_HALF_DOWN) ){
                    $ceta_amount = ($min_rate / $daily_rate) * $ceta;
                }else{
                    $ceta_amount = 0;
                    $total_valid_days = 0;
                }
            }else{
                $ceta_amount = ($min_rate / $daily_rate) * $ceta;    
            }

            if( $sea_custom_value_a ){
                if( round($daily_rate,0, PHP_ROUND_HALF_DOWN) <= round($min_rate,0, PHP_ROUND_HALF_DOWN) ){
                    $sea_amount  = ($min_rate / $daily_rate) * $sea;
                }else{
                    $sea_amount = 0;
                    $total_valid_days = 0;
                }
            }else{
                $sea_amount  = ($min_rate / $daily_rate) * $sea;  
            }

            $s = G_Employee_Basic_Salary_History_Finder::findByEmployeeAndDate($this, date("Y-m-d"));                        
            if( $s ){                                
                $salary_type   = $s->getType();                  
                switch ($salary_type) {                   
                    case G_Employee_Basic_Salary_History::SALARY_TYPE_DAILY:                         
                        foreach ($a as $at) {
                            $h = $at->getHoliday();                            
                            if( $h && $h->isLegal() ){                                                
                                $date = $at->getDate();                                
                                $previous_date = date("Y-m-d",strtotime("-1 days",strtotime($date)));                
                                $yesterday     = G_Attendance_Helper::sqlEmployeeAttendanceByDateAndEmployeeId($this->id, $previous_date);                                                              
                                if( ($yesterday['is_present']) || ($yesterday['is_restday']) || ($yesterday['is_holiday']) ){                                                         
                                    $total_valid_days += 1;
                                }
                            }                            
                        }
                        break;                    
                    default:                        
                        break;
                }

            }

            //echo "Daily Rate : {$daily_rate} / Ceta Amount : {$ceta_amount} / Sea Amount : {$sea_amount}<br> / Valid days : {$total_valid_days}";

            $ceta_value = $total_valid_days * $ceta_amount;
            $sea_value  = $total_valid_days * $sea_amount;
            $total_amount = $ceta_value + $sea_value;

            $return['ceta_amount']  = number_format($ceta_value,2);
            $return['sea_amount']   = number_format($sea_value,2);
            $return['total_amount'] = $total_amount;
            $return['total_counted_days'] = $total_valid_days;
        }

        return $return;
    }

    /**
    * Get employee ceta/sea amount base on employee rate
    *
    * @param int employee_id optional - manual setting of employee pkid
    * @return int
    */
    public function getEmployeeCetaSeaRate() {
        $ceta_sea_rate = 0;

        if( $this->id > 0 ){
            $end_date = date("Y-m-d");
            $s = G_Employee_Basic_Salary_History_Finder::findByEmployeeAndDate($this, $end_date);

            if( empty($s) ){
                return $ceta_sea_rate;
            }

            $salary_type   = $s->getType();
            $salary_amount = $s->getBasicSalary();

            $working_days = $this->getYearWorkingDays();
            if( $working_days <= 0 ){
                $sv = new G_Sprint_Variables(G_Sprint_Variables::FIELD_DEFAULT_TOTAL_WRKNG_DAYS);
                $working_days = $sv->getVariableValue();
            }

            switch ($salary_type):
                case G_Employee_Basic_Salary_History::SALARY_TYPE_MONTHLY:   
                    $daily_rate = $salary_amount / ($working_days / 12);
                    break;
                case G_Employee_Basic_Salary_History::SALARY_TYPE_DAILY:    
                    $monthly_rate_daily = ($salary_amount * $working_days) / 12;      
                    $daily_rate         = $monthly_rate_daily / ($working_days / 12);
                    break;
            endswitch;
        }  

        $sv = new G_Sprint_Variables();
        $min_rate = $sv->setVariableName(G_Sprint_Variables::FIELD_MIN_RATE)->getVariableValue();
        $ceta     = $sv->setVariableName(G_Sprint_Variables::FIELD_CETA)->getVariableValue();
        $sea      = $sv->setVariableName(G_Sprint_Variables::FIELD_SEA)->getVariableValue();

        $ceta_custom_value_a = $sv->setVariableName(G_Sprint_Variables::FIELD_CETA)->getVariableCustomValueA();
        $sea_custom_value_a  = $sv->setVariableName(G_Sprint_Variables::FIELD_SEA)->getVariableCustomValueA();
        $total_valid_days    = G_Attendance_Helper::getTotalValidCetaSeaNumberOfDays($a);

        if( $ceta_custom_value_a ){
            if( round($daily_rate,0, PHP_ROUND_HALF_DOWN) <= round($min_rate,0, PHP_ROUND_HALF_DOWN) ){
                $ceta_amount = ($min_rate / $daily_rate) * $ceta;
            }else{
                $ceta_amount = 0;
                $total_valid_days = 0;
            }
        }else{
            $ceta_amount = ($min_rate / $daily_rate) * $ceta;    
        }

        if( $sea_custom_value_a ){
            if( round($daily_rate,0, PHP_ROUND_HALF_DOWN) <= round($min_rate,0, PHP_ROUND_HALF_DOWN) ){
                $sea_amount  = ($min_rate / $daily_rate) * $sea;
            }else{
                $sea_amount = 0;
                $total_valid_days = 0;
            }
        }else{
            $sea_amount  = ($min_rate / $daily_rate) * $sea;  
        }

        //Get ceta / sea from benefits
        $ceta_sea_rate = $sea_amount + $ceta_amount;
        $search = "CTPA/SEA";
        $benefits = G_Employee_Benefits_Main_Helper::sqlGetEmployeeBenefitsByNameAndEmployeeId($search, $this->id);
        foreach( $benefits as $b ){
            $ceta_sea_rate += $b['amount'];
        }

        return $ceta_sea_rate;
    }

    public function getTotalBreakTimeHrsDeductible($schedule = array(), $day_type = array()){        
        $data = array();
        $data_unique          = array();
        $total_hrs_deductible = 0;

        if( $this->id > 0 && !empty($schedule) ){

            $department_id = 0;
            $breaktime_schedules = array();
            $fields  = array("department_company_structure_id");
            $details = G_Employee_Helper::sqlGetEmployeeDetailsById($this->id, $fields);
            if( $details ){
                $department_id = $details['department_company_structure_id'];
            } 

            $object_array = array(G_Break_Time_Schedule_Details::PREFIX_ALL => 0, G_Break_Time_Schedule_Details::PREFIX_EMPLOYEE => $this->id, G_Break_Time_Schedule_Details::PREFIX_DEPARTMENT => $department_id);            

            $br = new G_Break_Time_Schedule_Details();            
            foreach( $object_array as $key => $value ){                
                $br->setObjType($key);
                $br->setObjId($value);
                $details = $br->getObjDeductibleBreakTimeByScheduleInOut($schedule, $day_type);                
                foreach($details as $value){
                    $timestamp_break_in   = strtotime($value['break_in']);
                    $timestamp_break_out  = strtotime($value['break_out']);
                    $timestamp_actual_in  = strtotime($schedule['actual_in']);
                    $timestamp_actual_out = strtotime($schedule['actual_out']);

                    if( $timestamp_break_in < $timestamp_actual_out ){
                        if( $timestamp_break_in < $timestamp_actual_out && $timestamp_break_out > $timestamp_actual_out ){
                            $actual_time_out = date("G:i:s",strtotime($timestamp_actual_out));
                            $new_total_hrs_deductible = Tools::computeHoursDifferenceByDateTime($actual_time_out, $value['break_out']);   
                        }else{
                            $new_total_hrs_deductible = $value['total_hrs_deductible'];
                        }

                        $data[$value['Break Time']] = $value['total_hrs_deductible'];
                    }                    
                }
            }            
            //$data_unique = array_unique($data);
            $data_unique = $data;

            foreach($data_unique as $value){                
                $total_hrs_deductible += $value;
            }
        }
       
        return $total_hrs_deductible;
    }

    public function getEmployeeBreakTimeBySchedule($schedule = array(), $day_type = array()) {
        $data = array();
        $break_time_schedules = array();
        $break_time_unique    = array();

        if( $this->id > 0 && !empty($schedule) ){            
            $department_id = 0;
            $breaktime_schedules = array();
            $fields  = array("department_company_structure_id");
            $details = G_Employee_Helper::sqlGetEmployeeDetailsById($this->id, $fields);
            if( $details ){
                $department_id = $details['department_company_structure_id'];
            } 

            $object_array = array(G_Break_Time_Schedule_Details::PREFIX_ALL => 0, G_Break_Time_Schedule_Details::PREFIX_EMPLOYEE => $this->id, G_Break_Time_Schedule_Details::PREFIX_DEPARTMENT => $department_id);            

            $br = new G_Break_Time_Schedule_Details();            
            foreach( $object_array as $key => $value ){                
                $br->setObjType($key);
                $br->setObjId($value);
                $details = $br->getObjBreakTimeByScheduleInOut($schedule, $day_type);                
                foreach($details as $value){
                    foreach($value as $subValue){                       
                        $break_time_schedules[] = $subValue;
                    }
                }
            }

            $break_time_unique = array_unique($break_time_schedules);
        }
       
        return $break_time_unique;
    }


    public function getProcessedAndUnprocessedEmployeePayrollByCutoff($start_date, $end_date, $additional_qry, $employee_ids_qry = '', $frequency_id = 1) {
        $total_employees        = G_Employee_Helper::countEmployeeNotArchivedByDate($start_date, $additional_qry, $employee_ids_qry);

        if ($frequency_id == 2) {
            $processed_payroll      = G_Employee_Helper::countProcessedEmployeeWeeklyPayrollByCutoff($start_date, $end_date, $additional_qry, $employee_ids_qry);
            $processed_payroll_data = G_Employee_Helper::sqlProcessedEmployeeWeeklyPayrollByCutoff($start_date, $end_date, $additional_qry, $employee_ids_qry);
        }
        else {
            $processed_payroll      = G_Employee_Helper::countProcessedEmployeePayrollByCutoff($start_date, $end_date, $additional_qry, $employee_ids_qry);
            $processed_payroll_data = G_Employee_Helper::sqlProcessedEmployeePayrollByCutoff($start_date, $end_date, $additional_qry, $employee_ids_qry);
        }
        $unprocessed_payroll    = abs($total_employees - $processed_payroll);  

        $data['total_employees']        = $total_employees;
        $data['processed_payroll']      = $processed_payroll;
        $data['unprocessed_payroll']    = $unprocessed_payroll;
        $data['processed_payroll_data'] = $processed_payroll_data;
        return $data;
    }

    public function getProcessedAndUnprocessedEmployeeCount($start_date, $end_date, $additional_qry) {

        $current_date = date("Y-m-d");

        $total_employees     = G_Employee_Helper::countEmployeeNotArchivedByDate($current_date, $additional_qry);
        $processed_payroll   = G_Employee_Helper::countProcessedEmployeePayrollByCutoff($start_date, $end_date, $additional_qry);
        $unprocessed_payroll = abs($total_employees - $processed_payroll);  

        $data['total_employees']     = $total_employees;
        $data['processed_payroll']   = $processed_payroll;
        $data['unprocessed_payroll'] = $unprocessed_payroll;
        return $data;
    }    


    public function getEmployeeSalary() {
        $data = array();

        if( $this->id > 0 ){
            $date = date("Y-m-d");
            $data = G_Employee_Basic_Salary_History_Helper::getEmployeeCurrentSalaryAndPayPeriod($this->id);
        }

        return $data;
    }

    public function getEmployeeContributionsByCutoffNumber( $salary = array(), G_Cutoff_Period $period ) {        
        $data = array();        
        $cutoff_number     = $period->getCutoffNumber();
        $cutoff_start_date = $period->getStartDate();
        $cutoff_end_date   = $period->getEndDate();

        if( $cutoff_number <= 0 ){
            $cutoff_number = 1;
        }

        if( $this->id > 0 && !empty($salary) ){
            $eSalary = self::getEmployeeSalary();
            $contri  = G_Employee_Contribution_Finder::findCurrentContribution($this);   

            if( !empty( $salary ) ){
                
                $salary_type  = $eSalary['type'];
                $to_deduct    = unserialize($contri->getToDeduct());                 

                $working_days = $this->year_working_days;
                
                $old_basic_pay = $salary['basic_pay'];

                if( $working_days <= 0 ){
                    $sv = new G_Sprint_Variables(G_Sprint_Variables::FIELD_DEFAULT_TOTAL_WRKNG_DAYS);
                    $working_days = $sv->getVariableValue();
                }

                if( $salary_type == G_Employee_Basic_Salary_History::SALARY_TYPE_DAILY ){
                    $daily_basic_pay = ($salary['basic_pay'] * $working_days) / 12;
                    $salary['basic_pay'] = $daily_basic_pay;
                } 
                
                $d = new G_Settings_Deduction_Breakdown();
                $deduction_breakdown = $d->getActiveContributionsBreakDown();        
               
                foreach( $deduction_breakdown as $key => $deduction ){
                    $breakdown     = explode(":", $deduction['breakdown']);                    
                    $percentage    = $breakdown[$cutoff_number - 1];
                    $salary_credit = $deduction['salary_credit'];
                    $is_taxable    = $deduction['is_taxable'];

                    if( $salary_credit == G_Settings_Deduction_Breakdown::OPTION_SALARY_CREDIT_GROSS_PAY ){
                        $base_amount = $salary['gross_pay']; 
                    }elseif( $salary_credit == G_Settings_Deduction_Breakdown::OPTION_SALARY_CREDIT_MONTHLY_PAY ){
                        $base_amount = $salary['monthly_pay']; 
                    }else{
                        $base_amount = $salary['basic_pay'];                        
                    }

                    $fc = new G_Employee_Fixed_Contribution();

                    switch ($key) {
                        case 'SSS':
                            $is_deductible = $to_deduct['sss'];

                            if( $salary_credit == G_Settings_Deduction_Breakdown::OPTION_SALARY_CREDIT_MONTHLY_PAY ){
                                $fields = array("gross_pay");
                                $p_data = G_Payslip_Helper::sqlGetPreviousEmployeePayslipDetailsByEmployeeId($this->id, $cutoff_start_date, $cutoff_end_date, $fields);                                

                                if( $p_data['gross_pay'] > 0 ){
                                    //$base_amount = $salary['gross_pay_nocetasea'] + $p_data['gross_pay'];     
                                    $base_amount += $p_data['gross_pay'];     

                                }/*else{
                                    $base_amount = $salary['gross_pay_nocetasea'];
                                }*/

                                //echo "Previous Gross : " . $p_data['gross_pay'] . " / Current Gross : {$base_amount}";
                            }

                            $sss = G_SSS_Finder::findBySalary($base_amount);          
                            if( $sss ){                               
                                if( $is_deductible == G_Settings_Deduction_Breakdown::YES ){                                    
                                    $sss_amount = ($sss->getEmployeeShare() + $sss->getProvidentEe())  * ($percentage / 100);
                                    $sss_er     = ($sss->getCompanyShare() + $sss->getProvidentEr()) * ($percentage / 100);                                     
                                }else{                                    
                                    $sss_amount = 0;
                                    $sss_er     = 0;
                                }
                            }else{                                
                                $sss_amount = 0;
                                $sss_er     = 0;
                            }

                            $sss_fixed = $fc->getEmployeeFixedSSSContri($this->id);
                            if( !empty($sss_fixed) && $sss_fixed['is_activated'] == 1 ){
                                $sss_amount = $sss_fixed['ee_amount'] * ($percentage / 100);
                                $sss_er     = $sss_fixed['er_amount'] * ($percentage / 100);
                            }

                            $sss_amount = number_format($sss_amount, 2);
                            $sss_er     = number_format($sss_er, 2);

                            $data[$key]['data'] = array('ee' => $sss_amount, 'er' => $sss_er);                                                         

                            break;
                        case 'HDMF':
                            $is_deductible = $to_deduct['pagibig'];                                                       
                            $pagibig       = G_Pagibig_Table_Finder::findBySalary($base_amount);      

                            if( $pagibig ){
                                if( $is_deductible == G_Settings_Deduction_Breakdown::YES ){                                    
                                    $pagibig_amount = $pagibig['employee_share'] * ($percentage / 100);
                                    $pagibig_er     = $pagibig['company_share'] * ($percentage / 100); 
                                }else{                                    
                                    $pagibig_amount = 0;
                                    $pagibig_er     = 0;
                                }
                            }else{
                                $pagibig_amount = 0;
                                $pagibig_er     = 0;
                            }

                            $pagibig_fixed = $fc->getEmployeeFixedPagibigContri($this->id);
                            if( !empty($pagibig_fixed) && $pagibig_fixed['is_activated'] == 1 ){

                                /*
                                if($pagibig_fixed['ee_amount'] > 100) {
                                    $pagibig_fixed_ee   = 100;
                                    $pagibig_fixed_ee_2 = $pagibig_fixed['ee_amount'] - 100;
                                } else {
                                    $pagibig_fixed_ee   = $pagibig_fixed['ee_amount'];    
                                }
                                */
                                //$pagibig_amount   = $pagibig_fixed_ee * ($percentage / 100);
                                //$pagibig_amount_2 = $pagibig_fixed_ee_2 * ($percentage / 100);

                                $pagibig_amount = $pagibig_fixed['ee_amount'] * ($percentage / 100);
                                $pagibig_er     = $pagibig_fixed['er_amount'] * ($percentage / 100);
                            }

                            $pagibig_amount = number_format($pagibig_amount, 2);
                            $pagibig_er     = number_format($pagibig_er, 2);
                            //$pagibig_fixed_ee_2 = number_format($pagibig_fixed_ee_2, 2);

                            //$data[$key]['data'] = array('ee' => $pagibig_amount, 'ee2' => $pagibig_fixed_ee_2, 'er' => $pagibig_er);   
                            $data[$key]['data'] = array('ee' => $pagibig_amount, 'er' => $pagibig_er);   

                            break;
                        case 'Phil Health':
                            $is_deductible = $to_deduct['philhealth'];

                            //Old Computation 2017 - start
                            /*
                            $ph            = G_Philhealth_Finder::findBySalary($base_amount);
                            if( $ph ){
                                if( $is_deductible == G_Settings_Deduction_Breakdown::YES ){
                                    $ph_amount  = $ph->getEmployeeShare() * ($percentage / 100);
                                    $ph_er      = $ph->getCompanyShare() * ($percentage / 100); 
                                }else{
                                    $ph_amount = 0;
                                    $ph_er     = 0;
                                }
                            }else{
                                $ph_amount = 0;
                                $ph_er     = 0;
                            }  
                            */
                            //Old Computation 2017 - end 

                            $ph            = G_Philhealth_Table_Finder::findBySalary2($base_amount, $cutoff_start_date);

                            if( $ph ){
                                if( $is_deductible == G_Settings_Deduction_Breakdown::YES ){
                                    $ph_amount  = $ph['employee_share'] * ($percentage / 100);
                                    $ph_er      = $ph['company_share'] * ($percentage / 100);
                                }else{
                                    $ph_amount = 0;
                                    $ph_er     = 0;
                                }
                            }else{
                                $ph_amount = 0;
                                $ph_er     = 0;
                            }                              

                            $philhealth_fixed = $fc->getEmployeeFixedPhilHealthContri($this->id);

                            if( !empty($philhealth_fixed) && $philhealth_fixed['is_activated'] == 1 ){
                                $ph_amount = $philhealth_fixed['ee_amount'] * ($percentage / 100);
                                $ph_er     = $philhealth_fixed['er_amount'] * ($percentage / 100);
                            }

                            //$ph_amount = number_format($ph_amount, 2);
                            //$ph_er     = number_format($ph_er, 2);

                            $data[$key]['data'] = array('ee' => $ph_amount, 'er' => $ph_er);   

                            break;
                        default:
                            break;
                    }

                    $data[$key]['is_taxable']    = $is_taxable; 
                    $data[$key]['salary_credit'] = $salary_credit;    

                }
            }
        }

        return $data;
    }

    public function getEmployeeContributionsByWeeklyCutoffNumber( $salary = array(), G_Weekly_Cutoff_Period $period ) {        
        $data = array();        
        $cutoff_number     = $period->getCutoffNumber();
        $cutoff_start_date = $period->getStartDate();
        $cutoff_end_date   = $period->getEndDate();

        if( $cutoff_number <= 0 ){
            $cutoff_number = 1;
        }

        if( $this->id > 0 && !empty($salary) ){
            $eSalary = self::getEmployeeSalary();
            $contri  = G_Employee_Contribution_Finder::findCurrentContribution($this);   

            if( !empty( $salary ) ){
                
                $salary_type  = $eSalary['type'];
                $to_deduct    = unserialize($contri->getToDeduct());                 

                $working_days = $this->year_working_days;
                
                $old_basic_pay = $salary['basic_pay'];

                if( $working_days <= 0 ){
                    $sv = new G_Sprint_Variables(G_Sprint_Variables::FIELD_DEFAULT_TOTAL_WRKNG_DAYS);
                    $working_days = $sv->getVariableValue();
                }

                if( $salary_type == G_Employee_Basic_Salary_History::SALARY_TYPE_DAILY ){
                    $daily_basic_pay = ($salary['basic_pay'] * $working_days) / 12;
                    $salary['basic_pay'] = $daily_basic_pay;
                } 
                
                $d = new G_Settings_Weekly_Deduction_Breakdown();
                $deduction_breakdown = $d->getActiveContributionsBreakDown();        
                
                foreach( $deduction_breakdown as $key => $deduction ){
                    $breakdown     = explode(":", $deduction['breakdown']);                    
                    $percentage    = $breakdown[$cutoff_number - 1];

                    $salary_credit = $deduction['salary_credit'];
                    $is_taxable    = $deduction['is_taxable'];

                    if( $salary_credit == G_Settings_Weekly_Deduction_Breakdown::OPTION_SALARY_CREDIT_GROSS_PAY ){
                        $base_amount = $salary['gross_pay']; 
                    }elseif( $salary_credit == G_Settings_Weekly_Deduction_Breakdown::OPTION_SALARY_CREDIT_MONTHLY_PAY ){
                        $base_amount = $salary['monthly_pay']; 
                    }else{
                        $base_amount = $salary['basic_pay'];                        
                    }

                    $fc = new G_Employee_Fixed_Contribution();

                    switch ($key) {
                        case 'SSS':
                            $is_deductible = $to_deduct['sss'];

                            if( $salary_credit == G_Settings_Weekly_Deduction_Breakdown::OPTION_SALARY_CREDIT_MONTHLY_PAY ){
                                $fields = array("gross_pay");
                                $p_data = G_Weekly_Payslip_Helper::sqlGetPreviousEmployeePayslipDetailsByEmployeeId($this->id, $cutoff_start_date, $cutoff_end_date, $fields);                                

                                if( $p_data['gross_pay'] > 0 ){
                                    //$base_amount = $salary['gross_pay_nocetasea'] + $p_data['gross_pay'];     
                                    $base_amount += $p_data['gross_pay'];     

                                }/*else{
                                    $base_amount = $salary['gross_pay_nocetasea'];
                                }*/

                                //echo "Previous Gross : " . $p_data['gross_pay'] . " / Current Gross : {$base_amount}";
                            }
                            // var_dump($base_amount);exit();

                            $sss = G_SSS_Finder::findBySalary($base_amount);          
                            if( $sss ){                               
                                if( $is_deductible == G_Settings_Weekly_Deduction_Breakdown::YES ){                                    
                                    $sss_amount = ($sss->getEmployeeShare() + $sss->getProvidentEe())  * ($percentage / 100);
                                    $sss_er     = ($sss->getCompanyShare() + $sss->getProvidentEr()) * ($percentage / 100);                                     
                                }else{                                    
                                    $sss_amount = 0;
                                    $sss_er     = 0;
                                }
                            }else{                                
                                $sss_amount = 0;
                                $sss_er     = 0;
                            }

                            $sss_fixed = $fc->getEmployeeFixedSSSContri($this->id);
                            if( !empty($sss_fixed) && $sss_fixed['is_activated'] == 1 ){
                                $sss_amount = $sss_fixed['ee_amount'] * ($percentage / 100);
                                $sss_er     = $sss_fixed['er_amount'] * ($percentage / 100);
                            }

                            $sss_amount = number_format($sss_amount, 2);
                            $sss_er     = number_format($sss_er, 2);

                            $data[$key]['data'] = array('ee' => $sss_amount, 'er' => $sss_er);                                                         

                            break;
                        case 'HDMF':
                            $is_deductible = $to_deduct['pagibig'];                                                       
                            $pagibig       = G_Pagibig_Table_Finder::findBySalary($base_amount);      

                            if( $pagibig ){
                                if( $is_deductible == G_Settings_Weekly_Deduction_Breakdown::YES ){                                    
                                    $pagibig_amount = $pagibig['employee_share'] * ($percentage / 100);
                                    $pagibig_er     = $pagibig['company_share'] * ($percentage / 100); 
                                }else{                                    
                                    $pagibig_amount = 0;
                                    $pagibig_er     = 0;
                                }
                            }else{
                                $pagibig_amount = 0;
                                $pagibig_er     = 0;
                            }

                            $pagibig_fixed = $fc->getEmployeeFixedPagibigContri($this->id);

                            if( !empty($pagibig_fixed) && $pagibig_fixed['is_activated'] == 1 ){

                                /*
                                if($pagibig_fixed['ee_amount'] > 100) {
                                    $pagibig_fixed_ee   = 100;
                                    $pagibig_fixed_ee_2 = $pagibig_fixed['ee_amount'] - 100;
                                } else {
                                    $pagibig_fixed_ee   = $pagibig_fixed['ee_amount'];    
                                }
                                */
                                //$pagibig_amount   = $pagibig_fixed_ee * ($percentage / 100);
                                //$pagibig_amount_2 = $pagibig_fixed_ee_2 * ($percentage / 100);

                                $pagibig_amount = $pagibig_fixed['ee_amount'] * ($percentage / 100);
                                $pagibig_er     = $pagibig_fixed['er_amount'] * ($percentage / 100);
                            }

                            $pagibig_amount = number_format($pagibig_amount, 2);
                            $pagibig_er     = number_format($pagibig_er, 2);
                            //$pagibig_fixed_ee_2 = number_format($pagibig_fixed_ee_2, 2);

                            //$data[$key]['data'] = array('ee' => $pagibig_amount, 'ee2' => $pagibig_fixed_ee_2, 'er' => $pagibig_er);   
                            $data[$key]['data'] = array('ee' => $pagibig_amount, 'er' => $pagibig_er);   

                            break;
                        case 'Phil Health':
                            $is_deductible = $to_deduct['philhealth'];

                            //Old Computation 2017 - start
                            /*
                            $ph            = G_Philhealth_Finder::findBySalary($base_amount);
                            if( $ph ){
                                if( $is_deductible == G_Settings_Weekly_Deduction_Breakdown::YES ){
                                    $ph_amount  = $ph->getEmployeeShare() * ($percentage / 100);
                                    $ph_er      = $ph->getCompanyShare() * ($percentage / 100); 
                                }else{
                                    $ph_amount = 0;
                                    $ph_er     = 0;
                                }
                            }else{
                                $ph_amount = 0;
                                $ph_er     = 0;
                            }  
                            */
                            //Old Computation 2017 - end 

                            $ph            = G_Philhealth_Table_Finder::findBySalary2($base_amount, $cutoff_start_date);
                            if( $ph ){
                                if( $is_deductible == G_Settings_Weekly_Deduction_Breakdown::YES ){
                                    $ph_amount  = $ph['employee_share'] * ($percentage / 100);
                                    $ph_er      = $ph['company_share'] * ($percentage / 100);
                                }else{
                                    $ph_amount = 0;
                                    $ph_er     = 0;
                                }
                            }else{
                                $ph_amount = 0;
                                $ph_er     = 0;
                            }                              

                            $philhealth_fixed = $fc->getEmployeeFixedPhilHealthContri($this->id);
                            
                            if( !empty($philhealth_fixed) && $philhealth_fixed['is_activated'] == 1 ){
                                $ph_amount = $philhealth_fixed['ee_amount'] * ($percentage / 100);
                                $ph_er     = $philhealth_fixed['er_amount'] * ($percentage / 100);
                            }

                            $ph_amount = number_format($ph_amount, 2);
                            $ph_er     = number_format($ph_er, 2);
                           
                            $data[$key]['data'] = array('ee' => $ph_amount, 'er' => $ph_er);   

                            break;
                        default:
                            break;
                    }

                    $data[$key]['is_taxable']    = $is_taxable; 
                    $data[$key]['salary_credit'] = $salary_credit;    

                }
            }
        }

        return $data;
    }



    //alex monthly
      public function getEmployeeContributionsByMonthlyCutoffNumber( $salary = array(), G_Monthly_Cutoff_Period $period ) {        
        $data = array();        
        $cutoff_number     = $period->getCutoffNumber();
        $cutoff_start_date = $period->getStartDate();
        $cutoff_end_date   = $period->getEndDate();

        if( $cutoff_number <= 0 ){
            $cutoff_number = 1;
        }

        if( $this->id > 0 && !empty($salary) ){
            $eSalary = self::getEmployeeSalary();
            $contri  = G_Employee_Contribution_Finder::findCurrentContribution($this);   

            if( !empty( $salary ) ){
                
                $salary_type  = $eSalary['type'];
                $to_deduct    = unserialize($contri->getToDeduct());                 

                $working_days = $this->year_working_days;
                
                $old_basic_pay = $salary['basic_pay'];

                if( $working_days <= 0 ){
                    $sv = new G_Sprint_Variables(G_Sprint_Variables::FIELD_DEFAULT_TOTAL_WRKNG_DAYS);
                    $working_days = $sv->getVariableValue();
                }

                if( $salary_type == G_Employee_Basic_Salary_History::SALARY_TYPE_DAILY ){
                    $daily_basic_pay = ($salary['basic_pay'] * $working_days) / 12;
                    $salary['basic_pay'] = $daily_basic_pay;
                } 
                
                $d = new G_Settings_Monthly_Deduction_Breakdown();
                $deduction_breakdown = $d->getActiveContributionsBreakDown();  
   
               
                foreach( $deduction_breakdown as $key => $deduction ){
                    $breakdown     = explode(":", $deduction['breakdown']);                    
                    $percentage    = $breakdown[$cutoff_number - 1];
                    $salary_credit = $deduction['salary_credit'];
                    $is_taxable    = $deduction['is_taxable'];

                    if( $salary_credit == G_Settings_Monthly_Deduction_Breakdown::OPTION_SALARY_CREDIT_GROSS_PAY ){
                        $base_amount = $salary['gross_pay']; 
                    }elseif( $salary_credit == G_Settings_Monthly_Deduction_Breakdown::OPTION_SALARY_CREDIT_MONTHLY_PAY ){
                        $base_amount = $salary['monthly_pay']; 
                    }else{
                        $base_amount = $salary['basic_pay'];                        
                    }

                    $fc = new G_Employee_Fixed_Contribution();

                    switch ($key) {
                        case 'SSS':
                            $is_deductible = $to_deduct['sss'];

                            if( $salary_credit == G_Settings_Monthly_Deduction_Breakdown::OPTION_SALARY_CREDIT_MONTHLY_PAY ){
                                $fields = array("gross_pay");
                                $p_data = G_Monthly_Payslip_Helper::sqlGetPreviousEmployeePayslipDetailsByEmployeeId($this->id, $cutoff_start_date, $cutoff_end_date, $fields);                                

                                if( $p_data['gross_pay'] > 0 ){
                                    //$base_amount = $salary['gross_pay_nocetasea'] + $p_data['gross_pay'];     
                                    $base_amount += $p_data['gross_pay'];     

                                }/*else{
                                    $base_amount = $salary['gross_pay_nocetasea'];
                                }*/

                                //echo "Previous Gross : " . $p_data['gross_pay'] . " / Current Gross : {$base_amount}";
                            }
                            $sss = G_SSS_Finder::findBySalary($base_amount);  
                            //utilities::displayArray($sss);exit();        
                            if( $sss ){                               
                                if( $is_deductible == G_Settings_Monthly_Deduction_Breakdown::YES ){                                    
                                    //$sss_amount = $sss->getEmployeeShare() * ($percentage / 100);
                                    //$sss_er     = $sss->getCompanyShare() * ($percentage / 100);  
                                    $sss_amount = ($sss->getEmployeeShare() + $sss->getProvidentEe())  * ($percentage / 100);
                                    $sss_er     = ($sss->getCompanyShare() + $sss->getProvidentEr()) * ($percentage / 100);                                   
                                }else{                                    
                                    $sss_amount = 0;
                                    $sss_er     = 0;
                                }
                            }else{                                
                                $sss_amount = 0;
                                $sss_er     = 0;
                            }

                            $sss_fixed = $fc->getEmployeeFixedSSSContri($this->id);
                            if( !empty($sss_fixed) && $sss_fixed['is_activated'] == 1 ){
                                $sss_amount = $sss_fixed['ee_amount'] * ($percentage / 100);
                                $sss_er     = $sss_fixed['er_amount'] * ($percentage / 100);
                            }

                           // $sss_amount = number_format($sss_amount, 2);
                            //$sss_er     = number_format($sss_er, 2);

                            $data[$key]['data'] = array('ee' => $sss_amount, 'er' => $sss_er);                                                         

                            break;
                        case 'HDMF':
                            $is_deductible = $to_deduct['pagibig'];                                                       
                            $pagibig       = G_Pagibig_Table_Finder::findBySalary($base_amount);      

                            if( $pagibig ){
                                if( $is_deductible == G_Settings_Monthly_Deduction_Breakdown::YES ){                                    
                                    $pagibig_amount = $pagibig['employee_share'] * ($percentage / 100);
                                    $pagibig_er     = $pagibig['company_share'] * ($percentage / 100); 
                                }else{                                    
                                    $pagibig_amount = 0;
                                    $pagibig_er     = 0;
                                }
                            }else{
                                $pagibig_amount = 0;
                                $pagibig_er     = 0;
                            }

                            $pagibig_fixed = $fc->getEmployeeFixedPagibigContri($this->id);
                            if( !empty($pagibig_fixed) && $pagibig_fixed['is_activated'] == 1 ){

                                /*
                                if($pagibig_fixed['ee_amount'] > 100) {
                                    $pagibig_fixed_ee   = 100;
                                    $pagibig_fixed_ee_2 = $pagibig_fixed['ee_amount'] - 100;
                                } else {
                                    $pagibig_fixed_ee   = $pagibig_fixed['ee_amount'];    
                                }
                                */
                                //$pagibig_amount   = $pagibig_fixed_ee * ($percentage / 100);
                                //$pagibig_amount_2 = $pagibig_fixed_ee_2 * ($percentage / 100);

                                $pagibig_amount = $pagibig_fixed['ee_amount'] * ($percentage / 100);
                                $pagibig_er     = $pagibig_fixed['er_amount'] * ($percentage / 100);
                            }

                            //$pagibig_amount = number_format($pagibig_amount, 2);
                            //$pagibig_er     = number_format($pagibig_er, 2);
                            //$pagibig_fixed_ee_2 = number_format($pagibig_fixed_ee_2, 2);

                            //$data[$key]['data'] = array('ee' => $pagibig_amount, 'ee2' => $pagibig_fixed_ee_2, 'er' => $pagibig_er);   
                            $data[$key]['data'] = array('ee' => $pagibig_amount, 'er' => $pagibig_er);   

                            break;
                        case 'Phil Health':
                            $is_deductible = $to_deduct['philhealth'];

                            //Old Computation 2017 - start
                            /*
                            $ph            = G_Philhealth_Finder::findBySalary($base_amount);
                            if( $ph ){
                                if( $is_deductible == G_Settings_Deduction_Breakdown::YES ){
                                    $ph_amount  = $ph->getEmployeeShare() * ($percentage / 100);
                                    $ph_er      = $ph->getCompanyShare() * ($percentage / 100); 
                                }else{
                                    $ph_amount = 0;
                                    $ph_er     = 0;
                                }
                            }else{
                                $ph_amount = 0;
                                $ph_er     = 0;
                            }  
                            */
                            //Old Computation 2017 - end 

                           // $ph            = G_Philhealth_Table_Finder::findBySalary($base_amount);
                              $ph            = G_Philhealth_Table_Finder::findBySalary2($base_amount, $cutoff_start_date);
                            if( $ph ){
                                if( $is_deductible == G_Settings_Monthly_Deduction_Breakdown::YES ){
                                    $ph_amount  = $ph['employee_share'] * ($percentage / 100);
                                    $ph_er      = $ph['company_share'] * ($percentage / 100);
                                }else{
                                    $ph_amount = 0;
                                    $ph_er     = 0;
                                }
                            }else{
                                $ph_amount = 0;
                                $ph_er     = 0;
                            }                              

                            $philhealth_fixed = $fc->getEmployeeFixedPhilHealthContri($this->id);
                            if( !empty($philhealth_fixed) && $philhealth_fixed['is_activated'] == 1 ){
                                $ph_amount = $philhealth_fixed['ee_amount'] * ($percentage / 100);
                                $ph_er     = $philhealth_fixed['er_amount'] * ($percentage / 100);
                            }

                           // $ph_amount = number_format($ph_amount, 2);
                            //$ph_er     = number_format($ph_er, 2);

                            $data[$key]['data'] = array('ee' => $ph_amount, 'er' => $ph_er);   

                            break;
                        default:
                            break;
                    }

                    $data[$key]['is_taxable']    = $is_taxable; 
                    $data[$key]['salary_credit'] = $salary_credit;    

                }
            }
        }

        return $data;
    }


    /*
        Usage :
        $data = array(
            2 //Employee id => array(
                "Employee Category" => "Agency", //Label => Value
                "another category" => "Sample Category 01"
            ),
            3 //Employee id => array(
                "employee category" => "Direct", //Label => Value
                "Another Category" => "Sample Category 02"
            )
        );
        $eid = 2;
        $e   = new G_Employee();
        $return = $e->createDynamicField($data); //Returns array
:    */
    public function createDynamicFieldDepre( $data = array() ) {
        $return['is_success'] = false;
        $return['message']    = 'Cannot save record';
        if( !empty( $data ) ){
            foreach( $data as $key => $value ){
                foreach( $value as $subKey => $subValue ){                                    
                    $obj[$key] = array($subKey => $subValue);                          
                    $ed = new G_Employee_Dynamic_Field();
                    $return = $ed->setObjectFields($obj)->sanitizeObjectValue()->createDynamicField();
                }                                
            }
        }

        return $return;
    }

    public function createDynamicField( $data = array() ) {
        $return['is_success'] = false;
        $return['message']    = 'Cannot save record';
        if( !empty( $data ) ){                  
            $ed = new G_Employee_Dynamic_Field();      
            foreach( $data as $key => $value ){ 
                $ed->setEmployeeId($key);
                $ed->deleteAllByEmployeeId();
                foreach( $value as $subValue ){                                                  
                    $field_label = $subValue['other_details_label'];
                    $field_value = $subValue['other_details_value'];                    
                    if( $field_label != "" && $field_value != "" ){
                        $obj[$key] = array($field_label => $field_value);                          
                        $ed = new G_Employee_Dynamic_Field();
                        //$return = $ed->setObjectFields($obj)->sanitizeObjectValue()->deleteAllByEmployeeId()->createDynamicField();
                        $return = $ed->setObjectFields($obj)->sanitizeObjectValue()->createDynamicField();
                    }         
                }       
            }
        }

        return $return;
    }

    public function getDynamicFields() {    
        $data = array();

        if( $this->id > 0 ){
            $fields = array("id","title","value");
            $data = G_Employee_Dynamic_Field_Helper::sqlDynamicFieldsByEmployeeId($this->id, $fields);
        }
        return $data;
    }


    /*
        Usage :
        $education[] = "STI / BSIT / 2010-05-01 to 2012-03-03 / 5.0";
        $education[] = "STI / COE / 2012-05-01 to 2015-03-03 / 4.0";
        $e = G_Employee_Finder::findById(2);
        if( $e ){
            $e->createBulkEducationArray($education)->bulkAddEducation();
        }

        Returns array : 
        Array
        (
            [0] => Array
                (
                    [employee_id] => 2
                    [institution] => STI
                    [school] => BSIT
                    [gpa_score] => 5.0
                    [start_date] => 2010-05-01
                    [end_date] => 2012-03-03
                )

            [1] => Array
                (
                    [employee_id] => 2
                    [institution] => STI
                    [school] => COE
                    [gpa_score] => 4.0
                    [start_date] => 2012-05-01
                    [end_date] => 2015-03-03
                )

        )
    */

    public function createBulkEducationArray( $a_education = array() ) {
        $a_bulk_education = array();
        if( $this->id > 0 && !empty($a_education) ){           
            foreach( $a_education as $key => $education){
                $a_education_sub       = explode("|", $education);
                $a_education_graduated = explode("to", $a_education_sub[2]);

                $a_bulk_education[$key]['employee_id'] = $this->id;
                $a_bulk_education[$key]['institution'] = trim($a_education_sub[0]);
                $a_bulk_education[$key]['school']      = trim($a_education_sub[1]);
                $a_bulk_education[$key]['gpa_score']   = trim($a_education_sub[3]);
                $a_bulk_education[$key]['start_date']  = date("Y-m-d",strtotime(trim($a_education_graduated[0])));
                $a_bulk_education[$key]['end_date']    = date("Y-m-d",strtotime(trim($a_education_graduated[1])));

            } 
        }

        if( !empty($a_bulk_education) ){
            $this->bulk_education = $a_bulk_education;    
            $this->bulk_education_is_valid = true;
        }

        return $this;
    }

    /*
        Usage :
        $return = array();
        $emergency_contact[] = "Sisa | Mother | Blk1 lot 11, Golden City | Landline : 0234323, Mobile : 3333";
        $emergency_contact[] = "Basilio | Father | Blk1 lot 11, Golden City | Landline : 231121, Mobile : 56435, Work Telephone : 3342";
        $e = G_Employee_Finder::findById(57);
        if( $e ){           
            $return = $e->createBulkEmergencyContactArray($emergency_contact);          
        }

        Returns  :
        Array
        (
            [0] => Array
                (
                    [employee_id] => 57
                    [person] => Sisa
                    [relationship] => Mother
                    [address] => Blk1 lot 11, Golden City
                    [home_telephone] => 0234323
                    [mobile] => 3333
                    [work_telephone] => 
                )

            [1] => Array
                (
                    [employee_id] => 57
                    [person] => Basilio
                    [relationship] => Father
                    [address] => Blk1 lot 11, Golden City
                    [home_telephone] => 231121
                    [mobile] => 56435
                    [work_telephone] => 3342
                )

        )
    */

    public function createBulkEmergencyContactArray( $a_emergency_contact = array() ) {
        $a_bulk_emergency_contact = array();
        if( $this->id > 0 && !empty($a_emergency_contact) ){                               
            foreach( $a_emergency_contact as $key => $ec){
                $a_emergency_contact_sub = explode("|", $ec);
                $a_contact_numbers       = explode(",", $a_emergency_contact_sub[3]);               
                $landline = '';
                $mobile   = '';
                $work_telephone = '';

                foreach( $a_contact_numbers as $cn ){
                    $a_contact  = explode(":", $cn);
                    $index_name = trim(strtolower($a_contact[0]));
                    switch ($index_name) {
                        case 'landline':
                            $landline = trim($a_contact[1]);            
                            break;
                        case 'mobile':
                            $mobile = trim($a_contact[1]);
                            break;
                        case 'work telephone':
                            $work_telephone = trim($a_contact[1]);
                            break;
                        default:                          
                            break;
                    }
                }

                $a_bulk_emergency_contact[$key]['employee_id']  = $this->id;
                $a_bulk_emergency_contact[$key]['person']       = trim($a_emergency_contact_sub[0]);
                $a_bulk_emergency_contact[$key]['relationship'] = trim($a_emergency_contact_sub[1]);
                $a_bulk_emergency_contact[$key]['address']      = trim($a_emergency_contact_sub[2]);
                $a_bulk_emergency_contact[$key]['home_telephone'] = $landline;         
                $a_bulk_emergency_contact[$key]['mobile']         = $mobile;
                $a_bulk_emergency_contact[$key]['work_telephone'] = $work_telephone;   

            } 
        }

        if( !empty($a_bulk_emergency_contact) ){
            $this->bulk_emergency_contact = $a_bulk_emergency_contact;    
            $this->bulk_emergency_contact_is_valid = true;
        }
        
        return $this;
    }


    /*
        Note:
        Array structure :
        Array
        (
            [0] => Array
                (
                    [employee_id] => 2
                    [institution] => STI
                    [school] => BSIT
                    [gpa_score] => 5.0
                    [start_date] => 2010-05-01
                    [end_date] => 2012-03-03
                )

            [1] => Array
                (
                    [employee_id] => 2
                    [institution] => STI
                    [school] => COE
                    [gpa_score] => 4.0
                    [start_date] => 2012-05-01
                    [end_date] => 2015-03-03
                )

        )
    */
    public function  bulkAddEducation(){
        $return['is_success'] = false;
        $return['message']    = 'Cannot save record(s)';
        if( $this->id > 0 && $this->bulk_education_is_valid ){
            $a_insert_data = array();
            foreach( $this->bulk_education as $education ){ 
                $a_data   = array();  
                $a_data[] = Model::safeSql($education['employee_id']);   
                $a_data[] = Model::safeSql($education['institution']);             
                $a_data[] = Model::safeSql($education['school']);
                $a_data[] = Model::safeSql($education['start_date']);
                $a_data[] = Model::safeSql($education['end_date']);
                $a_data[] = "'" . $education['gpa_score'] . "'";
                $a_insert_data[] = "(" . implode(",", $a_data) . ")";
            }

            if( !empty($a_insert_data) ){
                $educ = new G_Employee_Education();
                $is_success = $educ->bulkInsert($a_insert_data);
                if( $is_success ){
                    $return['is_success'] = false;
                    $return['message']    = 'Record(s) was successfully saved';
                }
            }
        }

       return $return;
    }

    public function bulkAddEmergencyContact(){
        $return['is_success'] = false;
        $return['message']    = 'Cannot save record(s)';
        if( $this->id > 0 && $this->bulk_emergency_contact_is_valid ){            
            $a_insert_data = array();
            foreach( $this->bulk_emergency_contact as $ec ){ 
                $a_data   = array();  
                $a_data[] = Model::safeSql($ec['employee_id']);   
                $a_data[] = Model::safeSql($ec['person']);             
                $a_data[] = Model::safeSql($ec['relationship']);
                $a_data[] = Model::safeSql($ec['address']);
                $a_data[] = Model::safeSql($ec['home_telephone']);
                $a_data[] = Model::safeSql($ec['mobile']);
                $a_data[] = Model::safeSql($ec['work_telephone']);              
                $a_insert_data[] = "(" . implode(",", $a_data) . ")";
            }
                        
            if( !empty($a_insert_data) ){
                $eec = new G_Employee_Emergency_Contact();                
                $is_success = $eec->bulkInsert($a_insert_data);
                if( $is_success ){
                    $return['is_success'] = false;
                    $return['message']    = 'Record(s) was successfully saved';
                }
            }
        }

       return $return;
    }

    public function updateSection( $section_id = 0 ) {
        $return['is_success'] = false;
        $return['message']    = "No record(s) to update";
        if( ($this->section_id == '' || $this->section_id <= 0) && $section_id > 0 ){
            $this->section_id = $section_id;            
        }

        if( $this->section_id  > 0 && $this->id > 0 ){
            $total_records_updated = G_Employee_Manager::updateEmployeeSection($this);
            $return['is_success'] = false;
            $return['message']    = "<b>{$total_records_updated}</b> record(s) was updated";
        }

        return $return;
    }
    
    /** 
     * Retrieves all employee earnings by cutoffperiod
     * @param array $cutoff_data - cutoff_id, from and to date
     * @return Array of employee earnings
     */

    public function getEmployeeEarningsByCutoffPeriod( G_Cutoff_Period $cutoff_period ){
        $data = array();       
        if( $this->id > 0  && !empty($cutoff_period) && isset($cutoff_period['id']) ){
            $cutoff_id = $cutoff_period['id'];
            $fields = array("title","earning_type","percentage","percentage_multiplier","amount","is_taxable");

            //By Employee Id            
            $data['applied_by_employee_id']          = G_Employee_Earnings_Helper::sqlAllApprovedAndIsNotArchivedEearningsAppliedByEmployeeIdAndByCutoffPeriodId($this->id, $cutoff_id, $fields); 
            //By Department / Section
            $data['applied_by_dept_section_id']      = G_Employee_Earnings_Helper::sqlAllApprovedAndIsNotArchivedEearningsAppliedByDepartmentSectionIdAndByCutoffPeriodId($this->department_company_structure_id, $cutoff_id, $fields); 
            //By Employment Status
            $data['applied_by_employment_status_id'] = G_Employee_Earnings_Helper::sqlAllApprovedAndIsNotArchivedEearningsAppliedByEmploymentStatusIdIdAndByCutoffPeriodId($this->employment_status_id, $cutoff_id, $fields); 
            //Applied to all employees
            $data['applied_to_all_employees'] = G_Employee_Earnings_Helper::sqlAllApprovedAndIsNotArchivedEearningsAppliedToAllEmployeesAndByCutoffPeriodId($cutoff_id, $fields); 
           
            //Computation variables
            $working_days = $this->year_working_days;
            if( $working_days <= 0 ){
                $sv = new G_Sprint_Variables(G_Sprint_Variables::FIELD_DEFAULT_TOTAL_WRKNG_DAYS);
                $working_days = $sv->getVariableValue();
            }

            $end_date = $cutoff_period['to'];
            $s = G_Employee_Basic_Salary_History_Finder::findByEmployeeAndDate($this, $end_date);
            if( $s ){
                $salary_amount = $s->getBasicSalary();
                $salary_type   = $s->getType();
            }else{
                $salary_amount = 0;
                $salary_type   = '';
            }

            switch ($salary_type) {
                case G_Employee_Basic_Salary_History::SALARY_TYPE_MONTHLY:   
                    $monthly_rate = $salary_amount;
                    $daily_rate   = ($salary_amount * 12) / $working_days;
                    break;
                case G_Employee_Basic_Salary_History::SALARY_TYPE_DAILY:
                    $monthly_rate = ($salary_amount * $working_days) / 12;
                    $daily_rate   = ($monthly_rate * 12) / $working_days;
                    break;
                default:
                    $monthly_rate = 0;
                    $daily_rate   = 0;
                    break;
            }
            
            foreach ($data as $key => $value) {
                foreach( $value as $subKey => $subValue ){
                    $title        = $subValue['title'];
                    $is_taxable   = $subValue['is_taxable'] == G_Employee_Earnings::IS_TAXABLE_YES ? Earning::TAXABLE : Earning::NON_TAXABLE;
                    $earning_type = $subValue['earning_type'];
                    $percentage   = $subValue['percentage'];
                    $amount       = $subValue['amount'];
                    $percentage_multiplier = $subValue['percentage_multiplier'];                   

                    switch ($earning_type) {
                        case G_Employee_Earnings::EARNING_TYPE_AMOUNT:
                            $new_amount = $amount;
                            break;
                        case G_Employee_Earnings::EARNING_TYPE_PERCENTAGE:
                            if( $percentage_multiplier == G_Employee_Earnings::PERCENTAGE_SELECTION_MONTHLY ){
                                $new_amount = $monthly_rate * ($percentage / 100);
                            }elseif( $percentage_multiplier == G_Employee_Earnings::PERCENTAGE_SELECTION_DAILY ){
                                $new_amount = $daily_rate * ($percentage / 100);
                            }else{
                                $new_amount = 0.00;
                            }
                            break;
                        default:                            
                            break;
                    }
                    //Creates earnings array
                    $earn = new Earning($title, $new_amount, $is_taxable);
                    $earnings[] = $earn;
                }                
            }
        }

        return $earnings;
    }

    /** 
     * @param array date_from, date_to, gross
     * @return array
    */
    public function getEmployeeScheduledUnpaidLoans( $loan_data = array() ){
        $is_debug = false;
        $data = array();
        if( $this->id > 0 && !empty($loan_data) ){
            
            $date_from = $loan_data['date_from'];
            $date_to   = $loan_data['date_to'];
            $gross     = $loan_data['gross'];

            $l = new G_Employee_Loan();
            $l->setEmployeeId($this->id);
            $data = $l->setGrossPay($gross)->applyLoansGrossPayLimit()->getScheduledUnpaidLoans( array('date_from' => $date_from, 'date_to' => $date_to) )->adjustEmployeeLoansDeductionBaseOnGrossPay()->getEmployeesLoansDeducted();

            if( $is_debug ){
                Utilities::displayArray($data);
            }
            
        }
        return $data;
    }

    /** 
     * @param int
     * @return boolean
    */
    public function updateDependents( $number_of_dependents = 0 ) {
        $is_updated = false;        
        if( $this->id > 0 && $number_of_dependents > 0 ){
            $d = new G_Employee_Dependent();
            $d->setEmployeeId($this->id);
            $d->setDefaultRelationship();
            $is_updated = $d->deleteAllEmployeeDependents()->defaultDependents($number_of_dependents);
        }

        return $is_updated;
    }

    public function getEmployeeIncentiveReport($query, $add_query) {
       return G_Employee_Helper::getEmployeeIncentiveReport($query, $add_query);
    }

    public function getEmployeeBasicDetails($query, $add_query) {
       return G_Employee_Helper::getEmployeeBasicDetails($query, $add_query);
    }    

    /** 
     * Employees 13th Month summary by year
     * @param array query
     * @param string add_query
     * @return array
    */
    public function getEmployeesYearlyBonusByYear( $query = array(), $add_query = '' ){
        $data   = array();       
        $return = array();

        $fields = array('month_start', 'month_end', 'cutoff_start_date', 'cutoff_end_date');
        $yearly_bonus = G_Yearly_Bonus_Release_Date_Helper::sqlGetDistinctDataByYear($query['year'],$fields);

        $total_absent_amount = 0;
        $total_basic_pay     = 0;
        $deductions_array = array();

        foreach( $yearly_bonus as $yb ){
            $a_index = date("F d, Y",strtotime($yb['cutoff_start_date'])) . ' to ' . date("F d, Y",strtotime($yb['cutoff_end_date']));
            $date_start = $query['year'] . '-' . $yb['month_start'] . '-01';
            $date_start = date("Y-m-d",strtotime($date_start));
            $date_end   = $query['year'] . '-' . $yb['month_end'] . '-01';
            $date_end   = date("Y-m-t",strtotime($date_end));

            $fields = array('id','employee_id','deductions','basic_pay','other_deductions');
            //Migraged Data       
            $condition = array('start_date >= ' . Model::safeSql($date_start) . " AND end_date <= " . Model::safeSql($date_end));
            $mg = new G_Migrate_Data();
            $migrated = $mg->getAllMigratedData(array(),$condition);

            $migrated_data = array();
            foreach( $migrated as $md ){
                 $migrated_data[$md['employee_id']][$md['field']] = $md['amount'];
            }

            $payslip = G_Payslip_Finder::findAllByPeriod($date_start, $date_end, $fields);             
            foreach( $payslip as $p ){
                $ph = new G_Payslip_Helper($p);

                $deductions_array[$a_index][$p->getEmployeeId()]['total_absent_amount'] += $ph->getValue('absent_amount');
                $deductions_array[$a_index][$p->getEmployeeId()]['total_basic_pay'] += $p->getBasicPay();
            }    

            foreach( $migrated_data as $key => $md ){
                $deductions_array[$a_index][$key]['total_basic_pay'] += $md['basic_pay'];
            }      
        }
        
        $data = G_Yearly_Bonus_Release_Date_Helper::getDataByYear($query, $add_query);   

       //utilities::displayArray($data);exit();

        $end_date = "{$query['year']}-12-31";     
        foreach( $data as $key => $d ){

            foreach( $d as $subKey => $subValue ){
                $return[$key][$subKey] = $subValue;
                //if( isset($deductions_array[$key][$subKey]) ){  

                    $e = G_Employee_Finder::findById($return[$key][$subKey]['employee_pkid']);
                    $s = G_Employee_Basic_Salary_History_Finder::findByEmployeeAndDate($e, $end_date);

                    if($e){
                        $frequency_id = $e->getFrequencyId();
                    }

                    if($frequency_id == 2){

                         $e_payslip = G_Weekly_Payslip_Finder::findByEmployeeAndPeriod($e, $subValue['cutoff_start_date'], $subValue['cutoff_end_date']);
                    }
                    else{
                          $e_payslip = G_Payslip_Finder::findByEmployeeAndPeriod($e, $subValue['cutoff_start_date'], $subValue['cutoff_end_date']);
                    }
                  
                    if($e_payslip) {
                        foreach($e_payslip->getOtherDeductions() as $dkey => $odeduction) {
                            if($odeduction->getVariable() == 'tax_bonus_service_award') {
                                $return[$key][$subKey]['tax_bonus_service_award'] = $odeduction->getAmount();
                            }
                        }                        
                    }

                    if(!$s) {
                        $skip = true;
                    }else{
                        $salary_amount = $s->getBasicSalary();
                        $salary_type = $s->getType();
                    }

                    $working_days = $e->getYearWorkingDays();
                    if( $working_days <= 0 ){
                        $sv = new G_Sprint_Variables(G_Sprint_Variables::FIELD_DEFAULT_TOTAL_WRKNG_DAYS);
                        $working_days = $sv->getVariableValue();
                    }

                    switch ($salary_type):
                        case G_Employee_Basic_Salary_History::SALARY_TYPE_MONTHLY:                      
                            $monthly_rate = $salary_amount;
                            break;

                        case G_Employee_Basic_Salary_History::SALARY_TYPE_DAILY:      
                            $monthly_rate    = ($salary_amount * $working_days) / 12;    
                            break;
                    endswitch;                  
                    /*$return[$key][$subKey]['total_absent_amount'] = $deductions_array[$key][$subKey]['total_absent_amount'];*/
                    $return[$key][$subKey]['basic_pay'] = $monthly_rate;
                //}                
            }
        }       

        return $return;
    }


    /** 
     * Employees leave converted by year
     * @param array query
     * @param string add_query
     * @return array
    */
    public function getEmployeesYearlyConvertedLeave( $query = array(), $add_query = '' ){
        $data   = array();       
        $data = G_Converted_Leave_Helper::getDataByYear($query, $add_query);
        return $data;
    }

    /** 
     * Employees 13th Month 
     * @param array query
     * @param string add_query
     * @return array
    */
    public function getEmployeesYearlyBonus( $query = array(), $add_query = '' ){
        $data = array();       

        $now = date('Y-m-d');
        $current_year = date("Y");

        /*$fields = array('cutoff_start_date','cutoff_end_date');
        $yearly_bonus = G_Yearly_Bonus_Release_Date_Helper::getDataByYear($query['year'], $fields);

        if( !empty($yearly_bonus) ){            
            $query['start_date'] = $yearly_bonus['cutoff_start_date'];
            $query['end_date']   = $yearly_bonus['cutoff_end_date']; 
        }else{            
            $sv = new G_Sprint_Variables(G_Sprint_Variables::FIELD_DEFAULT_FISCAL_YEAR);
            $fiscal_year = $sv->getVariableValue();

            $p = G_Cutoff_Period_Finder::findByDate($now);
            if ($p) {
                $a_date   = explode(" ", $fiscal_year);
                $new_date = strtotime(trim($a_date[0]) . " " . trim($a_date[1]) . ", " . $query['year']);           

                $end_date   = $p->getEndDate();
                $start_date = date("Y-m-d",strtotime("-1 year", $new_date));  

                $query['start_date'] = $start_date;
                $query['end_date']   = $end_date;           

            }else{
                return $data;
            }  
        }*/

        $data = G_Employee_Helper::getEmployeesYearlyBonusByYear($query, $add_query);
        return $data;
    }

    //new
        public function getEmployeesYearlyBonusRev( $query = array(), $add_query = '', $cutoff_start = ''){
        $data = array();       
        $now = date('Y-m-d');
        $current_year = date("Y");

        /*$fields = array('cutoff_start_date','cutoff_end_date');
        $yearly_bonus = G_Yearly_Bonus_Release_Date_Helper::getDataByYear($query['year'], $fields);

        if( !empty($yearly_bonus) ){            
            $query['start_date'] = $yearly_bonus['cutoff_start_date'];
            $query['end_date']   = $yearly_bonus['cutoff_end_date']; 
        }else{            
            $sv = new G_Sprint_Variables(G_Sprint_Variables::FIELD_DEFAULT_FISCAL_YEAR);
            $fiscal_year = $sv->getVariableValue();

            $p = G_Cutoff_Period_Finder::findByDate($now);
            if ($p) {
                $a_date   = explode(" ", $fiscal_year);
                $new_date = strtotime(trim($a_date[0]) . " " . trim($a_date[1]) . ", " . $query['year']);           

                $end_date   = $p->getEndDate();
                $start_date = date("Y-m-d",strtotime("-1 year", $new_date));  

                $query['start_date'] = $start_date;
                $query['end_date']   = $end_date;           

            }else{
                return $data;
            }  
        }*/

        $data = G_Employee_Helper::getEmployeesYearlyBonusByYearRev($query, $add_query, $cutoff_start);
        return $data;
    }
    //new

    /** 
     * Employees 13th Month summary by year
     * @param array query
     * @param string add_query
     * @return array
    */
    public function getConvertedLeavesByYear( $year = 0 ){
        $data   = array();       

        $leave = new G_Convert_Leave();
        $data  = $leave->convertedLeavesByYear($year);

        return $data;
    }
    
    public function getAnnualizedTaxByYear( $year = 0 ) {
        $data = array();
        $data = G_Employee_Helper::sqlGetAllAnnualizedTaxByYear($year);
        return $data;
    }

    public function getEmployeeLastPayData() {
        $data = array();

        //$p = G_Payslip_Finder::findByEmployeeAndLastSalary($this);
        $p = G_Payslip_Finder::findByEmployeeLastSalaryByResignationDate($this);

        if(!$p) {
            return $data;
        }

        $emp_details           =  $p->getEmployee();
        $payslip_info          =  $p->getEmployeeBasicPayslipInfo();

        $data['employee_rate'] = "Php {$payslip_info['monthly_rate']}/mo., {$payslip_info['daily_rate']}/day, {$payslip_info['hourly_rate']}/hr.";
        $data['basic_pay']     = $p->basic_pay;
        $data['daily_rate']    = $payslip_info['daily_rate'];
        //$data['date_last_salary']          = date('F d, Y', strtotime($p->period_end_date . " +5 days"));
        $data['date_last_salary']          = date('F d, Y', strtotime($p->period_start_date . " +4 days"));
        $data['night_differential_hrs']    = 0;
        $data['night_differential_amount'] = 0;
        $data['overtime_pay_label']        = "";
        $data['overtime_pay_amount']       = 0;
        $data['other_earnings_amount']     = 0;
        $data['number_of_dependents']      = $emp_details->getNumberDependent();
        $data['monthly_rate']              = $payslip_info['monthly_rate'];

        $last_attendance = G_Attendance_Finder::findEmployeeLastAttendance($this->getId());
        if($last_attendance) {
            $data['last_attendance'] = date('F d, Y', strtotime($last_attendance->getDate()));
        }

        $payslip_label = $p->getLabels();
              
        $ns_array_hrs = array("regular_ns_hours", "restday_ns_hours", "regular_ns_ot_hours", "restday_ns_ot_hours", "restday_special_ns_ot_hours", "restday_legal_ns_ot_hours", "holiday_special_ns_ot_hours", "holiday_legal_ns_ot_hours", "restday_special_ns_hours", "restday_legal_ns_hours", "holiday_special_ns_hours", "holiday_legal_ns_hours");
        $ns_array_amount = array("regular_ns_amount", "regular_ns_ot_amount", "restday_ns_ot_amount", "restday_ns_amount", "restday_special_ns_amount", "restday_special_ns_ot_amount", "holiday_special_ns_ot_amount", "holiday_legal_ns_ot_amount", "restday_legal_ns_ot_amount", "restday_legal_ns_amount", "holiday_special_ns_amount", "holiday_legal_ns_amount");

        $ot_pay_array_hrs = array("regular_ot_hours", "restday_ot_hours", "restday_special_ot_hours", "restday_legal_ot_hours", "holiday_special_ot_hours", "holiday_legal_ot_hours", "restday_hours" );
        $ot_pay_array_amount = array("regular_ot_amount", "restday_ot_amount", "restday_special_ot_amount", "restday_legal_ot_amount", "holiday_special_ot_amount", "holiday_legal_ot_amount", "restday_amount" );

        $overtime_pay_label_key = array(
            'regular_ot_hours'              => 'ROT',
            'regular_ns_ot_hours'           => 'ROTND',
            'restday_ot_hours'              => 'RDOT',
            'restday_ns_ot_hours'           => 'RDOTND',
            'restday_special_ot_hours'      => 'RDSOT',
            'restday_special_ns_ot_hours'   => 'RDSOTND',
            'restday_legal_ot_hours'        => 'RDLOT',
            'restday_legal_ns_ot_hours'     => 'RDLOTND',
            'holiday_special_ot_hours'      => 'HSOT',
            'holiday_special_ns_ot_hours'   => 'HSOTND',
            'holiday_legal_ot_hours'        => 'HLOT',
            'holiday_legal_ns_ot_hours'     => 'HLOTND',
            );

        foreach($payslip_label as $key => $value) {
            if(in_array($value->variable, $ns_array_hrs)) {
                $data['night_differential_hrs'] += $value->value;
            }

            if(in_array($value->variable, $ns_array_amount)) {
                $data['night_differential_amount'] += $value->value;
            }

            if(in_array($value->variable, $ot_pay_array_hrs)) {
                if($value->value > 0) {
                    $overtime_pay_label[] = $value->value . " ". $overtime_pay_label_key[$value->variable];    
                } 
            }

            if(in_array($value->variable, $ot_pay_array_amount)) {
                $data['overtime_pay_amount'] += $value->value;
            }
        }

        if($p->getStartDate()) {
            $data['period_start_date'] = $p->getStartDate();
        }        

        if($p->getEndDate()) {
            $data['period_end_date'] = $p->getEndDate();
        }                
        
        if($data['night_differential_hrs'] > 0) {
            $data['night_differential_hrs'] = $data['night_differential_hrs'] . " hrs";
        }else{
            $data['night_differential_hrs'] = "";
        }

        $data['overtime_pay_label'] = implode(", ",$overtime_pay_label);

        $payslip_tardiness_deductions = $p->getTardinessDeductions();
        $data['absent_late_undertime_amount'] = $payslip_tardiness_deductions['late_amount']['amount'] + $payslip_tardiness_deductions['undertime_amount']['amount'] + $payslip_tardiness_deductions['absent_amount']['amount'];

        if($payslip_tardiness_deductions['late_amount']['amount'] > 0) {
            $absent_late_undertime_label[] = $payslip_tardiness_deductions['late_amount']['total_hours'] . " (late)";
        }

        if($payslip_tardiness_deductions['undertime_amount']['amount'] > 0) {
            $absent_late_undertime_label[] = $payslip_tardiness_deductions['undertime_amount']['total_hours'] . " (undertime)";
        }

        if($payslip_tardiness_deductions['absent_amount']['amount'] > 0) {
            $absent_late_undertime_label[] = $payslip_tardiness_deductions['absent_amount']['total_days'] . " (abs)";
        }

        $resignation_year = date("Y",strtotime($emp_details->getResignationDate()) );
        $month_13th = G_Yearly_Bonus_Release_Date_Helper::getDataByYearAndEmployeeId($emp_details->getId(),$resignation_year);

        $midyear_bonus_data  = G_Employee_Earnings_Finder::findAllApprovedByEmployeeIdAndTitleAndYear($emp_details->getId(),$resignation_year, 'Midyear Bonus');
        $year_end_bonus_data = G_Employee_Earnings_Finder::findAllApprovedByEmployeeIdAndTitleAndYear($emp_details->getId(),$resignation_year, 'Year-end Bonus');

        $data['yearly_total_13th_amount'] = 0;
        $data['1st_bunos_13th_month_amount'] = 0;
        $data['midyear_bonus']            = 0;
        $data['year_end_bonus']           = 0;

        foreach($midyear_bonus_data as $mbkey => $mb) {
            $data['midyear_bonus'] += $mb['amount'];
        }  
        foreach($year_end_bonus_data as $ybkey => $yb) {
            $data['year_end_bonus'] += $yb['amount'];
        }  

        if(!empty($month_13th)) {
            foreach($month_13th as $m13_key => $m13th) {
                $data['yearly_total_13th_amount'] += $m13th['total_bonus_amount'];
            }
            $data['1st_bunos_13th_month_amount'] = $month_13th[0]['amount'];
        }        

        $data['absent_late_undertime_label'] = implode(", ", $absent_late_undertime_label);
        $data['gross_pay'] = $p->gross_pay;
        $data['net_pay'] = $p->net_pay;
        $data['withheld_tax'] = $p->withheld_tax;
        $data['sss'] = $p->sss;
        $data['pagibig'] = $p->pagibig;
        $data['philhealth'] = $p->philhealth;
        $data['total_deductions'] = abs($p->total_deductions - $data['absent_late_undertime_amount']);
        $data['month_13th_amount'] = 0;

        $other_earnings_array = array();
        $other_earnings = $p->other_earnings;

        $data['other_earnings_array'] = $other_earnings;

        foreach($other_earnings as $key => $value) {
            if($value->getVariable() == '13th Month Bonus') {
                if($value->getAmount() > 0) {
                    $data['month_13th_amount'] = $value->getAmount();
                }
                
            }

            if($value->getVariable() != '13th Month Bonus') {
                if($value->getAmount() > 0) {
                    $data['other_earnings_amount'] += $value->getAmount();
                }
                
            }            
        }

        $data['other_deductions_amount']       = 0;
        $data['total_other_deductions_amount'] = 0;

        $other_deductions_array = array();
        $other_deductions = $p->other_deductions;

        $data['other_deductions_arr'] = $other_deductions;

        foreach($other_deductions as $key => $value) {
            if($value->getVariable() == 'employee_deduction') {
                if($value->getAmount() > 0) {
                    $other_deductions_array[$value->getLabel()] = $value->getAmount(); 
                    $data['total_other_deductions_amount'] += $value->getAmount();
                }
                
            } else {
                if($value->getAmount() > 0) {
                    $data['other_deductions_amount'] += $value->getAmount();
                    $data['total_other_deductions_amount'] += $value->getAmount();
                }
            }
        }

        $data['other_deductions_array'] = $other_deductions_array;
        $data['total_earnings'] = $p->total_earnings;
        $data['grand_total'] = abs($data['net_pay'] + $data['month_13th_amount'] - $data['total_other_deductions_amount']);
        
        return $data;
    }

    public function getBreakTimeData($schedule = array(), $day_type = array(), $attendance_breaks = null){        
        $data = array();
        $data_unique          = array();
        $schedule_breaks = array();
        $total_hrs_deductible = 0;
        $total_actual_break_hrs = 0;
        $has_early_break_out = false;
        $has_late_break_in = false;
        $total_early_break_out_hrs = 0;
        $total_late_break_in_hrs = 0;

        if( $this->id > 0 && !empty($schedule) ){

            $department_id = 0;
            $breaktime_schedules = array();
            $fields  = array("department_company_structure_id");
            $details = G_Employee_Helper::sqlGetEmployeeDetailsById($this->id, $fields);
            if( $details ){
                $department_id = $details['department_company_structure_id'];
            } 

            $object_array = array(G_Break_Time_Schedule_Details::PREFIX_ALL => 0, G_Break_Time_Schedule_Details::PREFIX_EMPLOYEE => $this->id, G_Break_Time_Schedule_Details::PREFIX_DEPARTMENT => $department_id);            

            $br = new G_Break_Time_Schedule_Details();            
            foreach( $object_array as $key => $value ){                
                $br->setObjType($key);
                $br->setObjId($value);
                $details = $br->getObjDeductibleBreakTimeByScheduleInOutDateStart($schedule, $day_type);                
                foreach($details as $value){
                    $schedule_breaks[] = $value;

                    $data[$value['Break Time']] = $value['total_hrs_deductible'];                
                }
            }            
                    
            if ($attendance_breaks) {
                $total_actual_break_hrs = G_Employee_Break_Logs_Summary_Helper::computeTotalBreakHrs($attendance_breaks);
            }

            //$data_unique = array_unique($data);
            $data_unique = $data;

            foreach($data_unique as $value){                
                $total_hrs_deductible += $value;
            }

            if (count($schedule_breaks) > 0 && $attendance_breaks) {
                $new_schedule_breaks = array();

                foreach ($schedule_breaks as $key => $schedule_break) {
                    $schedule_break_out = date('Y-m-d H:i:s', strtotime($attendance_breaks->getAttendanceDate() . ' ' . $schedule_break['break_in']));
                    $schedule_break_in = date('Y-m-d H:i:s', strtotime($attendance_breaks->getAttendanceDate() . ' ' . $schedule_break['break_out']));
        
                    if ($schedule_break_out > $schedule_break_in) {
                        $schedule_break_in = date('Y-m-d H:i:s', strtotime("+1 day", strtotime($schedule_break_in)));;
                    }
    
                    $new_schedule_breaks[$schedule_break_out] = $schedule_break;
                }
                ksort($new_schedule_breaks);

                $attendance_breaks = G_Employee_Break_Logs_Summary_Helper::matchBreakLogsToScheduleBreak($attendance_breaks, $new_schedule_breaks);

                $break_early_late = G_Employee_Break_Logs_Summary_Helper::earlyLateBreaks($attendance_breaks, $new_schedule_breaks);

                $has_early_break_out = $break_early_late['has_early_break_out'];
                $has_late_break_in = $break_early_late['has_late_break_in'];
                $total_early_break_out_hrs = $break_early_late['total_early_break_out_hrs'];
                $total_late_break_in_hrs = $break_early_late['total_late_break_in_hrs'];
                $total_actual_break_hrs += $break_early_late['unused_deductible_break_hrs'];
            }
        }
       
        return array(
            'total_hrs_deductible'      => $total_hrs_deductible,
            'total_actual_break_hrs'    => $total_actual_break_hrs,
            'has_early_break_out'       => $has_early_break_out,
            'has_late_break_in'         => $has_late_break_in,
            'total_early_break_out_hrs' => $total_early_break_out_hrs,
            'total_late_break_in_hrs'   => $total_late_break_in_hrs,
            'attendance_breaks'         => $attendance_breaks
        );
    }

}

?>