<?php
class G_Company_Structure_Manager {
	public static function save(G_Company_Structure $gcs) {
		if (G_Company_Structure_Helper::isIdExist($gcs) > 0 ) {
			$sql_start = "UPDATE ". COMPANY_STRUCTURE . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($gcs->getId());		
		}else{
			$sql_start = "INSERT INTO ". COMPANY_STRUCTURE . " ";
			$sql_end  = "";		
		}
		
		$sql = $sql_start ."
			SET
			company_branch_id = " . Model::safeSql($gcs->getCompanyBranchId()) . ",
			title             = " . Model::safeSql($gcs->getTitle()) . ",
			description       = " . Model::safeSql($gcs->getDescription()) . ",
			type       		  = " . Model::safeSql($gcs->getType()) . ",
			is_archive         = " . Model::safeSql($gcs->getIsArchive()) . ", 
			parent_id         = " . Model::safeSql($gcs->getParentId()) . " "
			
			. $sql_end ."	
		
		";
		Model::runSql($sql);
		return mysql_insert_id();		
	}
	
	public static function archive(G_Company_Structure $gcs) {

		$sql_start = "UPDATE ". COMPANY_STRUCTURE . " ";
		$sql_end   = "WHERE id = ". Model::safeSql($gcs->getId());		
		
		$sql = $sql_start ."
			SET			
			is_archive = " . Model::safeSql($gcs->getIsArchive()) . " "
			. $sql_end ."	
		
		";
		Model::runSql($sql);
		return mysql_insert_id();		
	}
	
	public static function directArchive(G_Company_Structure $gcs){
		if(G_Company_Structure_Helper::isIdExist($gcs) > 0){
			$sql = "
				UPDATE ". COMPANY_STRUCTURE ."
					SET is_archive = " . Model::safeSql(G_Company_Structure::YES) . " 
				WHERE id =" . Model::safeSql($gcs->getId());
			Model::runSql($sql);
		}	
	}
	
	public static function restore(G_Company_Structure $gcs){
		if(G_Company_Structure_Helper::isIdExist($gcs) > 0){
			$sql = "
				UPDATE ". COMPANY_STRUCTURE ."
					SET is_archive = " . Model::safeSql(G_Company_Structure::NO) . " 
				WHERE id =" . Model::safeSql($gcs->getId());
			Model::runSql($sql);
		}	
	}
		
	public static function delete(G_Company_Structure $gcs){
		if(G_Company_Structure_Helper::isIdExist($gcs) > 0){
			$sql = "
				DELETE FROM ". COMPANY_STRUCTURE ."
				WHERE id =" . Model::safeSql($gcs->getId());
			Model::runSql($sql);
		}	
	}
	
	public static function addEmployee(G_Company_Structure $c, G_Employee $e) {

		if (G_Employee_Helper::isIdExist($e)>0) {
			$sql_start = "UPDATE ". EMPLOYEE . "";
			$sql_end   = "WHERE id = ". Model::safeSql($e->getId()) ." 
			";	
			
			$sql = $sql_start ."
			SET
			company_structure_id     = " . Model::safeSql($c->getId()) ."
			"
			. $sql_end ."	
			
			";		
		}
		
		Model::runSql($sql);
		return mysql_insert_id();	
	}
	
		//add employee into subdivision
	public static function addEmployeeToSubdivision(G_Company_Structure $company, G_Employee $employee,$start_date,$end_date) {
		if (G_Company_Structure_Helper::isEmployeeSubdivisionHistoryExist($employee, $company, $start_date, $end_date) > 0 ) {
			$sql_start = "UPDATE ". G_EMPLOYEE_SUBDIVISION_HISTORY . "";
			$sql_end   = "WHERE employee_id = ". Model::safeSql($employee->getId()) ." AND company_structure_id=".Model::safeSql($company->getId())." 
						  AND name = ".Model::safeSql($company->getTitle()). " AND start_date = ".Model::safeSql($start_date)." AND end_date = " . Model::safeSql($end_date) . "
			";		
		}else{
			$sql_start = "INSERT INTO ". G_EMPLOYEE_SUBDIVISION_HISTORY . "";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			employee_id     		= " . Model::safeSql($employee->getId()) .",
			company_structure_id 	= " . Model::safeSql($company->getId()) .",
			name					= " . Model::safeSql($company->getTitle()) .",
			type					= " . Model::safeSql($company->getType()) .",
			start_date 				= " . Model::safeSql($start_date) .",
			end_date 				= " . Model::safeSql($end_date) . " "
		. $sql_end ."	
		
		";			
		Model::runSql($sql);
		return mysql_insert_id();
	}
}

?>