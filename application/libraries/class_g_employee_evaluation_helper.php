<?php
class G_Employee_Evaluation_Helper {

	
	public static function isIdExist(G_Employee_Evaluation $gcp) {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . G_EMPLOYEE_EVALUATION ."
			WHERE id = ". Model::safeSql($gcp->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}



	public static function countEmployeeWithEvaluationToday(){


		$date = date('Y-m-d', strtotime('now'));

        $sql = "
            SELECT COUNT(e.id) as total
            FROM " . G_EMPLOYEE_EVALUATION . " e
            WHERE e.next_evaluation_date = '{$date}' 
            	AND e.is_archive =" . Model::safeSql(G_Employee_Evaluation::NO) . " AND is_updated != ".Model::safeSql(G_Employee_Evaluation::YES)."
        ";

        $result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];

	}

	public static function countEmployeeWithUpcomingEvaluation(){

		$dateNow = date('Y-m-d', strtotime('+1 day'));
		$date = date('Y-m-d', strtotime($dateNow."+5 days"));

        $sql = "
            SELECT COUNT(e.id) as total
            FROM " . G_EMPLOYEE_EVALUATION . " e
            WHERE  e.next_evaluation_date >= '{$dateNow}' 
            	 AND  e.next_evaluation_date <= '{$date}' 
            	AND e.is_archive =" . Model::safeSql(G_Employee_Evaluation::NO) . " 
        ";

        $result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];

		//DATE_FORMAT(e.birthdate,'%m-%d') >= '{$start_date}'
            	//AND DATE_FORMAT(e.birthdate,'%m-%d') <= '{$end_date}' 
	}



	public static function getmployeeWithEvaluationToday(){


		$date = date('Y-m-d', strtotime('now'));

        $sql = "
           SELECT
				ev.id,
			ev.score as score,
			ev.next_evaluation_date as evaluation_date,
			company_branch.name as  branch_name,
			`d`.`name` AS `department`,
			(SELECT title FROM `g_company_structure` WHERE id = e.section_id LIMIT 1)AS section_name,
			`e`.`employee_code`,
			CONCAT(e.lastname,', ',e.firstname,' ',substring(e.middlename,1,1),'. ', e.extension_name) AS `employee_name`,
			`j`.`name` AS `position`, e.employee_code as employee_id
			
	
			FROM `g_employee_evaluation` AS `ev`
			inner Join `g_employee` AS `e`  ON `e`.`id` = `ev`.`employee_id`
			Left Join `g_employee_subdivision_history` AS `d` ON `ev`.`employee_id` = `d`.`employee_id` AND `d`.`end_date` = ''
			Left Join `g_employee_branch_history` AS `b` ON `ev`.`employee_id` = `b`.`employee_id` AND `b`.`end_date` = ''
			Left Join g_company_branch as company_branch ON b.company_branch_id=company_branch.id
			Left Join `g_employee_job_history` AS `j` ON  (`j`.`employee_id` = `ev`.`employee_id` AND `j`.`end_date` = '' )
			
			Inner Join `g_company_structure` AS `company` ON `e`.`company_structure_id` = `company`.`id`
			 
			where ev.is_archive = ". Model::safeSql(G_Employee_Evaluation::NO)." AND ev.employee_id = e.id
			 AND ev.next_evaluation_date='".$date."' AND is_updated != ". Model::safeSql(G_Employee_Evaluation::YES)."
        ";

         $result = Model::runSql($sql,true);		
		 return $result;

	}



	public static function getmployeeWithUpcomingEvaluation(){


		$dateNow = date('Y-m-d', strtotime('+1 day'));
		$date = date('Y-m-d', strtotime($dateNow."+5 days"));

        $sql = "
          SELECT
				ev.id,
			ev.score as score,
			ev.next_evaluation_date as evaluation_date,
			company_branch.name as  branch_name,
			`d`.`name` AS `department`,
			(SELECT title FROM `g_company_structure` WHERE id = e.section_id LIMIT 1)AS section_name,
			`e`.`employee_code`,
			CONCAT(e.lastname,', ',e.firstname,' ',substring(e.middlename,1,1),'. ', e.extension_name) AS `employee_name`,
			`j`.`name` AS `position`, e.employee_code as employee_id
			
	
			FROM `g_employee_evaluation` AS `ev`
			inner Join `g_employee` AS `e`  ON `e`.`id` = `ev`.`employee_id`
			Left Join `g_employee_subdivision_history` AS `d` ON `ev`.`employee_id` = `d`.`employee_id` AND `d`.`end_date` = ''
			Left Join `g_employee_branch_history` AS `b` ON `ev`.`employee_id` = `b`.`employee_id` AND `b`.`end_date` = ''
			Left Join g_company_branch as company_branch ON b.company_branch_id=company_branch.id
			Left Join `g_employee_job_history` AS `j` ON  (`j`.`employee_id` = `ev`.`employee_id` AND `j`.`end_date` = '' )
			
			Inner Join `g_company_structure` AS `company` ON `e`.`company_structure_id` = `company`.`id`
			 
			where ev.is_archive = ". Model::safeSql(G_Employee_Evaluation::NO)." AND ev.employee_id = e.id AND
			  ev.next_evaluation_date >= '".$dateNow."'  AND  ev.next_evaluation_date <= '".$date."' 
        ";

         $result = Model::runSql($sql,true);		
		 return $result;

	}




}
?>
