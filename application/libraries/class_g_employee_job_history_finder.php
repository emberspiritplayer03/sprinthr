<?php
class G_Employee_Job_History_Finder {
	
	public static function findById($id) {
		$sql = "
			SELECT 
			*

			FROM ". G_EMPLOYEE_JOB_HISTORY ." e
			WHERE e.id = ". Model::safeSql($id) ."	
			LIMIT 1		
		";
		return self::getRecord($sql);
	}

	public static function findPresentHistoryById($id) {
		$sql = "
			SELECT 
			*

			FROM ". G_EMPLOYEE_JOB_HISTORY ." e
			WHERE e.employee_id = ". Model::safeSql($id) ."
			ORDER BY start_date DESC
			LIMIT 1	
					
		";
		return self::getRecord($sql);
	}
	public static function findAllById($id){
					$sql = "
			SELECT 
			*
			FROM ". G_EMPLOYEE_JOB_HISTORY ." e
			WHERE e.employee_id  = ". Model::safeSql($id) ." ORDER BY start_date DESC		
		";

		return self::getRecords($sql);
	}
	
	public static function findByTerminatedJob(G_Employee $e) {
		$sql = "
			SELECT 
			*
			FROM ". G_EMPLOYEE_JOB_HISTORY ." e
			WHERE e.employee_id = ". Model::safeSql($e->getId()) ."	
			AND employment_status like 'Terminated%'
			LIMIT 1		
		";
		return self::getRecord($sql);	
	}
	
	public static function findCurrentJob(G_Employee $e) {
		$sql = "
			SELECT 
			*
			FROM ". G_EMPLOYEE_JOB_HISTORY ." e
			WHERE e.employee_id = ". Model::safeSql($e->getId()) ."	
			AND end_date=''
			LIMIT 1		
		";
		//echo $sql;
		return self::getRecord($sql);	
	}

    //reset to active status
	public static function findCurrentJob2(G_Employee $e) {
		$sql = "
			SELECT 
			*
			FROM ". G_EMPLOYEE_JOB_HISTORY ." e
			WHERE e.employee_id = ". Model::safeSql($e->getId()) ."	
			ORDER BY e.id DESC
			LIMIT 1		
		";
		//echo $sql;
		return self::getRecord($sql);	
	}
	
	public static function findAllEmployeeByCurrentJob($job_id) {
		$sql = "
			SELECT 
			*
			FROM ". G_EMPLOYEE_JOB_HISTORY ." e
			WHERE 
			job_id = " . Model::safeSql($job_id) . "
			AND end_date=''	
		";
		return self::getRecords($sql);
	}

    public static function findByEmployeeIdStartDate($employee_id, $start_date) {
        $sql = "
			SELECT
			*
			FROM ". G_EMPLOYEE_JOB_HISTORY ." e
			WHERE e.employee_id = " . Model::safeSql($employee_id) . "
			AND e.start_date = ". Model::safeSql($start_date) ."
			LIMIT 1
		";
        return self::getRecord($sql);
    }
	
	public static function findByEmployeeAndDate($e, $date) {		
		$sql = "
			SELECT *
			FROM ". G_EMPLOYEE_JOB_HISTORY ." e
			WHERE ((". Model::safeSql($date) ." >= e.start_date AND (e.end_date = '0000-00-00' OR e.end_date = '')) OR (". Model::safeSql($date) ." >= e.start_date AND ". Model::safeSql($date) ." <= e.end_date))
			AND e.employee_id = ". Model::safeSql($e->getId()) ."
			AND e.start_date = 
				(
					SELECT es2.start_date 
					FROM ". G_EMPLOYEE_JOB_HISTORY ." es2
					WHERE es2.start_date <= ". Model::safeSql($date) ."
					AND es2.employee_id = ". Model::safeSql($e->getId()) ."
					ORDER BY es2.start_date DESC
					LIMIT 1
				)
			ORDER BY e.start_date DESC
			LIMIT 1
		";
		return self::getRecord($sql);		
	}
	
	public static function findByEmployeeId($employee_id) {
		$sql = "
			SELECT 
				*

			FROM ". G_EMPLOYEE_JOB_HISTORY ." e
			WHERE e.employee_id = ". Model::safeSql($employee_id) ."	
			ORDER BY e.start_date desc
		";

		return self::getRecords($sql);
	}
	
	private static function getRecord($sql) {
		$result = Model::runSql($sql);
		$total = mysql_num_rows($result);
		if ($total == 0) {
			return false;	
		}		
		$row = Model::fetchAssoc($result);
		$records = self::newObject($row);	
		return $records;
	}
	
	private static function getRecords($sql) {
		$result = Model::runSql($sql);
		$total = mysql_num_rows($result);
		if ($total == 0) {
			return false;	
		}
		while ($row = Model::fetchAssoc($result)) {
			$records[$row['id']] = self::newObject($row);
		}
		return $records;
	}
	
	private static function newObject($row) {
		
		$e = new G_Employee_Job_History;
		$e->setId($row['id']);
		$e->setEmployeeId($row['employee_id']);
		$e->setJobId($row['job_id']);
		$e->setName($row['name']);
		$e->setEmploymentStatus($row['employment_status']);
		$e->setStartDate($row['start_date']);
		$e->setEndDate($row['end_date']);

		return $e;
	}
}
?>