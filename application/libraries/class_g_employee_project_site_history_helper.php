<?php
class G_Employee_Project_Site_History_Helper {

    /*
     * Ends the job history of an employee
     */


    public static function isIdExist(G_Employee_Job_History $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM g_employee_project_site_history
			WHERE id = ". Model::safeSql($e->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}


	
    public static function ended(IEmployee $e, $date_ended) {
        $jh = G_Employee_Job_History_Finder::findCurrentJob($e);
        if ($jh) {
            $jh->setEndDate($date_ended);
            $jh->save();
        }
    }


    public static function reset($e, $date_ended) {
        $jh = G_Employee_Job_History_Finder::findPresentHistoryById($e);
        if ($jh) {
            $jh->setEndDate($date_ended);
            $jh->save();
        }
    }

    public static function generate($employee_id, $job_id, $title, $employment_status, $start_date, $end_date = '') {
        $e = new G_Employee_Job_History;
        $e->setEmployeeId($employee_id);
        $e->setJobId($job_id);
        $e->setName($title);
        $e->setEmploymentStatus($employment_status);
        $e->setStartDate($start_date);
        $e->setEndDate($end_date);
        return $e;
    }

	

	public static function getTotalEmployeeStatusByDateRange($date_start,$date_end,$company_structure_id) {
		$date_start = date('Y-m',strtotime($date_start));
		$date_end   = date('Y-m',strtotime($date_end . "+1 month"));
		//SQL Sum String Generator
		$status  = G_Settings_Employment_Status_Finder::findByCompanyStructureId($company_structure_id);
		$count   = G_Settings_Employment_Status_Helper::countTotalRecordsByCompanyStructureId($company_structure_id);
		$counter = 1;
		if($status){
			$sql_sum = ',';
			foreach($status as $s){
				$tag = str_replace(" ","_",strtolower($s->getStatus())) . '_tag';
				//$tag = $s->getStatus();
			 	$sql_sum .= "SUM( IF( employment_status ='" . $s->getStatus() ."',1,0)) AS " . $tag;
				if($counter < $count){
					$sql_sum .= ',';
				}
				$counter++;
			}
		}else{
			$sql_sum = '';
		}

		$sql = "
		SELECT YEAR( start_date ) AS year, MONTH( start_date ) AS
		month " . $sql_sum . "
		FROM " . G_EMPLOYEE_JOB_HISTORY . "
		WHERE start_date >= " . Model::safeSql($date_start) . " AND start_date <= " . Model::safeSql($date_end) . " AND end_date = ''
		GROUP BY year,MONTH
		asc";
		//echo $sql;
		$result = Model::runSql($sql,true);
		return $result;
	}

	public static  function countTotalHistoryByEmployeeId($employee_id)
	{
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_JOB_HISTORY ."
			WHERE employee_id = ". Model::safeSql($employee_id) ."
			ORDER BY end_date
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];

	}

	public static function getAllEmployeeIdByJobIdConcatString($job_id) {
		$positions = G_Employee_Job_History_Finder::findAllEmployeeByCurrentJob($job_id);
		foreach($positions as $position):
			$id[] = $position->getEmployeeId();
		endforeach;

		if($id) {
			$string = " ea.employee_id = ".implode(" OR ea.employee_id = ",$id);
		}
		return $string;
	}

	public static function getCurrentJobAndStatusByEmployeeId($employee_id) {
		$sql = "
			SELECT id,name,employment_status
			FROM " . G_EMPLOYEE_JOB_HISTORY ."
			WHERE
				employee_id = ". Model::safeSql($employee_id) ." AND
				end_date = ''
		";

		return Model::runSql($sql,true);
	}

	public static function getEmployeeNoJobTitle() {
        $sql = "
            SELECT e.id, CONCAT(e.lastname , ', ' , e.firstname) as employee_name, e.hired_date, esh.name as department_name
            FROM " . EMPLOYEE . " e
            LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id
            WHERE e.id NOT
                IN (
                    SELECT employee_id
                    FROM " . G_EMPLOYEE_JOB_HISTORY . "
                    WHERE end_date >= IF( end_date = '', '', NOW( ) )
                )
        ";

        $result = Model::runSql($sql,true);
		return $result;
    }

	public static function countEmployeeNoJobTitle() {
        $sql = "
            SELECT COUNT(e.id) as total
            FROM " . EMPLOYEE . " e
            WHERE e.id NOT
                IN (
                    SELECT employee_id
                    FROM " . G_EMPLOYEE_JOB_HISTORY . "
                    WHERE end_date >= IF( end_date = '', '', '' )
                )
        ";

        $result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
    }

    public static function getEmployeeNoEmploymentStatus() {
        $sql = "
            SELECT e.id, CONCAT(e.lastname , ', ' , e.firstname) as employee_name, e.hired_date, esh.name as department_name
            FROM " . EMPLOYEE . " e
            LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id
            WHERE e.id NOT
                IN (
                    SELECT employee_id
                    FROM " . G_EMPLOYEE_JOB_HISTORY . "
                    WHERE employment_status <> ''
                )
        ";

        $result = Model::runSql($sql,true);
		return $result;
    }

    public static function countEmployeeNoEmploymentStatus() {
        $sql = "
            SELECT COUNT(e.id) as total
            FROM " . EMPLOYEE . " e
            WHERE e.id NOT
                IN (
                    SELECT employee_id
                    FROM " . G_EMPLOYEE_JOB_HISTORY . "
                    WHERE employment_status <> ''
                )
        ";

        $result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
    }

}
?>
