<?php
/*
    Usage:
    $e = G_Employee_Finder::findByEmployeeCode(2007);
    $o = new G_Overtime;
    $o->setDate('2012-09-10');
    $o->setTimeIn('18:00:00');
    $o->setTimeOut('23:00:00');
    $o->setEmployeeId($e->getId());
    $o->save();
*/
class G_Overtime extends Overtime {
    const STATUS_PENDING = 'Pending';
    const STATUS_APPROVED = 'Approved';
    const STATUS_DISAPPROVED = 'Disapproved';
    const ARCHIVED_NO = 'No';
    const ARCHIVED_YES = 'Yes';
    const AUTO_OVERTIME_DESCRIPTION = 'Auto Overtime';

    protected $id;
    protected $employee_id;
    protected $reason;
    protected $status;
    protected $date_created;
    protected $is_archived;

    protected $overtime_date_in;
    protected $overtime_date_out;
    protected $debug_mode = false;

    public function __construct() {
        $this->status = self::STATUS_APPROVED;
        $this->is_archived = self::ARCHIVED_NO;
    }

    protected function autoOvertimeSettings() {
        $settings = array(
            "step" => [],
            "start_step" => 0,
            "allowed_hr_start" => (float)MINIMUM_OVERTIME_MINS / 60,
            "max_allowed_ot_hrs" => 99
        );

        return $settings;
    }
    
    public function setId($value) {
        $this->id = $value;
    }

    public function getId() {
        return $this->id;
    }

    public function setStatus($value) {
        $this->status = $value;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setAsArchived() {
        $this->is_archived = self::ARCHIVED_YES;
    }

    public function setAsUnarchived() {
        $this->is_archived = self::ARCHIVED_NO;
    }

    public function getArchiveStatus() {
        return $this->is_archived;
    }

    public function isArchived() {
        if ($this->is_archived == self::ARCHIVED_YES) {
            return true;
        } else {
            return false;
        }
    }

    public function setReason($value) {
        $this->reason = $value; 
    }
    
    public function getReason() {
        return $this->reason;   
    }       
    
    public function setEmployeeId($value) {
        $this->employee_id = $value;    
    }
    
    public function getEmployeeId() {
        return $this->employee_id;  
    }

    public function getDateIn() {
    return $this->overtime_date_in;
}

    public function getDateOut() {
        return $this->overtime_date_out;
    }

    public function setDateIn($value) {
        $this->overtime_date_in = $value;
    }

    public function setDateOut($value) {
        $this->overtime_date_out = $value;
    }

    public function setDateCreated($value) {
        $this->date_created = $value;
    }

    public function getDateCreated() {
        return $this->date_created;
    }

    /*public function validateRequest() {
        $return = array();
        $return['message']  = '';
        $return['is_valid'] = 0;
        if( !empty($this->date) && !empty($this->time_in) && !empty($this->time_out) && !empty($this->employee_id) ){
            $e = G_Employee_Finder::findById(Utilities::decrypt($_POST['h_employee_id']));
            if( !empty($e) ){
                $overtime_date = $this->date;
                $a = G_Attendance_Finder::findByEmployeeAndDate($e, $overtime_date);
                if (!$a->isPresent()) {
                    $return['message']      = 'Error : Unable to file overtime request. ' . $e->getFirstName() .' '. $e->getLastName() . ' was absent on '. date('m/d/Y',strtotime($overtime_date));                    
                }else{

                }
            }
        } 

        return $return;
    }
    */

    public function autoOvertime($data = array(), $break_schedules = array() ) {
        $auto_overtime['has_auto_overtime'] = false;
        $auto_overtime['ot_hours']          = 0;
        $auto_overtime['ot_excess_hours']   = 0;
        $auto_overtime['ot_nd']             = 0;
        $auto_overtime['ot_excess_nd']      = 0;

        $settings = self::autoOvertimeSettings();           
        $time_in  = date("H:i:s",strtotime($data['time_in']));
        $time_out = date("H:i:s",strtotime($data['time_out'])); 
        $date_in  = date("Y-m-d",strtotime($data['date_in']));
        $date_out = date("Y-m-d",strtotime($data['date_out']));

        $schedule_date_in  = date("Y-m-d", strtotime($data['schedule_date_in']));
        $schedule_date_out = date("Y-m-d", strtotime($data['schedule_date_out']));
        $schedule_time_in  = date("H:i:s", strtotime($data['schedule_time_in']));
        $schedule_time_out = date("H:i:s", strtotime($data['schedule_time_out']));

        $hours_difference       = Tools::computeHoursDifferenceByDateTime($schedule_time_out, $time_out);   
        $settings_allowed_start = $settings['allowed_hr_start'];
        $settings_ot_step_count = $settings['step'];
        $settings_max_allowed_hrs = $settings['max_allowed_ot_hrs'];
        $settings_start_step      = $settings['start_step'];

        $date_1 = strtotime($schedule_date_out . " " . $schedule_time_out);
        $date_2 = strtotime($date_out . " " . $time_out);        

        if( $hours_difference >= $settings_allowed_start && $date_1 <= $date_2 ){
            $time_part = explode(":", $time_out);                            

            if( $time_part[1] < $settings_start_step ){
                $time_part[1] = "00";
            }else{
                foreach( $settings_ot_step_count as $key => $step ){
                    if( $step > $time_part[1] ){                                                                            
                        $time_part[1] = $settings_ot_step_count[$key - 1];                                        
                        break;
                    }elseif( $step == $time_part['1'] ){
                        $time_part[1] = $step;                                       
                        break;
                    }
                } 
            }

            $new_time_out = implode(":", $time_part);
            $overtime_date_time_in  = $date_out . " " . $schedule_time_out;
            $overtime_date_time_out = $date_out . " " . $new_time_out;
            $schedule_date_time_in  = $schedule_date_in . " " . $schedule_time_in;
            $schedule_date_time_out = $schedule_date_out . " " . $schedule_time_out;

            $o = new G_Overtime_Calculator_New($overtime_date_time_in, $overtime_date_time_out);
            $o->setScheduleDateTime($schedule_date_time_in, $schedule_date_time_out);

            $auto_overtime['ot_hours']        = $o->computeHours();
            $auto_overtime['ot_excess_hours'] = $o->computeExcessHours();
            $auto_overtime['ot_nd']           = $o->computeNightDiff();
            $auto_overtime['ot_excess_nd']    = $o->computeExcessNightDiff();  

            if( !empty($break_schedules) ){ 
                foreach( $break_schedules as $b_schedule ){
                    $a_schedule = explode("to",$b_schedule);                   
                 
                    if( strtotime($time_out) >= strtotime($a_schedule[1]) ){
                        $break_schedule_in  = date("H:i:s",strtotime($a_schedule[0]));
                        $break_schedule_out = date("H:i:s",strtotime($a_schedule[1]));      
                        $hours_difference = Tools::computeHoursDifference($break_schedule_in, $break_schedule_out); 
                        if( $hours_difference <= $auto_overtime['ot_nd'] ){
                            //$auto_overtime['ot_nd'] = $auto_overtime['ot_nd'] - $hours_difference;
                        }
                    }

                }               
            }

            $auto_overtime['has_auto_overtime'] = true; 

            if( $this->debug_mode ){
                echo "Date 1 : {$date_1} / Date 2 : {$date_2}";
            }
        }  

        return $auto_overtime;
    }

    public function autoFileRequest( IEmployee $e, G_Attendance $a ) {
        $return['is_success'] = false;
        $return['message']    = 'Cannot create entry';           
        if( !empty($e) && !empty($a) ){  

            $t = $a->getTimesheet();   
            if( $t ){
                //Utilities::displayArray($t);       
                $schedule_date_in  = $t->getScheduledDateIn();
                $schedule_time_in  = $t->getScheduledTimeIn();
                $schedule_date_out = $t->getScheduledDateOut();                        
                $schedule_time_out = $t->getScheduledTimeOut();
                $time_in           = $t->getTimeIn();
                $time_out          = $t->getTimeOut();
                $date_in           = $t->getDateIn();
                $date_out          = $t->getDateOut();      

                /* Custom fixes for now */
                if($schedule_time_out == '04:59:59') {
                    $schedule_time_out = '05:00:00';
                }        

                //add 1hr to shedule timeout to deduct breaktime in overtime
               // $schedule_time_out = date("H:i:s", strtotime($schedule_time_out.'+1 hour'));

                $date_time_start    = "{$schedule_date_out} {$schedule_time_out}";
                $date_time_end      = "{$date_out} {$time_out}";  
                $attendance_date_time_start = "{$date_in} {$time_in}";  
                $this->date         = $schedule_date_in;              
                $this->employee_id  = $e->getId();
                //Need to delete auto overtime records / requests of the same date and refile request
                $fields = array("id");
                $is_with_previous_requests = G_Overtime_Helper::sqlCountTotalAutoOvertimeRequestsByEmployeeIdAndDate($this->employee_id, $schedule_date_in,$fields);                                      
                if( $is_with_previous_requests ){  
                    $fields = array("id,status");  
                    $auto_ot = G_Overtime_Helper::sqlGetEmployeeAutoOvertimeRequest($this->employee_id, $schedule_date_in,$fields);                          
                    if( $auto_ot['status'] == self::STATUS_DISAPPROVED || $auto_ot['status'] == self::STATUS_APPROVED ){
                        return $return;
                    }else{

                        //check if overtime has request
                        $r_type = G_Request::PREFIX_OVERTIME;
                        $check_request = G_Request_Finder::findByRequestorIdAndRequestIdAndRequestType($this->employee_id,$auto_ot['id'],$r_type);

                        if($check_request){
                            foreach($check_request as $c){
                                G_Request_Manager::delete($c);
                            }
                            
                        }

                        $this->deleteAutoOvertimePendingRequestByEmployeeAndDate();
                    }                            
                }  

                $hours_difference = Tools::computeHoursDifference($date_time_start,$attendance_date_time_start);
                //echo "Hours Difference : {$hours_difference} / Attendance Date Time Start : {$attendance_date_time_start} / Schedule Out : {$date_time_start} <br />";

                if( strtotime($date_time_start) < strtotime($date_time_end) && $hours_difference > 2 ){                    

                    $late_hrs      = $t->getLateHours();
                    $undertime_hrs = $t->getUndertimeHours();

                    $hours_difference = Tools::computeHoursDifference($date_time_start, $date_time_end); 
                    //echo "Hours Difference : {$hours_difference}";

                    $settings = self::autoOvertimeSettings(); 
                    $settings_allowed_start = $settings['allowed_hr_start'];
                    $settings_ot_step_count = $settings['step'];
                    $settings_max_allowed_hrs = $settings['max_allowed_ot_hrs'];
                    $settings_start_step      = $settings['start_step'];   
                
                    if( $hours_difference >= $settings_allowed_start ){                        
                        $time_part = explode(":", $time_out);    
                        //Utilities::displayArray($time_part);
                        //Artnature
                        $dynamic_fields = $e->getDynamicFields();
                        $is_agency      = false;
                        $agency_title   = "employee category";
                        $agency_value   = "agency";

                        foreach($dynamic_fields as $field){
                            $field_name  = trim(strtolower($field['title']));
                            $field_value = trim(strtolower($field['value'])); 

                            if( $field_value == $agency_value ){                            
                                $is_agency = true;
                                break;
                            }
                        }
                       
                       /*
                        $custom_limit = 1.5;                        
                        if( $hours_difference > $custom_limit ){                                                
                            $settings_start_step    = 15;
                            $settings_ot_step_count = array(00,15,30,45,60);
                        }

                        if( $is_agency ){
                            $settings_start_step    = 30;
                            $settings_ot_step_count = array(00,30,60);
                        }
                        */
                        //Utilities::displayArray($settings_ot_step_count);
                        $is_art_nature    = false;
                        $hours_difference = Tools::computeHoursDifference($date_time_start, $date_time_end); //Validation for 3hrs       
                        //echo "Start Time : {$date_time_start} / End Time : {$date_time_end} / Hours Diff : {$hours_difference} <br />";           
                        //End Artnature

                        foreach( $settings_ot_step_count as $key => $step ){
                            if( $is_art_nature ){ //Custom for artnature 2nd reading 30mins count - Custom for artnature
                                if( $hours_difference >= 1 && $hours_difference <= 1.5 ){
                                    if( $time_part[1] >= 30 ){ 
                                        $time_part[1] = '30';   
                                    }else{
                                        $time_part[1] = '00';  
                                    }
                                }else{
                                    if( $step > $time_part[1] ){                                                                            
                                        $i_min_value = $settings_ot_step_count[$key - 1];
                                        if($i_min_value <= 0 || $i_min_value == ''){
                                            $time_part[1] = '00';    
                                        }else{
                                            $time_part[1] = $settings_ot_step_count[$key - 1];                                        
                                        }
                                        
                                        break;
                                    }elseif( $step == $time_part[1] ){
                                        $i_min_value = $settings_ot_step_count[$key];
                                        if($i_min_value <= 0 || $i_min_value == ''){
                                            $time_part[1] = '00';    
                                        }else{
                                            $time_part[1] = $settings_ot_step_count[$key];                                        
                                        }
                                    }
                                }
                            }else{
                                if( $step > $time_part[1] ){                                                                            
                                    $i_min_value = $settings_ot_step_count[$key - 1];
                                    if($i_min_value <= 0 || $i_min_value == ''){
                                        $time_part[1] = '00';    
                                    }else{
                                        $time_part[1] = $settings_ot_step_count[$key - 1];                                        
                                    }
                                    
                                    break;
                                }elseif( $step == $time_part[1] ){
                                    $i_min_value = $settings_ot_step_count[$key];
                                    if($i_min_value <= 0 || $i_min_value == ''){
                                        $time_part[1] = '00';    
                                    }else{
                                        $time_part[1] = $settings_ot_step_count[$key];                                        
                                    }
                                }
                            }
                        } 

                        //Utilities::displayArray($time_part);
                        $new_time_out = implode(":", $time_part);
                        $new_time_out = date("H:i:s",strtotime($new_time_out));
                        //echo "Date Out : {$date_out} / Time Out : {$time_out} / New Time Out : {$new_time_out} <br/>";

                        $overtime = G_Overtime_Finder::findByEmployeeIdAndDate($e->getId(), $schedule_date_in);
                        if( empty($overtime) ){
                            $overtime = new G_Overtime();
                            $overtime->setEmployeeId($this->employee_id);
                        }

                        $ot_remarks     = self::AUTO_OVERTIME_DESCRIPTION;
                        $default_status = self::STATUS_PENDING;
                        // $default_status = self::STATUS_APPROVED;
                        
                        $this->time_in            = $schedule_time_out;
                        $this->time_out           = $new_time_out;
                        $this->overtime_date_in   = $schedule_date_out;
                        $this->overtime_date_out  = $date_out;
                        $this->reason             = $ot_remarks;
                        $this->status             = $default_status;
                        $this->date_created       = date("Y-m-d H:i:s");

                        //Utilities::displayArray($this);

                        if( strtotime($this->overtime_date_in) <= strtotime($this->overtime_date_out) ){
                            //Create new request                            
                            $request_id = $this->save();

                            //new
                            //create new g_request for new OT
                             $employee_id = $e->getId();
                             $gra = new G_Request_Approver();
                             $gra->setEmployeeId($employee_id);
                             $approvers = $gra->getEmployeeRequestApprovers();

                             
                             if($approvers){

                                foreach($approvers as $level => $approver){
                                    
                                    $counter = 0;

                                    foreach($approver as $key => $value) {
                                        if($counter < 1){
                                            $approver_id[] = Utilities::encrypt($value['employee_id']);
                                        }

                                        $counter++;
                                    }
                                }

                                $request_type = G_Request::PREFIX_OVERTIME;
                                $request_status = G_Overtime::STATUS_PENDING;

                                $r = new G_Request();
                                $r->setRequestorEmployeeId($employee_id);
                                $r->setRequestId($request_id);
                                $r->setRequestType($request_type);
                                $r->setStatus($request_status);
                                $r->saveEmployeeRequest($approver_id); //Save request approvers

                             }



                            $new_ot_start = "{$schedule_date_out} {$schedule_time_out}";
                            $new_ot_end   = "{$date_out} {$new_time_out}";
                            $schedule_date_time_in  = "{$schedule_date_out} {$schedule_time_in}";
                            $schedule_date_time_out = "{$schedule_date_out} {$schedule_time_out}"; 
                            //echo "OT Start : {$new_ot_start} / OT End : {$new_ot_end} / Schedule In : {$schedule_date_time_in} / Schedule Out : {$schedule_date_time_out}";
                                                 
                            $o = new G_Overtime_Calculator_New($new_ot_start, $new_ot_end);
                            $o->setScheduleDateTime($schedule_date_time_in, $schedule_date_time_out);

                            $return['ot_details']['total_ot_hrs']    = $o->computeHours();
                            $return['ot_details']['ot_excess_hours'] = $o->computeExcessHours();
                            $return['ot_details']['ot_nd']           = $o->computeNightDiff();
                            $return['ot_details']['ot_excess_nd']    = $o->computeExcessNightDiff();

                            $return['ot_details']['overtime_date_in']  = $schedule_date_out;
                            $return['ot_details']['overtime_date_out'] = $date_out;
                            $return['ot_details']['overtime_in']  = $schedule_time_out;
                            $return['ot_details']['overtime_out'] = $new_time_out;
                            //$return['ot_details']['total_ot_hrs'] = Tools::computeHoursDifference($new_ot_start, $new_ot_end); 
                           /* print_r($return);
                            exit;*/
                            $return['is_success']   = true;
                            $return['message']      = 'Record saved';
                            
                        }
                    }
                }
            }              
        }

        return $return;
    }

    public function save() {
        return G_Overtime_Manager::save($this); 
    }

    public function deleteAutoOvertimePendingRequestByEmployeeAndDate() {
        if( $this->employee_id > 0 && !empty($this->date) ){
            G_Overtime_Manager::deleteAutoOvertimeRequestByEmployeeAndDate($this->employee_id, $this->date);
        }
    }
    
    public function delete() {
        return G_Overtime_Manager::delete($this);   
    }

    public function approve() {
        G_Overtime_Helper::approve($this);
    }
}
?>