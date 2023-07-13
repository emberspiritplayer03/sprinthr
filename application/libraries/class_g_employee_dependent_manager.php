<?php
class G_Employee_Dependent_Manager {
	public static function save(G_Employee_Dependent $e) {
		if (G_Employee_Dependent_Helper::isIdExist($e) > 0 ) {
			$sql_start = "UPDATE ". G_EMPLOYEE_DEPENDENT . "";
			$sql_end   = "WHERE id = ". Model::safeSql($e->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_EMPLOYEE_DEPENDENT . "";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			employee_id			= " . Model::safeSql($e->getEmployeeId()) .",
			name		   		= " . Model::safeSql($e->getName()) .",
			relationship   		= " . Model::safeSql($e->getRelationship()) .",
			birthdate			= " . Model::safeSql($e->getBirthdate()) ." "
		
			. $sql_end ."	
		
		";	

		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Employee_Dependent $e){
		if(G_Employee_Dependent_Helper::isIdExist($e) > 0){
			$sql = "
				DELETE FROM ". G_EMPLOYEE_DEPENDENT ."
				WHERE id =" . Model::safeSql($e->getId());
			Model::runSql($sql);
		}
	
	}

	public static function insertDefaultNumberOfDependents(G_Employee_Dependent $d, $number_of_dependents = 0){
		if( $d->getEmployeeId() > 0 && !empty($d) ){
			for($x = 1; $x<=$number_of_dependents; $x++){
				$default_name = "Dependent {$x}";
	        	$dependents[] = "(" . $d->getEmployeeId() . "," . Model::safeSql($default_name) . "," . Model::safeSql($d->getRelationship()) . "," . Model::safeSql($d->getBirthdate()) . ")"; 
	        }

	        $sql_values = implode(",", $dependents);
	        $sql        = "INSERT INTO " . G_EMPLOYEE_DEPENDENT . "(employee_id,name,relationship,birthdate)VALUES{$sql_values}";
	       
	        Model::runSql($sql);
	        return true;

		}else{
			return false;
		}
	}

	public static function addEmployeeDependents(G_Employee_Dependent $d, $number_of_dependents = 0){
		if( $d->getEmployeeId() > 0 && !empty($d) ){
			for($x = 1; $x<=$number_of_dependents; $x++){
				$default_name = "Dependent {$x}";
	        	$dependents[] = "(" . $d->getEmployeeId() . "," . Model::safeSql($default_name) . "," . Model::safeSql($d->getRelationship()) . "," . Model::safeSql($d->getBirthdate()) . ")"; 
	        }

	        $sql_values = implode(",", $dependents);
	        $sql        = "INSERT INTO " . G_EMPLOYEE_DEPENDENT . "(employee_id,name,relationship,birthdate)VALUES{$sql_values}";
	       	echo "{$sql}<Br /><br />";
	        Model::runSql($sql);
	        return true;

		}else{
			return false;
		}
	}

	public static function deleteAllEmployeeDependents($employee_id){
		$total_records_deleted = 0;
		$sql = "
			DELETE FROM ". G_EMPLOYEE_DEPENDENT ."
			WHERE employee_id =" . Model::safeSql($employee_id);
		Model::runSql($sql);
		$total_records_deleted = mysql_affected_rows();
		return $total_records_deleted;
	}
}
?>