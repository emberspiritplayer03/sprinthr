<?php
class G_Employee_Activities_Helper {

    public static function create($employee_id, $activity_category_id, $activity_skills_id, $date, $time_in, $date_out, $time_out, $reason = '',$project_site_name,$project_site_id) {
        $employee_activity = new G_Employee_Activities;
        $employee_activity->setEmployeeId($employee_id);
        $employee_activity->setActivityCategoryId($activity_category_id);
        $employee_activity->setActivitySkillsId($activity_skills_id);
        $employee_activity->setDate($date);
        $employee_activity->setTimeIn($time_in);
        $employee_activity->setDateOut($date_out);
        $employee_activity->setTimeOut($time_out);
        $employee_activity->setDateCreated(date('Y-m-d'));
        $employee_activity->setReason($reason);
        $employee_activity->setProjectSiteId($project_site_id);
        $employee_activity->setProjectSiteName($project_site_name);

        return $employee_activity;
    }

    public function compareActivityToDTR($employee_id, $activity_date_time_in, $activity_date_time_out) {
        $date = date("Y-m-d", strtotime($activity_date_time_in));



        $return['message'] = '';
        $return['is_invalid'] = false;

        $dtr = G_Fp_Attendance_Logs_Helper::sqlGetPairedLogsByEmployeeIdDate($employee_id, $date);

        if (count($dtr) > 0) {
            $dtr_date_time_in = $dtr['in']['date'] . ' ' . $dtr['in']['time'];
            $dtr_date_time_out = $dtr['out']['date'] . ' ' . $dtr['out']['time'];

            if ($activity_date_time_in >= $dtr_date_time_in && $activity_date_time_in <= $dtr_date_time_out && $activity_date_time_out >= $dtr_date_time_in && $activity_date_time_out <= $dtr_date_time_out) {
                if ($activity_date_time_in != $dtr_date_time_in || $activity_date_time_out != $dtr_date_time_out) {
                    $return['message'] = "Employee logs doesn't match to the filed activity. To fix, you can either proceed filing this activity without any changes in hours or adjust the Activity hours to be equal on the employee logs <br> Do you want to proceed?";
                }
            }
            else {
                $return['message'] = "Activity hours should be equal to employee's log. To fix, you can either adjust the logs of employee or change the employee activity hours";
                $return['is_invalid'] = true;
            }
        }
        else {
            $return['message'] = 'No dtr logs match.';
            $return['is_invalid'] = true;
        }

        return $return;
    }

    public function getEmployeeActivities($from, $to, $qry_add_on = array(),$employee_type, $frequency_id){
       
            if( !empty($qry_add_on) && $this->s_employee_type != "" ){
                $s_query .= " AND " . implode(" AND ", $qry_add_on);
            }elseif( !empty($qry_add_on) ){
                $s_query .= implode(" AND ", $qry_add_on);
            }
        $activities = array();
        $employee_activities_count = array();
 
         $sql = "
            SELECT 
                gea.id,
                ge.employee_code,
                ge.lastname,
                ge.firstname,
                gea.employee_id,
                gea.project_site,
                gea.activity_skills_id,
                gea.activity_category_id,
                gea.date,
                gea.time_in,
                gea.date_out,
                gea.time_out,
                geatt.scheduled_date_in,
                geatt.scheduled_time_in,
                geatt.scheduled_date_out,
                geatt.scheduled_time_out

            from g_employee_activities gea
            LEFT join g_employee e on e.id = gea.employee_id
            LEFT JOIN g_employee ge on gea.employee_id = ge.id
            LEFT JOIN g_employee_attendance geatt ON geatt.employee_id = gea.employee_id


            WHERE gea.date BETWEEN '".$from."' AND '".$to."'
            AND gea.date = geatt.scheduled_date_in
            AND e.frequency_id = ".$frequency_id."
          
            AND {$s_query}


        ";
       //   AND e.id = 1022
       
        $result = Model::runSql($sql,true); 

        if (count($result) > 0) {
            foreach ($result as $key => $data) {
                $new_data       = true;
                $key            = $data['employee_id'] . '_' . $data['activity_category_id'] . '_' . $data['activity_skills_id'];
                $scheduleIn     = date('Y-m-d H:i:s', strtotime($data['scheduled_date_in'] . ' ' . $data['scheduled_time_in']));
                $scheduleOut    = date('Y-m-d H:i:s', strtotime($data['scheduled_date_out'] . ' ' . $data['scheduled_time_out']));
                $activityIn     = date('Y-m-d H:i:s', strtotime($data['date'] . ' ' . $data['time_in']));
                $activityOut    = date('Y-m-d H:i:s', strtotime($data['date_out'] . ' ' . $data['time_out']));
                $ot_hrs   = 0;

                if ($activityOut > $scheduleOut) {
                    if ($activityIn > $scheduleOut) {
                        $ot_hrs = Tools::computeHoursDifferenceByDateTime($activityOut, $activityIn);
                    }
                    else {
                        $ot_hrs = Tools::computeHoursDifferenceByDateTime($activityOut, $scheduleOut);
                    }
                }

                if (isset($activities[$key])) {
                    if (
                        $activities[$key]['employee_id'] == $data['employee_id'] && 
                        $activities[$key]['activity_category_id'] == $data['activity_category_id'] && 
                        $activities[$key]['activity_skills_id'] == $data['activity_skills_id']
                    ) {
                        $new_data = false;
                    }
                }

                if ($new_data) {
                    $activities[$key] = array(
                        'id'                    => $data['id'],
                        'firstname'             => $data['firstname'],
                        'lastname'              => $data['lastname'],
                        'employee_code'         => $data['employee_code'],
                        'employee_id'           => $data['employee_id'],
                        'project_site'          => $data['project_site'],
                        'activity_skills_id'    => $data['activity_skills_id'],
                        'activity_category_id'  => $data['activity_category_id'],
                        'total_ot_hrs'          => $ot_hrs,
                        'days'                  => array(
                            $data['date']       => array()
                        )
                    );

                    if (isset($employee_activities_count[$data['employee_id']])) {
                        $employee_activities_count[$data['employee_id']]++;
                    }
                    else {
                        $employee_activities_count[$data['employee_id']] = 1;
                    }
                }
                else {
                    $activities[$key]['total_ot_hrs'] = $activities[$key]['total_ot_hrs'] + $ot_hrs;
                }
                
                $activities[$key]['days'][$data['date']] = array(
                    'date_in'               => $data['date'],
                    'time_in'               => $data['time_in'],
                    'date_out'              => $data['date_out'],
                    'time_out'              => $data['time_out'],
                    'scheduled_date_in'     => $data['scheduled_date_in'],
                    'scheduled_time_in'     => $data['scheduled_time_in'],
                    'scheduled_date_out'    => $data['scheduled_date_out'],
                    'scheduled_time_out'    => $data['scheduled_time_out'],
                    'ot_hrs'                => $ot_hrs
                );
                
            }
        }

        return array(
            'activities' => $activities,
            'employee_activities_count' => $employee_activities_count
        );    

    }

}
?>