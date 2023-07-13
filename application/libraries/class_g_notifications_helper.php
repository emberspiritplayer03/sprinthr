<?php 
class G_Notifications_Helper {

    public static function isIdExist(G_Notifications $n) {
        $sql = "
            SELECT COUNT(id) as total
            FROM " . G_NOTIFICATIONS ."
            WHERE id = ". Model::safeSql($n->getId()) ."
        ";
        $result = Model::runSql($sql);
        $row    = Model::fetchAssoc($result);
        return $row['total'];
    }
    
    public static function countTotalRecords() {
        $sql = "
            SELECT COUNT(id) as total
            FROM " . G_NOTIFICATIONS ."
        ";
        $result = Model::runSql($sql);
        $row    = Model::fetchAssoc($result);
        return $row['total'];
    }
    
    public static function countNotifications() {
        $sql = "
            SELECT COUNT(id) as total
            FROM " . G_NOTIFICATIONS ." 
            WHERE status = ".Model::safeSql(G_Notifications::STATUS_NEW)." 
                AND item > 0
        ";
        $result = Model::runSql($sql);
        $row    = Model::fetchAssoc($result);
        return $row['total'];
    }

    public static function countImportantNotifications() {

        /*
            9 = Incomplete DTR
        */
            
        $notification_ids = "9,10,13,44,142";
        $sql = "
            SELECT COUNT(id) as total
            FROM " . G_NOTIFICATIONS ." 
            WHERE status = ".Model::safeSql(G_Notifications::STATUS_NEW)." AND item > 0
            AND id IN (" . $notification_ids . ")
        ";

        $result = Model::runSql($sql);
        $row    = Model::fetchAssoc($result);
        return $row['total'];
    }    

    public static function countTotalEmployeeNotifications() {

        $notification_ids = "'No Salary Rate','End of Contract','No Department','No Job Title','No Employment Status','No Employee Status','No Bank Account','Upcoming Birthday','Birthday Today', 'Evaluation Today','Upcoming Evaluation'";

        $sql = "
            SELECT SUM(item) as total
            FROM " . G_NOTIFICATIONS ." 
            WHERE item > 0
            AND event_type IN (" . $notification_ids . ")
        ";

        $result = Model::runSql($sql);
        $row    = Model::fetchAssoc($result);
        return $row['total'];        
    }
    
    public static function countTotalAttendanceNotifications() {
        $notification_ids = "'Incomplete DTR','Tardiness', 'Employee with undertime','Employee with work against schedule','Employee with absent','Update Attendance','Multiple in/out records'";

        $sql = "
            SELECT SUM(item) as total
            FROM " . G_NOTIFICATIONS ." 
            WHERE item > 0
            AND event_type IN (" . $notification_ids . ")
        ";

        $result = Model::runSql($sql);
        $row    = Model::fetchAssoc($result);
        return $row['total'];
    }

    public static function getNotifications() {
        $sql = "
            SELECT *
            FROM " . G_NOTIFICATIONS ." 
            WHERE item > 0
        ";
        $result = Model::runSql($sql,true);
        return $result;
    }
    
    public static function updateNotifications( $event_type_arr = array(), $from = null, $to = null, $cutoff_01 = null, $cutoff_02 = null ) {

        /*
         * Disable Notification
        */

        $event_type_disable_arr = array(
            'INCOMPLETE_REQUIREMENTS'   => 'Incomplete Requirements',
            //'EMPLOYEE_WITH_NO_SCHEDULE' => 'Employee with no schedule',
            'EARLY_TIME_IN'             => 'Employee with early in',
        );

        $disable_n1 = G_Notifications_Finder::findByEventType($event_type_disable_arr['INCOMPLETE_REQUIREMENTS']);
        if($disable_n1) {
            $disable_n1->setStatus(G_Notifications::STATUS_SEEN); 
            $disable_n1->setItem(0);
            $disable_n1->save();
        }   

        /*$disable_n2 = G_Notifications_Finder::findByEventType($event_type_disable_arr['EMPLOYEE_WITH_NO_SCHEDULE']);
        if($disable_n2) {
            $disable_n2->setStatus(G_Notifications::STATUS_SEEN); 
            $disable_n2->setItem(0);
            $disable_n2->save();
        } */ 

        /*
         * Disable Notification - end
        */
        if($from != null && $to != null) {
            $current_period_array = array();
            $current_p      = G_Cutoff_Period_Helper::sqlGetCutoffPeriodByStartEndDate($from, $to);
            if($current_p) {
                $current_period_array['current_cutoff']['start'] = $current_p['period_start'];
                $current_period_array['current_cutoff']['end']   = $current_p['period_end'];
                $current_period_array['is_lock']                 = $current_p['is_lock'];
                $current_period_array['date']                    = $date;
                $current_period                                  = $current_period_array;
            }
        } else {
            $date   = date("Y-m-d");
            $current_period_array = array();
            $current_p      = G_Cutoff_Period_Helper::sqlGetCurrentCutoffPeriod($date);
            if($current_p) {
                $current_period_array['current_cutoff']['start'] = $current_p['period_start'];
                $current_period_array['current_cutoff']['end']   = $current_p['period_end'];
                $current_period_array['is_lock']                 = $current_p['is_lock'];
                $current_period_array['date']                    = $date;
                $current_period                                  = $current_period_array;
            } else {
                $cutoff = new G_Cutoff_Period();
                $expected_current_period = $cutoff->getCurrentCutoffPeriod($date);   
                $current_period = $expected_current_period;
            }
        }
        
        //Incomplete Requirements
        $has_update = false;
        $n = G_Notifications_Finder::findByEventType($event_type_arr['INCOMPLETE_REQUIREMENTS']);
        if(!$n) {
            $n = new G_Notifications();
            $n->setEventType($event_type_arr['INCOMPLETE_REQUIREMENTS']);
            $n->setStatus(G_Notifications::STATUS_NEW); 
            $n->setDateCreated(date('Y-m-d H:i:s'));
            $n->setItem(0);
            $has_update = true;
        }
        $incomplete_requirements = G_Employee_Requirements_Helper::countEmployeeIncompleteRequirements();
        if($incomplete_requirements > $n->getItem()) {
            //Update when there's new notification
            $n->setItem($incomplete_requirements);
            $n->setStatus(G_Notifications::STATUS_NEW);  
            $has_update = true;
        }else if($incomplete_requirements < $n->getItem()){
            //Update when the item was decreased
            $n->setItem($incomplete_requirements);
            $has_update = true;
        }

        $description = ($n->getItem() > 1 ? 'Employees have incomplete requirements.' : 'Employee has incomplete requirements.');
        $n->setDescription($description);

        if($has_update) {
            $n->setDateModified(date('Y-m-d H:i:s'));
            $n->save();
        }
              
        //No Salary Rate
        $has_update = false;
        $n = G_Notifications_Finder::findByEventType($event_type_arr['NO_SALARY_RATE']);
        if(!$n) {
            $n = new G_Notifications();
            $n->setEventType($event_type_arr['NO_SALARY_RATE']);
            $n->setDescription('');
            $n->setStatus(G_Notifications::STATUS_NEW); 
            $n->setDateCreated(date('Y-m-d H:i:s'));
            $n->setItem(0);
            $has_update = true;
        }
        $no_salary_rate = G_Employee_Basic_Salary_History_Helper::countEmployeeNoSalaryRate();
        if($no_salary_rate > $n->getItem()) {
            //Update when there's new notification
            $n->setItem($no_salary_rate);
            $n->setStatus(G_Notifications::STATUS_NEW);  
            $has_update = true;
        }else if($no_salary_rate < $n->getItem()){
            //Update when the item was decreased
            $n->setItem($no_salary_rate);
            $has_update = true;
        }

        $description = ($n->getItem() > 1 ? 'Employees have no salary rate yet.' : 'Employee has no salary rate yet.');
        $n->setDescription($description);

        if($has_update) {
            $n->setDateModified(date('Y-m-d H:i:s'));
            $n->save();
        }
        
        //End Of Contract
        $has_update = false;
        $n = G_Notifications_Finder::findByEventType($event_type_arr['END_OF_CONTRACT']);
        if(!$n) {
            $n = new G_Notifications();
            $n->setEventType($event_type_arr['END_OF_CONTRACT']);
            $n->setStatus(G_Notifications::STATUS_NEW); 
            $n->setDateCreated(date('Y-m-d H:i:s'));
            $n->setItem(0);
            $has_update = true;
        }
        $end_of_contract = G_Employee_Helper::countEmployeeEndOfContract30Days();
        if($end_of_contract > $n->getItem()) {
            //Update when there's new notification
            $n->setItem($end_of_contract);
            $n->setStatus(G_Notifications::STATUS_NEW);  
            $has_update = true;
        }else if($end_of_contract < $n->getItem()){
            //Update when the item was decreased
            $n->setItem($end_of_contract);
            $has_update = true;
        }

        $description = ($n->getItem() > 1 ? 'Employees reached end of contract this month.' : 'Employee reached end of contract this month.');
        $n->setDescription($description);

        if($has_update) {
            $n->setDateModified(date('Y-m-d H:i:s'));
            $n->save();
        }

        //End Of Contract PROB
        $has_update = false;
        
        $n = G_Notifications_Finder::findByEventType($event_type_arr['END_OF_CONTRACT_PROB']);
        if(!$n) {
            $n = new G_Notifications();
            $n->setEventType($event_type_arr['END_OF_CONTRACT_PROB']);
            $n->setStatus(G_Notifications::STATUS_NEW); 
            $n->setDateCreated(date('Y-m-d H:i:s'));
            $n->setItem(0);
            $has_update = true;
        }
        $end_of_contract = G_Employee_Helper::countEmployeeEndOfContractProbi30Days();
        if($end_of_contract > $n->getItem()) {
            //Update when there's new notification
            $n->setItem($end_of_contract);
            $n->setStatus(G_Notifications::STATUS_NEW);  
            $has_update = true;
        }else if($end_of_contract < $n->getItem()){
            //Update when the item was decreased
            $n->setItem($end_of_contract);
            $has_update = true;
        }

        $description = ($n->getItem() > 1 ? 'Employees reached end of contract this month.' : 'Employee reached end of contract this month.');
        $n->setDescription($description);

        if($has_update) {
            $n->setDateModified(date('Y-m-d H:i:s'));
            $n->save();
        }
        
        //No Department
        $has_update = false;
        $n = G_Notifications_Finder::findByEventType($event_type_arr['NO_DEPARTMENT']);
        if(!$n) {
            $n = new G_Notifications();
            $n->setEventType($event_type_arr['NO_DEPARTMENT']);
            $n->setStatus(G_Notifications::STATUS_NEW); 
            $n->setDateCreated(date('Y-m-d H:i:s'));
            $n->setItem(0);
            $has_update = true;
        }
        $no_department = G_Employee_Subdivision_History_Helper::countEmployeeNoDepartment();
        if($no_department > $n->getItem()) {
            //Update when there's new notification
            $n->setItem($no_department);
            $n->setStatus(G_Notifications::STATUS_NEW);  
            $has_update = true;
        }else if($no_department < $n->getItem()){
            //Update when the item was decreased
            $n->setItem($no_department);
            $has_update = true;
        }

        $description = ($n->getItem() > 1 ? 'Employees have no assigned department yet.' : 'Employee has no assigned department yet.');
        $n->setDescription($description);

        if($has_update) {
            $n->setDateModified(date('Y-m-d H:i:s'));
            $n->save();
        }
        
        //No Job Title
        $has_update = false;
        $n = G_Notifications_Finder::findByEventType($event_type_arr['NO_JOB_TITLE']);
        if(!$n) {
            $n = new G_Notifications();
            $n->setEventType($event_type_arr['NO_JOB_TITLE']);
            $n->setStatus(G_Notifications::STATUS_NEW); 
            $n->setDateCreated(date('Y-m-d H:i:s'));
            $n->setItem(0);
            $has_update = true;
        }
        $no_job_title = G_Employee_Job_History_Helper::countEmployeeNoJobTitle();
        if($no_job_title > $n->getItem()) {
            //Update when there's new notification
            $n->setItem($no_job_title);
            $n->setStatus(G_Notifications::STATUS_NEW);  
            $has_update = true;
        }else if($no_job_title < $n->getItem()){
            //Update when the item was decreased
            $n->setItem($no_job_title);
            $has_update = true;
        }

        $description = ($n->getItem() > 1 ? 'Employees have no Job Title yet.' : 'Employee has no Job Title yet.');
        $n->setDescription($description);

        if($has_update) {
            $n->setDateModified(date('Y-m-d H:i:s'));
            $n->save();
        }
        
        //No Employment Status
        $has_update = false;
        $n = G_Notifications_Finder::findByEventType($event_type_arr['NO_EMPLOYMENT_STATUS']);
        if(!$n) {
            $n = new G_Notifications();
            $n->setEventType($event_type_arr['NO_EMPLOYMENT_STATUS']);
            $n->setStatus(G_Notifications::STATUS_NEW); 
            $n->setDateCreated(date('Y-m-d H:i:s'));
            $n->setItem(0);
            $has_update = true;
        }
        $no_employment_status = G_Employee_Job_History_Helper::countEmployeeNoEmploymentStatus();
        if($no_employment_status > $n->getItem()) {
            //Update when there's new notification
            $n->setItem($no_employment_status);
            $n->setStatus(G_Notifications::STATUS_NEW);  
            $has_update = true;
        }else if($no_employment_status < $n->getItem()){
            //Update when the item was decreased
            $n->setItem($no_employment_status);
            $has_update = true;
        }

        $description = ($n->getItem() > 1 ? 'Employees have no employment status.' : 'Employee has no employment status.');
        $n->setDescription($description);

        if($has_update) {
            $n->setDateModified(date('Y-m-d H:i:s'));
            $n->save();
        }
        
        //No Employee Status
        $has_update = false;
        $n = G_Notifications_Finder::findByEventType($event_type_arr['NO_EMPLOYEE_STATUS']);
        if(!$n) {
            $n = new G_Notifications();
            $n->setEventType($event_type_arr['NO_EMPLOYEE_STATUS']);
            $n->setStatus(G_Notifications::STATUS_NEW); 
            $n->setDateCreated(date('Y-m-d H:i:s'));
            $n->setItem(0);
            $has_update = true;
        }
        $no_employee_status = G_Employee_Helper::countEmployeeNoEmployeeStatus();
        if($no_employee_status > $n->getItem()) {
            //Update when there's new notification
            $n->setItem($no_employee_status);
            $n->setStatus(G_Notifications::STATUS_NEW);  
            $has_update = true;
        }else if($no_employee_status < $n->getItem()){
            //Update when the item was decreased
            $n->setItem($no_employee_status);
            $has_update = true;
        }

        $description = ($n->getItem() > 1 ? 'Employees have no employee status.' : 'Employee has no employee status.');
        $n->setDescription($description);

        if($has_update) {
            $n->setDateModified(date('Y-m-d H:i:s'));
            $n->save();
        }

        //Tardiness
        $has_update = false;
        $n = G_Notifications_Finder::findByEventType($event_type_arr['TARDINESS']);
        if(!$n) {
            $n = new G_Notifications();
            $n->setEventType($event_type_arr['TARDINESS']);
            $n->setStatus(G_Notifications::STATUS_NEW); 
            $n->setDateCreated(date('Y-m-d H:i:s'));
            $n->setItem(0);
            $has_update = true;
        }
        
        //$tardiness = G_Employee_Helper::countEmployeeTardinessByCurrentDate();
        $from_tardi = $current_period['current_cutoff']['start'];
        $to_tardi   = $current_period['current_cutoff']['end'];        

        $tardiness  = G_Employee_Helper::countEmployeeTardinessByFromAndTo($from_tardi, $to_tardi);
        if($tardiness > $n->getItem()) {
            //Update when there's new notification
            $n->setItem($tardiness);
            $n->setStatus(G_Notifications::STATUS_NEW);  
            $has_update = true;
        }else if($tardiness < $n->getItem()){
            //Update when the item was decreased
            $n->setItem($tardiness);
            $has_update = true;
        }

        //$description = ($n->getItem() > 1 ? 'Employees are late today.' : 'Employee is late today.');
        $description = ($n->getItem() > 1 ? 'Employee(s) are late this cutoff.' : 'Employee is late this cutoff.');
        $n->setDescription($description);

        if($has_update) {
            $n->setDateModified(date('Y-m-d H:i:s'));
            $n->save();
        }
        
        //Incomplete DTR
        $has_update = false;
        $n = G_Notifications_Finder::findByEventType($event_type_arr['INCOMPLETE_DTR']);
        if(!$n) {
            $n = new G_Notifications();
            $n->setEventType($event_type_arr['INCOMPLETE_DTR']);
            $n->setStatus(G_Notifications::STATUS_NEW); 
            $n->setDateCreated(date('Y-m-d H:i:s'));
            $n->setItem(0);
            $has_update = true;
        }

        //FOR INCOMPLETE DTR ONLY
        $date_from = $current_period['current_cutoff']['start'];
        $date_to   = $current_period['current_cutoff']['end'];
        //$date_from = date("Y-m-01");
        //$date_to   = date("Y-m-t");               

        $count_incomplete_dtr = G_Employee_Helper::sqlCountIncompleteDTR($date_from, $date_to);
        if($count_incomplete_dtr > $n->getItem()) {
            //Update when there's new notification
            $n->setItem($count_incomplete_dtr);
            $n->setStatus(G_Notifications::STATUS_NEW);  
            $has_update = true;
        }else if($count_incomplete_dtr < $n->getItem()){
            //Update when the item was decreased
            $n->setItem($count_incomplete_dtr);
            $has_update = true;
        }

        $description = ($n->getItem() > 1 ? 'Employees with incomplete DTR this cutoff.' : 'Employee with incomplete DTR this cutoff.');
        $n->setDescription($description);

        if($has_update) {
            $n->setDateModified(date('Y-m-d H:i:s'));
            $n->save();
        }

        //Employee with no schedule
        $has_update = false;
        $n = G_Notifications_Finder::findByEventType($event_type_arr['EMPLOYEE_WITH_NO_SCHEDULE']);

        if(!$n) {
            $n = new G_Notifications();
            $n->setEventType($event_type_arr['EMPLOYEE_WITH_NO_SCHEDULE']);
            $n->setStatus(G_Notifications::STATUS_NEW); 
            $n->setDateCreated(date('Y-m-d H:i:s'));
            $n->setItem(0);
            $has_update = true;
        }

        $employee_with_no_schedule = G_Schedule_Group_Helper::countEmployeeWithNoSchedule();

        if($employee_with_no_schedule > $n->getItem()) {
            //Update when there's new notification
            $n->setItem($employee_with_no_schedule);
            $n->setStatus(G_Notifications::STATUS_NEW);  
            $has_update = true;
        }else if($employee_with_no_schedule < $n->getItem()){
            //Update when the item was decreased
            $n->setItem($employee_with_no_schedule);
            $has_update = true;
        }

        $description = ($n->getItem() > 1 ? 'Employees with no schedule.' : 'Employee with no schedule.');
        $n->setDescription($description);

        if($has_update) {
            $n->setDateModified(date('Y-m-d H:i:s'));
            $n->save();
        }

        //Incorrect Shifts
        $has_update = false;
        $n = G_Notifications_Finder::findByEventType($event_type_arr['EMPLOYEE_INCORRECT_SHIFT']);
        if(!$n) {
            $n = new G_Notifications();
            $n->setEventType($event_type_arr['EMPLOYEE_INCORRECT_SHIFT']);
            $n->setStatus(G_Notifications::STATUS_NEW); 
            $n->setDateCreated(date('Y-m-d H:i:s'));
            $n->setItem(0);
            $has_update = true;
        }

        $date_from = $current_period['current_cutoff']['start'];
        $date_to   = $current_period['current_cutoff']['end'];

        $rep = new G_Report();
        $rep->setFromDate($date_from);
        $rep->setToDate($date_to);
        $incorrect_shifts = $rep->allIncorrectShift();

        $total_incorrect_shifts = 0;
        foreach( $incorrect_shifts as $key => $shift ){
            foreach( $shift as $data ){
                $total_incorrect_shifts++;
            }
        }

         if($total_incorrect_shifts > $n->getItem()) {
            //Update when there's new notification
            $n->setItem($total_incorrect_shifts);
            $n->setStatus(G_Notifications::STATUS_NEW);  
            $has_update = true;
        }else if($total_incorrect_shifts < $n->getItem()){
            //Update when the item was decreased
            $n->setItem($total_incorrect_shifts);
            $has_update = true;
        }

        $description = ($n->getItem() > 1 ? 'Employees with incorrect shifts.' : 'Employee with incorrect shift.');
        $n->setDescription($description);

        if($has_update) {
            $n->setDateModified(date('Y-m-d H:i:s'));
            $n->save();
        }

        //Work Against Schedule
        /*$has_update = false;
        $n = G_Notifications_Finder::findByEventType($event_type_arr['WORK_AGAINST_SCHEDULE']);
        if(!$n) {
            $n = new G_Notifications();
            $n->setEventType($event_type_arr['WORK_AGAINST_SCHEDULE']);
            $n->setStatus(G_Notifications::STATUS_NEW); 
            $n->setDateCreated(date('Y-m-d H:i:s'));
            $n->setItem(0);
            $has_update = true;
        }

        $s_from = date("Y-m-d",strtotime("-1 day"));
        $s_to   = date("Y-m-d");
        $report = new G_Report();
        $report->setFromDate($s_from);
        $report->setToDate($s_to);
        $a_data = $report->summaryWorkAgainstSchedule();
        $i_total_with_work_against_schedule = $a_data['total'];

        if($i_total_with_work_against_schedule > $n->getItem()) {
            //Update when there's new notification
            $n->setItem($i_total_with_work_against_schedule);
            $n->setStatus(G_Notifications::STATUS_NEW);  
            $has_update = true;
        }else if($i_total_with_work_against_schedule < $n->getItem()){
            //Update when the item was decreased
            $n->setItem($i_total_with_work_against_schedule);
            $has_update = true;
        }

        $description = ($n->getItem() > 1 ? 'Employees with work against schedule.' : 'Employees with work against schedule.');
        $n->setDescription($description);

        if($has_update) {
            $n->setDateModified(date('Y-m-d H:i:s'));
            $n->save();
        }*/

        //Undertime
        $has_update = false;
        $n = G_Notifications_Finder::findByEventType($event_type_arr['UNDERTIME']);
        if(!$n) {
            $n = new G_Notifications();
            $n->setEventType($event_type_arr['UNDERTIME']);
            $n->setStatus(G_Notifications::STATUS_NEW); 
            $n->setDateCreated(date('Y-m-d H:i:s'));
            $n->setItem(0);
            $has_update = true;
        }

        //$s_from = date("Y-m-d",strtotime("-1 day"));
        //$s_to   = date("Y-m-d");

        $s_from = date("Y-m-d",strtotime($current_period['current_cutoff']['start'] . "-1 day"));
        $s_to   = $current_period['current_cutoff']['end'];

        $i_total_with_undertime = G_Attendance_Helper::sqlCountTotalWithUndertimeByFromAndToDate($s_from, $s_to);
        if($i_total_with_undertime > $n->getItem()) {
            //Update when there's new notification
            $n->setItem($i_total_with_undertime);
            $n->setStatus(G_Notifications::STATUS_NEW);  
            $has_update = true;
        }else if($i_total_with_undertime < $n->getItem()){
            //Update when the item was decreased
            $n->setItem($i_total_with_undertime);
            $has_update = true;
        }

        $description = ($n->getItem() > 1 ? 'Employees with undertime this cutoff.' : 'Employee with undertime this cutoff.');
        $n->setDescription($description);

        if($has_update) {
            $n->setDateModified(date('Y-m-d H:i:s'));
            $n->save();
        }

        //Early In
        /*$has_update = false;
        $n = G_Notifications_Finder::findByEventType($event_type_arr['EARLY_TIME_IN']);
        if(!$n) {
            $n = new G_Notifications();
            $n->setEventType($event_type_arr['EARLY_TIME_IN']);
            $n->setStatus(G_Notifications::STATUS_NEW); 
            $n->setDateCreated(date('Y-m-d H:i:s'));
            $n->setItem(0);
            $has_update = true;
        }

        $s_from = date("Y-m-d",strtotime("-1 day"));
        $s_to   = date("Y-m-d");
        $i_total_ealy_in = G_Attendance_Helper::sqlCountTotalEarlyInByFromAndToDate($s_from, $s_to);

        if($i_total_ealy_in > $n->getItem()) {
            //Update when there's new notification
            $n->setItem($i_total_ealy_in);
            $n->setStatus(G_Notifications::STATUS_NEW);  
            $has_update = true;
        }else if($i_total_ealy_in < $n->getItem()){
            //Update when the item was decreased
            $n->setItem($i_total_ealy_in);
            $has_update = true;
        }

        $description = ($n->getItem() > 1 ? 'Employees with early in.' : 'Employee with early in.');
        $n->setDescription($description);

        if($has_update) {
            $n->setDateModified(date('Y-m-d H:i:s'));
            $n->save();
        }*/

        //No Bank Account
        $has_update = false;
        $n = G_Notifications_Finder::findByEventType($event_type_arr['NO_BANK_ACCOUNT']);         
        if(!$n) {
            $n = new G_Notifications();
            $n->setEventType($event_type_arr['NO_BANK_ACCOUNT']);
            $n->setStatus(G_Notifications::STATUS_NEW); 
            $n->setDateCreated(date('Y-m-d H:i:s'));
            $n->setItem(0);
            $has_update = true;
        }
        $no_bank_account = G_Employee_Helper::countEmployeeNoBankAccount();            
        if($no_bank_account > $n->getItem()) {
            //Update when there's new notification
            $n->setItem($no_bank_account);
            $n->setStatus(G_Notifications::STATUS_NEW);  
            $has_update = true;
        }else if($no_bank_account < $n->getItem()){
            //Update when the item was decreased
            $n->setItem($no_bank_account);
            $has_update = true;
        }

        $description = ($n->getItem() > 1 ? 'Employees have no bank account.' : 'Employee has no bank account.');
        $n->setDescription($description);
        if($has_update) {
            $n->setDateModified(date('Y-m-d H:i:s'));
            $n->save();            
        }

        //Employee with absent
        $has_update = false;
        $n = G_Notifications_Finder::findByEventType($event_type_arr['EMPLOYEE_ABSENT']);         
        if(!$n) {            
            $n = new G_Notifications();
            $n->setEventType($event_type_arr['EMPLOYEE_ABSENT']);
            $n->setStatus(G_Notifications::STATUS_NEW); 
            $n->setDateCreated(date('Y-m-d H:i:s'));
            $n->setItem(0);
            $has_update = true;
        }  

        $from = $current_period['current_cutoff']['start'];
        $to   = $current_period['current_cutoff']['end'];

        $count_employee_absent = G_Attendance_Helper::sqlCountAbsentDaysByDateRange($from, $to);
        if($count_employee_absent > $n->getItem()) {
            //Update when there's new notification
            $n->setItem($count_employee_absent);
            $n->setStatus(G_Notifications::STATUS_NEW);  
            $has_update = true;
        }else if($count_employee_absent < $n->getItem()){
            //Update when the item was decreased
            $n->setItem($count_employee_absent);
            $has_update = true;
        }

        $description = ($n->getItem() > 1 ? 'Employees with absent(s).' : 'Employee with absent(s).');
        $n->setDescription($description);

        if($has_update) {
            $n->setDateModified(date('Y-m-d H:i:s'));
            $n->save();
        }

        //UPDATE ATTENDANCE
        $has_update = false;
        $n = G_Notifications_Finder::findByEventType($event_type_arr['UPDATE_ATTENDANCE']);         
        if(!$n) {            
            $n = new G_Notifications();
            $n->setEventType($event_type_arr['UPDATE_ATTENDANCE']);
            $n->setStatus(G_Notifications::STATUS_NEW); 
            $n->setDateCreated(date('Y-m-d H:i:s'));
            $n->setItem(0);
            $has_update = true;
        }

        $description = ($n->getItem() > 1 ? 'New DTR records detected, update attendance needed' : 'New DTR records detected, update attendance needed');
        $n->setDescription($description);

        if($has_update) {
            $n->setDateModified(date('Y-m-d H:i:s'));
            $n->save();
        }               

        //MULTIPLE IN/OUT DTR
        $has_update = false;
        $n = G_Notifications_Finder::findByEventType($event_type_arr['MULTIPLE_IN_OUT']);         
        if(!$n) {            
            $n = new G_Notifications();
            $n->setEventType($event_type_arr['MULTIPLE_IN_OUT']);
            $n->setStatus(G_Notifications::STATUS_NEW); 
            $n->setDateCreated(date('Y-m-d H:i:s'));
            $n->setItem(0);
            $has_update = true;
        } 

        $from = $current_period['current_cutoff']['start'];
        $to   = $current_period['current_cutoff']['end'];          

        $count_employee_multiple_attendance = G_Fp_Attendance_Logs_Helper::sqlCountMultipleInOutByDateRange($from, $to);
        if($count_employee_multiple_attendance > $n->getItem()) {
            $n->setItem($count_employee_multiple_attendance);
            $n->setStatus(G_Notifications::STATUS_NEW);  
            $has_update = true;
        }else if($count_employee_multiple_attendance < $n->getItem()){
            $n->setItem($count_employee_multiple_attendance);
            $has_update = true;
        }
        
        $description = 'Multiple DTR IN/OUT detected';
        $n->setDescription($description);

        if($has_update) {
            $n->setDateModified(date('Y-m-d H:i:s'));
            $n->save();
        }              

        //Upcoming Birthday
        $has_update = false;
        $n = G_Notifications_Finder::findByEventType($event_type_arr['UPCOMING_BIRTHDAY']);
        if(!$n) {
            $n = new G_Notifications();
            $n->setEventType($event_type_arr['UPCOMING_BIRTHDAY']);
            $n->setStatus(G_Notifications::STATUS_NEW); 
            $n->setDateCreated(date('Y-m-d H:i:s'));
            $n->setItem(0);
            $has_update = true;
        }

        $i_total_upcoming_birthday = G_Employee_Helper::countEmployeeWithUpcomingBirthday();

        if($i_total_upcoming_birthday > $n->getItem()) {
            //Update when there's new notification
            $n->setItem($i_total_upcoming_birthday);
            $n->setStatus(G_Notifications::STATUS_NEW);  
            $has_update = true;
        }else if($i_total_upcoming_birthday < $n->getItem()){
            //Update when the item was decreased
            $n->setItem($i_total_upcoming_birthday);
            $has_update = true;
        }

        $description = ($n->getItem() > 1 ? 'Employees with upcoming birthday.' : 'Employee with upcoming birthday.');
        $n->setDescription($description);

        if($has_update) {
            $n->setDateModified(date('Y-m-d H:i:s'));
            $n->save();
        }

        //NO PAYSLIP FOUND
        $has_update = false;
        $n = G_Notifications_Finder::findByEventType($event_type_arr['NO_PAYSLIP_FOUND']);
        if(!$n) {
            $n = new G_Notifications();
            $n->setEventType($event_type_arr['NO_PAYSLIP_FOUND']);
            $n->setStatus(G_Notifications::STATUS_NEW); 
            $n->setDateCreated(date('Y-m-d H:i:s'));
            $n->setItem(0);
            $has_update = true;
        }    

        //$start_date     = $current_p['period_start'];
        //$end_date       = $current_p['period_end'];

        $total_unprocessed_payroll = 0;
        $additional_qry = "";

        if( !empty($cutoff_01) && !empty($cutoff_02) ) {

            $first_cutoff  = explode("/", $cutoff_01);
            $second_cutoff = explode("/", $cutoff_02);

            $start_date_01     = $first_cutoff[0];
            $end_date_01       = $first_cutoff[1];

            $start_date_02     = $second_cutoff[0];
            $end_date_02       = $second_cutoff[1];

            $additional_qry_unprocess = " AND (( e.employee_status_id != 2 ) AND ( e.employee_status_id != 3 ) AND ( e.employee_status_id != 4 ) ) ";
            //$additional_qry_unprocess = " AND ( e.employee_status_id != 4 ) ";

            $cutoff_details_1 = G_Cutoff_Period_Finder::findByPeriod($start_date_01,$end_date_01);
            $cutoff_details_2 = G_Cutoff_Period_Finder::findByPeriod($start_date_02,$end_date_02);

            $ee = new G_Employee();
            if( isset($cutoff_details_1) && $cutoff_details_1->getIsLock() != 'Yes' ) {
                $process_unprocessed_payslip_cutoff_01 = $ee->getProcessedAndUnprocessedEmployeeCount($start_date_01,$end_date_01, $additional_qry_unprocess );            
            } else {
                $process_unprocessed_payslip_cutoff_01['unprocessed_payroll'] = 0;
            }

            if( isset($cutoff_details_2) && $cutoff_details_2->getIsLock() != 'Yes' ) {
                $process_unprocessed_payslip_cutoff_02 = $ee->getProcessedAndUnprocessedEmployeeCount($start_date_02,$end_date_02, $additional_qry_unprocess );            
            } else {
                $process_unprocessed_payslip_cutoff_02['unprocessed_payroll'] = 0;
            }

            /*echo '<pre>';
            echo print_r($process_unprocessed_payslip_cutoff_01);
            echo print_r($process_unprocessed_payslip_cutoff_02);
            echo '</pre>';*/

            $total_unprocessed_payroll = $process_unprocessed_payslip_cutoff_01['unprocessed_payroll'] + $process_unprocessed_payslip_cutoff_02['unprocessed_payroll'];
        } else {

            $additional_qry_remove_endo = " AND e.employee_status_id != 4";
            $additional_remove_resigned_terminated_qry = " AND (e.employee_status_id IN (2,3))";
            $total_employees   = G_Employee_Helper::countEmployeeNotArchivedByDate($start_date, $additional_qry_remove_endo);
            $total_resigned_terminated_employee = G_Employee_Helper::countEmployeeNotArchivedByDate($start_date, $additional_remove_resigned_terminated_qry);
            $processed_payroll = G_Employee_Helper::countProcessedEmployeePayrollByCutoff($start_date, $end_date, $additional_qry);

            $total_unprocessed_payroll = abs(($total_employees - $total_resigned_terminated_employee) - $processed_payroll);

        }
        
        if($total_unprocessed_payroll > $n->getItem()) {
            //Update when there's new notification
            $n->setItem($total_unprocessed_payroll);
            $n->setStatus(G_Notifications::STATUS_NEW);  
            $has_update = true;
        }else if($total_unprocessed_payroll < $n->getItem()){
            //Update when the item was decreased
            $n->setItem($total_unprocessed_payroll);
            $has_update = true;
        }         

        $description = ($n->getItem() > 1 ? 'Employees with unprocessed payroll, please do check the employee status, schedule, dtr and timesheet' : 'Employee with unprocessed payroll, please do check the employee status, schedule, dtr and timesheet');
        $n->setDescription($description);

        if($has_update) {
            $n->setDateModified(date('Y-m-d H:i:s'));
            $n->save();
        }             

        //Birthday Today
        $has_update = false;
        $n = G_Notifications_Finder::findByEventType($event_type_arr['BIRTHDAY_TODAY']);
        if(!$n) {
            $n = new G_Notifications();
            $n->setEventType($event_type_arr['BIRTHDAY_TODAY']);
            $n->setStatus(G_Notifications::STATUS_NEW); 
            $n->setDateCreated(date('Y-m-d H:i:s'));
            $n->setItem(0);
            $has_update = true;
        }

        $i_total_birthday_today = G_Employee_Helper::countEmployeeWithBirthdayToday();

        if($i_total_birthday_today > $n->getItem()) {
            //Update when there's new notification
            $n->setItem($i_total_birthday_today);
            $n->setStatus(G_Notifications::STATUS_NEW);  
            $has_update = true;
        }else if($i_total_birthday_today < $n->getItem()){
            //Update when the item was decreased
            $n->setItem($i_total_birthday_today);
            $has_update = true;
        }

        $description = ($n->getItem() > 1 ? 'Employees with birthday today.' : 'Employee with birthday today.');
        $n->setDescription($description);

        if($has_update) {
            $n->setDateModified(date('Y-m-d H:i:s'));
            $n->save();
        }

        $delete_null = G_Notifications_Finder::findByEventTypeNull();

        if($delete_null) {
            $delete_null->delete();
        } 



          //Evaluation Today
        $has_update = false;
        $n = G_Notifications_Finder::findByEventType($event_type_arr['EVALUATION_TODAY']);
        if(!$n) {
            $n = new G_Notifications();
            $n->setEventType($event_type_arr['EVALUATION_TODAY']);
            $n->setStatus(G_Notifications::STATUS_NEW); 
            $n->setDateCreated(date('Y-m-d H:i:s'));
            $n->setItem(0);
            $has_update = true;
        }

        $i_total_evaluation_today = G_Employee_Evaluation_Helper::countEmployeeWithEvaluationToday();

        if($i_total_evaluation_today > $n->getItem()) {
            //Update when there's new notification
            $n->setItem($i_total_evaluation_today);
            $n->setStatus(G_Notifications::STATUS_NEW);  
            $has_update = true;
        }else if($i_total_evaluation_today < $n->getItem()){
            //Update when the item was decreased
            $n->setItem($i_total_evaluation_today);
            $has_update = true;
        }

        $description = ($n->getItem() > 1 ? 'Employees with scheduled evaluation today.' : 'Employee with scheduled evaluation today.');
        $n->setDescription($description);

        if($has_update) {
            $n->setDateModified(date('Y-m-d H:i:s'));
            $n->save();
        }

        $delete_null = G_Notifications_Finder::findByEventTypeNull();

        if($delete_null) {
            $delete_null->delete();
        }  


        //Upcoming Evaluation 
        $has_update = false;
        $n = G_Notifications_Finder::findByEventType($event_type_arr['UPCOMING_EVALUATION']);
        if(!$n) {
            $n = new G_Notifications();
            $n->setEventType($event_type_arr['UPCOMING_EVALUATION']);
            $n->setStatus(G_Notifications::STATUS_NEW); 
            $n->setDateCreated(date('Y-m-d H:i:s'));
            $n->setItem(0);
            $has_update = true;
        }

        $i_total_upcoming_evaluation = G_Employee_Evaluation_Helper::countEmployeeWithUpcomingEvaluation();

        if($i_total_upcoming_evaluation > $n->getItem()) {
            //Update when there's new notification
            $n->setItem($i_total_upcoming_evaluation);
            $n->setStatus(G_Notifications::STATUS_NEW);  
            $has_update = true;
        }else if($i_total_upcoming_evaluation < $n->getItem()){
            //Update when the item was decreased
            $n->setItem($i_total_upcoming_evaluation);
            $has_update = true;
        }

        $description = ($n->getItem() > 1 ? 'Employees with upcoming scheduled for evaluation.' : 'Employee with upcoming scheduled for evaluation.');
        $n->setDescription($description);

        if($has_update) {
            $n->setDateModified(date('Y-m-d H:i:s'));
            $n->save();
        }

        $delete_null = G_Notifications_Finder::findByEventTypeNull();

        if($delete_null) {
            $delete_null->delete();
        }           
         


    }

    public static function getNotificationItems(G_Notifications $n) {
        $data = null;
        if($n) {
            $event_type_array = $n->getEventTypeArray();
            if($n->getEventType() == $event_type_array['INCOMPLETE_REQUIREMENTS']){
                $data = G_Employee_Requirements_Helper::getEmployeeIncompleteRequirements();
            }elseif($n->getEventType() == $event_type_array['NO_SALARY_RATE']) {
                $data = G_Employee_Basic_Salary_History_Helper::getEmployeeNoSalaryRate();
            }elseif($n->getEventType() == $event_type_array['END_OF_CONTRACT']) {
                $data = G_Employee_Helper::getEmployeeEndOfContractByCurrentMonth();
            }elseif($n->getEventType() == $event_type_array['NO_DEPARTMENT']) {
                $data = G_Employee_Subdivision_History_Helper::getEmployeeNoDepartment();
            }elseif($n->getEventType() == $event_type_array['NO_JOB_TITLE']) {
                $data = G_Employee_Job_History_Helper::getEmployeeNoJobTitle();
            }elseif($n->getEventType() == $event_type_array['NO_EMPLOYMENT_STATUS']) {
                $data = G_Employee_Job_History_Helper::getEmployeeNoEmploymentStatus();
            }elseif($n->getEventType() == $event_type_array['NO_EMPLOYEE_STATUS']) {
                $data = G_Employee_Helper::getEmployeeNoEmployeeStatus();
            }elseif($n->getEventType() == $event_type_array['TARDINESS']) {
                $data = G_Employee_Helper::getEmployeeTardinessByCurrentDate();
            }elseif($n->getEventType() == $event_type_array['INCOMPLETE_DTR']) {
                $query['date_from'] = date("Y-m-01");
                $query['date_to']   = date("Y-m-t");
                $query['remark']    = 'all';
                $data = G_Employee_Helper::getIncompleteTimeInOutData($query);
            }elseif($n->getEventType() == $event_type_array['EMPLOYEE_WITH_NO_SCHEDULE']) {
                $data = G_Schedule_Group_Helper::getEmployeeWithNoSchedule();
            }elseif($n->getEventType() == $event_type_array['UNDERTIME']){
                $s_from = date("Y-m-d",strtotime("-1 day"));
                $s_to   = date("Y-m-d");
                $data = G_Attendance_Helper::sqlEmployeesWithUndertimeByFromAndToDate($s_from, $s_to);
            /*}elseif($n->getEventType() == $event_type_array['EARLY_TIME_IN']){
                $s_from = date("Y-m-d",strtotime("-1 day"));
                $s_to   = date("Y-m-d");
                $data = G_Attendance_Helper::sqlEmployeesWithEarlyInByFromAndToDate($s_from, $s_to);*/
            }elseif($n->getEventType() == $event_type_array['LEAVE_ADDED']){
                $current_year = date("Y");
                $data = G_Employee_Leave_Credit_History_Helper::getAllLeaveCreditHistoryByYear($current_year);
            }elseif($n->getEventType() == $event_type_array['NO_BANK_ACCOUNT']){                
                $data = G_Employee_Helper::employeeWithNoBankAccount();
            }
            elseif($n->getEventType() == $event_type_array['EVALUATION_TODAY']){                
              $data =  G_Employee_Evaluation_Helper::getmployeeWithEvaluationToday();
            }
             elseif($n->getEventType() == $event_type_array['UPCOMING_EVALUATION']){                
              $data =  G_Employee_Evaluation_Helper::getmployeeWithUpcomingEvaluation();
            }
            elseif($n->getEventType() == $event_type_array['END_OF_CONTRACT_PROB']){                
                $data =  G_Employee_Helper::employeeEndOfContractProbi30Days();
                }
        }

        return $data;     
    }
                
}
?>