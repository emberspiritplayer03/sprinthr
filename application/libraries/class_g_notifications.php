<?php 
class G_Notifications extends Notifications {	
	
	// CONSTANTS
	const YES = "Yes";
	const NO = "No";
    
    const STATUS_NEW   = "New";
	const STATUS_SEEN  = "Seen";

    const TYPE_EMPLOYEE     = "Employee";
    const TYPE_ATTENDANCE   = "Attendance";
    const TYPE_SCHEDULE     = "Schedule";    
    const TYPE_PAYROLL      = "Payroll";
    const TYPE_DTR          = "Dtr";
    
    protected $event_type_arr = array();

	public function __construct() {
        $this->event_type_arr = array(
            //'INCOMPLETE_REQUIREMENTS'   => 'Incomplete Requirements',
            //'EARLY_TIME_IN'             => 'Employee with early in',            
            'NO_SALARY_RATE'            => 'No Salary Rate',
            'END_OF_CONTRACT'           => 'End of Contract',
            'END_OF_CONTRACT_PROB'      => 'Employees End of Contract',
            'NO_DEPARTMENT'             => 'No Department',
            'NO_JOB_TITLE'              => 'No Job Title',
            'NO_EMPLOYMENT_STATUS'      => 'No Employment Status',
            'NO_EMPLOYEE_STATUS'        => 'No Employee Status',
            'TARDINESS'                 => 'Tardiness',
            'INCOMPLETE_DTR'            => 'Incomplete DTR',
            'EMPLOYEE_WITH_NO_SCHEDULE' => 'Employee with no schedule',
            'WORK_AGAINST_SCHEDULE'     => 'Employee with work against schedule',
            'UNDERTIME'                 => 'Employee with undertime',
            'EMPLOYEE_INCORRECT_SHIFT'  => 'Employee with incorrect shift',
            'LEAVE_RESET'               => 'Total employee leave credit has been reset',
            'LEAVE_CONVERTED'           => 'Total leave credit converted to cash',
            'LEAVE_ADDED'               => 'Total employee with leave increase',
            'NO_BANK_ACCOUNT'           => 'No Bank Account',
            'UPCOMING_BIRTHDAY'         => 'Upcoming Birthday',
            'BIRTHDAY_TODAY'            => 'Birthday Today',
            'EMPLOYEE_ABSENT'           => 'Employee with absent',
            'UPDATE_ATTENDANCE'         => 'Update Attendance',
            'MULTIPLE_IN_OUT'           => 'Multiple in/out records',
            'NO_PAYSLIP_FOUND'          => 'Unprocessed Payroll',
            'EVALUATION_TODAY'          => 'Evaluation Today',
            'UPCOMING_EVALUATION'          => 'Upcoming Evaluation',
        );
	}

    public function getEventTypeArray() {
        return $this->event_type_arr;
    }
    	
	public function save() {
		return G_Notifications_Manager::save($this);
	}
	
	public function delete() {
		return G_Notifications_Manager::delete($this);
	}
    
    /* 
     * will count NEW notifications only 
     */
    public function countNotifications() {
        return G_Notifications_Helper::countNotifications();
    }	

    public function countImportantNotifications() {
        return G_Notifications_Helper::countImportantNotifications();
    }
	
    /* 
     * will check and update each type of notification 
     */
    public function updateNotifications($from, $to, $cutoff_01, $cutoff_02) {
        return G_Notifications_Helper::updateNotifications($this->event_type_arr, $from, $to, $cutoff_01, $cutoff_02);
    }

    public function updateNotificationsByPeriod($from,$to) {
        return G_Notifications_Helper::updateNotifications($this->event_type_arr, $from, $to);   
    }
    
    /*
     * will get all notifications
     * can pass argument (Optional)
     * accepts : 
            @string 'Attendance'
            @string 'Employee'
     * @usage : 
            $n = new G_Notifications();
            $n->getNotifications();
            
            OR
            
            $n = new G_Notifications();
            $n->getNotifications('Attendance');
     */
    public function getNotifications($type = '') {
        $data = array();
        if($type == G_Notifications::TYPE_EMPLOYEE) {       
            $selected_type = array(
                //$this->event_type_arr['INCOMPLETE_REQUIREMENTS'],
                $this->event_type_arr['NO_SALARY_RATE'],
                $this->event_type_arr['END_OF_CONTRACT'],
                $this->event_type_arr['END_OF_CONTRACT_PROB'],
                $this->event_type_arr['NO_DEPARTMENT'],
                $this->event_type_arr['NO_JOB_TITLE'],
                $this->event_type_arr['NO_EMPLOYMENT_STATUS'],
                $this->event_type_arr['NO_EMPLOYEE_STATUS'],
                $this->event_type_arr['LEAVE_ADDED'],
                $this->event_type_arr['LEAVE_CONVERTED'],
                $this->event_type_arr['LEAVE_RESET'],
                $this->event_type_arr['NO_BANK_ACCOUNT'],
                $this->event_type_arr['UPCOMING_BIRTHDAY'],
                $this->event_type_arr['BIRTHDAY_TODAY'],
                $this->event_type_arr['EVALUATION_TODAY'],
                 $this->event_type_arr['UPCOMING_EVALUATION']
            );
        }else if($type == G_Notifications::TYPE_ATTENDANCE) {
            $selected_type = array(
                $this->event_type_arr['TARDINESS'],
                $this->event_type_arr['UNDERTIME'],                
                $this->event_type_arr['WORK_AGAINST_SCHEDULE'],
                $this->event_type_arr['INCOMPLETE_DTR'],
                $this->event_type_arr['EMPLOYEE_ABSENT'],
                $this->event_type_arr['UPDATE_ATTENDANCE'],
                $this->event_type_arr['MULTIPLE_IN_OUT']                
            );
        }else if($type == G_Notifications::TYPE_SCHEDULE) {
            $selected_type = array(
                $this->event_type_arr['EMPLOYEE_WITH_NO_SCHEDULE'],
                $this->event_type_arr['EMPLOYEE_INCORRECT_SHIFT']
            );
        }else{ $selected_type = array(); }
   
        $notifications = G_Notifications_Helper::getNotifications();
        foreach($notifications as $key => $value) {      
            if(in_array($value['event_type'],$selected_type)){
                $data[$key] = $notifications[$key];
            }else if(empty($selected_type)){
                $data[$key] = $notifications[$key];
            }
        }
        
        return $data;
    }

    public function getNotificationsImportant($type = '') {
        $data = array();
        if($type == G_Notifications::TYPE_EMPLOYEE) {       
            $selected_type = array(
                $this->event_type_arr['NO_SALARY_RATE'],
                $this->event_type_arr['END_OF_CONTRACT'],
                $this->event_type_arr['END_OF_CONTRACT_PROB'],
                $this->event_type_arr['NO_DEPARTMENT'],
                $this->event_type_arr['NO_JOB_TITLE'],
                $this->event_type_arr['NO_EMPLOYMENT_STATUS'],
                $this->event_type_arr['NO_EMPLOYEE_STATUS'],
                $this->event_type_arr['NO_BANK_ACCOUNT']
            );
        }else if($type == G_Notifications::TYPE_ATTENDANCE) {
            $selected_type = array(
                $this->event_type_arr['INCOMPLETE_DTR'],
                $this->event_type_arr['TARDINESS'],
                $this->event_type_arr['UNDERTIME'],                
                $this->event_type_arr['WORK_AGAINST_SCHEDULE'],
                $this->event_type_arr['EMPLOYEE_ABSENT'],
                $this->event_type_arr['UPDATE_ATTENDANCE'],
                $this->event_type_arr['MULTIPLE_IN_OUT']
            );
        }else if($type == G_Notifications::TYPE_SCHEDULE) {
            $selected_type = array(
                $this->event_type_arr['EMPLOYEE_WITH_NO_SCHEDULE'],
                $this->event_type_arr['EMPLOYEE_INCORRECT_SHIFT']
            );
        }else if($type == G_Notifications::TYPE_PAYROLL) {
            $selected_type = array(
                $this->event_type_arr['NO_PAYSLIP_FOUND'],
            );
        }else{ $selected_type = array(); }
   
        $notifications = G_Notifications_Helper::getNotifications();
        foreach($notifications as $key => $value) {      
            if(in_array($value['event_type'],$selected_type)){
                $data[$key] = $notifications[$key];
            }else if(empty($selected_type)){
                $data[$key] = $notifications[$key];
            }
        }
        
        return $data;
    }    

    public function getSelectedNotification($type = '', $modules = '') {
        $data = array();
        if($type == G_Notifications::TYPE_SCHEDULE) {
            $selected_type = array(
                $this->event_type_arr['EMPLOYEE_WITH_NO_SCHEDULE'],
                $this->event_type_arr['EMPLOYEE_INCORRECT_SHIFT']
            );
        }else if($type == G_Notifications::TYPE_EMPLOYEE) {
            $selected_type = array(
                $this->event_type_arr['NO_SALARY_RATE'],
                $this->event_type_arr['END_OF_CONTRACT'],
                $this->event_type_arr['END_OF_CONTRACT_PROB'],
                $this->event_type_arr['NO_DEPARTMENT'],
                $this->event_type_arr['NO_JOB_TITLE'],
                $this->event_type_arr['NO_EMPLOYMENT_STATUS'],
                $this->event_type_arr['NO_EMPLOYEE_STATUS'],
                $this->event_type_arr['LEAVE_ADDED'],
                $this->event_type_arr['LEAVE_CONVERTED'],
                $this->event_type_arr['LEAVE_RESET'],
                $this->event_type_arr['NO_BANK_ACCOUNT'],
                $this->event_type_arr['UPCOMING_BIRTHDAY'],
                $this->event_type_arr['BIRTHDAY_TODAY'],
                $this->event_type_arr['EVALUATION_TODAY'],
                $this->event_type_arr['UPCOMING_EVALUATION']
            ); 
        }else if($type == G_Notifications::TYPE_ATTENDANCE) {
            $selected_type = array(
                $this->event_type_arr['INCOMPLETE_DTR'],
                $this->event_type_arr['TARDINESS'],
                $this->event_type_arr['UNDERTIME'],                
                $this->event_type_arr['WORK_AGAINST_SCHEDULE'],
                $this->event_type_arr['EMPLOYEE_ABSENT'],
                $this->event_type_arr['UPDATE_ATTENDANCE'],
                $this->event_type_arr['MULTIPLE_IN_OUT'],
                $this->event_type_arr['EMPLOYEE_INCORRECT_SHIFT']
            );
        }else if($type == G_Notifications::TYPE_DTR) {
            $selected_type = array(
                $this->event_type_arr['INCOMPLETE_DTR'],
                $this->event_type_arr['MULTIPLE_IN_OUT'],
                $this->event_type_arr['UPDATE_ATTENDANCE']
            );
        }else{ $selected_type = array(); }
   
        $notifications = G_Notifications_Helper::getNotifications();
        foreach($notifications as $key => $value) {      
            if(in_array($value['event_type'],$selected_type)){
                $data[$key] = $notifications[$key];
            }else if(empty($selected_type)){
                $data[$key] = $notifications[$key];
            }
        }

        return $data;      
    }

    public function getSingleNotification($event_type) {
        return G_Notifications_Finder::findByEventType($event_type);
    }

    public function getNotificationItems() {
        return G_Notifications_Helper::getNotificationItems($this);
    }

}
?>