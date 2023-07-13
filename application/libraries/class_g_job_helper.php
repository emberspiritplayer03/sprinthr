<?php
class G_Job_Helper {
    public static function generate($company_structure_id, $position_name) {
        $j = new G_Job;
        $j->setCompanyStructureId($company_structure_id);
        $j->setTitle($position_name);
        $j->setIsActive(G_Job::ACTIVE);
        return $j;
    }

	public static function isIdExist(G_Job $g) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_JOB ."
			WHERE id = ". Model::safeSql($g->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecords() {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_JOB			
		;
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecordsByCompanyStructureId(G_Company_Structure $gcs) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_JOB ."
			WHERE company_structure_id = ". Model::safeSql($gcs->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function isEmployeeJobHistoryExist(G_Employee $e, G_Job $j, $start_date, $end_date) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_JOB_HISTORY ."
			WHERE employee_id = ". Model::safeSql($e->getId()) ." 
			AND job_id = ".Model::safeSql($j->getId())." AND title = ".Model::safeSql($j->getTitle())." AND start_date = ". Model::safeSql($start_date) ." AND end_date = ".Model::safeSql($end_date)."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function sqlFindByCompanyStructureId($csid, $order_by = '', $limit = '') {
		
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "  
			SELECT g_job.id, g_job.company_structure_id, g_job.job_specification_id , g_job.title ,g_job.is_active,
			 g_job_specification.name, g_job_specification.description,g_job_specification.duties 
			FROM g_job
			LEFT JOIN g_job_specification
			ON g_job.job_specification_id=g_job_specification.id
			WHERE g_job.company_structure_id =". Model::safeSql($csid) ." AND is_active=1
			" . $order_by . "
			" . $limit . "					
		";	
		$result = Model::runSql($sql,true);
		return $result;
	}
}
?>