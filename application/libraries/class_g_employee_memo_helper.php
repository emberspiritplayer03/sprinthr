<?php
class G_Employee_Memo_Helper {
		
	public static function isIdExist(G_Employee_Memo $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_MEMO ."
			WHERE id = ". Model::safeSql($e->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}	

	public static function getEmployeeMemoByEmployeeIdEmployeeDepartmentEmployeeStatus($employee_ids, $deptsection_ids, $employment_status_ids, $date_from, $date_to, $add_query = '', $project_site_id){
    	$sql_add_query = '';
    	if( $add_query != '' ){
    		$sql_add_query = $add_query;
    	}

		$sql_employee_ids = implode(",", $employee_ids);
		$sql_deptsection_ids = implode(",", $deptsection_ids);
		$sql_employment_status_ids = implode(",", $employment_status_ids);

		$sql = "
			SELECT e.employee_code, e.project_site_id,
			CONCAT(e.lastname,', ', e.firstname) as employee_name, cs.title as department_title,
			es.status, gem.title, gem.date_of_offense, gem.offense_description, gem.remarks, 
			COALESCE(esh.name,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = e.id 
                        AND end_date <> ''
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS department_name, 
                COALESCE(ejh.name,(
                SELECT name FROM `g_employee_job_history`
                WHERE employee_id = e.id 
                    AND end_date <> ''
                ORDER BY end_date DESC 
                LIMIT 1
                ))AS position,			
			(SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name
			FROM " . G_EMPLOYEE_MEMO . " gem
			LEFT JOIN " . EMPLOYEE ." e
			ON gem.employee_id = e.id
			LEFT JOIN ". EMPLOYMENT_STATUS ." es
			ON e.employment_status_id = es.id
			LEFT JOIN ". COMPANY_STRUCTURE ." cs
			ON e.department_company_structure_id = cs.id
            LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
            LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON e.id = ejh.employee_id AND ejh.end_date = ''			
			WHERE gem.date_of_offense BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . "
			{$sql_add_query}
		";

		if($sql_employee_ids){
			$sql .= "AND gem.employee_id IN({$sql_employee_ids})";
		}

		if($sql_deptsection_ids){
			$sql = "AND e.department_company_structure_id IN({$sql_deptsection_ids})";
		}

		if($sql_employment_status_ids){
			$sql .= "AND e.employment_status_id IN({$sql_employment_status_ids})";
		}

		if($project_site_id != '' && $project_site_id != 'all'){
			$sql .= " AND e.project_site_id =" . Model::safeSql($project_site_id);
		}

		$result = Model::runSql($sql,true);
		return $result;

	}

}
?>