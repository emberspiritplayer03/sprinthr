<?php
class G_Company_Branch_Manager {
	public static function save(G_Company_Branch $gcb, G_Company_Structure $gcs) {

		if (G_Company_Branch_Helper::isIdExist($gcb) > 0 ) {
			$sql_start = "UPDATE ". COMPANY_BRANCH . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($gcb->getId());		
		}else{
			$sql_start = "INSERT INTO ". COMPANY_BRANCH . " ";
			$sql_end  = "";	
		}

		$sql = $sql_start ."
			SET
			company_structure_id = " . Model::safeSql($gcs->getId()) . ",
			name                 = " . Model::safeSql($gcb->getName()) . ",
			province             = " . Model::safeSql($gcb->getProvince()) . ",
			city                 = " . Model::safeSql($gcb->getCity()) . ",
			address              = " . Model::safeSql($gcb->getAddress()) . ",
			zip_code             = " . Model::safeSql($gcb->getZipCode()) . ",
			phone                = " . Model::safeSql($gcb->getPhone()) . ",
			fax                  = " . Model::safeSql($gcb->getFax()) . ",
			is_archive           = " . Model::safeSql($gcb->getIsArchive()) . ",
			location_id          = " . Model::safeSql($gcb->getLocationId()) . " "
			. $sql_end ."			
		";
		
		Model::runSql($sql);
		return mysql_insert_id();		
	}
	
	public static function archive(G_Company_Branch $gcb){
		if(G_Company_Branch_Helper::isIdExist($gcb) > 0){
			$sql = "
				UPDATE ". COMPANY_BRANCH ."
					SET is_archive =" . Model::safeSql(G_Company_Branch::YES) . "
				WHERE id =" . Model::safeSql($gcb->getId());
			Model::runSql($sql);
		}	
	}
	
	public static function restore(G_Company_Branch $gcb){
		if(G_Company_Branch_Helper::isIdExist($gcb) > 0){
			$sql = "
				UPDATE ". COMPANY_BRANCH ."
					SET is_archive =" . Model::safeSql(G_Company_Branch::NO) . "
				WHERE id =" . Model::safeSql($gcb->getId());
			Model::runSql($sql);
		}	
	}
		
	public static function delete(G_Company_Branch $gcb){
		if(G_Company_Branch_Helper::isIdExist($gcb) > 0){
			$sql = "
				DELETE FROM ". COMPANY_BRANCH ."
				WHERE id =" . Model::safeSql($gcb->getId());
			Model::runSql($sql);
		}	
	}
	
	public static function addEmployee($branch, $employee,$start_date,$end_date) {
		if (G_Company_Branch_Helper::isEmployeeBranchHistoryExist($employee, $branch, $start_date, $end_date) > 0 ) {
			$sql_start = "UPDATE ". G_EMPLOYEE_BRANCH_HISTORY . "";
			$sql_end   = "WHERE employee_id = ". Model::safeSql($employee->getId()) ." AND company_branch_id=".Model::safeSql($branch->getId())." 
						  AND branch_name = ".Model::safeSql($branch->getName()). " AND start_date = ".Model::safeSql($start_date)." AND end_date = " . Model::safeSql($end_date) . "
			";		
		}else{
			$sql_start = "INSERT INTO ". G_EMPLOYEE_BRANCH_HISTORY . "";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			employee_id     	= " . Model::safeSql($employee->getId()) .",
			company_branch_id 	= " . Model::safeSql($branch->getId()) .",
			branch_name			= " . Model::safeSql($branch->getName()) .",
			start_date 			= " . Model::safeSql($start_date) .",
			end_date 			= " . Model::safeSql($end_date) . ""
		. $sql_end ."	
		
		";	
	
		Model::runSql($sql);
		return mysql_insert_id();
	}
	
	/*public static function saveToEmployee(G_Branch $b, G_Employee $e, $start_date, $end_date) {
		if (G_Company_Branch_Helper::isEmployeeBranchHistoryExist($e, $j, $start_date, $end_date) > 0 ) {
			$sql_start = "UPDATE ". G_EMPLOYEE_BRANCH_HISTORY . "";
			$sql_end   = "WHERE employee_id = ". Model::safeSql($e->getId()) ." AND job_id=".Model::safeSql($j->getId())." 
						  AND name = ".Model::safeSql($j->getTitle()). " AND start_date = ".Model::safeSql($start_date)." AND end_date = " . Model::safeSql($end_date) . "
			";		
		}else{
			$sql_start = "INSERT INTO ". G_EMPLOYEE_BRANCH_HISTORY . "";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			employee_id     	= " . Model::safeSql($e->getId()) .",
			company_branch_id 	= " . Model::safeSql($b->getId()) .",
			branch_name			= " . Model::safeSql($b->getName()) .",
			start_date 			= " . Model::safeSql($start_date) .",
			end_date 			= ". Model::safeSql($end_date) . ""
		. $sql_end ."	
		
		";	
	
		Model::runSql($sql);
		return mysql_insert_id();
	}*/
}
?>