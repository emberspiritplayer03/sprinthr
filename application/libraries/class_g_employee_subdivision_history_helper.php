<?php 
class G_Employee_Subdivision_History_Helper {

    /*
     * Ends the department history of an employee
     */
    public static function ended(IEmployee $e, $date_ended) {
        $s = G_Employee_Subdivision_History_Finder::findCurrentSubdivision($e);
        if ($s) {
            $s->setEndDate($date_ended);
            $s->save();
        }
    }

    public static function resetActive(IEmployee $e, $date_ended) {
        $s = G_Employee_Subdivision_History_Finder::findCurrentSubdivision2($e);
        if ($s) {
            $s->setEndDate($date_ended);
            $s->save();
        }
    }

	public static function isIdExist(G_Employee_Subdivision_History $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_SUBDIVISION_HISTORY ."
			WHERE id = ". Model::safeSql($e->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function findByCompanyStructureId($company_structure_id) {
		$sql = "
			SELECT *
			FROM " . G_EMPLOYEE_SUBDIVISION_HISTORY ."
			WHERE company_structure_id = ". Model::safeSql($company_structure_id) ."
		";
		
		$result = Model::runSql($sql,true);
		return $result;
	}
	
	public static  function countTotalCurrentEmployeeByCompanyStructureId($company_structure_id) 
	{
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_SUBDIVISION_HISTORY ."
			WHERE company_structure_id = ". Model::safeSql($company_structure_id) ." AND end_date = '' 
			ORDER BY end_date 
		";
		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
		
	}
	
	public static  function countTotalHistoryByEmployeeId($employee_id) 
	{
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_SUBDIVISION_HISTORY ."
			WHERE employee_id = ". Model::safeSql($employee_id) ."
			ORDER BY end_date 
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function getAllEmployeeIdByJobIdConcatString($department_id) {
		$departments = G_Employee_Subdivision_History_Finder::findAllCurrentEmployeesByCompanyStructureId($department_id);
		foreach($departments as $department):
			$id[] = $department->getEmployeeId();
		endforeach;
		
		if($id) {
			$string = " ea.employee_id = ".implode(" OR ea.employee_id = ",$id);
		}
		return $string;
	}
	
	public static function getAllEmployeeByCurrentSubdivision($company_structure_id) {
		$sql = "
			SELECT s.employee_id
			FROM " . G_EMPLOYEE_SUBDIVISION_HISTORY ." s
			WHERE 
				company_structure_id = " . Model::safeSql($company_structure_id) . " AND
				type = " . Model::safeSql(G_Employee_Subdivision_History::DEPARTMENT) . " AND
				end_date = ''
		
		";
		return Model::runSql($sql,true);
	}
	
	public static function getAllEmployeeCurrentSubdivision() {
		$sql = "
			SELECT s.employee_id
			FROM " . G_EMPLOYEE_SUBDIVISION_HISTORY ." s
			WHERE 
				type = " . Model::safeSql(G_Employee_Subdivision_History::DEPARTMENT) . " AND
				end_date = ''
		
		";
		return Model::runSql($sql,true);
	}

	public static function getEmployeeNoDepartment() {
        $sql = "
            SELECT e.id, CONCAT(e.lastname , ', ' , e.firstname) as employee_name, e.hired_date
            FROM " . EMPLOYEE . " e
            WHERE e.id NOT
                IN (
                    SELECT employee_id
                    FROM " . G_EMPLOYEE_SUBDIVISION_HISTORY . "
                    WHERE end_date >= IF( end_date = '', '', NOW( ) ) 
                    AND type = ". Model::safeSql(G_Employee_Subdivision_History::DEPARTMENT). "
                )
        ";

        $result = Model::runSql($sql,true);
		return $result;
    }

	public static function countEmployeeNoDepartment() {
        $sql = "
            SELECT COUNT(e.id) as total
            FROM " . EMPLOYEE . " e
            WHERE e.id NOT
                IN (
                    SELECT employee_id
                    FROM " . G_EMPLOYEE_SUBDIVISION_HISTORY . "
                    WHERE end_date >= IF( end_date = '', '', NOW( ) ) 
                    AND type = ". Model::safeSql(G_Employee_Subdivision_History::DEPARTMENT). "
                )
                AND e.employee_status_id = 1
        ";

        $result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
    }
    
}
?>