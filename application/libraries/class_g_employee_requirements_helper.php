<?php 
class G_Employee_Requirements_Helper {
	public static function isIdExist(G_Employee_Requirements $gcb) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_REQUIREMENTS ."
			WHERE id = ". Model::safeSql($gcb->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	
	
	public static function findByEmployeeId($employee_id,$order_by,$limit) {
		$sql = "
			*
			FROM ". G_EMPLOYEE_REQUIREMENTS ."
			WHERE a.employee_id=".$employee_id."
			
			GROUP BY
			`a`.`id`
			
			".$order_by."
			".$limit."
		";
		//echo $sql;
		$result = Model::runSql($sql,true);

		return $result;
	}

	public static function getEmployeeIncompleteRequirements() {
        $sql = "
            SELECT e.id, er.id as requirement_id, CONCAT(e.lastname , ', ' , e.firstname) as employee_name, esh.name as department_name, 
            	er.requirements as incomplete_requirement
            FROM " . EMPLOYEE . " e
            	LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id 
            	LEFT JOIN " . G_EMPLOYEE_REQUIREMENTS . " er ON e.id = er.employee_id 	
           	    WHERE e.id NOT IN (
            		SELECT employee_id FROM ". G_EMPLOYEE_REQUIREMENTS ." WHERE is_complete = 1
                )
        ";
        $result = Model::runSql($sql,true);
		return $result;
    }
    
    public static function countEmployeeIncompleteRequirements() {
        $sql = "
            SELECT COUNT(e.id) as total
            FROM " . EMPLOYEE . " e
           	    WHERE e.id NOT IN (
            		SELECT employee_id FROM ". G_EMPLOYEE_REQUIREMENTS ." WHERE is_complete = 1
                )
        ";
        $result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
    }

}
?>